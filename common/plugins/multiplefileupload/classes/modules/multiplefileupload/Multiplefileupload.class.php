<?php
/**
 * Created by PhpStorm.
 * User: shtrih
 * Date: 10.08.15
 * Time: 14:57
 */

class PluginMultiplefileupload_ModuleMultiplefileupload extends Module {

    const TARGET_TYPE = 'multiple-file-upload';

    public function Init() {

    }

    /**
     *
     */
    public function getAttachedFiles($iTopicId) {
        $aResult = array();

        $oModuleResource = E::ModuleMresource();

        $aMresourceRels = $oModuleResource->GetMresourcesRelByTarget(self::TARGET_TYPE, $iTopicId);
        foreach ($aMresourceRels as $oMresourceRel) {
            /* @var $oMresource ModuleMresource_EntityMresource
             * @var $oMresourceRel ModuleMresource_EntityMresourceRel
             */
            $oMresource = $oModuleResource->GetMresourceById($oMresourceRel->getMresourceId());

            $oFile = new stdClass();
            // Пока привызяваемся к идентификатору ресурса вместо идентификатора связи
//            $oFile->id = $oMresourceRel->getId();
            $oFile->id = $oMresource->GetId();
            $oFile->name = $oMresource->getParamValue('original_filename');
            if (!$oFile->name)
                $oFile->name = basename($oMresource->getPathUrl());

//            $oFile->url = $oMresource->getWebPath();
            // Пока привызяваемся к идентификатору ресурса вместо идентификатора связи
//            $oFile->url = Config::Get('path.root.web') . 'multiplefileupload/get/' . $oMresourceRel->getId();
            $oFile->url = Config::Get('path.root.web') . 'multiplefileupload/get/' . $oMresource->GetId();

            $sFilePath = $oMresource->GetFile();
            $oFile->size = file_exists($sFilePath) ? filesize($sFilePath) : 0;

            $aResult[] = $oFile;
        }

        return $aResult;
    }

    public static function sizeFormat($iSize) {
        $aSizes = array('B', 'KB', 'MB', 'GB', 'TB');
        $i = 0;
        while ($iSize > 1000) {
            $iSize /= 1024;
            $i++;
        }
        return sprintf('%.2f %s', $iSize, $aSizes[$i]);
    }
}