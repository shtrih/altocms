<?php
/*---------------------------------------------------------------------------
 * @Project: Alto CMS
 * @Project URI: http://altocms.com
 * @PluginId: counters
 * @PluginName: Counters
 * @Description: Counters for topics views
 * @Copyright: Alto CMS Team
 * @License: GNU GPL v2 & MIT
 *----------------------------------------------------------------------------
 */

/**
 * @package plugin Counters
 */

class PluginCounters_HookCounters extends Hook {

    protected $aConfig;

    public function RegisterHook() {


        $this->aConfig = C::GetData('plugin.counters');
        if ($this->aConfig['show_topic.enable']) {
            $this->_addHook('show_topic', 'template_topic_show_end', 'CountTopicShow');
            $this->AddHookTemplate('topic_show_info', array($this, 'DisplayTopicCounter'));
        }
    }

    protected function _addHook($sConfigKey, $sHookName, $sMethod) {

        if ($this->aConfig[$sConfigKey . '.enable']) {
            $aIgnore = $this->aConfig[$sConfigKey . '.ignore'];
            $bHookEnable = true;
            if ($aIgnore) {
                if (isset($aIgnore['agent']) && isset($_SERVER['HTTP_USER_AGENT'])) {
                    foreach ($aIgnore['agent'] as $sMask) {
                        if (strpos($_SERVER['HTTP_USER_AGENT'], $sMask) !== false) {
                            $bHookEnable = false;
                            break;
                        }
                    }
                }
            }
            if ($bHookEnable) {
                $this->AddHook($sHookName, $sMethod);
            }
        }
    }

    public function CountTopicShow($aParams) {

        if ((isset($aParams['oTopic']) || isset($aParams['topic'])) && isset($aParams['bTopicList'])) {
            /** @var ModuleTopic_EntityTopic $oTopic */
            $oTopic = (isset($aParams['oTopic']) ? $aParams['oTopic'] : $aParams['topic']);
            if (!$aParams['bTopicList'] && $oTopic && $oTopic->getPublish()) {
                $aIgnore = $this->aConfig['show_topic.ignore'];
                $bHookEnable = true;
                if ($aIgnore) {
                    if (E::IsAdmin() && in_array('admin', $aIgnore)) {
                        $bHookEnable = false;
                    } elseif (in_array('owner', $aIgnore) && $oTopic->getUserId() == E::UserId()) {
                        $bHookEnable = false;
                    }
                }
                if ($this->aConfig['show_topic.check_session']) {
                    if (E::ModuleSession()->Get('show_topic_' . $oTopic->getId()) || empty($_COOKIE)) {
                        return;
                    }
                }
                if ($bHookEnable) {
                    E::Module('PluginCounters\Counter')->IncCounter('topic', $oTopic->getId(), 'show');
                    if ($this->aConfig['show_topic.check_session']) {
                        E::ModuleSession()->Set('show_topic_' . $oTopic->getId(), 1);
                    }
                }
            }
        }
    }

    public function DisplayTopicCounter($aParams) {

        if (isset($aParams['oTopic']) || isset($aParams['topic'])) {
            $oTopic = (isset($aParams['oTopic']) ? $aParams['oTopic'] : $aParams['topic']);
            E::ModuleViewer()->Assign('oTopic', $oTopic);
            return E::ModuleViewer()->Fetch(Plugin::GetTemplateDir(__CLASS__) . 'tpls/hook.topic_show_counter.tpl');
        }
        return null;
    }

}

// EOF