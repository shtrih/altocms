<?php
//
//  Attachments plugin
//  (P) Rafrica.net Studio, 2010 - 2012
//  http://we.rafrica.net/
//

class PluginAttachments_BlockUnlinked extends Block {
	public function Exec() {
		if ($this->User_IsAuthorization()){
			$oUserCurrent = $this->User_GetUserCurrent();
			$aUnlinkedFiles = $this->PluginAttachments_Attachments_GetUnlinkedAttachmentsByUserId($oUserCurrent->getId());

			$this->Viewer_Assign('aUnlinkedFiles',$aUnlinkedFiles);
		}
	}
}

?>