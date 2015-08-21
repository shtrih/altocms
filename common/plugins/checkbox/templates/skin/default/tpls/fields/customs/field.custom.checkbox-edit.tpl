{if $oField}
    {$iFieldId=$oField->getFieldId()}
    <div class="form-group checkbox">
        <div class="input-group">
            <label>
            <input class="" name="fields[{$iFieldId}]" id="fields-{$iFieldId}"
               value="{$iFieldId}"
               {if $_aRequest.fields.$iFieldId}checked="checked"{/if}
               type="checkbox" />{$oField->getFieldName()}</label>
        </div>
        <small class="control-notice">{$oField->getFieldDescription()}</small>
    </div>
{/if}


