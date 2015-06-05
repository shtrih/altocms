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
 * Расширяем объект маппера для работы с БД
 *
 * @package modules.topic
 * @since 1.0
 */
class PluginBlogcontent_ModuleTopic_MapperTopic extends PluginBlogcontent_Inherit_ModuleTopic_MapperTopic {
	/**
	 * Получаем список типов топиков
	 *
	 */
    public function GetTopicTypes(){
        $sql = "SELECT COLUMN_TYPE
                FROM INFORMATION_SCHEMA.COLUMNS
                WHERE TABLE_NAME = '".Config::Get('db.table.topic')."'
                AND COLUMN_NAME = 'topic_type';";
        $oDb = $this->Database_GetConnect();
        $sResult = $oDb->selectCell($sql);
        preg_match_all('/\'(\w*)\'/i',$sResult, $aTypes );
        return $aTypes[1];

    }

}