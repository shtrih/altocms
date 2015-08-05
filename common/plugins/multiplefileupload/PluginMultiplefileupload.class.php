<?php

/**
 * Запрещаем напрямую через браузер обращение к этому файлу.
 */
if (!class_exists('Plugin')) {
    die('Hacking attemp!');
}

class PluginMultiplefileupload extends Plugin {

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
            'tpls/fields/customs/field.custom.multiple-file-upload-edit.tpl'=>'_tpls/fields/customs/field.custom.multiple-file-upload-edit.tpl',
            'tpls/fields/customs/field.custom.multiple-file-upload-show.tpl'=>'_tpls/fields/customs/field.custom.multiple-file-upload-show.tpl',
        ),
    );

    // Объявление переопределений (модули, мапперы и сущности)
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
        return true;
    }

    // Деактивация плагина
    public function Deactivate(){
        return true;
    }


    // Инициализация плагина
    public function Init() {
        $sPluginLibDir = Plugin::GetDir(__CLASS__) . 'templates/vendor/';
        $sTemplateDir = Plugin::GetTemplateDir(__CLASS__);
        E::ModuleViewer()->AppendScript($sPluginLibDir . 'JavaScript-Templates-2.5.5/js/tmpl.js');
        E::ModuleViewer()->AppendScript($sPluginLibDir . 'jQuery-File-Upload-9.10.5/js/jquery.fileupload.js');
        E::ModuleViewer()->AppendScript($sPluginLibDir . 'jQuery-File-Upload-9.10.5/js/jquery.fileupload-process.js');
        E::ModuleViewer()->AppendScript($sPluginLibDir . 'jQuery-File-Upload-9.10.5/js/jquery.fileupload-image.js');
        E::ModuleViewer()->AppendScript($sPluginLibDir . 'jQuery-File-Upload-9.10.5/js/jquery.fileupload-ui.js');
        E::ModuleViewer()->AppendScript($sTemplateDir . 'assets/js/fileupload-init.js');
        E::ModuleViewer()->AppendStyle($sPluginLibDir . 'jQuery-File-Upload-9.10.5/css/jquery.fileupload.css');
        E::ModuleViewer()->AppendStyle($sPluginLibDir . 'jQuery-File-Upload-9.10.5/css/jquery.fileupload-ui.css');
    }
}

