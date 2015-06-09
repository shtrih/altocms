<?php
/*---------------------------------------------------------------------------
 * @Project: Alto CMS
 * @Project URI: http://altocms.com
 * @Plugin Name: Similar Topics
 * @Plugin ID: similartopics
 * @Description: Similar Topics (defined by tags)
 * @Copyright: Alto CMS Team
 * @License: GNU GPL v2 & MIT
 *----------------------------------------------------------------------------
 */

class PluginSimilartopics_HookSimilartopics extends Hook {

    public function RegisterHook() {

        if ($aWidgetParams = Config::Get('plugin.similartopics.widget_showtopic')) {
            if (isset($aWidgetParams['display']) && $aWidgetParams['display']) {
                $this->AddHookTemplate('topic_show_end', array($this, 'TplTopicShowEnd'));
            }
        }
    }

    public function TplTopicShowEnd($aParams) {

        if ((!isset($aParams['bTopicList']) || !$aParams['bTopicList']) && (isset($aParams['topic'])) || isset($aParams['oTopic'])) {
            if (isset($aParams['topic'])) {
                E::ModuleViewer()->Assign('oTopic', $aParams['topic']);
            }
            E::ModuleViewer()->Assign('aWidgetParams', Config::Get('plugin.similartopics.widget_showtopic'));
            return E::ModuleViewer()->Fetch(Plugin::GetTemplateDir(__CLASS__) . 'tpls/widgets/widget.similartopics_bottom.tpl');
        }
        return null;
    }
}

// EOF