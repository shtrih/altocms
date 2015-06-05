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

/**
 * Расширяем для работы с топиками
 *
 */
class PluginBlogcontent_ModuleTopic extends PluginTopicfilter_Inherit_ModuleTopic {

	/**
	 * Возвращает список типов топика
	 *
	 * @return array
	 */
	public function GetTopicTypes() {
        //Если версия LS < 1.0 спользуем sql запрос для получения типов топиков иначе берем из модуля
        if(defined('LS_VERSION') and version_compare(LS_VERSION,'1.0','<'))
            return $this->oMapperTopic->GetTopicTypes();
        return $this->aTopicTypes;
	}


	/**
	 * Проеверяем тип топика перед добавлением
	 *
	 * @param ModuleTopic_EntityTopic $oTopic	Объект топика
	 * @return ModuleTopic_EntityTopic|bool
	 */
	public function AddTopic(ModuleTopic_EntityTopic $oTopic) {
        if(!$this->checkTopicType($oTopic)) return false;
		return parent::Addtopic($oTopic);
	}

	/**
	 * Проеверяем тип топика перед обновлением
	 *
	 * @param ModuleTopic_EntityTopic $oTopic	Объект топика
	 * @return bool
	 */
	public function UpdateTopic(ModuleTopic_EntityTopic $oTopic) {
        if(!$this->checkTopicType($oTopic)) return false;
        return parent::UpdateTopic($oTopic);
	}
    protected function checkTopicType($oTopic){

        $aConfig = unserialize(@file_get_contents(Config::Get('sys.cache.dir').'blogcontent.cfg'));
        $sBlogId   = $oTopic/*['0']*/->getBlogId();
        $sBlogType = $this->Blog_GetBlogById($sBlogId)->getUrl();
        if ($sBlogType ==='personal'){
            if (!in_array($oTopic/*['0']*/->getType(), $aConfig['personal'])) {
                return false;
            }
        }
        if (array_key_exists($sBlogId,$aConfig)) {
            if (!in_array($oTopic/*['0']*/->getType(),$aConfig[$sBlogId])) {
                return false;
            }
        } else {
            if (!in_array($oTopic/*['0']*/->getType(), $aConfig['default'])) {
                return false;
            }
        }
        return true;
    }
}