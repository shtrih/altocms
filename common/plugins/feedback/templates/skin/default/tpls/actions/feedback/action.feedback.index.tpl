{extends file="_index.tpl"}

{block name="layout_vars"}
    {$menu="topics"}
{/block}

{block name="layout_content"}
    <div class="panel panel-default">
        <div class="panel-body">
            <h1 class="panel-header">{$oFeedback->getFeedbackTitle()|escape}</h1>

            <p>{$oFeedback->getFeedbackText()}</p>

            <div class="clearfix"></div>
            <hr />
            <form action="" method="POST" enctype="multipart/form-data" id="form-feedback">
                <input type="hidden" name="security_key" value="{$ALTO_SECURITY_KEY}"/>
                <input type="hidden" id="topic_id"  name="topic_id" value="{$_aRequest.topic_id}"/>

                {if $aFields}
                    {foreach $aFields as $oField}
                        {include file="fields/customs/field.custom.`$oField->getFieldType()`-edit.tpl" oField=$oField}
                    {/foreach}
                {/if}

                <script>
                    $(function () {
                        $('.captcha-image').prop('src', ls.routerUrl('captcha') + '?n=' + Math.random());
                    });
                </script>
                {hook run="registration_captcha"}

                <button type="submit" name="submit" class="btn btn-light btn-normal corner-no">
                    {$aLang.plugin.feedback.feedback_submit}
                </button>
            </form>
        </div>
    </div>
{/block}
