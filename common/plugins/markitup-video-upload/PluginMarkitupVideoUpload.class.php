<?php

if (!class_exists('Plugin')) {
    die('Hacking attempt!');
}

class PluginMarkitupVideoUpload extends Plugin {

    // Объявление делегирований (нужны для того, чтобы переопределить файлы шаблонов)
    public $aDelegates = array(
            /**
             * 'template' => array('index.tpl'=>'_my_plugin_index.tpl'),
             * Замена index.tpl из корня скина файлом /common/plugins/abcplugin/templates/skin/default/my_plugin_index.tpl
             *
             * 'template'=>array('actions/ActionIndex/index.tpl'=>'_actions/ActionTest/index.tpl'),
             * Замена index.tpl из скина из папки actions/ActionIndex/ файлом /common/plugins/abcplugin/templates/skin/default/actions/ActionTest/index.tpl
             */
    );

    // Объявление наследований (экшены, модули, мапперы и сущности)
    protected $aInherits=array(
       /**
        * Переопределение модулей (функционал):
        * 'module'  =>array('ModuleTopic'=>'_ModuleTopic'),
        *
        * К классу ModuleTopic (/classes/modules/Topic.class.php) добавляются методы из
        * PluginAbcplugin_ModuleTopic (/plugins/abcplugin/classes/modules/Topic.class.php) - новые или замена существующих
        *
        *
        *
        * Переопределение мапперов (запись/чтение объектов в/из БД):
        * 'mapper'  =>array('ModuleTopic_MapperTopic' => '_ModuleTopic_MapperTopic'),
        *
        * К классу ModuleTopic_MapperTopic (/classes/modules/mapper/Topic.mapper.class.php) добавляются методы из
        * PluginAbcplugin_ModuleTopic_EntityTopic (/plugins/abcplugin/classes/modules/mapper/Topic.mapper.class.php) - новые или замена существующих
        *
        *
        *
        * Переопределение сущностей (интерфейс между объектом и записью/записями в БД):
        * 'entity'  =>array('ModuleTopic_EntityTopic' => '_ModuleTopic_EntityTopic'),
        *
        * К классу ModuleTopic_EntityTopic (/classes/modules/entity/Topic.entity.class.php) добавляются методы из
        * PluginAbcplugin_ModuleTopic_EntityTopic (/plugins/abcplugin/classes/modules/entity/Topic.entity.class.php) - новые или замена существующих
        *
        */
    );

    // Активация плагина
    public function Activate() {
        exec('ffmpeg -version', $aOutput, $iResultCode);
        if ($iResultCode > 0) {
            E::ModuleMessage()->AddError('На сервере не установлен пакет «ffmpeg», работа плагина без этого пакета невозможна.');

            return false;
        }

        return true;
    }

    // Деактивация плагина
    public function Deactivate(){
        return true;
    }

    // Инициализация плагина
    public function Init() {
        // TODO: При удалении постов, проверять и удалять видео с картинками
        $sTemplateDir = Plugin::GetTemplateDir(__CLASS__);
        if ($sTemplateDir) {
            E::ModuleViewer()->AppendStyle($sTemplateDir . "assets/css/mrktp-video-upload.css"); // Добавление своего CSS
            E::ModuleViewer()->AppendScript($sTemplateDir . "assets/js/mrktp-video-upload.js"); // Добавление своего JS

            //E::ModuleViewer()->AddMenu('blog', $sTemplateDir . 'menu.blog.tpl'); // например, задаем свой вид меню
        }

        return true;
    }
}
