<?php
/*
 * Этот файл является частью плагина Multiple File Upload
 * Copyright © 2015 https://github.com/shtrih
 * Распространение, продажа, обмен и передача плагина третьим лицам запрещено, за исключением когда третье лицо занимается разработкой вашего проекта.
 */

class PluginMfuPermissions_ModuleMresource extends PluginMfuPermissions_Inherit_ModuleMresource {

    /**
     * @param $aMresourcesId
     * @param $sTargetType
     *
     * @return array
     */
    public function GetMresourcesTargetIds($aMresourcesId, $sTargetType) {
        return $this->oMapper->GetMresourcesTargetIds($aMresourcesId, $sTargetType);
    }
}
