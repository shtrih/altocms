<?php
/*
	Audiorecordslight plugin
	(P) PSNet, 2008 - 2013
	http://psnet.lookformp3.net/
	http://livestreet.ru/profile/PSNet/
	https://catalog.livestreetcms.com/profile/PSNet/
	http://livestreetguide.com/developer/PSNet/
*/

class PluginAudiorecordslight_ModuleAudiorecordslight extends Module {

	protected $oMapper;

	// ---

	public function Init () {
		$this -> oMapper = Engine::GetMapper (__CLASS__);
	}

	// ---

	public function GetAttachedAudio ($iTopicId) {
		return $this -> oMapper -> GetAttachedFilesByTopicID ('mp3', $iTopicId, 100);
	}

}

?>