<?php
/*---------------------------------------------------------------------------
 * @Project: Alto CMS
 * @Project URI: http://altocms.com
 * @PluginId: counters
 * @PluginName: Counters
 * @Description: Counters for topics views
 * @Copyright: Alto CMS Team
 * @License: GNU GPL v2 & MIT
 *----------------------------------------------------------------------------
 */

/**
 * Запрещаем напрямую через браузер обращение к этому файлу.
 */
if (!class_exists('Plugin')) {
    die('Hacking attempt!');
}

/**
 * @package plugin Counters
 */
class PluginCounters extends Plugin {

    protected $aDelegates = array(
    );

    protected $aInherits = array(
        'entity' => array(
            'ModuleTopic_EntityTopic',
        ),
        'module' => array(
            'ModuleTopic',
        ),
        'action' => array(
        ),
        'widget' => array(
        ),
    );

    /**
     * Активация плагина
     */
    public function Activate() {

        $this->ExportSQL(__DIR__ . '/install/db/init.sql');
        return true;
    }

    /**
     * Инициализация плагина
     */
    public function Init() {

        return true;
    }
}

// EOF