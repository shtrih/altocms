<?php

class PluginCheckbox_ModuleTopic extends PluginCheckbox_Inherits_ModuleTopic {

    /**
     * Добавляет поле
     *
     * @param ModuleTopic_EntityField $oFieldEntity    Объект поля
     *
     * @return ModuleTopic_EntityField|bool
     */
    public function AddContentField($oFieldEntity) {
        if ('checkbox' == $oFieldEntity->getFieldType()) {
            $oFieldEntity->setOptionValue('select', F::GetRequest('field_values'));
        }

        return parent::AddContentField($oFieldEntity);
    }

    /**
     * Обновляет топик
     *
     * @param ModuleTopic_EntityField $oFieldEntity    Объект поля
     *
     * @return bool
     */
    public function UpdateContentField($oFieldEntity) {
        if ('checkbox' == $oFieldEntity->getFieldType()) {
            $oFieldEntity->setOptionValue('select', F::GetRequest('field_values'));
        }

        return parent::UpdateContentField($oFieldEntity);
    }
}
