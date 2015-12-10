<?php

class PluginMfuPermissions_ModuleMresource_MapperMresource extends PluginMfuPermissions_Inherit_ModuleMresource_MapperMresource {

    /**
     * @param $aMresourcesId
     * @param $sTargetType
     *
     * @return array
     */
    public function GetMresourcesTargetIds($aMresourcesId, $sTargetType) {
        $aCriteria = array(
            'fields' => array(
                'id as relation_id',
                'target_id'
            ),
            'filter' => array(
                'mresource_id' => $aMresourcesId,
                'target_type' => $sTargetType,
            ),
        );

        $aData = $this->_getMresourcesRelByCriteria($aCriteria);
        $aResult = array();
        if ($aData['data']) {
            foreach ($aData['data'] as $xValue) {
                $aResult[] = $xValue;
            }
        }
        return $aResult;
    }
}
