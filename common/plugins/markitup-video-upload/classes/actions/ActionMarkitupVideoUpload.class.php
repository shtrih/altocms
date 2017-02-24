<?php

class PluginMarkitupVideoUpload_ActionMarkitupVideoUpload extends ActionPlugin {

    /**
     * Регистрируем евенты
     */
    protected function RegisterEvent() {
        $this->AddEvent('add-video', 'eventUploadVideo');
    }

    protected function eventUploadVideo() {
        /*
          * Т.к. используется обработка отправки формы, то устанавливаем тип ответа 'jsonIframe'
          * (тот же JSON только обернутый в textarea)
          * Это позволяет избежать ошибок в некоторых браузерах, например, Opera
          */
        E::ModuleViewer()->SetResponseAjax(F::AjaxRequest(true)?'json':'jsonIframe', false);

        // * Пользователь авторизован?
        if (!$oUserCurrent = E::ModuleUser()->GetUserCurrent()) {
            E::ModuleMessage()->AddErrorSingle(E::ModuleLang()->Get('need_authorization'), E::ModuleLang()->Get('error'));
            return;
        }

        $aOptions = array();
        // Check options of uploaded image
        if ($nWidth = $this->GetPost('img_width')) {
            if ($this->GetPost('width_unit') == 'percent') {
                // Max width according width of text area
                if ($this->GetPost('width_ref') == 'text' && ($nWidthText = intval($this->GetPost('width_text')))) {
                    $nWidth = round($nWidthText * $nWidth / 100);
                    $aOptions['max_width'] = $nWidth;
                }
            }
            // pixels
            else {
                $aOptions['max_width'] = (int)$nWidth;
            }
        }

        $sFile = null;
        // * Был выбран файл с компьютера и он успешно загрузился?
        if ($aUploadedFile = $this->GetUploadedFile('file')) {
            if ($sFileTmp = E::ModuleUploader()->UploadLocal($aUploadedFile, 'video.webm')) {
                $sFile = E::Module('PluginMarkitupVideoUpload_ModuleMarkitupVideoUpload')->storeVideo($sFileTmp, $oUserCurrent, 'webm', $aOptions);
            }

            if (!$sFile) {
                $sMessage = E::ModuleLang()->Get('uploadimg_file_error');
                if (E::ModuleUploader()->GetError()) {
                    $sMessage .= ' (' . E::ModuleUploader()->GetErrorMsg() . ')';
                }
                E::ModuleMessage()->AddErrorSingle($sMessage, E::ModuleLang()->Get('error'));
                return;
            }
        } elseif (($sUrl = $this->GetPost('url')) && ($sUrl != 'http://')) {
            // * Загрузка файла по URL
            if (preg_match('~(https?://)(\w([\w]+)?\.[\w.\-/]+.*)$~i', $sUrl, $aM)) {
                // Иногда перед нормальным адресом встречается лишний 'http://' и прочий "мусор"
                $sUrl = $aM[1] . $aM[2];
                if ($sFileTmp = E::ModuleUploader()->UploadRemote($sUrl, 'video.webm')) {
                    $sFile = E::Module('PluginMarkitupVideoUpload_ModuleMarkitupVideoUpload')->storeVideo($sFileTmp, $oUserCurrent, 'webm', $aOptions);
                }
            }
        } else {
            E::ModuleMessage()->AddErrorSingle(E::ModuleLang()->Get('uploadimg_file_error'));
            return;
        }
        // * Если файл успешно загружен, формируем HTML вставки и возвращаем в ajax ответе
        if ($sFile) {
            $sText = E::ModuleImg()->BuildHTML($sFile, $_REQUEST);
            if (strpos($sText, 'class="')) {
                $sText = str_replace('class="', 'class="mvu-webm ', $sText);
            }
            else {
                $sText = str_replace('<img', '<img class="mvu-webm"', $sText);
            }

            E::ModuleViewer()->AssignAjax('sText', $sText);
        } else {
            E::ModuleMessage()->AddErrorSingle(E::ModuleUploader()->GetErrorMsg(), E::ModuleLang()->Get('error'));
        }
    }

    /**
     * Завершение работы экшена
     */
    public function EventShutdown() {

    }
}
