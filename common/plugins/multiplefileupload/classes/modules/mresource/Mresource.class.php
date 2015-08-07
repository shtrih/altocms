<?php

class PluginMultiplefileupload_ModuleMresource extends PluginMultiplefileupload_Inherit_ModuleMresource {

    /**
     * @param $aMresourcesId
     * @param $sTargetType
     * @param $iTargetId
     * @return
     */
    public function GetMresourcesRelIds($aMresourcesId, $sTargetType, $iTargetId) {
        return $this->oMapper->GetMresourcesRelIds($aMresourcesId, $sTargetType, $iTargetId);
    }
}