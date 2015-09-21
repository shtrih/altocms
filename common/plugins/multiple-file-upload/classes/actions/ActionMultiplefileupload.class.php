<?php
/*
 * Этот файл является частью плагина Multiple File Upload
 * Copyright © 2015 https://github.com/shtrih
 * Распространение, продажа, обмен и передача плагина третьим лицам запрещено, за исключением когда третье лицо занимается разработкой вашего проекта.
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

    public function eventUpload() {
        $this->checkSecurityKey();
        E::ModuleViewer()->SetResponseAjax('json');

        $iTargetId = (int)F::GetRequest('topic_id');
        $aFiles = [];

        // Проверяем, целевой объект и права на его редактирование
        if (!E::ModuleUploader()->CheckAccessAndGetTarget('topic', $iTargetId)) {
            E::ModuleMessage()->AddError(E::ModuleLang()->Get('not_access'), E::ModuleLang()->Get('error'));

            return;
        }

        if (isset($_FILES['multiple-file-upload'])) {
            if (is_array($_FILES['multiple-file-upload']['tmp_name'])) {
                foreach ($_FILES['multiple-file-upload']['tmp_name'] as $key => $value) {
                    $aFiles[] = E::Module('PluginMultiplefileupload_ModuleMultiplefileupload')->handleUploadedFile(
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
                $aFiles[] = E::Module('PluginMultiplefileupload_ModuleMultiplefileupload')->handleUploadedFile(
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

            $sFileName = $oMresource->getParamValue('original_filename');
            $sFileExtension = F::File_GetExtension($sFileName, true);

            // Позволяем запрашивать файл с любым именем, но если расширение отличается, то файл не отдаем
            $sUrlFileName = $this->GetParam(1);
            if ($sUrlFileName) {
                $sUrlExtension = F::File_GetExtension($sUrlFileName, true);
                if ($sUrlExtension != $sFileExtension) {
                    return parent::EventNotFound();
                }
            }

            header('Content-Type: ' . $sType);
            header('Content-Length: ' . $iFileSize);

            // Файл, размером большим, чем указано в конфиге, отдаем как attachment, в ином случае, без оного заголовка,
            // чтобы браузер сам решил, что с ним делать
            if ($iFileSize > F::MemSize2Int(Config::Get('plugin.multiplefileupload.attachment-header-max-file-size'))
                || !in_array($sFileExtension, Config::Get('plugin.multiplefileupload.attachment-header-extensions'))) {
                header('Content-Disposition: attachment; filename="' . $sFileName . '"');
            }

            // TODO: вынести в настройки
            // Apache XSendFile
            // https://tn123.org/mod_xsendfile/
            if (in_array('mod_xsendfile', apache_get_modules()) && Config::Get('plugin.multiplefileupload.apache2-xsendfile')) {
                header('X-Sendfile: ' . $sFilePath);
                header("X-mfu: sendfile");
            }
            // Nginx as frontend server
            // http://wiki.nginx.org/XSendfile
            elseif (Config::Get('plugin.multiplefileupload.nginx-xsendfile')) {
                /*
                // http://mailman.nginx.org/pipermail/nginx-ru/2005-May/002145.html
                header("HTTP/1.1 206 Partial Content");
                header("Accept-Ranges: bytes");
                header("Content-Range: bytes 0-");
                */
                header("X-Accel-Redirect: " . $sFilePath);
            }
            else {
                ob_clean();
                flush();
                readfile($sFilePath);
            }
        }
        else {
            return parent::EventNotFound();
        }
        exit;
    }

    public function eventAttach() {
        $this->checkSecurityKey();
        E::ModuleViewer()->SetResponseAjax('json');

        $iTargetId = F::GetRequest('target_id');
        $iMresourceId = F::GetRequest('file_id');
        if (!$iMresourceId) {
            E::ModuleMessage()->AddError(E::ModuleLang()->Get('not_access'), E::ModuleLang()->Get('error'));

            return;
        }

        if (E::ModuleMresource()->updateMresourceRelTargetId($iMresourceId, $iTargetId)) {
            E::ModuleMessage()->AddNotice(E::ModuleLang()->Get('plugin.multiplefileupload.success_attach'));
        }
        else {
            E::ModuleMessage()->AddError(E::ModuleLang()->Get('plugin.multiplefileupload.error_attach'), E::ModuleLang()->Get('error'));
        }
    }
}
