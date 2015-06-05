<?php
/*-------------------------------------------------------
*
*	Plugin "Commentreport"
*	Author: Vladimir Yuriev (extravert)
*	Official site: altocms.ru
*	Contact e-mail: support@lsmods.ru
*
---------------------------------------------------------
*/

if (!class_exists('Plugin')) {
  die('Hacking attemp!');
}

class PluginCommentreport extends Plugin {

	protected $aInherits=array(
	   'action'=>array('ActionAjax'),
    );

	public function Init() {
		$this->Viewer_AppendScript(Plugin::GetTemplateWebPath(__CLASS__).'js/report.js');
        return true;
	}

	public function Activate() {
		return true;
	}

	public function Deactivate() {
		return true;
	}
}
