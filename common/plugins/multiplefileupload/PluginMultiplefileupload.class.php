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
        'module' => array(
            'ModuleMresource',
            'ModuleMresource_MapperMresource'
        )
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
        Config::Set('router.page.multiplefileupload', 'PluginMultiplefileupload_ActionMultiplefileupload');

        $sPluginLibDir = Plugin::GetDir(__CLASS__) . 'templates/vendor/';
        $sTemplateDir = Plugin::GetTemplateDir(__CLASS__);
        $oModuleViewer = E::ModuleViewer();

        $oModuleViewer->AppendScript($sPluginLibDir . 'JavaScript-Templates-2.5.5/js/tmpl.js');
        // The Load Image plugin is included for the preview images and image resizing functionality
        $oModuleViewer->AppendScript($sPluginLibDir . 'JavaScript-Load-Image-1.13.1/js/load-image.all.min.js');
        // The Iframe Transport is required for browsers without support for XHR file uploads
        $oModuleViewer->AppendScript($sPluginLibDir . 'jQuery-File-Upload-9.10.5/js/jquery.iframe-transport.js');
        // The basic File Upload plugin
        $oModuleViewer->AppendScript($sPluginLibDir . 'jQuery-File-Upload-9.10.5/js/jquery.fileupload.js');
        // The File Upload processing plugin
        $oModuleViewer->AppendScript($sPluginLibDir . 'jQuery-File-Upload-9.10.5/js/jquery.fileupload-process.js');
        // The File Upload image preview & resize plugin
        $oModuleViewer->AppendScript($sPluginLibDir . 'jQuery-File-Upload-9.10.5/js/jquery.fileupload-image.js');
        // The File Upload audio preview plugin
        $oModuleViewer->AppendScript($sPluginLibDir . 'jQuery-File-Upload-9.10.5/js/jquery.fileupload-audio.js');
        // The File Upload video preview plugin
        $oModuleViewer->AppendScript($sPluginLibDir . 'jQuery-File-Upload-9.10.5/js/jquery.fileupload-video.js');
        // The File Upload validation plugin
        $oModuleViewer->AppendScript($sPluginLibDir . 'jQuery-File-Upload-9.10.5/js/jquery.fileupload-validate.js');
        // The File Upload user interface plugin
        $oModuleViewer->AppendScript($sPluginLibDir . 'jQuery-File-Upload-9.10.5/js/jquery.fileupload-ui.js');
        // The main application script
        $oModuleViewer->AppendScript($sTemplateDir . 'assets/js/fileupload-init.js');

        $oModuleViewer->AppendStyle($sPluginLibDir . 'jQuery-File-Upload-9.10.5/css/jquery.fileupload.css');
        $oModuleViewer->AppendStyle($sPluginLibDir . 'jQuery-File-Upload-9.10.5/css/jquery.fileupload-ui.css');
    }
}

