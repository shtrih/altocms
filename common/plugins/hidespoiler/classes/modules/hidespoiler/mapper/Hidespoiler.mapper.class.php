<?php

/**
 * Class PluginHidespoiler_ModuleHidespoiler_MapperHidespoiler
 */
class PluginHidespoiler_ModuleHidespoiler_MapperHidespoiler extends Mapper {

    /**
     * Получаем массив количеств комментариев по топику
     * @param $iUserId
     * @return array
     */
    public function GetCommentsCountByUser($iUserId) {
        $sql = "SELECT
                    COUNT(`comment_id`) as count
                FROM
                  " . Config::Get('db.table.comment') . "
                WHERE
                    `user_id` = ?d
                    AND `target_type` = 'topic'";
        $aResult = 0;
        if ($aRow = $this->oDb->selectRow($sql, $iUserId)) {
                return $aRow['count'];
        }
        return $aResult;
    }



}