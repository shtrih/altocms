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

class PluginBlogcontent_ActionAdmin extends ActionPlugin
{
    protected  $sMenuItemSelect = 'blogcontent';

    public function Init()
    {
        if(!$this->User_IsAuthorization() or !$oUserCurrent=$this->User_GetUserCurrent() or !$oUserCurrent->isAdministrator()) {
            return parent::EventNotFound();
        }
       $this->SetDefaultEvent('admin');
       $this->oUserCurrent=$oUserCurrent;
    }

    protected function RegisterEvent()
    {
         $this->AddEvent('admin', 'EventAdmin');
    }

    protected function EventAdmin()
    {   $aConfig = unserialize(@file_get_contents(Config::Get('sys.cache.dir').'blogcontent.cfg'));
        if (isPost('save')){
            $this->Security_ValidateSendForm();
            $aConfig = getRequest('config');
            if (!isset($aConfig['personal'])) $aConfig['personal'] = array();
            if (!isset($aConfig['default'])) $aConfig['default'] = array();
            $success = file_put_contents(Config::Get('sys.cache.dir').'blogcontent.cfg',serialize($aConfig) );
            if ($success) $this->Message_AddNotice('Настройки успешно сохраненны');
                else  $this->Message_AddError('Не удалось сохранить настройки');

        }
        $aBlogs = $this->Blog_GetBlogs();
        $aTypes = $this->Topic_GetTopicTypes();
        $this->Viewer_Assign('aBlogs',$aBlogs);
        $this->Viewer_Assign('aTypes',$aTypes);
        $this->Viewer_Assign('aConfig',$aConfig);
        $this->Viewer_Assign('bNoSidebar',true);
        $this->SetTemplateAction('index');
    }

    public function EventShutdown()
    {
        $this->Viewer_Assign('sMenuItemSelect',$this->sMenuItemSelect);
    }
}