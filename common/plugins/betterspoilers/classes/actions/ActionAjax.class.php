<?php

class PluginBetterspoilers_ActionAjax extends PluginBetterspoilers_Inherit_ActionAjax {

    /**
     * Предпросмотр текста
     *
     */
    protected function EventPreviewText() {
        parent::EventPreviewText();
        $sTextResult = E::ModuleViewer()->getAssignedAjax('sText');
        $sTextResult = E::Module('PluginBetterspoilers_ModuleBetterspoilers')->MakeCorrection($sTextResult, 0);
        E::ModuleViewer()->AssignAjax('sText', $sTextResult);
    }
}