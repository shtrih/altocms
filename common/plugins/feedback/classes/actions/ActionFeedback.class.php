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
        $this->AddEventPreg('~^\d+$~', 'showFeedback');
    }

    protected function index() {
        var_dump(__FUNCTION__);exit;
        return parent::EventNotFound();
    }

    protected function showFeedback() {
var_dump(__FUNCTION__);exit;
        E::Module('PluginFeedback_ModuleFeedback')->getFeedbackBy;
        E::ModuleViewer()->Assign('aFields', []);
    }

    /**
     * Завершение работы экшена
     */
    public function EventShutdown() {

    }
}
