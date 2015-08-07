<?php

class PluginMultiplefileupload_ModuleMresource_MapperMresource extends PluginMultiplefileupload_Inherit_ModuleMresource_MapperMresource {

    /**
     * Returns media resources' relation entities by target
     *
     * @param $aMresourcesId
     * @param $sTargetType
     * @param $iTargetId
     * @return array
     */
    public function GetMresourcesRelIds($aMresourcesId, $sTargetType, $iTargetId) {
        $aCriteria = array(
            'fields' => 'id',
            'filter' => array(
                'mresource_id' => $aMresourcesId,
                'target_type' => $sTargetType,
                'target_id' => $iTargetId,
            ),
        );

        $aData = $this->_getMresourcesRelByCriteria($aCriteria);
        $aResult = array();
        if ($aData['data']) {
            foreach ($aData['data'] as $xValue) {
                $aResult[] = $xValue['id'];
            }
        }
        return $aResult;
    }
}

// EOF
