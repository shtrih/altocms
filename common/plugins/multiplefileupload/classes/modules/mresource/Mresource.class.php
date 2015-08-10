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

    /**
     * @param int $iId
     *
     * @return ModuleMresource_EntityMresource|null
     */
    public function GetMresourceByRelId($iId) {
        $xResult = array();
        $aMrecourcesRel = $this->oMapper->GetMresourcesRelById($iId);
        $oMresourceRel = array_pop($aMrecourcesRel);

        if ($oMresourceRel)
            $xResult = $this->oMapper->GetMresourcesById($oMresourceRel->getMresourceId());

        return array_pop($xResult);
    }

}