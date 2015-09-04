<?php

class PluginLegacyspoilers_ModuleTopic extends PluginLegacyspoilers_Inherit_ModuleTopic {
/*
    public function GetTopicsByArrayId($aTopicId) {
        $aTopics = parent::GetTopicsByArrayId($aTopicId);
        foreach ($aTopics as $k => $oTopic) {
            $aTopics[$k]->setText(E::Module('PluginLegacyspoilers_ModuleLegacyspoilers')->MakeCorrection($oTopic->getText()));
            $aTopics[$k]->setTextShort(E::Module('PluginLegacyspoilers_ModuleLegacyspoilers')->MakeCorrection($oTopic->getTextShort()));
        }
        return $aTopics;
    }
*/

    public function getText() {
        return E::Module('PluginLegacyspoilers_ModuleLegacyspoilers')->MakeCorrection(
            parent::getText()
        );
    }

    public function getTextShort() {
        return E::Module('PluginLegacyspoilers_ModuleLegacyspoilers')->MakeCorrection(
            parent::getTextShort()
        );
    }
}
