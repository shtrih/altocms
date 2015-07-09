<?php

class PluginBetterspoilers_ModuleTopic extends PluginBetterspoilers_Inherit_ModuleTopic {

    public function GetTopicsByArrayId($aTopicId) {
        $aTopics = parent::GetTopicsByArrayId($aTopicId);
        foreach ($aTopics as $k => $oTopic) {
            $aTopics[$k]->setText(E::Module('PluginBetterspoilers_ModuleBetterspoilers')->MakeCorrection($oTopic->getText()));
            $aTopics[$k]->setTextShort(E::Module('PluginBetterspoilers_ModuleBetterspoilers')->MakeCorrection($oTopic->getTextShort()));
        }
        return $aTopics;
    }
}