<?php

class PluginFeedback_ModuleFeedback_MapperFeedback extends Mapper
{
    public function add($sWebPath, $sActive, $sTitle, $sContent, $sContentSource) {
        $this->oDb->query(
            'INSERT INTO `'.Config::Get('db.table.prefix').'feedback` ' .
            '(feedback_id, feedback_webpath, feedback_active, feedback_title, feedback_text, feedback_text_source) ' .
            'VALUES (NULL, ?, ?d, ?, ?, ?) ' .
            $sWebPath, (int)$sActive, $sTitle, $sContent, $sContentSource
        );
    }

    public function update($iFeedbackId, $sWebPath, $sActive, $sTitle, $sContent, $sContentSource) {
        $this->oDb->query(
            'UPDATE `'.Config::Get('db.table.prefix').'feedback` ' .
            'SET feedback_webpath = ?, feedback_active = ?, feedback_title = ?, feedback_text = ?, feedback_text_source = ? ' .
            'WHERE feedback_id = ?',
            $sWebPath, (int)$sActive, $sTitle, $sContent, $sContentSource, $iFeedbackId
        );
    }
}
