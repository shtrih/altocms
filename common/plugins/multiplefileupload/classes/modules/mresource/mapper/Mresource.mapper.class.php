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
            'fields' => array(
                'id',
                'mr.mresource_id'
            ),
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
                // в ключе mresource_id. Можем позволить, потому что сейчас
                // mresource и mresource_target 1:1 из-за уникального ключа `target_type, target_id, mresource_id`.
                $aResult[ $xValue['mresource_id'] ] = $xValue['id'];
            }
        }
        return $aResult;
    }

    public function updateMresourceRelTargetId($iMresourceId, $sTargetType, $iTargetId) {
        $sql = "UPDATE ?_mresource_target SET
                  target_id = ?,
                  target_tmp = null
                WHERE mresource_id = ?d AND target_type = ? AND target_id = 0";
        return $this->oDb->query($sql,
            $iTargetId,
            $iMresourceId,
            $sTargetType
        );
    }
}

// EOF
