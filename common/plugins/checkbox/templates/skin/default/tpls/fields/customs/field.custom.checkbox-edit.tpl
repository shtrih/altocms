{if $oField}
    {$iFieldId=$oField->getFieldId()}
    {$aPresetValues = $oField->getSelectVal()}
    {if is_array($_aRequest.fields.$iFieldId)}
        {$aFieldValues = array_filter(array_map('trim', $_aRequest.fields.$iFieldId))}
    {else}
        {$aFieldValues = array_filter(array_map('trim', explode(',', $_aRequest.fields.$iFieldId)))}
    {/if}
    <div class="form-group checkbox">
        <div class="input-group">
            {if count($aPresetValues) <= 1}
                <label>
                    <input name="fields[{$iFieldId}][]" id="fields-{$iFieldId}"
                       value="{$iFieldId}"
                       {if $_aRequest.fields.$iFieldId}checked="checked"{/if}
                       type="checkbox" />{$oField->getFieldName()}
                </label>
            {else}
                <fieldset>
                    <legend>{$oField->getFieldName()}</legend>
                    {foreach $aPresetValues as $sValue}
                        <label class="checkbox-inline">
                            <input name="fields[{$iFieldId}][]"
                                   value="{trim($sValue)|escape:'htmlall'}"
                                   {if in_array(trim($sValue), $aFieldValues)}checked="checked"{/if}
                                   type="checkbox" />{$sValue}
                        </label>
                    {/foreach}
                </fieldset>
            {/if}
        </div>
        <small class="control-notice">{$oField->getFieldDescription()}</small>
    </div>
{/if}


