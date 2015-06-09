<?php
/*---------------------------------------------------------------------------
 * @Project: Alto CMS
 * @Project URI: http://altocms.com
 * @Plugin Name: Similar Topics
 * @Plugin ID: similartopics
 * @Description: Similar Topics (defined by tags)
 * @Copyright: Alto CMS Team
 * @License: GNU GPL v2 & MIT
 *----------------------------------------------------------------------------
 */

/**
 * @package plugin Similar Topics
 */

class PluginSimilartopics extends Plugin {

    protected $aInherits
        = array(
            'module' => array(
                'ModuleTopic',
            ),
            'entity' => array(
                'ModuleTopic_EntityTopic',
            ),
            'mapper' => array(
                'ModuleTopic_MapperTopic',
            ),
        );

    /**
     * Активация плагина
     */
    public function Activate() {

        return true;
    }

    /**
     * Инициализация плагина
     */
    public function Init() {

        E::ModuleViewer()->AppendStyle(Plugin::GetTemplateDir(__CLASS__) . 'assets/css/similartopics.css');
        return true;
    }


}

// EOF