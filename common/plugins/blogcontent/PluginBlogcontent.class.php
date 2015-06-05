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
*	Plugin BlogContent
*	Ilya Shlykov (tih)
*	contact e-mail: contact@shlykov.info
*
*/

/**
 * Запрещаем напрямую через браузер обращение к этому файлу.
 */
if (!class_exists('Plugin')) {die('Hacking attemp!');}

class PluginBlogcontent extends Plugin {
    protected $aInherits=array(
        'module'  =>array('ModuleTopic')
    );

    /**
    * Активация плагина
    */
    public function Activate() {
        $aTypes = $this->Topic_GetTopicTypes();
        $aConfig['personal'] = $aTypes;
        $aConfig['default'] = $aTypes;
        $success = file_put_contents(Config::Get('sys.cache.dir').'blogcontent.cfg',serialize($aConfig) );
        if ($success) $this->Message_AddNotice('Настройки успешно сохраненны');
        else  $this->Message_AddError('Не удалось сохранить настройки');
        return $success;
    }

    /**
     * Инициализация плагина
     */
    public function Init() {
    }
}
