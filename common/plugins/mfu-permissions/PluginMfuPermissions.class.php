<?php

/**
 * Запрещаем напрямую через браузер обращение к этому файлу.
 */
if (!class_exists('Plugin')) {
    die('Hacking attempt!');
}

class PluginMfuPermissions extends Plugin
{
    // Объявление делегирований (нужны для того, чтобы переопределить файлы шаблонов)
    public $aDelegates = [
        /**
     * 'template' => array('index.tpl'=>'_my_plugin_index.tpl'),
     * Замена index.tpl из корня скина файлом /common/plugins/abcplugin/templates/skin/default/my_plugin_index.tpl
     *
     * 'template'=>array('actions/ActionIndex/index.tpl'=>'_actions/ActionTest/index.tpl'),
     * Замена index.tpl из скина из папки actions/ActionIndex/ файлом /common/plugins/abcplugin/templates/skin/default/actions/ActionTest/index.tpl
     */
    ];

    // Объявление наследований (экшены, модули, мапперы и сущности)
    protected $aInherits = [
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
        'module' => [
            'ModuleMresource',
            'ModuleMresource_MapperMresource',
        ]
    ];

    public function Activate() {
        return true;
    }

    public function Deactivate() {
        return true;
    }

    public function Init() {
        return true;
    }
}
