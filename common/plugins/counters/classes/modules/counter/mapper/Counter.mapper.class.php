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
 * @package plugin Counters
 */

class PluginCounters_ModuleCounter_MapperCounter extends Mapper {

    /**
     * @param string $sTargetType
     * @param int    $iTargetId
     * @param string $sAction
     * @param int    $iTic
     *
     * @return int|bool
     */
    public function TicCounter($sTargetType, $iTargetId, $sAction = '', $iTic = 1) {

        $sql = "
            SELECT id, counter FROM ?_counter WHERE target_type=? AND target_id=?d AND saction=? LIMIT 1
        ";
        $aRow = $this->oDb->selectRow($sql, $sTargetType, $iTargetId, $sAction);
        if ($aRow) {
            $sql = "
                UPDATE ?_counter SET counter=counter+?d WHERE id=?d LIMIT 1
            ";
            $bResult = $this->oDb->query($sql, $iTic, $aRow['id']);
        } else {
            $sql = "
                INSERT
                  INTO ?_counter(target_type, target_id, saction, counter)
                  VALUES(?, ?d, ?, ?d)
            ";
            $bResult = $this->oDb->query($sql, $sTargetType, $iTargetId, $sAction, $iTic);
        }
        return ($bResult !== false) ? $aRow['counter'] + $iTic : false;
    }

    /**
     * @param string $sTargetType
     * @param int    $iTargetId
     * @param string $sAction
     *
     * @return int
     */
    public function GetCounter($sTargetType, $iTargetId, $sAction = '') {

        $sql = "
            SELECT counter FROM ?_counter WHERE target_type=? AND target_id=?d AND saction=? LIMIT 1
        ";
        $iCounter = $this->oDb->selectCell($sql, $sTargetType, $iTargetId, $sAction);
        return ($iCounter ? $iCounter : 0);
    }

    /**
     * @param string $sTargetType
     * @param array  $aTargetsId
     * @param string $sAction
     *
     * @return array
     */
    public function GetCountersByTargetsId($sTargetType, $aTargetsId, $sAction = '') {

        $sql = "
            SELECT target_id AS ARRAY_KEY, counter
            FROM ?_counter
            WHERE
                target_type=?
                AND target_id IN (?a)
                AND saction=? LIMIT ?d
        ";
        $aCounters = $this->oDb->select($sql, $sTargetType, $aTargetsId, $sAction, sizeof($aTargetsId));
        $aResult = array();
        if ($aCounters) {
            foreach($aCounters as $iTopicId => $aRow) {
                $aResult[$iTopicId] = $aRow['counter'];
            }
        }
        return $aResult;
    }

    /**
     * @param string $sTargetType
     * @param int    $iTargetId
     * @param string $sAction
     *
     * @return int
     */
    public function SumCounter($sTargetType=null, $iTargetId=null, $sAction = null) {

        if (!$sTargetType && !$iTargetId && !$sAction) {
            return 0;
        }

        $sql = "
            SELECT Count(counter)
            FROM ?_counter
            WHERE
              1=1
              {AND target_type=?}
              {AND target_id=?d}
              {AND saction=?}
        ";
        $iCounter = $this->oDb->selectCell($sql,
            $sTargetType ? $sTargetType : DBSIMPLE_SKIP,
            $iTargetId ? $iTargetId : DBSIMPLE_SKIP,
            $sAction ? $sAction : DBSIMPLE_SKIP
        );
        return ($iCounter ? $iCounter : 0);
    }

    /**
     * @param string $sTargetType
     * @param int    $iTargetId
     * @param string $sAction
     *
     * @return bool
     */
    public function DeleteCounter($sTargetType = null, $iTargetId = null, $sAction = null) {

        if (!$sTargetType && !$iTargetId && !$sAction) {
            return false;
        }

        $sql = "
            DELETE FROM ?_counter
            WHERE
              1=1
              {AND target_type=?}
              {AND target_id=?d}
              {AND saction=?}
        ";
        $bResult = $this->oDb->query($sql,
            $sTargetType ? $sTargetType : DBSIMPLE_SKIP,
            $iTargetId ? $iTargetId : DBSIMPLE_SKIP,
            $sAction ? $sAction : DBSIMPLE_SKIP
        );
        return ($bResult !== false);
    }

}
// EOF