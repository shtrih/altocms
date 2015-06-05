<?php

/**
 * Запрещаем напрямую через браузер обращение к этому файлу.
 */
if (!class_exists('Plugin')) {
    die('Hacking attemp!');
}

class PluginHidespoiler extends Plugin {

    public static $aUserComments = 0;

    protected $aInherits = array(
        'modules' => array(
            'ModuleTopic' => '_ModuleTopic',
        ),
    );

    // Активация плагина
    public function Activate() {
        return TRUE;
    }

    // Деактивация плагина
    public function Deactivate() {
        return TRUE;
    }

    // Инициализация плагина
    public function Init() {
        parent::Init();

        self::$aUserComments = $this->PluginHidespoiler_ModuleHidespoiler_GetCommentsCountByUser();

        $this->Viewer_AppendStyle(Plugin::GetTemplateWebPath('HideSpoiler') . "/css/hidespoler.css");
    }
}