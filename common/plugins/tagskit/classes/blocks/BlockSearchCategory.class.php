<?php

class PluginTagskit_BlockSearchCategory extends Block {
	/**
	 * Запуск обработки
	 */
	public function Exec() {
		$oSmarty=$this->Viewer_GetSmartyObject();
		if ($oBlog=$oSmarty->getTemplateVars('oBlog')) {
			$this->Viewer_Assign('aTagsTkBlog', $oBlog);
		}

        $aResult = $this->PluginTagskit_Main_GetTopicTagsByTagsCategory(Config::Get('plugin.tagskit.tags_list_white_category'), Config::Get('plugin.tagskit.white_list_sort'));
        $this->Viewer_Assign('aTagsTkWhiteCategory', $aResult);
	}
}