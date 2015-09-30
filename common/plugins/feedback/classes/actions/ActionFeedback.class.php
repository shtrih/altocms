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
        $this->AddEvent('index','EventIndex');
    }

    protected function EventIndex() {

        E::ModuleViewer()->Assign('aFields', []);
    }

    /**
     * Завершение работы экшена
     */
    public function EventShutdown() {

    }
}
