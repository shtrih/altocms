<?php
/*
	Audiorecordslight plugin
	(P) PSNet, 2008 - 2013
	http://psnet.lookformp3.net/
	http://livestreet.ru/profile/PSNet/
	https://catalog.livestreetcms.com/profile/PSNet/
	http://livestreetguide.com/developer/PSNet/
*/

class PluginAudiorecordslight_ModuleAudiorecordslight_MapperAudiorecordslight extends Mapper {

	public function GetAttachedFilesByTopicID ($FileExtension, $TopicID, $SelectLimit) {
		$sql = "SELECT *
			FROM
				attachments
			WHERE
				attachments.attachment_extension = ?
				AND
				attachments.topic_id = ?d
			LIMIT ?d
		";
		return $this -> oDb -> select ($sql, $FileExtension, $TopicID, $SelectLimit);
	}
	
}

?>