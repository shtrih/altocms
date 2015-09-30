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
        $aPostParams = $this->getPost();
        if ($aPostParams && E::ModuleSecurity()->ValidateSecurityKey()) {
            E::Module('PluginFeedback_ModuleFeedback')->update(
                1,
                F::GetRequestStr('feedback_webpath', 'feedback'),
                F::GetRequestStr('feedback_title', 'Написать администрации'),
                F::GetRequestStr('feedback_active', '0'),
                F::GetRequestStr('feedback_description', '')
            );
        }
    }

    protected function fieldAdd() {
        $this->_setTitle('Добавить поле');

        $this->SetTemplateAction('field-add');
        E::ModuleViewer()->Assign('aTypes', []);
    }

    protected function fieldEdit() {
        $this->_setTitle('Редактировать поле');

        $this->SetTemplateAction('field-add');
        E::ModuleViewer()->Assign('aTypes', []);
    }

    protected function fieldRemove() {

    }
}
