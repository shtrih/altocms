<?php
/*
	Audiorecordslight plugin
	(P) PSNet, 2008 - 2013
	http://psnet.lookformp3.net/
	http://livestreet.ru/profile/PSNet/
	https://catalog.livestreetcms.com/profile/PSNet/
	http://livestreetguide.com/developer/PSNet/
*/

class PluginAudiorecordslight_ModuleFileloader extends Module {

	public function Init () {}
	
	// ---

	public function CreateDirectory ($sDirToCheck) {
		if (!is_dir ($sDirToCheck)) {
			@mkdir ($sDirToCheck, 0755, true);
		}
	}
	
	// ---
	
	public function GetDirForAudio () {
		$sDir = Config::Get ('path.root.server') . Config::Get('path.uploads.root') . '/audiorecordslight/' . $this -> User_GetUserCurrent () -> getId () . date ('/Y/m/d/');
		$this -> CreateDirectory ($sDir);
		return $sDir;
	}
	
	// ---
	
	public function UploadAudioFile ($aFile) {
		if (!is_array ($aFile) or !isset ($aFile ['tmp_name'])) {
			return array (false, $this -> Lang_Get ('system_error'));
		}
		
		// base rules checking
		$sFileExtension = pathinfo ($aFile ['name'], PATHINFO_EXTENSION);
		if (strtolower ($sFileExtension) != 'mp3') {
			return array (false, $this -> Lang_Get ('plugin.audiorecordslight.Not_Mp3'));
		}
		if ($aFile ['size'] > Config::Get ('plugin.audiorecordslight.Max_File_Size')) {
			return array (false, $this -> Lang_Get ('plugin.audiorecordslight.Size_Limit') . Config::Get ('plugin.audiorecordslight.Max_File_Size') / 1024 . 'Mb');
		}
		
		// build filename
		$sFileNew = $this -> GetDirForAudio () . func_generator (12) . '.' . $sFileExtension;
		if (!move_uploaded_file ($aFile ['tmp_name'], $sFileNew)) {
			return array (false, $this -> Lang_Get ('system_error'));
		}

		return array ($this -> Image_GetWebPath ($sFileNew), '');
	}
	
	// ---
	
	public function DownloadAudioFile ($sUrl) {
		// check url is correct
		if (!preg_match ('#^http(?:s)?://(?:[\w-]+\.)+(/.*)?#iuU', $sUrl)) {
			return array (false, $this -> Lang_Get ('plugin.audiorecordslight.UrlFileIsNotCorrect'));
		}
		
		// check headers
		$aHeaders = get_headers ($sUrl, 1);
		if (!isset ($aHeaders ['Content-Type']) or !in_array ($aHeaders ['Content-Type'], array ('audio/mpeg', 'audio/mpeg3', 'audio/x-mpeg-3'))) {
			return array (false, $this -> Lang_Get ('plugin.audiorecordslight.Not_Mp3'));
		}
		
		$iMaxSizeKb = Config::Get ('plugin.audiorecordslight.Max_File_Size') / 1024;
		
		// if cant create new file
		$sTempFileName = Config::Get ('sys.cache.dir') . func_generator (12);
		if (!$fTemp = fopen ($sTempFileName, 'w')) {
			return array (false, $this -> Lang_Get ('system_error'));
		}
		
		// if cant open remote file
		if (!$oFile = fopen ($sUrl, 'r')) {
			return array (false, $this -> Lang_Get ('system_error'));
		}
		
		$iSizeKb = 0;
		// write directly to new file for not to store in memory
		while (!feof ($oFile) and $iSizeKb < $iMaxSizeKb) {
			$sContent = fread ($oFile, 1024);
			$iSizeKb ++;
			fwrite ($fTemp, $sContent);
		}
		
		// file size limit exceed
		$bSizeLimitError = false;
		if (!feof ($oFile)) $bSizeLimitError = true;
		
		fclose ($oFile);
		fclose ($fTemp);

		if ($bSizeLimitError) {
			@unlink ($sTempFileName);
			return array (false, $this -> Lang_Get ('plugin.audiorecordslight.Size_Limit') . Config::Get ('plugin.audiorecordslight.Max_File_Size') / 1024 . 'Mb');
		}

		// we should trust that this is mp3 file, just add .mp3 extension
		
		// move file to new location, build filename
		$sFileNew = $this -> GetDirForAudio () . func_generator (12) . '.mp3';
		if (!copy ($sTempFileName, $sFileNew)) {
			return array (false, $this -> Lang_Get ('system_error'));
		}
		@unlink ($sTempFileName);
		return array ($this -> Image_GetWebPath ($sFileNew), '');
	}
	
	// ---
	
	public function BuildAudioTag ($sFile) {
		return '<' . Config::Get ('plugin.audiorecordslight.Tag_Name') . ' src="' .
			$sFile .
			'" title="Audio Records"></' . Config::Get ('plugin.audiorecordslight.Tag_Name') . '>';
	}
	
}

?>