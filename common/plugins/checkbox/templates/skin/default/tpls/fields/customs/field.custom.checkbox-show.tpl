{strip}
{if $oField}
    {$oTopicField = $oTopic->getField($oField->getFieldId())}
    {$aPresetValues = array_map('trim', $oField->getSelectVal())}
    {if $oTopicField}
        <p>
        {if count($aPresetValues) <= 1}
            <strong>{$oField->getFieldName()}</strong>:&nbsp;
            {if $oTopicField->getValue()}
                {$aLang.plugin.checkbox.text_checked}
            {else}
                {$aLang.plugin.checkbox.text_unchecked}
            {/if}
        {else}
            {$aValues = $oTopicField->getValueExploded()}
            {if $aValues}
                <strong>{$oField->getFieldName()}</strong>:&nbsp;
                {foreach $aValues as $sValue}
                    {if in_array($sValue, $aPresetValues)}
                        {$sValue|escape}
                        {if !$sValue@last}, {/if}
                    {/if}
                {/foreach}
            {/if}
        {/if}
        </p>
    {/if}
{/if}
{/strip}
