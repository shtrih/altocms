<?php
/*
	Audiorecordslight plugin
	(P) PSNet, 2008 - 2013
	http://psnet.lookformp3.net/
	http://livestreet.ru/profile/PSNet/
	https://catalog.livestreetcms.com/profile/PSNet/
	http://livestreetguide.com/developer/PSNet/
*/

if (!class_exists ('Plugin')) {
	die ('Kokobubble!');
}

class PluginAudiorecordslight extends Plugin {

	public function Activate () {
		return true;
	}
	
	// ---

	public function Init () {}
	
	// ---
	
	protected $aInherits = array (
		'entity' => array ('ModuleTopic_EntityTopic'),
	);

}

?>