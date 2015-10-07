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

        $this->_setTitle('Добавить поле');


        $aFields = [];
        if ($oFeedback)
            $aFields = $oFeedback->getFields();

        E::ModuleViewer()->Assign('aFields', $aFields);
    }

    /**
     * Завершение работы экшена
     */
    public function EventShutdown() {

    }
}
