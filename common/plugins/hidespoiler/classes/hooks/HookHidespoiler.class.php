<?php

/**
 * Скрывает часть текста топика для пользователей у которых мало комментариев
 */
class PluginHidespoiler_HookHidespoiler extends Hook {

    /**
     * Регистрация хуков
     */
    public function RegisterHook() {
        $this->AddHook('topic_show', 'correctTopic');
        $this->AddHook('template_markitup_before_init', 'markitupBeforeInit');
    }

    /**
     * Хук, выполняющий смену содержимого топика
     *
     * @param array() $params
     */
    public function correctTopic($params) {
        /** @var ModuleTopic_EntityTopic $oTopic Открываемый топик */
        if (Config::Get('plugin.hidespoiler.use_hook')) {
            $oTopic = $params['oTopic'];
            $oTopic->setText($this->PluginHidespoiler_ModuleHidespoiler_MakeCorrection($oTopic->getText()));
        }
    }

    public function markitupBeforeInit($aParams) {
        return E::ModuleViewer()->Fetch('markitup_before_init.tpl', $aParams);
    }
}