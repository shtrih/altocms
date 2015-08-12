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
        if (UPLOAD_ERR_OK != $iError) {
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

        if (is_uploaded_file($sUploadedFile)) {
            if ('file/link' == $sType) {
                $sUrl = file_get_contents($sUploadedFile);
                $xUrlFileContent = @file_get_contents($sUrl);

                F::File_Delete($sUploadedFile);

                if (!$xUrlFileContent) {
                    $iError = UPLOAD_ERR_NO_FILE;
                }
                else {
                    $sTmpName = tempnam(sys_get_temp_dir(), "mfu");
                    if (false === $sTmpName) {
                        $iError = UPLOAD_ERR_NO_TMP_DIR;
                    }
                    else {
                        $iSize = file_put_contents($sTmpName, $xUrlFileContent);
                        if (false === $iSize) {
                            $iError = UPLOAD_ERR_CANT_WRITE;
                        }
                        $sUploadedFile = $sTmpName;
//                        $hFinfo = finfo_open(FILEINFO_MIME_TYPE);
//                        $sType = finfo_file($hFinfo, $sTmpName);
//                        finfo_close($hFinfo);
                    }
                }
            }

            if ($this->validateFile($sUploadedFile, $oFile, $iError)) {
                $sDirSave = Config::Get('path.uploads.root') . '/mfu-files/' . E::ModuleUser()->GetUserCurrent()->getId() . '/' . F::RandomStr(16);
                if (mkdir(Config::Get('path.root.dir') . $sDirSave, 0777, true)) {
                    $aPathInfo = pathinfo($oFile->name);
                    $sFile = $sDirSave . '/' . F::RandomStr(10) . '.' . strtolower($aPathInfo['extension']);
                    $sFileFullPath = Config::Get('path.root.dir') . $sFile;

                    if (move_uploaded_file($sUploadedFile, $sFileFullPath) || 'file/link' == $sType && rename($sUploadedFile, $sFileFullPath)) {
                        $oStoredFile = E::ModuleUploader()->Store($sFileFullPath, $sFileFullPath);

                        if ($oStoredFile !== false) {
                            /** @var ModuleMresource_EntityMresource $oMresource */
                            $oMresource = E::ModuleMresource()->GetMresourcesByUuid($oStoredFile->getUuid());
                            if ($oMresource) {
                                $iUserId = E::UserId();

                                $oMresource->setType(PluginMultiplefileupload_ModuleMultiplefileupload::TARGET_TYPE);
                                $oMresource->setUserId($iUserId);

                                $oMresource->setParams(array('original_filename' => $oFile->name));
                                E::ModuleMresource()->UpdateParams($oMresource);

                                if (Config::Get('plugin.multiplefileupload.hide-direct-links')) {;
                                    $oFile->url = Config::Get('path.root.web') . 'multiplefileupload/get/' . $oMresource->GetId();
                                }
                                else {
                                    $oFile->url = $oMresource->getWebPath();
                                }
                                //E::ModuleMresource()->UnlinkFile(self::TARGET_TYPE, 0, E::UserId());
                                E::ModuleMresource()->AddTargetRel($oMresource, PluginMultiplefileupload_ModuleMultiplefileupload::TARGET_TYPE, $iTargetId);

                                // Пока привызяваемся к идентификатору ресурса вместо идентификатора связи
                                $oFile->id = $oMresource->getMresourceId();
                                // $aMresourceRelIds = E::ModuleMresource()->GetMresourcesRelIds($oResource->getMresourceId(), PluginMultiplefileupload_ModuleMultiplefileupload::TARGET_TYPE, $iTargetId);
                                // $oFile->id = array_shift($aMresourceRelIds);
                            }
                            else {
                                $oFile->error = E::ModuleLang()->Get('plugin.multiplefileupload.upload_err_resource_not_found');
                            }
                        }
                        else {
                            $oFile->error = E::ModuleUploader()->GetErrorMsg();
                            if (!$oFile->error) {
                                $oFile->error = E::ModuleLang()->Get('plugin.multiplefileupload.upload_err_unknown');
                            }
                        }
                    }
                    else {
                        $oFile->error = E::ModuleLang()->Get('plugin.multiplefileupload.upload_err_move');
                    }
                }
                else {
                    $oFile->error = E::ModuleLang()->Get('plugin.multiplefileupload.upload_err_cannot_create_dir');
                }
            }
        }
        else {
            $oFile->error = E::ModuleLang()->Get('plugin.multiplefileupload.upload_err_method_not_supported');
        }
        F::File_Delete($sUploadedFile);

        return $oFile;
    }

    public function eventUpload() {
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
        E::ModuleViewer()->DisplayAjax();
    }

    public function eventRemove() {
        $this->checkSecurityKey();
        E::ModuleViewer()->SetResponseAjax('json');

        $iTopicId = (int)F::GetRequest('topic_id');
        // Проверяем, целевой объект и права на его редактирование
        if (!E::ModuleUploader()->CheckAccessAndGetTarget('topic', $iTopicId)) {
            E::ModuleMessage()->AddError(E::ModuleLang()->Get('not_access'), E::ModuleLang()->Get('error'));

            return;
        }

        $xTargetId = F::GetRequest('target_id');
        if (!$xTargetId) {
            E::ModuleMessage()->AddError(E::ModuleLang()->Get('not_access'), E::ModuleLang()->Get('error'));

            return;
        }

        $aRelIds = E::ModuleMresource()->GetMresourcesRelIds($xTargetId, PluginMultiplefileupload_ModuleMultiplefileupload::TARGET_TYPE, $iTopicId);
        // Удаляем связь (а вместе с ней ресурс и файл)
        E::ModuleMresource()->DeleteMresourcesRel($aRelIds);

        E::ModuleMessage()->AddNotice(E::ModuleLang()->Get('plugin.multiplefileupload.success_remove'));
    }

    public function eventSort() {
        $this->checkSecurityKey();

        // * Устанавливаем формат Ajax ответа
        E::ModuleViewer()->SetResponseAjax('json');

        $sTargetType = F::GetRequest('target', FALSE);
        // В нашем случае, права точно такие же, как у топика, поэтому не будем переопределять метод, а просто используем код для топика
        if ('multiple-file-upload' == $sTargetType) {
            $sTargetType = 'topic';
        }

        $sTargetId = F::GetRequest('target_id', FALSE);

        // Проверяем, целевой объект и права на его редактирование
        if (!$oTarget = E::ModuleUploader()->CheckAccessAndGetTarget($sTargetType, $sTargetId)) {
            E::ModuleMessage()->AddError(E::ModuleLang()->Get('not_access'), E::ModuleLang()->Get('error'));

            return;
        }

        if (!($aOrder = F::GetRequest('order', FALSE))) {
            E::ModuleMessage()->AddError(E::ModuleLang()->Get('not_access'), E::ModuleLang()->Get('error'));

            return;
        }

        if (!is_array($aOrder)) {
            E::ModuleMessage()->AddError(E::ModuleLang()->Get('not_access'), E::ModuleLang()->Get('error'));

            return;
        }

        E::ModuleMresource()->UpdateSort(array_flip($aOrder), PluginMultiplefileupload_ModuleMultiplefileupload::TARGET_TYPE, $sTargetId);
        E::ModuleMessage()->AddNoticeSingle(E::ModuleLang()->Get('plugin.multiplefileupload.success_sort'));
    }

    public function eventGet() {
//        $this->checkSecurityKey();

        // Пока привызяваемся к идентификатору ресурса вместо идентификатора связи
        // $iMresourceRelId = (int)$this->GetParam(0);
        // $oMresource = E::ModuleMresource()->GetMresourceByRelId($iMresourceRelId);
        $iMresourceId = (int)$this->GetParam(0);
        $oMresource = E::ModuleMresource()->GetMresourceById($iMresourceId);

        if (!$oMresource) {
            return parent::EventNotFound();
        }

        $sFilePath = $oMresource->GetFile();
        if (file_exists($sFilePath)) {
            $iFileSize = filesize($sFilePath);
            $rFinfo = finfo_open(FILEINFO_MIME_TYPE);
            $sType = finfo_file($rFinfo, $sFilePath);
            finfo_close($rFinfo);
            header('Content-Type: ' . $sType);

            // Файл, размером большим, чем указано в конфиге, отдаем как attachment, в ином случае, без оного заголовка, чтобы браузер сам решил, что с ним делать
            if ($iFileSize > Config::Get('plugin.multiplefileupload.attachment-header-max-file-size')) {
                header('Content-Disposition: attachment; filename="' . $oMresource->getParamValue('original_filename') . '"');
            }

            // TODO: Nginx frontend, Apache XSendfile
            // http://mailman.nginx.org/pipermail/nginx-ru/2005-May/002145.html
            /*
            header("HTTP/1.1 206 Partial Content");
            header("Accept-Ranges: bytes");
            header("Content-Range: bytes 0-");
            header("X-Xox: static");
            header("X-Accel-Redirect: /internal-file-proxy" . $http_file);
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
        $iResourceId = F::GetRequest('target_id');
        $oMresource = E::ModuleMresource()->GetMresourceById($iResourceId);
        E::ModuleMresource()->AddTargetRel($oMresource, 'topic', $iTopicId);
    }
}