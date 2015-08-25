<?php
/*
 * Этот файл является частью плагина Multiple File Upload
 * Copyright © 2015 https://github.com/shtrih
 * Распространение, продажа, обмен и передача плагина третьим лицам запрещено, за исключением когда третье лицо занимается разработкой вашего проекта.
 */

class PluginMultiplefileupload_HookMultiplefileupload extends Hook {

    /**
     * Регистрация событий на хуки
     *
     * * Хук в начало функции AddTopic() в модуле Topic (файл /classes/modules/topic/Topic.class.php , если этот модуль не переопределен в других плагинах):
     *
     * $this->AddHook('module_topic_addtopic_before','func_topic_addtopic_before');
     *
     * Будет вызвана функция func_topic_addtopic_before($aVars) , где $aVars - НЕассоциативный массив аргументов, переданных этой функции.
     * Передача результата в функцию AddTopic() делается путем изменения аргументов по ссылке - например, &$aVars[0]
     *
     *
     * * Хук в конец функции AddTopic() в модуле Topic (файл /classes/modules/topic/Topic.class.php , если этот модуль не переопределен в других плагинах):
     *
     * $this->AddHook('module_topic_addtopic_after','func_topic_addtopic_after');
     *
     * Будет вызвана функция func_topic_addtopic_after($Var) , где $Var - это то, что возвращает AddTopic() (т.е. или false или объект топика $oTopic)
     * Функция должна завершаться при помощи return $Var
     *
     *
     * * Хук в конкреное место движка
     *
     * $this->AddHook('init_action','func_init_action', __CLASS__, -5);
     *
     * Приоритет для вызова хука = -5. Этот приоритет так же можно указывать и в хуках на модели.
     * Будет вызвана функция func_init_action($Var) в том месте движка, где стоит данный хук
     */
    public function RegisterHook() {
        $this->AddHook('template_admin_content_add_field_list', 'RenderSelectOption');
        $this->AddHook('content_field_proccess', 'SaveFormValues');
        $this->AddHook('template_html_head_tags', 'htmlHeadTags');
    }

    public function RenderSelectOption(array $aVars) {
        return E::ModuleViewer()->Fetch(Plugin::GetTemplateFile(__CLASS__, 'tpls/content-field-select-option.tpl'), $aVars);
    }

    public function SaveFormValues(array &$aVars) {
        /**
         * @var ModuleTopic_EntityTopic $oTopic
         * @var ModuleTopic_EntityField $oField
         */
        $oField = $aVars['oField'];
        $oTopic = $aVars['oTopic'];
        $iTopicId = $oTopic->getId();
        if ('add' == $aVars['sType'] && 'multiple-file-upload' == $oField->getFieldType()) {
            $aValues = isset($_REQUEST['fields'][$oField->getFieldId()]) ? $_REQUEST['fields'][$oField->getFieldId()] : array();
            if (is_array($aValues) && count($aValues)) {
                foreach ($aValues as $iMresourceId) {
                    if (is_numeric($iMresourceId)) {
                        E::ModuleMresource()->updateMresourceRelTargetId($iMresourceId, $iTopicId);
                    }
                }
            }
        }
    }

    public function htmlHeadTags() {
        $aAcceptTypes = (array)Config::Get('plugin.multiplefileupload.accept-file-types');
        array_walk($aAcceptTypes, function (&$v) {
            $v = preg_quote($v, '/');
        });
        $aVars = [
            'aConfig' => [
                'auto-upload'   => Config::Get('plugin.multiplefileupload.auto-upload'),
                'max-file-size' => F::MemSize2Int(Config::Get('module.uploader.files.multiple-file-upload.file_maxsize')),
                'accept-file-types' => $aAcceptTypes ? '(\.|\/)(' . join('|', $aAcceptTypes) . ')$' : '',
            ]
        ];

        return E::ModuleViewer()->Fetch(Plugin::GetTemplateFile(__CLASS__, 'tpls/hooks/hook.template_html_head_tags.tpl'), $aVars);
    }
}

// EOF
