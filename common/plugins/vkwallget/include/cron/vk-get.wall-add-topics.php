<?php

$sDirRoot=dirname(dirname(dirname(dirname(dirname(__FILE__)))));
set_include_path(get_include_path().PATH_SEPARATOR.$sDirRoot);
chdir($sDirRoot);

require_once($sDirRoot . "/config/loader.php");
require_once($sDirRoot . "/engine/classes/Cron.class.php");

/**
 * Class VKGetWallAddTopics
 * Получаем посты со стены группы и добавляем их в специальный блог
 * https://vk.com/dev/wall.get
 */
class VKGetWallAddTopics extends Cron {
	private $aConfig;

	public function Client() {
		$sPluginPath = dirname(dirname(dirname(__FILE__)));

		$aPlugins = $this->Plugin_GetActivePlugins();
		if (!in_array(basename($sPluginPath), $aPlugins)) {
			return false;
		}

		$oConfig = Config::LoadFromFile($sPluginPath . '/config/config.php', false);
		try {
			if (!$oConfig)
				throw new Exception("Could not load config file!");

			$this->aConfig = $oConfig->Get('vk.wallget');
			$data = $this->getWallPosts();

			if (isset($data['error']))
				throw new Exception($data['error']['error_msg'], $data['error']['error_code']);

			$oMapper = LS::Mpr('PluginVkwallget_ModuleVkwallget');
			$oUser = $this->User_GetUserById($this->aConfig['from_user_id']);
			// авторизуем юзера, чтобы плагины, использующие User_GetUserCurrent() получали нашего пользователя
			$this->User_Authorization($oUser, false);

			$oBlog = $this->Blog_GetBlogById($this->aConfig['to_blog_id']);

			// существует ли указанный блог
			if (!$oBlog) {
				throw new Exception('Blog #' . $this->aConfig['to_blog_id'] . ' is not exists.');
			}
			// есть ли права на постинг в блог
			if (!$this->ACL_IsAllowBlog($oBlog, $oUser)) {
				throw new Exception($this->Lang_Get('topic_create_blog_error_noallow'));
			}

			foreach ($data['response']['items'] as $item) {
				// пропускаем посты, которые у нас уже есть
				if ($oMapper->GetPostExists($item['id'])) {
					$this->Log('Skip vk_post_id #' . $item['id']);

					continue;
				}
				$this->Log('Adding vk_post_id #' . $item['id']);

				$item['tags'] = $this->parseTags($item['text']);
				$item['text'] = $this->removeTags($item['text']);
				$sTopicTitle  = $this->textCutTitle($item['text']);

				// Заголовок про фигурки предваряем подзаголовком
				if (in_array('Figure', $item['tags']))
					$sTopicTitle = 'Фигурки. ' . $sTopicTitle;

				// обрабатываем файлы из поста
				// https://vk.com/dev/attachments_w
				$bHasVideo = false;
				$bCutted = false;
				if ($iSizeAttachments = sizeof($item['attachments'])) {
					foreach($item['attachments'] as $attachment) {
						switch ($attachment['type']) {
							// https://vk.com/dev/photo
							case 'photo': {
								// есть несколько миниатюр photo_75, photo_130 и т.д. Интересует самая большая миниатюра
								$photo_size_key = 0;
								foreach (array_keys($attachment['photo']) as $key) {
									if (preg_match('/^photo_(\d+)$/', $key, $m)) {
										$new_size = (int)$m[1];
										$photo_size_key = ($photo_size_key > $new_size ? $photo_size_key : $new_size);
									}
								}
								$sFileUrl = $attachment['photo']['photo_' . $photo_size_key];

								// Загрузка файла по URL
								$sFile = $this->Topic_UploadTopicImageUrl($sFileUrl, $oUser);
								try {
									switch (true) {
										case is_string($sFile):
											break;

										case ($sFile==ModuleImage::UPLOAD_IMAGE_ERROR_READ):
											throw new Exception($this->Lang_Get('uploadimg_url_error_read'));

										case ($sFile==ModuleImage::UPLOAD_IMAGE_ERROR_SIZE):
											throw new Exception($this->Lang_Get('uploadimg_url_error_size'));

										case ($sFile==ModuleImage::UPLOAD_IMAGE_ERROR_TYPE):
											throw new Exception($this->Lang_Get('uploadimg_url_error_type'));

										default:
										case ($sFile==ModuleImage::UPLOAD_IMAGE_ERROR):
											throw new Exception($this->Lang_Get('uploadimg_url_error'));
									}
								}
								catch (Exception $e) {
									$this->Log($e->getMessage());
								}

								// Если файл успешно загружен, формируем HTML вставки
								if ($sFile) {
									$item['text'] .= "\n" . $this->Image_BuildHTML(
										$sFile, array(
											'title' => $attachment['photo']['text'],
											'align' => 'center'
										)
									);

									// если прикреплено более одной картинки, остальное прячем под кат
									if (!$bCutted && $iSizeAttachments > 1) {
										$item['text'] .= '<cut name="Читать дальше">';
										$bCutted = true;
									}
								}
							}
							break;

							// https://vk.com/dev/video_object
							case 'video': {
								// для видео требуются дополнительные права, для этого надо регистрировать сайт как приложение
								// такие посты добавляем в черновики для ручной вставки видео
								$bHasVideo = true;
								$item['text'] .= "\nhttps://vk.com/video{$attachment['video']['owner_id']}_{$attachment['video']['id']}";
								/*try {
									// запрашиваем инфу о видео
									$video_data = $this->getVideo($attachment['video']['owner_id'], $attachment['video']['id'], $attachment['video']['access_key']);
									if (isset($video_data['error']))
										throw new Exception($video_data['error']['error_msg'], $video_data['error']['error_code']);

									if (preg_match('|^https?://youtube\.com/embed/([-\_a-zA-Z0-9]+)$|', $video_data['response']['items'][0]['player'], $m)) {
										$item['text'] .= "\n<video>http://youtu.be/".$m[1].'</video>';
									}
									elseif(preg_match('|^https?://vk\.com/video_ext|', $video_data['response']['items'][0]['player'])) {
										$item['text'] .= "\n<video>".$video_data['player'].'</video>';
									}
								}
								catch (Exception $e) {
								}*/
							}
							break;
						}
					}
				}

				$oTopic = $this->oEngine->GetEntity('ModuleTopic_EntityTopic', array(
					'blog_id' => $this->aConfig['to_blog_id'],
					'user_id' => $this->aConfig['from_user_id'],
					'topic_type' => 'topic',
					'topic_title' => $sTopicTitle,
					'topic_tags' => join(', ', $item['tags']),
					'topic_date_add' => date('Y-m-d H:i:s'),
					'topic_user_ip' => '',
					'topic_publish' => '1',
					'topic_publish_draft' => '1',
					'topic_publish_index' => '0',
					'topic_text_source' => $item['text'],
					'topic_forbid_comment' => '0',
					'topic_text_hash' => md5($item['text']),
				));
				$oTopic->_setValidateScenario('topic');

				// Получаемый и устанавливаем разрезанный текст по тегу <cut>
				list($sTextShort, $sTextNew, $sTextCut) = $this->Text_Cut($oTopic->getTextSource());
				$oTopic->setCutText($sTextCut);
				$oTopic->setText($this->Text_Parser($sTextNew));
				$oTopic->setTextShort($this->Text_Parser($sTextShort));

				// Запрет на комментарии к топику
				if ($this->aConfig['forbid_comment']) {
					$oTopic->setForbidComment(1);
				}
				// Публикуем или сохраняем
				if ($this->aConfig['topic_publish']) {
					$oTopic->setPublish(1);
					$oTopic->setPublishDraft(1);
				}
				else {
					$oTopic->setPublish(0);
					$oTopic->setPublishDraft(0);
				}

				// если содержится видео, то потребуется ручная вставка плеера, так что в черновики пост
				if ($bHasVideo) {
					$oTopic->setPublish(0);
					$oTopic->setPublishDraft(0);
				}

				// Запускаем выполнение хуков
				$this->Hook_Run('topic_add_before', array('oTopic' => $oTopic, 'oBlog' => $oBlog));

				// Добавляем топик
				if ($this->Topic_AddTopic($oTopic)) {
					$this->Hook_Run('topic_add_after', array('oTopic' => $oTopic, 'oBlog' => $oBlog));

					// Получаем топик, чтоб подцепить связанные данные
					$oTopic = $this->Topic_GetTopicById($oTopic->getId());

					// Добавляем связь поста вк с топиком
					$oMapper->AddRelation($oTopic->getId(), $item['id']);

					// Обновляем количество топиков в блоге
					$this->Blog_RecalculateCountTopicByBlogId($oTopic->getBlogId());

					// Добавляем автора топика в подписчики на новые комментарии к этому топику
					// $this->Subscribe_AddSubscribeSimple('topic_new_comment', $oTopic->getId(), $oUser->getMail());

					// Делаем рассылку спама всем, кто состоит в этом блоге
					if ($oTopic->getPublish() == 1 and $oBlog->getType() != 'personal') {
						$this->Topic_SendNotifyTopicNew($oBlog, $oTopic, $oUser);
					}

					// Добавляем событие в ленту
					$this->Stream_write($oTopic->getUserId(), 'add_topic', $oTopic->getId(), $oTopic->getPublish() && $oBlog->getType() != 'close');

					$this->Log('Added topic #' . $oTopic->getId() . ' for vk_post_id #' . $item['id']);
				}
				else {
					throw new Exception($this->Lang_Get('system_error'));
				}
			}
		}
		catch (Exception $e) {
			$this->Log(sprintf('Error %s: %s', $e->getCode(), $e->getMessage()));
		}
	}

	/**
	 * @see https://vk.com/dev/wall.get
	 * @return mixed
	 * @throws Exception
	 */
	private function getWallPosts() {
/*response: {
count: 2835,
items: [{
	id: 6257,
	from_id: -56227762,
	owner_id: -56227762,
	date: 1399622899,
	post_type: 'post',
	text: 'Компания Bandai выпустила две версии своего Bandai's Hako Vision с Хацунэ Мику.
	В каждой по две песни - "Tell Your World" и "Nijigen Dream Fever" ("2D Dream Fever") или "World is Mine" и "Story Rider".
	На видео ниже видно, как работает эта технология (с Мику пока видео нет, но, как только появится, обязательно запостим). Ещё не полноценное 3Д, но уже большой шаг к своему домашнему мэскоту :)
	Цена каждой версии 185 рублей (500 йен), никакого языкового барьера нет.
	#fun@moeshumi #Vocaloid@moeshumi',
	attachments: [{
		type: 'video',
		video: {
		id: 169541683,
		owner_id: -56227762,
		title: '&#12496;&#12531;&#12480;&#12452;&#12300;&#12495;&#12467;&#12499;&#12472;&#12519;&#12531;&#12301;&#12503;&#12525;&#12514;&#12540;&#12471;&#12519;&#12531;&#12512;&#12540;&#12499;&#12540;&#65295;&#19990;&#30028;&#21021;&#65281;&#12473;&#12510;&#12507;&#12434;',
		duration: 184,
		description: 'https://vk.com/moeshumi?w=wall-56227762_6257',
		date: 1399622880,
		views: 7,
		comments: 0,
		photo_130: 'http://cs419523.vk.me/u3690013/video/s_cd531b6e.jpg',
		photo_320: 'http://cs419523.vk.me/u3690013/video/l_a235c1d0.jpg',
		album_id: 49241223,
		access_key: 'aba79f5dcd80d6374a'
		}
	}],
	post_source: {
		type: 'vk'
	},
	comments: {
		count: 0,
		can_post: 1
	},
	likes: {
		count: 1,
		user_likes: 0,
		can_like: 1,
		can_publish: 1
	},
	reposts: {
		count: 0,
		user_reposted: 0
	}
}]
}*/
		$ch = curl_init('http://api.vk.com/method/wall.get?' . http_build_query($this->aConfig['api']));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$result = curl_exec($ch);

		if (!$result)
			throw new Exception(curl_error($ch), curl_errno($ch));

		curl_close($ch);

		return json_decode($result, true);
	}

	/**
	 * Запрос видео к посту вконтакте
	 * @see https://vk.com/dev/video.get
	 */
	private function getVideo($owner_id, $video_id, $access_key = null) {
		$ch = curl_init('http://api.vk.com/method/video.get?' . http_build_query(array(
			'owner_id' => $owner_id,
			'videos'   => $owner_id . '_' . $video_id . (isset($access_key) ? '_' . $access_key : ''),
			'v' => '5.21'
		)));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$result = curl_exec($ch);

		if (!$result)
			throw new Exception(curl_error($ch), curl_errno($ch));

		curl_close($ch);

		return json_decode($result, true);
	}

	/**
	 * Парсит хештеги в тексте, обрезая часть @группа_нейм в конце
	 * @param $sText String
	 * @return array
	 */
	private function parseTags($sText) {
		preg_match_all('/#([-_0-9a-z]+)(?:@[-_0-9a-z]+)?/i', $sText, $tags);

		return $tags[1];
	}

	/**
	 * Удаляет хештеги из текста
	 * @param $sText String
	 * @return String
	 */
	private function removeTags($sText) {
		return preg_replace('/#([-_0-9a-z]+)(@[-_0-9a-z]+)\s*?/i', '', $sText);
	}

	private function textCutTitle($sText) {
		$sText = trim(strip_tags($sText));
		if(($pos = mb_strpos($sText, "\n", 0, 'utf8')) > 10 && $pos <= 60)
			$sText = rtrim(mb_substr($sText, 0, $pos, 'utf8'), '.');
		else
			$sText = mb_substr($sText, 0, 50, 'utf8') . '…';

		return $sText;
	}
}

/**
 * Создаем объект крон-процесса 
 */
$app=new VKGetWallAddTopics();
print $app->Exec();
?>