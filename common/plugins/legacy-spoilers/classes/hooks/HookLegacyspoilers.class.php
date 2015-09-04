<?php

class PluginLegacyspoilers_HookLegacyspoilers extends Hook {

    /**
     * Регистрация хуков
     */
    public function RegisterHook() {
        $this->AddHook('topic_show', 'correctTopic');
    }

    /**
     * Хук, выполняющий смену содержимого топика
     *
     * @param $aParams
     */
    public function correctTopic($aParams) {
        /** @var ModuleTopic_EntityTopic $oTopic Открываемый топик */
        if (Config::Get('plugin.betterspoilers.use_hook')) {
            $oTopic = $aParams['oTopic'];
            $oTopic->setText(E::Module('PluginLegacyspoilers_ModuleLegacyspoilers')->MakeCorrection($oTopic->getText()));
        }
    }
}
