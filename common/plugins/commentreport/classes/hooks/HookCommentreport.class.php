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

class PluginCommentreport_HookCommentreport extends Hook {

    public function RegisterHook() {
        $this->AddHook('template_comment_action', 'link');
		$this->AddHook('template_body_end', 'form');
    }

    public function link($aVars) {
		$this->Viewer_Assign('oComment',$aVars['comment']);
        return $this->Viewer_Fetch(Plugin::GetTemplatePath(__CLASS__) . 'comment_report_link.tpl');
    }

	public function form() {
        return $this->Viewer_Fetch(Plugin::GetTemplatePath(__CLASS__) . 'form.tpl');
    }

}

?>
