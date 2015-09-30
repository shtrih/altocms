{extends file="_index.tpl"}

{block name="layout_vars"}
    {$sMainMenuItem='settings'}
{/block}

{block name="content-bar"}Назначение странице с формой адреса.

{/block}

{block name="content-body"}

<div class="span12">
    <div class="b-wbox">
        <div class="b-wbox-header">
            <div class="b-wbox-header-title">
                Редактирование формы обратной связи
            </div>
        </div>

        {include_once file='modals/modal.upload_img.tpl' sToLoad=$sImgToLoad}
        {include_once file='modals/modal.insert_img.tpl' sTargetType=$sTargetType bTmp=$bTmp}
        <form action="" method="post" class="form-horizontal uniform">
            <input type="hidden" name="security_key" value="{$ALTO_SECURITY_KEY}" />

            <div class="b-wbox-content nopadding">
                <div class="control-group">
                    <label for="feedback_webpath" class="control-label">
                        Адрес формы обратной связи:
                    </label>

                    <div class="controls">
                        <input type="text" name="feedback_webpath" value="{$_aRequest.feedback_webpath}" class="input-text">
                        <span class="help-block">Адрес страницы с обратной связью, от корня сайта. Например, «/mypage/feedback»</span>
                    </div>
                </div>

                <div class="control-group">
                    <label for="feedback_title" class="control-label">
                        Заголовок:
                    </label>

                    <div class="controls">
                        <input type="text" name="feedback_title" value="{$_aRequest.feedback_title}" class="input-text">
                        <span class="help-block">Будет отображён на странице обратной связи.</span>
                    </div>
                </div>

                <div class="control-group">
                    <label class="control-label">
                        Активна:
                    </label>

                    <div class="controls">
                        <label><input type="radio" name="feedback_active" value="0"{if !$_aRequest.feedback_active} checked="checked"{/if} class="input-radio">Нет</label>
                        <label><input type="radio" name="feedback_active" value="1"{if $_aRequest.feedback_active} checked="checked"{/if} class="input-radio">Да</label>
                        <span class="help-block"></span>
                    </div>
                </div>

                <div class="control-group">
                    <label for="feedback_description" class="control-label">
                        {$aLang.action.admin.contenttypes_description}:
                    </label>

                    <div class="controls">
                        <textarea name="feedback_description"  class="input-text js-editor-wysiwyg js-editor-markitup" rows="10">{$_aRequest.feedback_description}</textarea>
                        {if Config::Get('view.wysiwyg')}
                            {include_once file="editors/editor.tinymce.tpl"}
                        {else}
                            {include_once file="editors/editor.markitup.tpl"}
                        {/if}
                        <span class="help-block">Будет отображено под заголовком на странице обратной связи.</span>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">
                        {$aLang.action.admin.contenttypes_submit}
                    </button>
                </div>
            </div>
        </form>

        <div class="b-wbox-header">
            <div class="b-wbox-header-title">{$aLang.action.admin.contenttypes_fields_added}</div>
        </div>
        <div class="b-wbox-content form-horizontal">
            <table class="table table-bordered">
                <thead class="topiccck_thead">
                <tr>
                    <th>ID</th>
                    <th>{$aLang.action.admin.contenttypes_type}</th>
                    <th>{$aLang.action.admin.contenttypes_name}</th>
                    <th>{$aLang.action.admin.contenttypes_description}</th>
                    <th>{$aLang.action.admin.contenttypes_actions}</th>
                </tr>
                </thead>

                <tbody class="content js-sortable">
                {foreach $aFields as $oField}
                    <tr id="{$oField->getFieldId()}" class="cursor-x">
                        <td align="center">
                            {$oField->getFieldId()}
                        </td>
                        <td align="center">
                            {$oField->getFieldType()}
                        </td>
                        <td align="center">
                            {$oField->getFieldName()}
                        </td>
                        <td align="center">
                            {$oField->getFieldDescription()}
                        </td>
                        <td align="center">
                            <a href="{router page='admin'}settings-feedback/field-edit/{$oField->getFieldId()}/">{$aLang.action.admin.contenttypes_edit}</a>
                            |
                            <a href="{router page='admin'}settings-feedback/field-remove/{$oField->getFieldId()}/?security_key={$ALTO_SECURITY_KEY}"
                               onclick="return confirm('{$aLang.action.admin.contenttypes_field_detele_confirm}');">{$aLang.action.admin.contenttypes_delete}</a>
                        </td>
                    </tr>
                {/foreach}
                {if !$aFields}<tr><td colspan="5">Нет ни одного поля.</td></tr>{/if}
                </tbody>
            </table>
            <div class="control-group">
                <a class="btn btn-default fl-r" href="{router page="admin"}settings-feedback/field-add">
                    <i class="icon icon-plus"></i> {$aLang.action.admin.contenttypes_add_field}
                </a>
            </div>
        </div>
    </div>

</div>
{/block}
