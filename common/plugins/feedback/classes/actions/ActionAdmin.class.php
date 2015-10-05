<?php

class PluginFeedback_ActionAdmin extends PluginFeedback_ActionAdmin_Inherits_ActionAdmin {

    protected function RegisterEvent() {
        $this->AddEventPreg('~^settings-feedback$~i', '~^field-add$~i', 'fieldAdd');
        $this->AddEventPreg('~^settings-feedback$~i', '~^field-edit~i', '~^\d+$~i', 'fieldEdit');
        $this->AddEventPreg('~^settings-feedback$~i', '~^field-remove~i', '~^\d+$~i', 'fieldRemove');
        $this->AddEvent('settings-feedback', 'settingsFeedback');

        parent::RegisterEvent();
    }

    protected function settingsFeedback() {
        $this->_setTitle('Настройки формы обратной связи');
//        $this->SetTemplateAction('settings-feedback');

        /**
         * @var PluginFeedback_ModuleFeedback
         */
        $oModuleFeedback = E::Module('PluginFeedback_ModuleFeedback');
        $oFeedback = $oModuleFeedback->getFeedbackById(1);

        if (F::isPost('security_key') && E::ModuleSecurity()->ValidateSecurityKey()) {
            $oFeedback->setFeedbackId(1);
            $oFeedback->setFeedbackWebpath(F::GetRequestStr('feedback_webpath', 'feedback'));
            $oFeedback->setFeedbackActive(F::GetRequestStr('feedback_active', '0'));
            $oFeedback->setFeedbackTitle(F::GetRequestStr('feedback_title', 'Написать администрации'));
            $oFeedback->setFeedbackText(F::GetRequestStr('feedback_description', ''));
            $oFeedback->setFeedbackTextSource(E::ModuleText()->Parser(F::GetRequestStr('feedback_description', '')));

            $oModuleFeedback->updateFeedback($oFeedback);

            Router::Location(Router::GetPathWebCurrent());
        }
        else {
            E::ModuleViewer()->Assign('aFields', $oFeedback->getFields());

            $_REQUEST['feedback_webpath'] = $oFeedback->getFeedbackWebpath();
            $_REQUEST['feedback_active'] = $oFeedback->getFeedbackActive();
            $_REQUEST['feedback_title'] = $oFeedback->getFeedbackTitle();
            $_REQUEST['feedback_description'] = $oFeedback->getFeedbackTextSource();
        }
    }

    protected function fieldAdd() {
        $this->_setTitle('Добавить поле');

        $this->SetTemplateAction('field-add');

        if (F::isPost('security_key') && E::ModuleSecurity()->ValidateSecurityKey()) {
            $oField = E::GetEntity('Topic_Field');
            $oField->setFieldType(F::GetRequest('field_type'));
            $oField->setFeedbackId(1);
            $oField->setFieldName(F::GetRequest('field_name'));
            $oField->setFieldUniqueName(F::GetRequest('field_unique_name'));
            $oField->setFieldDescription(F::GetRequest('field_description'));
            $oField->setFieldRequired(F::GetRequest('field_required'));
            if (F::GetRequest('field_type') == 'select') {
                $oField->setOptionValue('select', F::GetRequest('field_values'));
            }

            if (E::Module('PluginFeedback_ModuleFeedback')->addField($oField)) {
                E::ModuleMessage()->AddNoticeSingle(E::ModuleLang()->Get('action.admin.contenttypes_success_fieldadd'), null, true);
                R::Location(dirname(Router::RealUrl()));
            }
        }

        return false;
    }

    protected function fieldEdit() {
        $this->_setTitle('Редактировать поле');

        $this->SetTemplateAction('field-add');
        E::ModuleViewer()->Assign('aTypes', []);
    }

    protected function fieldRemove() {

    }
}
