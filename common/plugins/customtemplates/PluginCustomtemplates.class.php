<?php

if (!class_exists('Plugin')) {
    die('Hacking attempt!');
}

class PluginCustomtemplates extends Plugin {

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
            'tpls/topics/topic.type_nsfw_toggleable-edit.tpl' => '_tpls/topics/topic.type_nsfw_toggleable-edit.tpl',
            'tpls/topics/topic.type_nsfw_toggleable-list.tpl' => '_tpls/topics/topic.type_nsfw_toggleable-list.tpl',
            'tpls/topics/topic.type_nsfw_toggleable-show.tpl' => '_tpls/topics/topic.type_nsfw_toggleable-show.tpl',
            'widgets/widget.stream_comment.tpl' => '_widgets/widget.stream_comment.tpl',
        )
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

    public function Activate() {
        return true;
    }

    public function Deactivate(){
        return true;
    }

    public function Init() {
        $sTemplateDir = Plugin::GetTemplateDir(__CLASS__);
        E::ModuleViewer()->AppendStyle($sTemplateDir . "assets/css/styles.css");
        E::ModuleViewer()->AppendStyle($sTemplateDir . "assets/css/field-nonhtml-tags-help.css");
        E::ModuleViewer()->AppendScript($sTemplateDir . "assets/js/field-nonhtml-tags-help.js");

        //E::ModuleViewer()->AddMenu('blog',Plugin::GetTemplateDir(__CLASS__).'/menu.blog.tpl'); // например, задаем свой вид меню
    }
}
