{if $oField}
    {$oTopicField = $oTopic->getField($oField->getFieldId())}
    {if $oTopicField}
        <p>
            <strong>{$oField->getFieldName()}</strong>:
            {if $oTopicField->getValue() == 'checked'}{$aLang.text_yes}{else}{$aLang.text_no}{/if}
        </p>
    {/if}
{/if}