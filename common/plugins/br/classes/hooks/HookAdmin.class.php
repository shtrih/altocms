<?php

/**
 * HookAdmin
 * Файл хука плагина Br
 *
 * @author      Андрей Воронов <andreyv@gladcode.ru>
 * @copyrights  Copyright © 2014, Андрей Воронов
 *              Является частью плагина Br
 * @version     0.0.1 от 11.11.2014 12:36
 */
class PluginBr_HookAdmin extends Hook {
    /**
     * Регистрация хуков
     */
    public function RegisterHook() {
        if (E::IsAdmin()) {
            $this->AddHook('template_admin_menu_content', 'AdminMenuInject', __CLASS__);
        }
    }

    public function AdminMenuInject() {

        return $this->Viewer_Fetch(Plugin::GetTemplatePath('br') . '/tpls/inject.admin.menu.tpl');
    }

}
