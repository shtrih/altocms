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

class PluginCounters_ModuleTopic extends PluginCounters_Inherits_ModuleTopic {

    public function GetTopicsAdditionalData($aTopicsId, $aAllowData = null) {

        $aTopics = parent::GetTopicsAdditionalData($aTopicsId, $aAllowData);
        if ($aTopics && C::GetData('plugin.counters.show_topic.enable')) {
            $aTopicsId = array_keys($aTopics);
            $aCounters = E::Module('PluginCounters\Counter')->GetCountersByTargetsId('topic', $aTopicsId, 'show');
            foreach($aCounters as $iTopicId => $iCounter) {
                $aTopics[$iTopicId]->setShowCounter($iCounter);
            }
        }
        return $aTopics;
    }

}

// EOF