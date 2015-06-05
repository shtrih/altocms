<?php
//
//  Attachments plugin
//  (P) Rafrica.net Studio, 2010 - 2012
//  http://we.rafrica.net/
//

class PluginAttachments_ActionAttachments extends ActionPlugin {
	protected $oUserCurrent = null;

	public function Init() {
		if ($this->User_IsAuthorization()){
			$this->oUserCurrent=$this->User_GetUserCurrent();
		}
		$this->SetDefaultEvent('receive');

	}
	
	//*****************************************************************************************
	protected function RegisterEvent(){
		$this->AddEvent('receive','EventReceive');
		$this->AddEvent('get','EventGet');
		$this->AddEvent('delete','EventDelete');
		$this->AddEvent('link','EventLink');
		$this->AddEvent('sort','EventSort');
		$this->AddEvent('linkto','EventLinkToTopic');
		
		$this->AddEvent('debug','EventDebug');
		$this->AddEvent('debugreceiver','EventDebugReceiver');
	}
	
	//*****************************************************************************************
	protected function SetError($sMessage,$iToShow=0,$iToLog=1){
		$this->PluginAttachments_Attachments_SetError($sMessage,$iToShow,$iToLog);
	}
	
	//*****************************************************************************************
	protected function SetDebug($sMessage){
		$this->PluginAttachments_Attachments_SetDebug($sMessage);
	}
	
	//*****************************************************************************************
	protected function ShowErrors($iToScreen = 0){
		$aErrors = $this->PluginAttachments_Attachments_GetErrors();
		$sResult = '';

		if ($iToScreen == 1) {
			foreach ($aErrors as $sError){
				print $sError."\n";
			}
		}
		else {
			$sResult = join(', ', $aErrors);
		}
		
		return $sResult;
	}
	
	//*****************************************************************************************
	protected function CheckErrorStatus(){
		return $this->PluginAttachments_Attachments_CheckErrorStatus();
	}
	
	//*****************************************************************************************
	protected function CheckParams($sMode){
		if(!$this->oUserCurrent){
			$this->SetError($this->Lang_Get('plugin.attachments.upload_not_logged_in'),1,1);
			return false;
		}
		
		$this->SetDebug($sMode);
		
		if($sMode == 'upload'){
			$iTopicId = getRequest('topic_id');
		}elseif($sMode == 'delete'){
			$iFileId = $this->getParam(0);
			if($aFile = $this->PluginAttachments_Attachments_GetFileById($iFileId)){
				$iTopicId = $aFile['topic_id'];
			}else return false;
		}

		$iFormId = getRequest('form_id');
		if(!empty($iTopicId)){			
			if(!$oTopic = $this->Topic_GetTopicById($iTopicId)){
				$this->SetError('t: '.$iTopicId,1,1);
				return false;
			}
		}elseif(!empty($iFormId)){
			$this->SetDebug($iFormId);
		}else{
			if(empty($iFileId))	return false;
		}
		
		return true;
	
	}

	protected function getFileUploadErrorExplanation() {

	}

	//*****************************************************************************************
	protected function CheckFile(){
	
		if(!$_FILES['newfile']['error'] == 0) {
			switch ($_FILES['newfile']['error']) {
				case UPLOAD_ERR_INI_SIZE:
					$this->SetError($this->Lang_Get('plugin.attachments.upload_err_ini_size'), 1, 1);
					break;
				case UPLOAD_ERR_FORM_SIZE:
					$this->SetError($this->Lang_Get('plugin.attachments.upload_err_form_size'), 1, 1);
					break;
				case UPLOAD_ERR_PARTIAL:
					$this->SetError($this->Lang_Get('plugin.attachments.upload_err_partial'), 1, 1);
					break;
				case UPLOAD_ERR_NO_FILE:
					$this->SetError($this->Lang_Get('plugin.attachments.upload_err_no_file'), 1, 1);
					break;
				case UPLOAD_ERR_NO_TMP_DIR:
					$this->SetError($this->Lang_Get('plugin.attachments.upload_err_no_tmp_dir'), 1, 1);
					break;
				case UPLOAD_ERR_CANT_WRITE:
					$this->SetError($this->Lang_Get('plugin.attachments.upload_err_cant_write'), 1, 1);
					break;
				case UPLOAD_ERR_EXTENSION:
					$this->SetError($this->Lang_Get('plugin.attachments.upload_err_extension'), 1, 1);
					break;
				default: $this->SetError($this->Lang_Get('plugin.attachments.upload_err_unknown'),1,1);
			}  
			return false;
		}

		$iTopicId = getRequest('topic_id');
		if(!empty($iTopicId)){
			$aFiles = $this->PluginAttachments_Attachments_GetAttachedFilesByTopicId($iTopicId);
			if(count($aFiles) >= Config::Get('plugin.attachments.max_files_per_topic')){
				$this->SetError($this->Lang_Get('plugin.attachments.upload_error_topic_if_full'),1,1);
				return false;
			}
		}
		
		if(count($this->PluginAttachments_Attachments_GetUnlinkedAttachmentsByUserId($this->oUserCurrent->getId())) >= Config::Get('plugin.attachments.max_unattached_files_per_user')){
				$this->SetError($this->Lang_Get('plugin.attachments.upload_unattached_limit'),1,1);
				return false;
		}	
		
		$iFormId = getRequest('form_id');
		if(!empty($iFormId)){
			$aFiles = $this->PluginAttachments_Attachments_GetAttachedFilesByFormId($iFormId);
			if(count($aFiles) >= Config::Get('plugin.attachments.max_files_per_topic')){
				$this->SetError($this->Lang_Get('plugin.attachments.upload_error_topic_if_full'),1,1);
				return false;
			}
		}		
			
		if($this->oUserCurrent->getRating() < Config::Get('plugin.attachments.min_rating_to_post_files')){
			$this->SetError($this->Lang_Get('plugin.attachments.upload_rating_to_low'),1,1);
			return false;
		}
	
		if($_FILES['newfile']['size'] > Config::Get('plugin.attachments.max_filesize_limit')){
			$this->SetError($this->Lang_Get('plugin.attachments.upload_file_to_big'),1,1);
			return false;
		} 
		
		return true;
		
	}
	
	//*****************************************************************************************
	protected function EventLink(){
		$this -> Viewer_SetResponseAjax ('json');
		$iFileId = $this->getParam(0);
		$iFormId = $this->getParam(1);
		
		if((!empty($iFileId)) and (!empty($iFormId))){
			$this->PluginAttachments_Attachments_LinkFileToFormId($iFileId,$iFormId);
		} else {
			$this -> Message_AddErrorSingle ('error: empty fileid or formid', '');
		}
	}
	protected function EventLinkToTopic(){
		$this -> Viewer_SetResponseAjax ('json');
		$iFileId = $this->getParam(0);
		$iTopicId = $this->getParam(1);
		
		if((!empty($iFileId)) and (!empty($iTopicId))){
			$this->PluginAttachments_Attachments_LinkFileToTopicId($iFileId,$iTopicId);
		} else {
			$this -> Message_AddErrorSingle ('error: empty fileid or iTopicId', '');
		}
	}

	//*****************************************************************************************	
	protected function EventReceive(){
		if(!$this->CheckParams('upload')) die($this->Lang_Get('plugin.attachments.upload_universal_answer'));
		
		$sError = null;
		$iTopicId = getRequest('topic_id');
		$iFormId  = getRequest('form_id');

		if (isset($_FILES['newfile']) && $this->CheckFile() && is_uploaded_file($_FILES['newfile']['tmp_name'])) {
			if ($_FILES['newfile']['type'] == 'file/link') {
				$url = file_get_contents($_FILES['newfile']['tmp_name']);
				$file = file_get_contents($url);
				if (!$file) { return; }
				$tmpname = tempnam("/tmp", "upload_to_beta");
				file_put_contents($tmpname, $file);

				$_FILES['newfile']['tmp_name'] = $tmpname;
				$_FILES['newfile']['size'] = filesize($tmpname);
				$finfo = finfo_open(FILEINFO_MIME_TYPE);
				$_FILES['newfile']['type'] = finfo_file($finfo, $tmpname);
				finfo_close($finfo);			
			}

			$sFileName 	= $_FILES['newfile']['name'];
			$sTmpName	= $_FILES['newfile']['tmp_name'];
			$iFileSize	= $_FILES['newfile']['size'];
			$fileNameParts = explode(".",$sFileName);
			$sExtension = strtolower(array_pop($fileNameParts));
			$iUserId	= $this->oUserCurrent->getId();

			if(!($iId = $this->PluginAttachments_Attachments_AttachFile($sTmpName,$sFileName,$iFileSize,$sExtension,$iTopicId,$iFormId,$iUserId))){
				$sError = $this->ShowErrors();
			}
		}
		else {
			$sError = $this->ShowErrors();
		}

		$this->SetDebug($sError);
		
		if ($sError || empty($iId)) {
			$aFile = array(
				'name' => $_FILES['newfile']['name'],
				'size' => 0,
				'error' => $sError ?: 'Неизвестная ошибка при загрузке файла.'
			);
		}
		else {
			$aFile = array(
				'name' => $sFileName,
				'size' => $iFileSize,
				'url' => '/attachments/get/' . $iId,
				'id' => $iId
			);
		}

		$this->Viewer_Assign('data', array('files' => array($aFile)));
		
		$this->SetTemplateAction('upload');
	}

	//*****************************************************************************************	
	protected function EventGet(){
		if(!Config::Get('plugin.attachments.ShowAttachedFiles')){
			if(!$this->oUserCurrent) return Router::Action('error');
			else{
				if(!$this->oUserCurrent->isAdministrator()) return Router::Action('error');
			}
		}

		$iFileId = $this->getParam(0);	
		if ($sFullPath  = $this->PluginAttachments_Attachments_GetFilePathById($iFileId)){
			$sFullPath = Config::Get('path.root.server') . $sFullPath;
			if (!is_file($sFullPath)) {
				error_log('[Attachments] File not found: ' . $sFullPath);
				return Router::Action('error', '404');
			}
			$sFilename = $this->PluginAttachments_Attachments_GetFileNameById($iFileId);//so sorry
			$this->DoDownload($sFullPath, $sFilename);
		}
		else
			print $this->Lang_Get('plugin.attachments.upload_universal_answer');

		$this->SetTemplateAction('null');
	}
	
	protected function DoDownload($file, $filename) {
		$size = filesize($file);

		$filename = (strstr($_SERVER['HTTP_USER_AGENT'], 'MSIE'))
			? preg_replace('/\./', '%2e', $filename, substr_count($filename, '.') - 1)
			: $filename
		;

		$finfo = finfo_open(FILEINFO_MIME_TYPE);
		$type = finfo_file($finfo, $file);
		finfo_close($finfo);
		header('Content-Type: ' . $type);

		// http://mailman.nginx.org/pipermail/nginx-ru/2005-May/002145.html
		header("HTTP/1.1 206 Partial Content");
		header("Accept-Ranges: bytes");
		header("Content-Range: bytes 0-");
		// header('Content-Length: ' . $size);

		// Файл, размером более 15Мб отдаем как attachment, в ином случае, без оного заголовка, чтобы браузер сам решил, что с ним делать
		if ($size > 15 * 1024 * 1024) {
			header('Content-Disposition: attachment; filename="' . $filename . '"');
		}
		header("X-Xox: doDownload");
		header('X-Accel-Redirect: /internal-file-proxy' . str_replace(Config::Get('path.root.server'), '', $file));

		/*
		if(isset($_SERVER['HTTP_RANGE'])) {
			list($size_unit, $range_orig) = explode('=', $_SERVER['HTTP_RANGE'], 2);
			if ($size_unit == 'bytes') {
				list($range, $extra_ranges) = explode(',', $range_orig, 2);
			} else {
				$range = '-';
			}
		} else {
			$range = '-';
		}

		list($seek_start, $seek_end) = explode('-', $range, 2);

		$seek_end = (empty($seek_end)) ? ($size - 1) : min(abs(intval($seek_end)),($size - 1));
		$seek_start = (empty($seek_start) || $seek_end < abs(intval($seek_start))) ? 0 : max(abs(intval($seek_start)),0);

		header('Content-type: text/plain');

		if ($seek_start > 0 || $seek_end < ($size - 1)) {
			header('HTTP/1.1 206 Partial Content');
		}

		header('Accept-Ranges: bytes');
		header('Content-Range: bytes '.$seek_start.'-'.$seek_end.'/'.$size);

		header('Content-Disposition: attachment; filename="' . $filename . '"');
		header('Content-Length: '.($seek_end - $seek_start + 1));

		set_time_limit(0);

		$fp = fopen($file, 'rb');
		fseek($fp, $seek_start);

		while(is_resource($fp) && !feof($fp)) {
			print(fread($fp, 1024*1024));
			ob_flush();
			flush();
		}

		fclose($fp);
		*/
	}
	
	//*****************************************************************************************	
	protected function EventDelete(){
		$this -> Viewer_SetResponseAjax ('json');
		if(!$this->CheckParams('delete')) die($this->Lang_Get('plugin.attachments.upload_universal_answer'));
		
		$iFileId = $this->getParam(0);
		$this->PluginAttachments_Attachments_DeleteFileById($iFileId);
	}
	
	//*****************************************************************************************	
	protected function EventSort(){
		$this -> Viewer_SetResponseAjax('json');
		$this->PluginAttachments_Attachments_Sort($_POST);
	}
	
	//*****************************************************************************************	
	protected function EventDebugReceiver(){
		if (isset($_FILES['newfile'])) {
			print "Files array is set<br>";
			if(is_uploaded_file($_FILES['newfile']['tmp_name'])){
				print "File is uploaded<br>";
					if($this->CheckFile() == true){
						print "OK<br>";
					}else print $this->ShowErrors();	
			}else print "File is not uploaded<br>";
		}else print "Files array is not set <br>";
		$this->SetTemplateAction('null');
	}
	
	//*****************************************************************************************	
	protected function EventDebug(){
		print "OK";
		if(!$this->oUserCurrent) return Router::Action('error');
			else{
				if(!$this->oUserCurrent->isAdministrator()) return Router::Action('error');
		}
	}

	//*****************************************************************************************	
	public function EventShutdown(){}
}
