<?php

class PluginFeedback_ModuleFeedback_EntityFeedback extends Entity
{
    public function getFields() {
        $aResult = [];
        $iFeedbackId = $this->getFeedbackId();
        if ($iFeedbackId)
            $aResult = E::Module('PluginFeedback_ModuleFeedback')->getFields($iFeedbackId);

        return $aResult;
    }
}
