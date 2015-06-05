<?php
/** Запрещаем напрямую через браузер обращение к этому файлу.  */
if (!class_exists('Plugin')) {
    die('Hacking attempt!');
}

/**
 * PluginBr.class.php
 * Файл основного класса плагина Br
 *
 * @author      Андрей Воронов <andreyv@gladcode.ru>
 * @copyrights  Copyright © 2014, Андрей Воронов
 *              Является частью плагина Br
 *
 * @method void Viewer_AppendStyle
 * @method void Viewer_AppendScript
 * @method void Viewer_Assign
 *
 * @version     0.0.1 от 03.11.2014 01:59
 */
class PluginBr extends Plugin {

    /** @var array $aDelegates Объявление делегирований */
    protected $aDelegates = array(
        'template' => array(),
    );

    /** @var array $aInherits Объявление переопределений (модули, мапперы и сущности) */
    protected $aInherits = array(
        'actions' => array(
            'ActionAjax',
            'ActionAdmin',
        ),
        'modules' => array(
            'ModuleUploader',
        ),
        'entity'  => array(
            'ModuleBlog_EntityBlog',

        ),
    );

    /**
     * Активация плагина
     * @return bool
     */
    public function Activate() {
        if (!$this->isTableExists('prefix_branding')) {
            $this->ExportSQL(dirname(__FILE__) . '/sql/install.sql');
        }

        return TRUE;
    }

    /**
     * Деактивация плагина
     * @return bool
     */
    public function Deactivate() {
        return TRUE;
    }

    /**
     * Инициализация плагина
     */
    public function Init() {
        $this->Viewer_Assign("sTemplatePathBr", Plugin::GetTemplatePath(__CLASS__));

        // Подключение стилей
        $this->Viewer_AppendStyle(Plugin::GetTemplatePath(__CLASS__) . "assets/css/style.min.css");

        // Подключение скриптов
        $this->Viewer_AppendScript(Plugin::GetTemplatePath(__CLASS__) . "assets/js/script.min.js");

    }

}
