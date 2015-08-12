{if $oField}
    {$iMaxShow = C::Get('plugin.multiplefileupload.files-show-count')}

    <strong>{$oField->getFieldName()}</strong>:
    {$aFiles = E::Module('PluginMultiplefileupload_ModuleMultiplefileupload')->getAttachedFiles($oTopic->getId())}
    <ul class="list-unstyled multiple-file-upload">
    {foreach $aFiles as $oFile}
        <li{if $oFile@index gte $iMaxShow} class="hide"{/if}>
            <a href="{$oFile->url}" title="{$oFile->name|escape:'htmlall'}">{$oFile->name|escape}</a>
            <span class="pull-right">{PluginMultiplefileupload_ModuleMultiplefileupload::sizeFormat($oFile->size)}</span>
        </li>
        {if $oFile@index eq $iMaxShow}
            <li><span class="fa fa-chevron-right"></span>&nbsp;<a class="toggle-others" href="#">Показать остальные ({sizeof($aFiles) - $iMaxShow})</a></li>
        {/if}
    {/foreach}
    </ul>
{/if}