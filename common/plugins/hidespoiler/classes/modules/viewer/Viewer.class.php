<?php

class PluginHidespoiler_ModuleViewer extends PluginHidespoiler_Inherit_ModuleViewer {

    public function getAssignedAjax($sName) {
        return isset($this->aVarsAjax[$sName]) ? $this->aVarsAjax[$sName] : null;
    }
}