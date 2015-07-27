{if $oField}
    {$sVal = $oTopic->getSingleImage($oField->getFieldId(), '250fit')}
    {if $sVal}
        <img src="{$sVal}" alt="image" align="left" />
    {/if}
{/if}