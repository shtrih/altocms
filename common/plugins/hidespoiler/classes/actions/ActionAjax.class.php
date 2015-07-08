<?php

class PluginHidespoiler_ActionAjax extends PluginHidespoiler_Inherit_ActionAjax {

    /**
     * Предпросмотр текста
     *
     */
    protected function EventPreviewText() {
        parent::EventPreviewText();
        $sTextResult = E::ModuleViewer()->getAssignedAjax('sText');
        $sTextResult = E::Module('PluginHidespoiler_ModuleHidespoiler')->MakeCorrection($sTextResult, 0);
        E::ModuleViewer()->AssignAjax('sText', $sTextResult);
    }
}