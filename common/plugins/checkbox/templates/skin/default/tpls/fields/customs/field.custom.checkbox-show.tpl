{strip}
{if $oField}
    {$oTopicField = $oTopic->getField($oField->getFieldId())}
    {$aPresetValues = $oField->getSelectVal()}
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
                {foreach $oTopicField->getValueExploded() as $sValue}
                    {if in_array($sValue, $aPresetValues)}
                        {$sValue|escape}
                    {/if}
                    {if !$sValue@last}, {/if}
                {/foreach}
            {/if}
        {/if}
        </p>
    {/if}
{/if}
{/strip}
