<?php

/*-------------------------------------------------------
*
*   Must Have Blogs.
*   Copyright © 2012 Alexei Lukin
*
*--------------------------------------------------------
*
*   Official site: imthinker.ru/stickytopics2
*   Contact e-mail: kerbylav@gmail.com
*
---------------------------------------------------------
*/

class PluginMhb_HookMhb extends Hook
{
    public function RegisterHook() {
        $this->AddHook('template_admin_menu_settings', 'AdminMenuInject');
    }

    public function AdminMenuInject() {
        return E::ModuleViewer()->Fetch(Plugin::GetTemplateFile(__CLASS__, 'inject.admin.menu.tpl'));
    }
}
