<?php

/**
 * Запрещаем напрямую через браузер обращение к этому файлу.
 */
if (!class_exists('Plugin')) {
    die('Hacking attemp!');
}

class PluginHidespoiler extends Plugin {

    public static $aUserComments = 0;

    // Объявление делегирований (нужны для того, чтобы назначить свои экшны и шаблоны)
    public $aDelegates = array(
        /**
         * 'action' => array('ActionIndex'=>'_ActionSomepage'),
         * Замена экшна ActionIndex на ActionSomepage из папки плагина
         *
         * 'template' => array('index.tpl'=>'_my_plugin_index.tpl'),
         * Замена index.tpl из корня скина файлом /common/plugins/abcplugin/templates/skin/default/my_plugin_index.tpl
         *
         * 'template'=>array('actions/ActionIndex/index.tpl'=>'_actions/ActionTest/index.tpl'),
         * Замена index.tpl из скина из папки actions/ActionIndex/ файлом /common/plugins/abcplugin/templates/skin/default/actions/ActionTest/index.tpl
         */
        'template' => array(
            'editors/editor.markitup.tpl' => '_editors/editor.markitup.tpl',
            'comments/comment.single.tpl' => '_comments/comment.single.tpl',
        )
    );

    protected $aInherits = array(
        'modules' => array(
            'ModuleTopic'  => '_ModuleTopic',
            'ModuleText'   => '_ModuleText',
            'ModuleViewer' => '_ModuleViewer',
        ),
        'actions' => array(
            'ActionAjax' => '_ActionAjax',
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
        $sTemplateDir = Plugin::GetTemplateDir(__CLASS__);
        E::ModuleViewer()->AppendScript($sTemplateDir . "assets/js/hidespoiler.js");
        E::ModuleViewer()->AppendStyle($sTemplateDir . "assets/css/hidespoiler.backward.css");
        E::ModuleViewer()->AppendStyle($sTemplateDir . "assets/css/hidespoiler.css");
    }
}