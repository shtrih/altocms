<?php
/*-------------------------------------------------------
*
*	Plugin "Commentreport"
*	Author: Vladimir Yuriev (extravert)
*	Site: altocms.ru
*	Contact e-mail: support@lsmods.ru
*
---------------------------------------------------------
*/

class PluginCommentreport_ActionAjax extends PluginCommentreport_Inherit_ActionAjax {

    protected function RegisterEvent() {
		parent::RegisterEvent();
		$this->AddEvent('commentreport','EventAjaxCommentreport');
	}
		
    public function EventAjaxCommentreport(){

		$this->oUserCurrent=$this->User_GetUserCurrent();

		if (!$this->oUserCurrent) {
            $this->Message_AddErrorSingle($this->Lang_Get('system_error'),$this->Lang_Get('error'));
            return;
        }

		if (!getRequest('comment_id') || !is_numeric(getRequest('comment_id'))) {
            $this->Message_AddErrorSingle($this->Lang_Get('system_error'),$this->Lang_Get('error'));
            return;
        }

		if (!getRequest('url')) {
            $this->Message_AddErrorSingle($this->Lang_Get('system_error'),$this->Lang_Get('error'));
            return;
        }

		if (!$oComment=$this->Comment_GetCommentById(getRequest('comment_id'))) {
            $this->Message_AddErrorSingle($this->Lang_Get('system_error'),$this->Lang_Get('error'));
            return;
        }

		foreach(Config::Get('plugin.commentreport.report_emails') as $email) {
			$this->Notify_Send($email,'notify.commentreport.tpl',$this->Lang_Get('plugin.commentreport.mailtitle'),array('oUser'=>$this->oUserCurrent,'oComment'=>$oComment,'url'=>getRequest('url'),'reason'=>$this->Text_Parser(getRequest('reason'))),'commentreport');
		}

		$this->Message_AddNoticeSingle($this->Lang_Get('plugin.commentreport.success'),$this->Lang_Get('attention'));
		return;

    }


}
?>