<?php
/**
 * Created by PhpStorm.
 * User: shtrih
 * Date: 06.08.15
 * Time: 13:27
 */

class PluginMultiplefileupload_ActionMultiplefileupload extends Action {


    protected function RegisterEvent() {
        $this->AddEvent('upload', 'eventUpload');
        $this->AddEvent('remove', 'eventRemove');
        $this->AddEvent('sort', 'eventSort');
        $this->AddEventPreg('~^get$~i', '~^\d+$~', 'eventGet');
        $this->AddEvent('attach', 'eventAttach');
    }

    protected function checkSecurityKey($bDie = true) {
        if (E::ModuleSecurity()->ValidateSecurityKey()) {
            return true;
        }
        else {
            E::ModuleMessage()->AddError(E::ModuleLang()->Get('plugin.multiplefileupload.error_invalid_security_key'));
        }

        if ($bDie)
            E::ModuleViewer()->DisplayAjax();

        return false;
    }

    /**
     *
     * @param $sUploadedFile
     * @param $oFile
     * @param $iError
     * @return bool
     */
    protected function validateFile($sUploadedFile, $oFile, $iError) {
        if ($iError != 0) {
            switch ($iError) {
                case UPLOAD_ERR_INI_SIZE:
                    $oFile->error = E::ModuleLang()->Get('plugin.multiplefileupload.upload_err_ini_size');
                    break;

                case UPLOAD_ERR_FORM_SIZE:
                    $oFile->error = E::ModuleLang()->Get('plugin.multiplefileupload.upload_err_form_size');
                    break;

                case UPLOAD_ERR_PARTIAL:
                    $oFile->error = E::ModuleLang()->Get('plugin.multiplefileupload.upload_err_partial');
                    break;

                case UPLOAD_ERR_NO_FILE:
                    $oFile->error = E::ModuleLang()->Get('plugin.multiplefileupload.upload_err_no_file');
                    break;

                case UPLOAD_ERR_NO_TMP_DIR:
                    $oFile->error = E::ModuleLang()->Get('plugin.multiplefileupload.upload_err_no_tmp_dir');
                    break;

                case UPLOAD_ERR_CANT_WRITE:
                    $oFile->error = E::ModuleLang()->Get('plugin.multiplefileupload.upload_err_cant_write');
                    break;

                case UPLOAD_ERR_EXTENSION:
                    $oFile->error = E::ModuleLang()->Get('plugin.multiplefileupload.upload_err_extension');
                    break;

                default:
                    $oFile->error = E::ModuleLang()->Get('plugin.multiplefileupload.upload_err_unknown');
            }
        }
        else {
            $iMaxFileSize = F::MemSize2Int(Config::Get('module.uploader.files.default.file_maxsize'));
            if ($iMaxFileSize && $oFile->size > $iMaxFileSize) {
                $oFile->error = E::ModuleLang()->Get('topic_field_file_upload_err_size', array('size' => $iMaxFileSize));
            }

            $aFileExtensions = Config::Get('module.uploader.files.default.file_extensions');
            $aPathInfo = pathinfo($oFile->name);
            if ($aFileExtensions && (empty($aPathInfo['extension']) || !in_array(strtolower($aPathInfo['extension']), $aFileExtensions))) {
                $oFile->error = E::ModuleLang()->Get('topic_field_file_upload_err_type', array('types' => implode(', ', $aFileExtensions)));
            }
        }

        return empty($oFile->error);
    }

    protected function handleUploadedFile($iTargetId, $sUploadedFile, $sName, $sType, $iSize, $iError) {
        // TODO: $iTargetId может отсутствовать
        $oFile = new \stdClass();
        $oFile->name = $sName;
        $oFile->size = $iSize;
        if ($this->validateFile($sUploadedFile, $oFile, $iError)) {
            if (is_uploaded_file($sUploadedFile)) {
                $sDirSave = Config::Get('path.uploads.root') . '/files/' . E::ModuleUser()->GetUserCurrent()->getId() . '/' . F::RandomStr(16);
                if (mkdir(Config::Get('path.root.dir') . $sDirSave, 0777, true)) {
                    $aPathInfo = pathinfo($oFile->name);
                    $sFile = $sDirSave . '/' . F::RandomStr(10) . '.' . strtolower($aPathInfo['extension']);
                    $sFileFullPath = Config::Get('path.root.dir') . $sFile;

                    if (move_uploaded_file($sUploadedFile, $sFileFullPath)) {
                        $oStoredFile = E::ModuleUploader()->Store($sFileFullPath, $sFileFullPath);

                        if ($oStoredFile !== false) {
                            /** @var ModuleMresource_EntityMresource $oResource */
                            $oResource = E::ModuleMresource()->GetMresourcesByUuid($oStoredFile->getUuid());
                            if ($oResource) {
                                $iUserId = E::UserId();

                                $oResource->setType(PluginMultiplefileupload_ModuleMultiplefileupload::TARGET_TYPE);
                                $oResource->setUserId($iUserId);

                                $oResource->setParams(array('original_filename' => $oFile->name));
                                E::ModuleMresource()->UpdateParams($oResource);

                                $oFile->url = $oResource->GetUrl();
                                //E::ModuleMresource()->UnlinkFile(self::TARGET_TYPE, 0, E::UserId());
                                E::ModuleMresource()->AddTargetRel($oResource, PluginMultiplefileupload_ModuleMultiplefileupload::TARGET_TYPE, $iTargetId);
                                $aMresourceRelIds = E::ModuleMresource()->GetMresourcesRelIds($oResource->getMresourceId(), PluginMultiplefileupload_ModuleMultiplefileupload::TARGET_TYPE, $iTargetId);
                                $oFile->id = array_shift($aMresourceRelIds);
                            }
                            else {
                                $oFile->error = 'File resource not found.';
                            }
                        }
                        else {
                            $oFile->error = E::ModuleUploader()->GetErrorMsg() . 'adsasd';
                        }
                    }
                    else {
                        $oFile->error = 'There is not a valid upload file OR file cannot be moved for some reason.';
                    }
                }
                else {
                    $oFile->error = 'Cannot create save dir.';
                }

/*                $sDirSave = Config::Get('path.uploads.root') . '/files/' . E::ModuleUser()->GetUserCurrent()->getId() . '/' . F::RandomStr(16);
                mkdir(Config::Get('path.root.dir') . $sDirSave, 0777, true);
                if (is_dir(Config::Get('path.root.dir') . $sDirSave)) {
                    $aPathInfo = pathinfo($oFile->name);
                    $sFile = $sDirSave . '/' . F::RandomStr(10) . '.' . strtolower($aPathInfo['extension']);
                    $sFileFullPath = Config::Get('path.root.dir') . $sFile;
                    if (copy($sUploadedFile, $sFileFullPath)) {
                        $oFile->url = $sFile;

                        $aFileObj = array();
                        $aFileObj['file_hash'] = F::RandomStr(32);
                        $aFileObj['file_name'] = E::ModuleText()->Parser($_FILES['multiple-file-upload']['name']);
                        $aFileObj['file_url'] = $sFile;
                        $aFileObj['file_size'] = $_FILES['multiple-file-upload']['size'];
                        $aFileObj['file_extension'] = $aPathInfo['extension'];
                        $aFileObj['file_downloads'] = 0;
                        $sData = serialize($aFileObj);

                        F::File_Delete($sUploadedFile);
                    }
                }*/
            }
            else {
                $oFile->error = 'This upload method is not supported.';
            }
        }
        F::File_Delete($sUploadedFile);

        return $oFile;
    }

    public function eventUpload($aParams = null) {
        $this->checkSecurityKey();

        $iTargetId = (int)F::GetRequest('topic_id');
        $aFiles = array();

        if (isset($_FILES['multiple-file-upload'])) {
            if (is_array($_FILES['multiple-file-upload']['tmp_name'])) {
                foreach ($_FILES['multiple-file-upload']['tmp_name'] as $key => $value) {
                    $aFiles[] = $this->handleUploadedFile(
                        $iTargetId,
                        $_FILES['multiple-file-upload']['tmp_name'][$key],
                        $_FILES['multiple-file-upload']['name'][$key],
                        $_FILES['multiple-file-upload']['type'][$key],
                        $_FILES['multiple-file-upload']['size'][$key],
                        $_FILES['multiple-file-upload']['error'][$key]
                    );
                }
            }
            else {
                $aFiles[] = $this->handleUploadedFile(
                    $iTargetId,
                    $_FILES['multiple-file-upload']['tmp_name'],
                    $_FILES['multiple-file-upload']['name'],
                    $_FILES['multiple-file-upload']['type'],
                    $_FILES['multiple-file-upload']['size'],
                    $_FILES['multiple-file-upload']['error']
                );
            }
        }

        E::ModuleViewer()->AssignAjax('files', $aFiles);

        /*
                // 1. Удалить значение target_tmp
                // Нужно затереть временный ключ в ресурсах, что бы в дальнейшем картнка не
                // воспринималась как временная.
                if ($sTargetTmp = E::ModuleSession()->GetCookie(ModuleUploader::COOKIE_TARGET_TMP)) {
                    // 2. Удалить куку.
                    // Если прозошло сохранение вновь созданного топика, то нужно
                    // удалить куку временной картинки. Если же сохранялся уже существующий топик,
                    // то удаление куки ни на что влиять не будет.
                    E::ModuleSession()->DelCookie(ModuleUploader::COOKIE_TARGET_TMP);

                    // 3. Переместить фото

                    $sNewPath = E::ModuleUploader()->GetUserImageDir(E::UserId(), true, false);
                    $aMresourceRel = E::ModuleMresource()->GetMresourcesRelByTargetAndUser(self::TARGET_TYPE, 0, E::UserId());

                    if ($aMresourceRel) {
                        $oResource = array_shift($aMresourceRel);
                        $sOldPath = $oResource->GetFile();

                        $oStoredFile = E::ModuleUploader()->Store($sOldPath, $sNewPath);
                        /** @var ModuleMresource_EntityMresource $oResource */
                /*
                $oResource = E::ModuleMresource()->GetMresourcesByUuid($oStoredFile->getUuid());
                if ($oResource) {
                    $oResource->setUrl(E::ModuleMresource()->NormalizeUrl(E::ModuleUploader()->GetTargetUrl(self::TARGET_TYPE, $iTargetId)));
                    $oResource->setType($sTargetType);
                    $oResource->setUserId(E::UserId());
                    // 4. В свойство поля записать адрес картинки
                    $sData = $oResource->getMresourceId();
                    $oResource = array($oResource);
                    E::ModuleMresource()->UnlinkFile($sTargetType, 0, $oTopic->getUserId());
                    E::ModuleMresource()->AddTargetRel($oResource, $sTargetType, $iTargetId);
                }
            }
        } else {
            // Топик редактируется, просто обновим поле
            $aMresourceRel = E::ModuleMresource()->GetMresourcesRelByTargetAndUser(self::TARGET_TYPE, $iTargetId, E::UserId());
            if ($aMresourceRel) {
                $oResource = array_shift($aMresourceRel);
                $sData = $oResource->getMresourceId();
            } else {
                $sData = false;
//                                    $this->DeleteField($oField);
            }
        }
*/
//        E::ModuleViewer()->AssignAjax('lol', $aParams);
        E::ModuleViewer()->DisplayAjax();
    }

    public function eventRemove() {
        $this->checkSecurityKey();

    }

    public function eventSort() {
        $this->checkSecurityKey();

    }

    public function eventGet() {
//        $this->checkSecurityKey();

        $iMresourceRelId = (int)$this->GetParam(0);
        $oMresource = E::ModuleMresource()->GetMresourceByRelId($iMresourceRelId);

        $sFilePath = $oMresource->GetFile();
        if (file_exists($sFilePath)) {
            $iFileSize = filesize($sFilePath);
            $rFinfo = finfo_open(FILEINFO_MIME_TYPE);
            $sType = finfo_file($rFinfo, $sFilePath);
            finfo_close($rFinfo);
            header('Content-Type: ' . $sType);

            // Файл, размером более 15Мб отдаем как attachment, в ином случае, без оного заголовка, чтобы браузер сам решил, что с ним делать
            if ($iFileSize > 15 * 1024 * 1024) {
                header('Content-Disposition: attachment; filename="' . $oMresource->getParamValue('original_filename') . '"');
            }

            // http://mailman.nginx.org/pipermail/nginx-ru/2005-May/002145.html
            /*
            header("HTTP/1.1 206 Partial Content");
            header("Accept-Ranges: bytes");
            header("Content-Range: bytes 0-");
            header("X-Xox: static");
            header("X-Accel-Redirect: /internal-file-proxy" . $http_file);

            sleep(1);
*/
            header('Content-Length: ' . $iFileSize);
            ob_clean();
            flush();
            readfile($sFilePath);
        }
        else {
            return parent::EventNotFound();
        }
        exit;
    }

    public function eventAttach() {
        $this->checkSecurityKey();

        $iTopicId = F::GetRequest('topic_id');
        $iResourceId = F::GetRequest('resource_id');
        $oMresource = E::ModuleMresource()->GetMresourceById($iResourceId);
        E::ModuleMresource()->AddTargetRel($oMresource, 'topic', $iTopicId);
    }

    public function eventGetList() {

        $oFile = new stdClass();
        $oFile->id = $iMresourceRelId;
        $oFile->name = $oMresource->getParamValue('original_filename');
        $oFile->url = $oMresource->getParamValue('original_filename');
    }
}