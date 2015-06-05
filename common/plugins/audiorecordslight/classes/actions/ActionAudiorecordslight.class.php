<?php
/*
	Audiorecordslight plugin
	(P) PSNet, 2008 - 2013
	http://psnet.lookformp3.net/
	http://livestreet.ru/profile/PSNet/
	https://catalog.livestreetcms.com/profile/PSNet/
	http://livestreetguide.com/developer/PSNet/
*/

class PluginAudiorecordslight_ActionAudiorecordslight extends ActionPlugin {

	protected $oUserCurrent = null;

	// ---

	public function Init () {
		if (!$this -> oUserCurrent = $this -> User_GetUserCurrent ()) {
			$this -> Message_AddError ($this -> Lang_Get ('need_authorization'), $this -> Lang_Get ('error'));
			return Router::Action ('error');
		}
	}

	// ---

	protected function RegisterEvent () {
		$this -> AddEvent ('uploadfile', 'EventUploadFile');
	}

	// ---

	/**
	 * Эвент добавления файла
	 *
	 * @return bool
	 */
	public function EventUploadFile () {
		/*
		 * подготовка ответа
		 */
		if (!isAjaxRequest ()) {
			/*
			 * для старых браузеров, которые не поддерживают XMLHttpRequest
			 */
			$this -> Viewer_SetResponseAjax ('jsonIframe', false);
		} else {
			$this -> Viewer_SetResponseAjax ('json');
		}
		/*
		 * если загрузка файла была запрещена
		 */
		if (!Config::Get ('plugin.audiorecordslight.Allow_Files_Uploads')) {
			$this -> Message_AddError ($this -> Lang_Get ('plugin.audiorecordslight.Files_Uploads_Are_Disabled'), $this -> Lang_Get ('error'));
			return false;
		}

		/*
		 * была ли нажата кнопка отправки файла
		 */
		//if (isPost ('ARL_Submit')) {// LS currently use old version, todo: uncomment when FormData version will be 3.31.0-2013.03.27
		$this->PerformFileUploading ();
		// LS currently use old version, todo: uncomment when FormData version will be 3.31.0-2013.03.27
		/*
		} else {
			$this -> Message_AddError ('Submit button is not pressed', $this -> Lang_Get ('error'));
		}
		*/
	}
	
	// ---

	/**
	 * Получить сообщение об ошибке по коду
	 *
	 * @param $iError		код ошибки
	 * @return string
	 */
	protected function GetUploadError($iError) {
		switch ($iError) {
			case UPLOAD_ERR_OK:
				return 'There is no error, the file uploaded with success';
			case UPLOAD_ERR_INI_SIZE:
				return 'The uploaded file exceeds the upload_max_filesize directive in php.ini';
			case UPLOAD_ERR_FORM_SIZE:
				return 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form';
			case UPLOAD_ERR_PARTIAL:
				return 'The uploaded file was only partially uploaded';
			case UPLOAD_ERR_NO_FILE:
				return 'No file was uploaded';
			case UPLOAD_ERR_NO_TMP_DIR:
				return 'Missing a temporary folder. Introduced in PHP 4.3.10 and PHP 5.0.3';
			case UPLOAD_ERR_CANT_WRITE:
				return 'Failed to write file to disk. Introduced in PHP 5.1.0';
			case UPLOAD_ERR_EXTENSION:
				return 'File upload stopped by extension. Introduced in PHP 5.2.0';
			default:
				return 'Unknown error';
		}
	}

	// ---

	/**
	 * Выполнить загрузку файла
	 *
	 * @return bool
	 */
	protected function PerformFileUploading () {
		/*
		 * если был прислан файл
		 */
		if (isset ($_FILES ['audio'])) {
			/*
			 * и он загружен на сервер
			 */
			if (is_uploaded_file ($_FILES ['audio']['tmp_name'])) {
				/*
				 * поместить файл в нужный каталог
				 */
				list ($sFile, $sErrorMsg) = $this->PluginAudiorecordslight_Fileloader_UploadAudioFile ($_FILES ['audio']);
				/*
				 * если произошла ошибка при перемещении
				 */
				if (!$sFile) {
					$this->Message_AddError ($sErrorMsg, $this->Lang_Get ('error'));
					return false;
				}
			/*
			 * если была ошибка при загрузке на сервер (ограничения сервера) - сообщить
			 */
			} elseif (isset($_FILES ['audio']['error'])) {
				$this->Message_AddError ($this->GetUploadError ($_FILES ['audio']['error']), $this->Lang_Get ('error'));
				return false;
			}
		/*
		 * если указана ссылка для загрузки
		 */
		} elseif (isPost ('audio_url') and getRequest ('audio_url') and !in_array (getRequest ('audio_url'), array ('', 'http://'))) {
			/*
			 * если нужно загрузить на сервер файл по ссылке
			 */
			if (getRequest ('download_from_url')) {
				list ($sFile, $sErrorMsg) = $this->PluginAudiorecordslight_Fileloader_DownloadAudioFile (getRequest ('audio_url'));
				/*
				 * если произошла ошибка при загрузке
				 */
				if (!$sFile) {
					$this->Message_AddError ($sErrorMsg, $this->Lang_Get ('error'));
					return false;
				}
			} else {
				/*
				 * использовать прямую ссылку
				 */
				$sFile = getRequest ('audio_url');
			}

		/*
		 * не указан файл
		 */
		} else {
			$this->Message_AddError ($this->Lang_Get ('plugin.audiorecordslight.WrongRequest'), $this->Lang_Get ('error'));
			return false;
		}

		/*
		 * если есть файл - указать для аякса ответ
		 */
		if (isset ($sFile)) {
			$this->Viewer_AssignAjax ('sText', $this->PluginAudiorecordslight_Fileloader_BuildAudioTag ($sFile));
		}
	}

}

?>