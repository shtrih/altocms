<?php

class PluginFeedback_ActionFeedback extends ActionPlugin {

    /**
     * Инициализация экшена
     */
    public function Init() {
        $this->SetDefaultEvent('index');
    }

    /**
     * Регистрируем евенты
     */
    protected function RegisterEvent() {
        $this->AddEvent('index', 'index');
        $this->AddEventPreg('~^\d+$~', 'showFeedbackPage');
    }

    protected function index() {
        return parent::EventNotFound();
    }

    protected function showFeedbackPage() {
        $this->SetTemplateAction('index');

        // У нас в ивенте передаётся идентификатор
        $iFeedbackId = $this->sCurrentEvent;

        /**
         * @var PluginFeedback_ModuleFeedback_EntityFeedback $oFeedback
         */
        $oFeedback = E::Module('PluginFeedback_ModuleFeedback')->getFeedbackById($iFeedbackId);

        if (!$oFeedback) {
            return parent::EventNotFound();
        }

        $aFields = $oFeedback->getFields();
        E::ModuleViewer()->Assign('aFields', $aFields);
        E::ModuleViewer()->Assign('header', $oFeedback->getTitle());
        E::ModuleViewer()->AddHtmlTitle($oFeedback->getTitle());

        if (F::GetPost('security_key')) {
            if (!E::ModuleSecurity()->ValidateSecurityKey()) {
                E::ModuleMessage()->AddError('Что-то пошло не так.');
                R::Location(R::GetPathWebCurrent());
            }

//            E::ModuleCaptcha()->Verify(F::GetPostStr('captcha'));

            /** @var ModuleTopic_EntityField $oField */
            foreach ($aFields as $oField) {
                var_dump($oField);
            }
            exit;
        }
    }

    /**
     * Завершение работы экшена
     */
    public function EventShutdown() {

    }
}
