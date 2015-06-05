<?php
//
//  Attachments plugin
//  (P) Rafrica.net Studio, 2010 - 2012
//  http://we.rafrica.net/
//

class PluginAttachments_ModuleAttachments_MapperAttachments extends Mapper {

	public function AttachFile($sDestFullPath,$sName,$iFileSize,$sExtension,$iTopicId,$iFormId,$iUserId){
		if ($iTopicId) {
			$sql = "SELECT max(`sort`) FROM attachments WHERE topic_id = ?d AND topic_id <> ''";
			$rows = $this->oDb->select($sql,$iTopicId);
		} else {
			$sql = "SELECT max(`sort`) FROM attachments WHERE attachment_form_id = ?d AND attachment_form_id <> ''";
			$rows = $this->oDb->select($sql,$iFormId);
		}
		$max = (int) reset($rows[0]);
		
		$sql = "INSERT INTO  `attachments` (
			`attachment_id` ,
			`topic_id` ,
			`user_id` ,
			`attachment_url`,
			`attachment_name`,
			`attachment_size`,
			`attachment_extension`,
			`attachment_form_id`,
			`sort`
		)
		VALUES ( NULL , ?d, ?d, ?, ?, ?d, ?, ?, ?d);";

		return $this->oDb->query($sql,$iTopicId,$iUserId,$sDestFullPath,$sName,$iFileSize,$sExtension,$iFormId,$max+1); 
	}
	
	////////////////////////////////////////////////////////////////////////////////
	public function GetAttachedFilesByTopicId($iTopicId){
		$sql 		= "SELECT * FROM attachments WHERE topic_id = ?d AND topic_id <> '' order by sort";
		$aResult	= array();
		if ($aRows = $this->oDb->select($sql,$iTopicId)){
			$aResult	= $aRows;
		}		
		return $aResult;
	}
	
	////////////////////////////////////////////////////////////////////////////////
	public function GetAttachedFilesByFormId($iFormId){
		$sql = "SELECT * FROM attachments WHERE attachment_form_id = ? AND attachment_form_id <> '' order by sort";
		if ($aRows=$this->oDb->select($sql,$iFormId)) {
			return $aRows;
		}else return false;		
	}	
	
	////////////////////////////////////////////////////////////////////////////////
	public function GetAttachedFilesByExtensionByTopicId($iTopicId, $sExtension){
		$sql = "SELECT * FROM attachments WHERE topic_id = ?d AND attachment_extension = ? order by sort";
		if ($aRows=$this->oDb->select($sql,$iTopicId,$sExtension)) {
			return $aRows;
		}else return array();		
	}

	////////////////////////////////////////////////////////////////////////////////
	public function GetFilePathById($iFileId){
		$sql = "SELECT attachment_url FROM attachments WHERE attachments.attachment_id = ?d";
		if ($aRows=$this->oDb->select($sql,$iFileId)) {
			return $aRows;
		}else return false;		
	
	}
	
	////////////////////////////////////////////////////////////////////////////////
	public function GetFileNameById($iFileId){
		$sql = "SELECT attachment_name FROM attachments WHERE attachments.attachment_id = ?d";
		if ($aRows=$this->oDb->select($sql,$iFileId)) {
			return $aRows;
		}else return false;		
	
	}
	
	////////////////////////////////////////////////////////////////////////////////
	public function GetFileById($iFileId){
		$sql = "SELECT * FROM attachments WHERE attachments.attachment_id = ?d";
		if ($aRows=$this->oDb->select($sql,$iFileId)) {
			return $aRows;
		}else return false;		
	}
	
	////////////////////////////////////////////////////////////////////////////////
	public function DeleteFileById($iFileId){
		$sql = "DELETE FROM attachments WHERE attachments.attachment_id = ?d LIMIT 1";
		return $this->oDb->query($sql,$iFileId); 
	}
	
	/////////////////////////////////////////////////////////////////////////////////
	public function LinkFormIdToTopicId($iFormId,$iTopicId){
		$sql = "UPDATE  attachments SET `topic_id` = ?d, `attachment_form_id` = '' WHERE  attachment_form_id = ?";
		return $this->oDb->query($sql,$iTopicId,$iFormId);	
	}
	
	//////////////////////////////////////////////////////////////////////////////////
	public function LinkFilesToUserTopic($iTopicId,$iUserId){
		$sql = "UPDATE  attachments SET `topic_id` = ?d, `attachment_form_id` = '' WHERE  attachment_form_id <> '' AND user_id = ?d";
		return $this->oDb->query($sql,$iTopicId,$iUserId);	
	}
	
	//////////////////////////////////////////////////////////////////////////////////
	public function GetUnlinkedAttachmentsByUserId($iUserId){
		$sql = "SELECT * FROM attachments WHERE user_id = ?d AND topic_id = 0";
		if ($aRows=$this->oDb->select($sql,$iUserId)) {
			return $aRows;
		}else return false;		
	}
	
	///////////////////////////////////////////////////////////////////////////////////
	public function LinkFileToFormId($iFileId,$iFormId){
		$sql = "UPDATE  attachments SET `attachment_form_id` = ? WHERE attachment_id = ?d";
		return $this->oDb->query($sql,$iFormId,$iFileId);	
	}
	public function LinkFileToTopicId($iFileId,$iTopicId){
		$sql = "UPDATE  attachments SET `topic_id` = ?d, `attachment_form_id` = '' WHERE attachment_id = ?d";
		return $this->oDb->query($sql,$iTopicId,$iFileId);	
	}	
	
	///////////////////////////////////////////////////////////////////////////////////
	public function sortForm($iFormId, $sort){
		$existing = $this->GetAttachedFilesByFormId($iFormId);
		foreach ($existing as $file) {
			$id = (int) $file['attachment_id'];
			if (!isset($sort[$id]) || $sort[$id] == (int) $file['sort']) {
				continue;
			}
			$sql = "UPDATE attachments SET `sort` = ?d WHERE attachment_id = ?d";
			$this->oDb->query($sql,$sort[$id],$id);
		}
	}
	public function sortTopic($iTopicId, $sort){
		$existing = $this->GetAttachedFilesByTopicId($iTopicId);
		foreach ($existing as $file) {
			$id = (int) $file['attachment_id'];
			if (!isset($sort[$id]) || $sort[$id] == (int) $file['sort']) {
				continue;
			}
			$sql = "UPDATE attachments SET `sort` = ?d WHERE attachment_id = ?d";
			$this->oDb->query($sql,$sort[$id],$id);
		}
	}
}

?>
