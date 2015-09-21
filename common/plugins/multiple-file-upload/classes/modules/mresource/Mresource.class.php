<?php
/*
 * Этот файл является частью плагина Multiple File Upload
 * Copyright © 2015 https://github.com/shtrih
 * Распространение, продажа, обмен и передача плагина третьим лицам запрещено, за исключением когда третье лицо занимается разработкой вашего проекта.
 */

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
        $xResult = [];
        $aMrecourcesRel = $this->oMapper->GetMresourcesRelById($iId);
        $oMresourceRel = array_pop($aMrecourcesRel);

        if ($oMresourceRel)
            $xResult = $this->oMapper->GetMresourcesById($oMresourceRel->getMresourceId());

        return array_pop($xResult);
    }

    public function updateMresourceRelTargetId($iMresourceId, $iTargetId) {
        return $this->oMapper->updateMresourceRelTargetId($iMresourceId, PluginMultiplefileupload_ModuleMultiplefileupload::TARGET_TYPE, $iTargetId);
    }
}
