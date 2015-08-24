{$aFiles = E::Module('PluginMultiplefileupload_ModuleMultiplefileupload')->getAttachedFiles(0, E::UserId())}
{if $aFiles}
    {$aLangPlugin = $aLang.plugin.multiplefileupload}
    <div class="mfu-unattached">
        <h5 class="text-center">{$aLang.plugin.multiplefileupload.widget_header}</h5>

        <div class="table-responsive">
            <table class="table table-striped table-unattached">
                {foreach $aFiles as $oFile}
                    <tr data-file-id="{$oFile->id}" data-file-size="{$oFile->size}">
                        <td><a class="name" href="{$oFile->url}" title="{$oFile->name|escape:'htmlall'}">{$oFile->name|escape}</a></td>
                        <td class="col-md-4">{PluginMultiplefileupload_ModuleMultiplefileupload::sizeFormat($oFile->size)}</td>
                        <td class="col-md-8">
                            <a class="btn btn-small btn-default mfu-attach-file" href="#"
                               title="{$aLangPlugin.btn_attach_title|escape:'htmlall'}"><span class="fa fa-thumb-tack"></span>&nbsp;{$aLangPlugin.btn_attach}</a>
                            <a class="btn btn-small btn-danger mfu-remove-file" href="#"><span class="fa fa-trash-o"></span>&nbsp;{$aLangPlugin.btn_remove}</a>
                        </td>
                    </tr>
                {/foreach}
            </table>
        </div>
    </div>
{/if}
