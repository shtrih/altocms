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
            if (!$oFile->name) {
                $oFile->name = basename($oMresource->getPathUrl());
            }
            $oFile->extension = $sExtension;
            $sFilePath = $oMresource->GetFile();
            $oFile->size = file_exists($sFilePath) ? filesize($sFilePath) : 0;
            $oFile->url = $this->getFileUrl($oMresource, $oFile);

            $aResult[] = $oFile;
        }

        return $aResult;
    }

    public function getFileUrl(ModuleMresource_EntityMresource $oMresource, stdClass $oFile) {
        return E::Module('PluginMultiplefileupload_ModuleMultiplefileupload')->getFileUrl($oMresource, $oFile);
    }
}
