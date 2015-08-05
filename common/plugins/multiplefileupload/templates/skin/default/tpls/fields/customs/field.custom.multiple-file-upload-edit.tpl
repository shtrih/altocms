{if $oField}
    {$iFieldId=$oField->getFieldId()}

    <label for="">Файлы:</label>
    <div class="fileupload-wrapper">
        <div id="multiple-file-upload" rel="{$_aRequest.topic_id}" class="uneditable-input">
            <div class="fileupload-buttonbar">
                <span class="span5">
                    <span class="btn btn-success fileinput-button">
                        <span>Добавить</span>
                        <input type="file" name="fields[{$iFieldId}]" multiple />
                    </span>
                    <button type="button" class="btn" id="uploadFromUrl">
                        <span>Указать url</span>
                    </button>
                    <button type="submit" class="btn btn-primary start hide">
                        <span>Загрузить всё</span>
                    </button>
                    <button type="reset" class="btn btn-warning cancel hide">
                        <span>Отменить</span>
                    </button>
                    <span class="fileupload-process"></span>
                </span>
                <div class="fileupload-progress fade pull-right">
                    <div class="progress progress-striped active progress-animated" role="progressbar" aria-valuemin="0" aria-valuemax="100">
                        <div class="progress-bar progress-bar-success" style="width:0%;"></div>
                    </div>
                    <div class="progress-extended">&nbsp;</div>
                </div>
            </div>
            <table class="table table-striped table-uploaded"><tbody></tbody></table>
            <table role="presentation" class="table table-striped table-files"><tbody class="files"></tbody></table>
        </div>
    </div>
    <span class="help-block"><small>Максимальный размер файла: <strong>{min((int)ini_get("post_max_size"), (int)ini_get("upload_max_filesize"))} Мб</strong>.
            Если прикрепить mp3-файл, то его можно будет прослушать в плеере на странице топика.</small></span>

{literal}
    <script>
        var fileList = [
            {
                name: 'Filename.jpg',
                size: '10kb',
                url: '/file/down',
                id: 1
            }
        ];
    </script>
{/literal}
{literal}
<script id="template-upload" type="text/x-tmpl">
{% for (var i=0, file; file=o.files[i]; i++) { %}
	<tr class="template-upload fade">
        <td>
            <span class="preview"></span>
        </td>
		<td>
			<p class="name">{%=file.name%}</p>
			<strong class="error text-danger"></strong>
		</td>
		<td>
			<div class="progress progress-animated progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0">
				<div class="progress-bar progress-bar-success" style="width:0%;"><p class="size">Загружается...</p></div>
			</div>
		</td>
		<td>
			{% if (!i && !o.options.autoUpload) { %}
				<button class="btn btn-primary start" disabled>
					<i class="glyphicon glyphicon-upload"></i>
					<span>Загрузить</span>
				</button>
			{% } %}
			{% if (!i) { %}
				<button class="btn btn-warning cancel">
					<i class="glyphicon glyphicon-ban-circle"></i>
					<span>Отменить</span>
				</button>
			{% } %}
		</td>
	</tr>
{% } %}
</script>

<script id="template-download" type="text/x-tmpl">
{% for (var i=0, file; file=o.files[i]; i++) { %}
	<tr class="template-download fade" data-id="{%=file.id%}">
		<td>
			<p class="name">
				{% if (file.url) { %}
					<a href="{%=file.url%}" title="{%=file.name%}" download="{%=file.name%}" {%=file.thumbnailUrl?'data-gallery':''%}>{%=file.name%}</a>
				{% } else { %}
					<span>{%=file.name%}</span>
				{% } %}
			</p>
			{% if (file.error) { %}
				<div><span class="label label-warning">Error</span> {%=file.error%}</div>
			{% } %}
		</td>
		<td>
			<span class="size">{%=o.formatFileSize(file.size)%}</span>
		</td>
		<td>
			<span class="sort"><img src="{/literal}{cfg name='path.root.web'}{literal}/templates/skin/norma/images/arrows.gif"></span>
		</td>
		<td>
			<button type="button" class="btn btn-danger" onclick="if (deletefile({%=file.id%}, '{%=file.name.replace(/\'/g, "\\'")%}')) $(this).parents('.template-download').remove(); return false;">
				<i class="glyphicon glyphicon-ban-circle"></i>
				<span>Удалить</span>
			</button>
		</td>
	</tr>
{% } %}
</script>
{/literal}
    {*<div class="form-group checkbox">
        <div class="input-group">
            <label>
            <input class="form-control" name="fields[{$iFieldId}]" id="fields-{$iFieldId}"
               value="{$iFieldId}"
               {if $_aRequest.fields.$iFieldId}checked="checked"{/if}
               type="checkbox" />{$oField->getFieldName()}</label>
        </div>
        <small class="control-notice">{$oField->getFieldDescription()}</small>
    </div>*}
{/if}


