<?php

class PluginLegacyspoilers_ActionAjax extends PluginLegacyspoilers_Inherit_ActionAjax {

    /**
     * Предпросмотр текста
     *
     */
    protected function EventPreviewText() {
        parent::EventPreviewText();
        $sTextResult = E::ModuleViewer()->getAssignedAjax('sText');
        $sTextResult = E::Module('PluginLegacyspoilers_ModuleLegacyspoilers')->MakeCorrection($sTextResult, 0);
        E::ModuleViewer()->AssignAjax('sText', $sTextResult);
    }
}
