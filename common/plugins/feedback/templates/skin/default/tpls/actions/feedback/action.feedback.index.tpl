{extends file="_index.tpl"}

{block name="layout_vars"}
    {$menu="topics"}
{/block}

{block name="layout_content"}
    {*{include file='topics/topic.list.tpl'}*}
    <h1>Написать администрации</h1>



    <form action="" method="POST" enctype="multipart/form-data" id="form-feedback">
        {hook run='form_add_topic_begin'}

        <input type="hidden" name="security_key" value="{$ALTO_SECURITY_KEY}"/>
        <input type="hidden" id="topic_id"  name="topic_id" value="{$_aRequest.topic_id}"/>

        {if $oContentType}
            {foreach from=$oContentType->getFields() item=oField}
                {include file="fields/customs/field.custom.`$oField->getFieldType()`-edit.tpl" oField=$oField}
            {/foreach}
        {/if}

        <button type="submit" name="submit_topic_draft" class="btn btn-light btn-normal corner-no">
            {$aLang.topic_create_submit_publish}
        </button>
    </form>
{/block}
