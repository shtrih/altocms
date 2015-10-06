<?php

class PluginFeedback_ModuleFeedback_MapperFeedback extends Mapper
{
    public function addFeedback($sWebPath, $sActive, $sTitle, $sContent, $sContentSource) {
        $this->oDb->query(
            'INSERT INTO `'.Config::Get('db.table.prefix').'feedback` ' .
            '(feedback_id, feedback_webpath, feedback_active, feedback_title, feedback_text, feedback_text_source) ' .
            'VALUES (NULL, ?, ?d, ?, ?, ?) ' .
            $sWebPath, (int)$sActive, $sTitle, $sContent, $sContentSource
        );
    }

    public function updateFeedback($iFeedbackId, $sWebPath, $sActive, $sTitle, $sContent, $sContentSource) {
        $this->oDb->query(
            'UPDATE `'.Config::Get('db.table.prefix').'feedback` ' .
            'SET feedback_webpath = ?, feedback_active = ?, feedback_title = ?, feedback_text = ?, feedback_text_source = ? ' .
            'WHERE feedback_id = ?',
            $sWebPath, (int)$sActive, $sTitle, $sContent, $sContentSource, $iFeedbackId
        );
    }

    /**
     * @param $iItemId
     * @return PluginFeedback_ModuleFeedback_EntityFeedback|null
     */
    public function getFeedbackById($iItemId) {
        $oResult = null;

        $aRow = $this->oDb->selectRow(
            'SELECT * FROM `'.Config::Get('db.table.prefix').'feedback` WHERE `feedback_id` = ?d', $iItemId
        );

        if ($aRow) {
            $oResult = E::GetEntity('PluginFeedback_Feedback', $aRow);
        }

        return $oResult;
    }

    /**
     * Добавляет поле
     *
     * @param ModuleTopic_EntityField $oField    Объект поля
     *
     * @return int|bool
     */
    public function addField(ModuleTopic_EntityField $oField) {
        $sql = 'INSERT INTO '.Config::Get('db.table.prefix').'feedback_fields
            (
            feedback_id,
            field_unique_name,
            field_name,
            field_type,
            field_description,
            field_options,
            field_required,
            field_postfix
            )
            VALUES(?d, ?, ?, ?, ?, ?, ?d, ?)
        ';

        $cOldErrorHandler = $this->oDb->setErrorHandler(array($this, 'SqlErrorHandler'));
        if ($iId = $this->oDb->query(
            $sql,
            $oField->getFeedbackId(),
            $oField->getFieldUniqueName() ?: null,
            $oField->getFieldName(),
            $oField->getFieldType(),
            $oField->getFieldDescription(),
            $oField->getFieldOptions(),
            $oField->getFieldRequired() ? 1 : 0,
            $oField->getFieldPostfix()
        )
        ) {
            $this->oDb->setErrorHandler($cOldErrorHandler);
            $oField->setFieldId($iId);

            return $iId;
        }

        return false;
    }

    /**
     * Список полей
     *
     * @param $iFeedbackId
     * @return ModuleTopic_EntityField[]
     */
    public function getFields($iFeedbackId) {
        $sql = 'SELECT
    *, field_id AS ARRAY_KEY
FROM '.Config::Get('db.table.prefix').'feedback_fields
WHERE feedback_id = ?d
ORDER BY field_sort DESC';

        $aResult = array();
        $aRows = $this->oDb->select($sql, $iFeedbackId);
        if ($aRows) {
            $aResult = E::GetEntityRows('Topic_Field', $aRows);
        }

        return $aResult;
    }

    public function getField($iFieldId) {
        $oResult = null;

        $aRow = $this->oDb->selectRow(
            'SELECT * FROM '.Config::Get('db.table.prefix').'feedback_fields WHERE field_id = ?d',
            $iFieldId
        );
        if ($aRow) {
            $oResult = E::GetEntity('Topic_Field', $aRow);
        }

        return $oResult;
    }

    /**
     * Обновляет поле
     *
     * @param ModuleTopic_EntityField $oField    Объект поля
     *
     * @return bool
     */
    public function updateField(ModuleTopic_EntityField $oField) {

        $sql = 'UPDATE '.Config::Get('db.table.prefix').'feedback_fields
            SET
                feedback_id=?d,
                field_unique_name=?,
                field_name=?,
                field_sort=?d,
                field_type=?,
                field_description=?,
                field_options=?,
                field_required=?d,
                field_postfix=?
            WHERE
                field_id = ?d
        ';

        $cOldErrorHandler = $this->oDb->setErrorHandler(array($this, 'SqlErrorHandler'));
        $bResult = $this->oDb->query(
            $sql,
            $oField->getFeedbackId(),
            $oField->getFieldUniqueName() ?: null,
            $oField->getFieldName(),
            $oField->getFieldSort(),
            $oField->getFieldType(),
            $oField->getFieldDescription(),
            $oField->getFieldOptions(),
            $oField->getFieldRequired() ? 1 : 0,
            $oField->getFieldPostfix(),
            $oField->getFieldId()
        );
        $this->oDb->setErrorHandler($cOldErrorHandler);

        return $bResult !== false;
    }

    public function removeField($iFieldId) {
        $sql = '
            DELETE FROM '.Config::Get('db.table.prefix').'feedback_fields
            WHERE
                field_id = ?d
        ';

        return $this->oDb->query($sql, $iFieldId) !== false;
    }

    /**
     * Функция для перехвата SQL ошибок
     *
     * @param   string  $sMessage Сообщение об ошибке
     * @param   array   $aInfo Информация об ошибке
     * @throws Exception
     */
    public function SqlErrorHandler($sMessage, $aInfo) {
        $this->oDb->rollback();

        throw new Exception($sMessage, $aInfo['code']);
    }
}
