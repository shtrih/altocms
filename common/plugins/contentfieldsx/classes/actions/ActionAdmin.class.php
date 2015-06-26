<?php

class PluginContentfieldsx_ActionAdmin extends PluginContentfieldsx_Inherits_ActionAdmin {

    protected function EventEditField() {
        parent::EventEditField();

        if (empty($_REQUEST['submit_field'])) {
            $oField = E::ModuleViewer()->getTemplateVars('oField');
            $_REQUEST['field_unique_name'] = $oField->getUniqueName();
        }
    }

    /**
     * Редактирование поля контента
     *
     * @param ModuleTopic_EntityContentType $oContentType
     * @param ModuleTopic_EntityField $oField
     * @return bool
     */
    protected function SubmitEditField($oContentType, $oField) {

        // * Проверяем отправлена ли форма с данными
        if (!F::isPost('submit_field')) {
            return false;
        }

        // * Проверка корректности полей формы
        if (!$this->CheckFieldsField($oContentType)) {
            return false;
        }

        if (!E::ModuleTopic()->GetFieldValuesCount($oField->getFieldId())) {
            // Нет ещё ни одного значения этого поля, тогда можно сменить ещё и тип
            $oField->setFieldType(F::GetRequest('field_type'));
        }
        $oField->setFieldName(F::GetRequest('field_name'));
        $oField->setFieldUniqueName(F::GetRequest('field_unique_name'));
        $oField->setFieldDescription(F::GetRequest('field_description'));
        $oField->setFieldRequired(F::GetRequest('field_required'));
        if ($oField->getFieldType() == 'select') {
            $oField->setOptionValue('select', F::GetRequest('field_values'));
        }

        try {
            if (E::ModuleTopic()->UpdateContentField($oField)) {
                E::ModuleMessage()->AddNoticeSingle(E::ModuleLang()->Get('action.admin.contenttypes_success_fieldedit'), null, true);
                R::Location('admin/settings-contenttypes/edit/' . $oContentType->getContentId() . '/');
            }
        }
        catch (Exception $e) {
            // Если ошибка дублирования уникального ключа, то выводим соответствующее сообщение
            if (1062 == $e->getCode())
                E::ModuleMessage()->AddErrorSingle("Поле с таким уникальным именем уже существует", null, false);
        }

        return false;
    }
}


