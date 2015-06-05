<?php

class PluginHidespoiler_ModuleTopic extends PluginHidespoiler_Inherit_ModuleTopic {

    public function GetTopicsByArrayId($aTopicId) {
        $aTopics = parent::GetTopicsByArrayId($aTopicId);
        foreach ($aTopics as $k => $oTopic) {
            $aTopics[$k]->setText($this->PluginHidespoiler_ModuleHidespoiler_MakeCorrection($oTopic->getText(), $oTopic->getId()));
            $aTopics[$k]->setTextShort($this->PluginHidespoiler_ModuleHidespoiler_MakeCorrection($oTopic->getTextShort(), $oTopic->getId()));
        }
        return $aTopics;
    }
}