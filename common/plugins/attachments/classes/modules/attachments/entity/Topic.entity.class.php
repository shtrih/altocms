<?php
//
//  Attachments plugin
//  (P) Rafrica.net Studio, 2010 - 2012
//  http://we.rafrica.net/
//

class PluginAttachments_ModuleAttachments_EntityTopic extends PluginAttachments_Inherit_ModuleTopic_EntityTopic{    
	protected static $size_types = array('б', 'кб', 'мб', 'гб');
	
	public function getAttachments(){
		return $this->PluginAttachments_Attachments_GetAttachedFilesByTopicId($this->getId());
	}
	
	public function getAttachmentsForDisplay(){
		$attachments = $this->getAttachments();
		$return = array();
		foreach ($attachments as $attachment) {
			if ($attachment['attachment_extension'] != 'mp3') {
				$return[] = $attachment;
			}
		}
		
		return $return;		
	}
	
	public function getAttachmentsByExtension($sExtension){
		return $this->PluginAttachments_Attachments_GetAttachedFilesByTopicId($this->getId());
	} 
	
	public static function humanizeAttachmentSize($size) {
		$type = 0;
		while ($size > 1024 && $type < 3) {
			$type++;
			$size = $size / 1024;
		}

		$size = round($size, 1);
		return $size . ' ' . self::$size_types[$type];
	}
}
