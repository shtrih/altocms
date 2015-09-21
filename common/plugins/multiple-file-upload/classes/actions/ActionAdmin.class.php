<?php
/*
 * Этот файл является частью плагина Multiple File Upload
 * Copyright © 2015 https://github.com/shtrih
 * Распространение, продажа, обмен и передача плагина третьим лицам запрещено, за исключением когда третье лицо занимается разработкой вашего проекта.
 */

class PluginMultiplefileupload_ActionAdmin extends PluginMultiplefileupload_Inherits_ActionAdmin {

    protected function EventAddField() {
        $sContentType = F::GetRequest('field_type');
        if ('multiple-file-upload' == $sContentType) {
            $bExists = false;
            $aContentFields = E::ModuleTopic()->getContentFields(['content_id' => $this->GetParam(0)]);
            foreach ($aContentFields as $oEntityField) {
                if ($sContentType == $oEntityField->getFieldType()) {
                    $bExists = true;
                    break;
                }
            }

            if ($bExists) {
                // * Устанавливаем шаблон вывода
                $this->SetTemplateAction('settings/contenttypes_fieldadd');

                // * Получаем тип
                if (!$oContentType = E::ModuleTopic()->GetContentTypeById($this->GetParam(0))) {
                    return parent::EventNotFound();
                }

                E::ModuleViewer()->Assign('oContentType', $oContentType);

                E::ModuleMessage()->AddError(
                    E::ModuleLang()->Get('plugin.multiplefileupload.error_field_type_duplicate'),
                    null,
                    false
                );

                return false;
            }
        }

        return parent::EventAddField();
    }
}
