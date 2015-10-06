<?php

class PluginFeedback_ModuleFeedback extends Module {

    /**
     * @var PluginFeedback_ModuleFeedback_MapperFeedback
     */
    protected $oMapper;

    public function Init() {
        $this->oMapper = E::GetMapper(__CLASS__);
    }

    public function updateFeedback(PluginFeedback_ModuleFeedback_EntityFeedback $oFeedback) {
        $this->oMapper->updateFeedback(
            $oFeedback->getFeedbackId(),
            $oFeedback->getFeedbackWebpath(),
            $oFeedback->getFeedbackActive(),
            $oFeedback->getFeedbackTitle(),
            $oFeedback->getFeedbackText(),
            $oFeedback->getFeedbackTextSource()
        );
    }

    public function getFeedbackById($iItemId) {
        return $this->oMapper->getFeedbackById($iItemId);
    }

    public function addField(ModuleTopic_EntityField $oField) {
        return $this->oMapper->addField($oField);
    }

    public function getFields($iFeedbackId) {
        return $this->oMapper->getFields($iFeedbackId);
    }

    public function getField($iFieldId) {
        return $this->oMapper->getField($iFieldId);
    }

    public function updateField(ModuleTopic_EntityField $oField) {
        return $this->oMapper->updateField($oField);
    }

    public function removeField($iFieldId) {
        return $this->oMapper->removeField($iFieldId);
    }
}
