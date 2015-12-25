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

class PluginCounters_ModuleTopic_EntityTopic extends PluginCounters_Inherits_ModuleTopic_EntityTopic {

    public function getShowCounter() {

        return intval($this->getProp('show_counter'));
    }

    public function setShowCounter($iCounter) {

        $this->setProp('show_counter', $iCounter);
    }

}

// EOF