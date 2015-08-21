{if $oField}
    {$iFieldId=$oField->getFieldId()}
    {if $oTopic}
        {$iTopicId = $oTopic->getId()}
    {else}
        {$iTopicId = 0}
    {/if}
    {$aLangPlugin = $aLang.plugin.multiplefileupload}

    <div class="form-group">
        <label>{$oField->getFieldName()}</label><br />

        <div class="fileupload-wrapper">
        <div id="multiple-file-upload" data-topic-id="{$iTopicId}" data-field-id="{$iFieldId}">
            <div class="fileupload-buttonbar">
                <div>
                    <span class="btn btn-success fileinput-button">
                        <span class="fa fa-plus-circle"></span>
                        {$aLangPlugin.btn_add}
                        <input type="file" name="multiple-file-upload[]" multiple />
                    </span>
                    <button type="button" class="btn btn-default url-upload" title="Загрузить по ссылке">
                        <span class="fa fa-link"></span>
                        {$aLangPlugin.btn_add_url}
                    </button>
                    <button type="submit" class="btn btn-primary start hide">
                        <span class="fa fa-upload"></span>
                        {$aLangPlugin.btn_upload_all}
                    </button>
                    <button type="reset" class="btn btn-warning cancel hide">
                        <span class="fa fa-ban"></span>
                        {$aLangPlugin.btn_cancel}
                    </button>
                </div>

                <div class="control-notice mab12">
                    {$iPhpMin = min(F::MemSize2Int(ini_get("post_max_size")), F::MemSize2Int(ini_get("upload_max_filesize")))}
                    {$sUploadMaxSize = PluginMultiplefileupload_ModuleMultiplefileupload::sizeFormat(min($iPhpMin, F::MemSize2Int(Config::Get('module.uploader.files.multiple-file-upload.file_maxsize'))))}
                    {$sRemoteMaxSize = PluginMultiplefileupload_ModuleMultiplefileupload::sizeFormat(min($iPhpMin, F::MemSize2Int(Config::Get('module.uploader.files.multiple-file-upload.url_maxsize'))))}
                    {$aLangPlugin.file_maxsize}: <strong>{$sUploadMaxSize}</strong>.
                    <br />{$aLangPlugin.url_maxsize}: <strong>{$sRemoteMaxSize}</strong>.
                    <br />{$aLangPlugin.file_extensions}: <strong>{join(', ', array_unique(Config::Get('module.uploader.files.multiple-file-upload.file_extensions')))|escape}</strong>
                    <br />{$oField->getFieldDescription()}
                </div>

                <div class="fileupload-progress fade in mab6">
                    <div class="progress progress-striped active progress-animated" role="progressbar" aria-valuemin="0" aria-valuemax="100">
                        <div class="progress-bar progress-bar-success" style="width:0%;"></div>
                    </div>
                    <div class="progress-extended">&nbsp;</div>
                </div>
            </div>
            <table class="table table-striped table-uploaded"><tbody></tbody></table>
            <table role="presentation" class="table table-striped table-files"><tbody class="files"></tbody></table>

            {wgroup group="mfu-after-file-list"}
        </div>
        </div>
    </div>

{literal}
<script>
$(document).ready(function () {
    ls.multiplefileupload.addFiles(
    {/literal}{strip}
        {if $iTopicId}
            {json_encode(E::Module('PluginMultiplefileupload_ModuleMultiplefileupload')->getAttachedFiles({$iTopicId}))}
        {else}
            []
        {/if}
    {/strip}{literal}
    );
});
</script>
<script id="template-upload" type="text/x-tmpl">
{% for (var i=0, file; file=o.files[i]; i++) { %}
	<tr class="template-upload fade">
		<td>
			<span class="preview"></span>
		</td>
		<td>
			<div class="name">{%=file.name%}</div>
			<strong class="error text-danger"></strong>
		</td>
		<td class="col-md-4">
			<div class="progress progress-animated progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0">
				<div class="progress-bar progress-bar-success" style="width:0%;"><span class="size">{/literal}{$aLangPlugin.progress_loading}{literal}</span></div>
			</div>
		</td>
		<td class="col-md-8">
			{% if (!i && !o.options.autoUpload) { %}
				<button class="btn btn-primary start" disabled>
					<span class="fa fa-upload"></span>
					{/literal}{$aLangPlugin.btn_upload}{literal}
				</button>
			{% } %}
			{% if (!i) { %}
				<button class="btn btn-warning cancel">
					<span class="fa fa-ban"></span>
					{/literal}{$aLangPlugin.btn_cancel}{literal}
				</button>
			{% } %}
		</td>
	</tr>
{% } %}
</script>
<script id="template-download" type="text/x-tmpl">
{% for (var i=0, file; file=o.files[i]; i++) { %}
	<tr class="template-download fade" data-file-id="{%=file.id%}">
		<td>
			<div class="name">
				{% if (file.url) { %}
					<a href="{%=file.url%}" title="{%=file.name%}" download="{%=file.name%}" {%=file.thumbnailUrl?'data-gallery':''%}>{%=file.name%}</a>
				{% } else { %}
					<span>{%=file.name%}</span>
				{% } %}
			</div>
			{% if (file.error) { %}
				<div><span class="label label-warning">Error</span> {%=file.error%}</div>
			{% } %}
		</td>
		<td class="col-md-4">
			<span class="size">{%=o.formatFileSize(file.size)%}</span>
		</td>
		<td class="col-md-2">
			{% if (!file.error) { %}
				<a href="#" title="{/literal}{$aLangPlugin.title_sort|escape:'htmlall'}{literal}" class="btn btn-default btn-sm sort">
					<span class="fa fa-arrows-v"></span>
				</a>
			{% } %}
		</td>
		<td class="col-md-4">
			<button type="button" class="btn btn-danger mfu-remove-file"{% if (file.error) { %} disabled{% } %}>
				<span class="fa fa-trash-o"></span>
				{/literal}{$aLangPlugin.btn_remove}{literal}
			</button>
		</td>
	</tr>
{% } %}
</script>
{/literal}
{/if}


