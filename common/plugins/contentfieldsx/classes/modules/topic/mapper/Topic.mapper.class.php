<?php

class PluginContentfieldsx_ModuleTopic_MapperTopic extends PluginContentfieldsx_Inherits_ModuleTopic_MapperTopic {

    /**
     * Обновляет поле
     *
     * @param ModuleTopic_EntityField $oField    Объект поля
     *
     * @return bool
     * @throws Exception
     */
    public function UpdateContentField(ModuleTopic_EntityField $oField) {

        $sql = "UPDATE ?_content_field
			SET
				content_id=?d,
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
		";

        set_error_handler(array($this, 'SqlErrorHandler'));
        $bResult = $this->oDb->query(
            $sql,
            $oField->getContentId(),
            $oField->getFieldUniqueName(),
            $oField->getFieldName(),
            $oField->getFieldSort(),
            $oField->getFieldType(),
            $oField->getFieldDescription(),
            $oField->getFieldOptions(),
            $oField->getFieldRequired() ? 1 : 0,
            $oField->getFieldPostfix(),
            $oField->getFieldId()
        );
        restore_error_handler();

        return $bResult !== false;
    }

    /**
     * Список полей типа контента
     *
     * @param  array $aFilter    Фильтр
     *
     * @return ModuleTopic_EntityField[]
     */
    public function getContentFields($aFilter) {

        $sql = "SELECT
            cf.field_id AS ARRAY_KEY, cf.*
        FROM
            ?_content_field AS cf
        WHERE
            1=1
            { AND cf.content_id = ?d }
            { AND cf.field_unique_name = ? }
        ORDER BY cf.field_sort DESC
        ";
        $aResult = array();
        $aRows = $this->oDb->select($sql,
            (isset($aFilter['content_id']) ? $aFilter['content_id'] : DBSIMPLE_SKIP),
            (empty($aFilter['field_unique_name']) ? DBSIMPLE_SKIP : $aFilter['field_unique_name'])
        );
        if ($aRows) {
            $aResult = E::GetEntityRows('Topic_Field', $aRows);
        }
        return $aResult;
    }

    /**
     * Функция для перехвата SQL ошибок
     *
     * @param   string  $sMessage Сообщение об ошибке
     * @param   array   $aInfo Информация об ошибке
     * @throws Exception
     */
    function SqlErrorHandler($sMessage, $aInfo) {
        throw new Exception($sMessage, $aInfo['code']);
    }
}

