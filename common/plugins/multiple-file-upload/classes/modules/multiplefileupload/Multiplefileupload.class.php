<?php
/*
 * Этот файл является частью плагина Multiple File Upload
 * Copyright © 2015 https://github.com/shtrih
 * Распространение, продажа, обмен и передача плагина третьим лицам запрещено, за исключением когда третье лицо занимается разработкой вашего проекта.
 */

class PluginMultiplefileupload_ModuleMultiplefileupload extends Module {

    const TARGET_TYPE = 'multiple-file-upload';

    protected static $aUploadErrors = [
        UPLOAD_ERR_INI_SIZE                       => 'plugin.multiplefileupload.upload_err_ini_size',
        UPLOAD_ERR_FORM_SIZE                      => 'plugin.multiplefileupload.upload_err_form_size',
        UPLOAD_ERR_PARTIAL                        => 'plugin.multiplefileupload.upload_err_partial',
        UPLOAD_ERR_NO_FILE                        => 'plugin.multiplefileupload.upload_err_no_file',
        UPLOAD_ERR_NO_TMP_DIR                     => 'plugin.multiplefileupload.upload_err_no_tmp_dir',
        UPLOAD_ERR_CANT_WRITE                     => 'plugin.multiplefileupload.upload_err_cant_write',
        UPLOAD_ERR_EXTENSION                      => 'plugin.multiplefileupload.upload_err_extension',
        ModuleUploader::ERR_NOT_POST_UPLOADED     => 'plugin.multiplefileupload.upload_err_method_not_supported',
        ModuleUploader::ERR_NOT_FILE_VARIABLE     => 'plugin.multiplefileupload.upload_err_not_file_variable',
        ModuleUploader::ERR_MAKE_UPLOAD_DIR       => 'plugin.multiplefileupload.upload_err_cannot_create_dir',
        ModuleUploader::ERR_MOVE_UPLOAD_FILE      => 'plugin.multiplefileupload.upload_err_move',
        ModuleUploader::ERR_COPY_UPLOAD_FILE      => 'plugin.multiplefileupload.upload_err_copy_upload_file',
        ModuleUploader::ERR_REMOTE_FILE_OPEN      => 'plugin.multiplefileupload.upload_err_remote_file_open',
        ModuleUploader::ERR_REMOTE_FILE_MAXSIZE   => 'plugin.multiplefileupload.upload_err_remote_file_maxsize',
        ModuleUploader::ERR_REMOTE_FILE_READ      => 'plugin.multiplefileupload.upload_err_remote_file_read',
        ModuleUploader::ERR_REMOTE_FILE_WRITE     => 'plugin.multiplefileupload.upload_err_remote_file_write',
        ModuleUploader::ERR_NOT_ALLOWED_EXTENSION => 'plugin.multiplefileupload.upload_err_not_allowed_extension',
        ModuleUploader::ERR_FILE_TOO_LARGE        => 'plugin.multiplefileupload.upload_err_file_too_large',
        ModuleUploader::ERR_IMG_NO_INFO           => 'plugin.multiplefileupload.upload_err_img_no_info',
        ModuleUploader::ERR_IMG_LARGE_WIDTH       => 'plugin.multiplefileupload.upload_err_img_large_width',
        ModuleUploader::ERR_IMG_LARGE_HEIGHT      => 'plugin.multiplefileupload.upload_err_img_large_height',
        ModuleUploader::ERR_TRANSFORM_IMAGE       => 'plugin.multiplefileupload.upload_err_transform_image',
    ];

    public function Init() {

    }

    public static function getUploadErrorMsg($iErrorCode) {
        if (isset(self::$aUploadErrors[$iErrorCode])) {
            return E::ModuleLang()->Get(self::$aUploadErrors[$iErrorCode]);
        }

        return E::ModuleLang()->Get('plugin.multiplefileupload.upload_err_unknown');
    }

    public function handleUploadedFile($iTargetId, $sUploadedFile, $sName, $sType, $iSize, $iError) {
        $oFile = new \stdClass();
        $oFile->name = $sName;
        $oFile->size = $iSize;

        if (is_uploaded_file($sUploadedFile)) {
            $bRemote = $sUrl = false;
            if ('file/link' == $sType) {
                $sUrl = file_get_contents($sUploadedFile);
                $bRemote = true;
            }

            if ($this->validateFile($sUploadedFile, $oFile, $iError)) {
                if ($bRemote) {
                    $sFileTmp = E::ModuleUploader()->UploadRemote($sUrl, 'multiple-file-upload');
                }
                else {
                    $sFileTmp = E::ModuleUploader()->UploadLocal(
                        array(
                            'tmp_name' => $sUploadedFile,
                            'name'     => $oFile->name,
                            'error'    => $iError
                        ),
                        'multiple-file-upload',
                        null,
                        true
                    );
                }
                if ($sFileTmp) {
                    $oStoredFile = E::ModuleUploader()->Store($sFileTmp, null);

                    if ($oStoredFile !== false) {
                        /** @var ModuleMresource_EntityMresource $oMresource */
                        $oMresource = E::ModuleMresource()->GetMresourcesByUuid($oStoredFile->getUuid());
                        if ($oMresource) {
                            $iUserId = E::UserId();

                            // Нет константы для всех остальных файлов
                            $oMresource->setType(128);
                            $oMresource->setUserId($iUserId);

                            $oMresource->setParams(array('original_filename' => $oFile->name));
                            E::ModuleMresource()->UpdateParams($oMresource);

                            $oFile->extension = F::File_GetExtension($oFile->name, true);
                            $oFile->url = $this->getFileUrl($oMresource, $oFile);

                            //E::ModuleMresource()->UnlinkFile(self::TARGET_TYPE, 0, E::UserId());
                            E::ModuleMresource()->AddTargetRel($oMresource, self::TARGET_TYPE, $iTargetId);

                            // Пока привызяваемся к идентификатору ресурса вместо идентификатора связи
                            $oFile->id = $oMresource->getMresourceId();
                            // $aMresourceRelIds = E::ModuleMresource()->GetMresourcesRelIds($oResource->getMresourceId(), self::TARGET_TYPE, $iTargetId);
                            // $oFile->id = array_shift($aMresourceRelIds);
                        }
                        else {
                            $oFile->error = E::ModuleLang()->Get('plugin.multiplefileupload.upload_err_resource_not_found');
                        }
                    }
                    else {
                        $oFile->error = self::getUploadErrorMsg(E::ModuleUploader()->GetError());
                    }
                }
                else {
                    $oFile->error = self::getUploadErrorMsg(E::ModuleUploader()->GetError());
                }
            }
        }
        else {
            $oFile->error = self::getUploadErrorMsg(ModuleUploader::ERR_NOT_POST_UPLOADED);
        }
        F::File_Delete($sUploadedFile);

        return $oFile;
    }

    /**
     *
     * @param $sUploadedFile
     * @param $oFile
     * @param $iError
     * @return bool
     */
    protected function validateFile($sUploadedFile, $oFile, $iError) {
        if (UPLOAD_ERR_OK !== $iError) {
            $oFile->error = self::getUploadErrorMsg($iError);
        }
        else {
            $iMaxFileSize = F::MemSize2Int(Config::Get('module.uploader.files.multiple-file-upload.file_maxsize'));
            if ($iMaxFileSize && $oFile->size > $iMaxFileSize) {
                $oFile->error = E::ModuleLang()->Get('topic_field_file_upload_err_size', array('size' => self::sizeFormat($iMaxFileSize)));
            }

            $aFileExtensions = Config::Get('module.uploader.files.multiple-file-upload.file_extensions');
            $aPathInfo = pathinfo($oFile->name);
            if ($aFileExtensions && (empty($aPathInfo['extension']) || !in_array(strtolower($aPathInfo['extension']), $aFileExtensions))) {
                $oFile->error = E::ModuleLang()->Get('topic_field_file_upload_err_type', array('types' => implode(', ', $aFileExtensions)));
            }
        }

        return empty($oFile->error);
    }

    /**
     * @param $iTopicId int Идентификатор топика. 0, если надо получить неприкреплённые файлы.
     * @param null $iUserId
     * @return array Массив с объектами файлов
     */
    public function getAttachedFiles($iTopicId, $iUserId = null) {
        $aResult = array();

        $oModuleResource = E::ModuleMresource();

        $aMresourceRels = $oModuleResource->GetMresourcesRelByTargetAndUser(self::TARGET_TYPE, (int)$iTopicId, $iUserId);
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
            if (!$oFile->name) {
                $oFile->name = basename($oMresource->getPathUrl());
            }
            $oFile->extension = F::File_GetExtension($oFile->name, true);
            $sFilePath = $oMresource->GetFile();
            $oFile->size = file_exists($sFilePath) ? filesize($sFilePath) : 0;
            $oFile->url = $this->getFileUrl($oMresource, $oFile);

            $aResult[] = $oFile;
        }

        return $aResult;
    }

    public static function sizeFormat($iSize) {
        $aSizes = array('б', 'Кб', 'Мб', 'Гб', 'Тб');
        $i = 0;
        while ($iSize > 1000) {
            $iSize /= 1024;
            $i++;
        }
        return rtrim(rtrim(sprintf('%.2f', $iSize), '0'), ',.') . "\xc2\xa0" . $aSizes[$i];
    }

    public function getFileUrl(ModuleMresource_EntityMresource $oMresource, stdClass $oFile) {
        if (Config::Get('plugin.multiplefileupload.hide-direct-links')) {
            // Пока привызяваемся к идентификатору ресурса вместо идентификатора связи
            // $oFile->url = Config::Get('path.root.web') . 'multiplefileupload/get/' . $oMresourceRel->getId() . '/';
            $sResult = Config::Get('path.root.web') . 'multiplefileupload/get/' . $oMresource->getId() . '/';

            if ($oFile->size <= F::MemSize2Int(Config::Get('plugin.multiplefileupload.attachment-header-max-file-size'))
                && in_array($oFile->extension, Config::Get('plugin.multiplefileupload.attachment-header-extensions'))) {
                $sResult .= $oFile->name;
            }
        }
        else {
            $sResult = $oMresource->getWebPath();
        }

        return $sResult;
    }
}
