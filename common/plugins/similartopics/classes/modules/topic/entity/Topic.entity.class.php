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

class PluginSimilartopics_ModuleTopic_EntityTopic extends PluginSimilartopics_Inherits_ModuleTopic_EntityTopic {

    public function GetSimilarTopics($nLimit = null) {

        return E::ModuleTopic()->GetSimilarTopics($this, $nLimit);
    }

    public function CountSimilarTopics() {

        return E::ModuleTopic()->CountSimilarTopics($this);
    }

    public function getIntroText($sPostfix = '...', $bIgnoreShortText = false) {

        $sText = parent::getIntroText($sPostfix, $bIgnoreShortText);
        if (!$sText) {
            $sText = $this->getTextShort();
        }
        if (!$sText) {
            $sText = $this->getText();
        }
        $iMaxLenth = Config::Get('plugin.similartopics.widget_showtopic.text_maxlen');
        $sText = strip_tags($sText);
        if ($iMaxLenth) {
            $sText = F::TruncateText($sText, $iMaxLenth, '...', true);
        }

        return $sText;
    }

}

// EOF