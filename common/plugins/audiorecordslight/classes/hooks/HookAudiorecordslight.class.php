<?php
/*
	Audiorecordslight plugin
	(P) PSNet, 2008 - 2013
	http://psnet.lookformp3.net/
	http://livestreet.ru/profile/PSNet/
	https://catalog.livestreetcms.com/profile/PSNet/
	http://livestreetguide.com/developer/PSNet/
*/

class PluginAudiorecordslight_HookAudiorecordslight extends Hook {

	public function RegisterHook () {
		$this -> AddHook ('engine_init_complete', 'AddStylesAndJS');
		//$this -> AddHook ('template_form_add_topic_topic_end', 'NewTopicEnd');
		//$this -> AddHook ('template_topic_show_info', 'TopicShowInfo');
		//$this -> AddHook ('template_body_begin', 'BodyBegin');
		$this -> AddHook ('template_topic_content_begin', 'TopicShowInfo');
	}
	
	// ---

	public function AddStylesAndJS () {
		$sTemplateWebPath = Plugin::GetTemplateWebPath (__CLASS__);
		//$this -> Viewer_AppendStyle ($sTemplateWebPath . 'css/style.css');
		//$sLibPath = Config::Get('path.root.engine_lib') . '/external/jPlayer-2.9.2/dist';
		$this -> Viewer_AppendStyle ($sTemplateWebPath . 'jPlayer-2.9.2/dist/skin/4otaku/css/jplayer.4otaku.css');
		$this -> Viewer_AppendScript ($sTemplateWebPath . 'jPlayer-2.9.2/dist/jplayer/jquery.jplayer.min.js');
		$this -> Viewer_AppendScript ($sTemplateWebPath . 'jPlayer-2.9.2/dist/add-on/jplayer.playlist.min.js');

		//$this -> Viewer_AppendScript ($sTemplateWebPath . 'js/init.js');
		//$this -> Viewer_AppendScript ($sTemplateWebPath . 'js/fileupload.js');
		//$this -> Viewer_AppendScript ($sTemplateWebPath . 'js/jquery.form.js');		// LS currently use old version, todo: uncomment when FormData version will be 3.31.0-2013.03.27
	}
	
	// ---

	public function TopicShowInfo ($aVars) {
		$oTopic = $aVars ['topic'];
		/*
		 * если нет аудио файлов для данного топика
		 */
		if (!$aMP3Audio = $oTopic -> getMP3FilesARL ()) return false;
		$this -> Viewer_Assign ('oTopic', $oTopic);
		$this -> Viewer_Assign ('oConfig', Config::GetInstance ());

		foreach ($aMP3Audio as $item) {
			$data = parse_url($item);
			$file = substr($data['path'], 1);
			$proxy = 'uploads/audio/' . md5(basename($file)) . '.mp3';
			/*if (!file_exists($proxy)) {
				file_put_contents($proxy, file_get_contents($_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . $file));
			}*/
			if (!file_exists($proxy) || !linkinfo($proxy)) {
				if (!@symlink($_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . $file, $proxy)) {
					error_log(sprintf("[%s] Error creating symlink in %s:%s %s → %s\n", gmdate('Y-m-d H:i:s.u'), __FILE__, __LINE__-1, basename($file), $proxy), E_USER_WARNING);
				}
			}
		}
		$this -> Viewer_Assign ('aMP3Audio', $aMP3Audio);
        $this->Viewer_Assign('topic_id', $oTopic->getID());
		return $this -> Viewer_Fetch (Plugin::GetTemplatePath (__CLASS__) . 'topic_info.tpl');
	}

	// ---

	public function NewTopicEnd () {
		return $this -> Viewer_Fetch (Plugin::GetTemplatePath (__CLASS__) . 'new_topic_end.tpl');
	}
	
	// ---

	public function BodyBegin () {
		return $this -> Viewer_Fetch (Plugin::GetTemplatePath (__CLASS__) . 'body_begin.tpl');
	}
	
}
