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

class PluginCounters_ModuleCounter extends Module {

    /** @var  PluginCounters_ModuleCounter_MapperCounter */
    protected $oMapper;

    public function Init() {

        $this->oMapper = E::GetMapper(__CLASS__);
    }

    /**
     * @param string $sTargetType
     * @param int    $iTargetId
     * @param string $sAction
     * @param int    $iTic
     *
     * @return int|bool
     */
    public function TicCounter($sTargetType, $iTargetId, $sAction = '', $iTic = 1) {

        return $this->oMapper->TicCounter($sTargetType, $iTargetId, $sAction, $iTic);
    }

    /**
     * @param string $sTargetType
     * @param int    $iTargetId
     * @param string $sAction
     *
     * @return int|bool
     */
    public function IncCounter($sTargetType, $iTargetId, $sAction = '') {

        return $this->oMapper->TicCounter($sTargetType, $iTargetId, $sAction, 1);
    }

    /**
     * @param string $sTargetType
     * @param int    $iTargetId
     * @param string $sAction
     *
     * @return int|bool
     */
    public function DecCounter($sTargetType, $iTargetId, $sAction = '') {

        return $this->oMapper->TicCounter($sTargetType, $iTargetId, $sAction, -1);
    }

    /**
     * @param string $sTargetType
     * @param int    $iTargetId
     * @param string $sAction
     *
     * @return int
     */
    public function GetCounter($sTargetType, $iTargetId, $sAction = '') {

        return $this->oMapper->GetCounter($sTargetType, $iTargetId, $sAction);
    }

    public function GetCountersByTargetsId($sTargetType, $aTargetsId, $sAction = '') {

        if (!$aTargetsId) {
            return array();
        }
        return $this->oMapper->GetCountersByTargetsId($sTargetType, $aTargetsId, $sAction);
    }

}

// EOF