<?php
/*-------------------------------------------------------
*
*   LiveStreet Engine Social Networking
*   Copyright © 2008 Mzhelskiy Maxim
*
*--------------------------------------------------------
*
*   Official site: www.livestreet.ru
*   Contact e-mail: rus.engine@gmail.com
*
*   GNU General Public License, version 2:
*   http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
*
---------------------------------------------------------
*
*   Plugin BlogContent
*	Ilya Shlykov (tih)
*	contact e-mail: contact@shlykov.info
*/

class PluginBlogcontent_HookBlogcontent extends Hook {
    public function RegisterHook() {
        $this->AddHook('module_Blog_GetBlogsAllowByUser_after','filterBlogs',__CLASS__,0);
        $this->oUserCurrent = $this->User_GetUserCurrent();
        if($this->oUserCurrent && $this->oUserCurrent->isAdministrator())
        {
           if(Config::Get('plugin.blogcontent.addmenu')) $this->AddHook('template_menu_blog', 'addMenu', __CLASS__);
            $this->AddHook('template_menu_admin', 'addMenu', __CLASS__);
        }
    }
    /**
     * Фильтрует список доступных блогов в зависимости от экшена. Если пришедшего
     * экшена нет в настройках, то выводит все доступные пользователю блоги
     *
     * @return type
     */
    public function filterBlogs(&$aBlogsAll){
        $aConfig = unserialize(@file_get_contents(Config::Get('sys.cache.dir').'blogcontent.cfg'));
        $sAction = Router::GetAction();
        foreach ($aBlogsAll['result'] as $key => $oBlog) {
            if (!array_key_exists($oBlog->getId(), $aConfig) ){
                if (!in_array($sAction,$aConfig['default']))
                    unset($aBlogsAll['result'][$key]);
            } else {
                if (!in_array($sAction,$aConfig[$oBlog->getId()]))
                    unset($aBlogsAll['result'][$key]);
            }
        }
    }

    public function addMenu()
    {
        return $this->Viewer_Fetch(Plugin::GetTemplatePath(__CLASS__).'/menu.admin.tpl');
    }
}