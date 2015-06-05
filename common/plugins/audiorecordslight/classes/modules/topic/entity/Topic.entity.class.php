<?php
/*
	Audiorecordslight plugin
	(P) PSNet, 2008 - 2013
	http://psnet.lookformp3.net/
	http://livestreet.ru/profile/PSNet/
	https://catalog.livestreetcms.com/profile/PSNet/
	http://livestreetguide.com/developer/PSNet/
*/

class PluginAudiorecordslight_ModuleTopic_EntityTopic extends PluginAudiorecordslight_Inherit_ModuleTopic_EntityTopic {

	/**
	 * Получить список аудио-файлов у топика
	 *
	 * @return array
	 */
	public function getMP3FilesARL () {
		$aEnclosures = array ();

		if (Config::Get ('plugin.audiorecordslight.Plugin_Attachments_Mode') and class_exists ('PluginAttachments')) {
			if ($aFiles = $this -> PluginAudiorecordslight_Audiorecordslight_GetAttachedAudio ($this -> getId ())) {
				$sSiteURL = Config::Get ('path.root.web');
				foreach ($aFiles as $aFile) {
					$aEnclosures [] = $sSiteURL . $aFile ['attachment_url'];
				}
			}
		}

		if (Config::Get ('plugin.audiorecordslight.Parse_Topic_Text_Mode')) {
			$aMatches = "";
			if (preg_match_all ("#href=[\"'](.*\.mp3)[\"']#iuU", $this -> getText (), $aMatches) !== false) {
				for ($i = 0; $i < count ($aMatches [1]); $i ++) {
					$aEnclosures [] = $aMatches [1] [$i];
				}
			}
		}
		return $aEnclosures;
	}

}

?>