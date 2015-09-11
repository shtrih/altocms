<?php

class PluginAudiofilepreview_ModuleAudiofilepreview extends Module {

    public function Init() {

    }

    /**
     * @param $iTopicId int Идентификатор топика. 0, если надо получить неприкреплённые файлы.
     * @param null $iUserId
     * @return array Массив с объектами файлов
     */
    public function getAttachedFiles($iTopicId, $iUserId = null) {
        $aResult = array();

        $aExtensions = Config::Get('plugin.audiofilepreview.audio-extensions');
        $oModuleResource = E::ModuleMresource();

        $aMresourceRels = $oModuleResource->GetMresourcesRelByTargetAndUser(PluginMultiplefileupload_ModuleMultiplefileupload::TARGET_TYPE, (int)$iTopicId, $iUserId);
        foreach ($aMresourceRels as $oMresourceRel) {
            /* @var $oMresource ModuleMresource_EntityMresource
             * @var $oMresourceRel ModuleMresource_EntityMresourceRel
             */
            $oMresource = $oModuleResource->GetMresourceById($oMresourceRel->getMresourceId());
            $sExtension = F::File_GetExtension($oMresource->getPathFile(), true);

            if (!in_array($sExtension, $aExtensions)) {
                continue;
            }

            $oFile = new stdClass();
            // Пока привызяваемся к идентификатору ресурса вместо идентификатора связи
//            $oFile->id = $oMresourceRel->getId();
            $oFile->id = $oMresource->GetId();
            $oFile->name = $oMresource->getParamValue('original_filename');
            $oFile->extension = $sExtension;
            if (!$oFile->name)
                $oFile->name = basename($oMresource->getPathUrl());

            // Пока привызяваемся к идентификатору ресурса вместо идентификатора связи
            if (Config::Get('plugin.multiplefileupload.hide-direct-links')) {
                // $oFile->url = Config::Get('path.root.web') . 'multiplefileupload/get/' . $oMresourceRel->getId();
                $oFile->url = Config::Get('path.root.web') . 'multiplefileupload/get/' . $oMresource->GetId();
            }
            else {
                $oFile->url = $oMresource->getWebPath();
            }

            $sFilePath = $oMresource->GetFile();
            $oFile->size = file_exists($sFilePath) ? filesize($sFilePath) : 0;

            $aResult[] = $oFile;
        }

        return $aResult;
    }
}
