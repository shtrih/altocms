<?php

class PluginFeedback_ModuleFeedback extends Module {

    /**
     * @var PluginFeedback_ModuleFeedback_MapperFeedback
     */
    protected $oMapper;

    public function Init() {
        $this->oMapper = E::GetMapper(__CLASS__);
    }

    public function getFields() {

    }

    public function update($iFeedbackId, $sWebPath, $bActive, $sTitle, $sContent) {
        $sParsedContent = E::ModuleText()->Parser($sContent);

        $this->oMapper->update($iFeedbackId, $sWebPath, $bActive, $sTitle, $sParsedContent, $sContent);
    }
}
