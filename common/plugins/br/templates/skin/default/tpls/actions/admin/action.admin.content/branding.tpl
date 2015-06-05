{extends file="_index.tpl"}

{block name="layout_vars"}
    {$sMainMenuItem='content'}
{/block}

{block name="content-bar"}

{/block}

{block name="content-body"}

    <div class="span12">
    <div class="b-wbox">
    <div class="b-wbox-header">
        <h3 class="b-wbox-header-title">
            {$aLang.plugin.br.admin_title}
        </h3>
    </div>
    <div class="b-wbox-content">
    <div class="b-wbox-content">

    <form method="post" action="" enctype="multipart/form-data" id="branding-setting" class="form-horizontal uniform">
    <input type="hidden" name="security_ls_key" value="{$LIVESTREET_SECURITY_KEY}"/>


        {******************************************************************************************************}

        <div class="control-group">
            <label class="control-label">
                {$aLang.plugin.br.admin_use_list}
            </label>

            <div class="controls">
                <label>
                        <input type="checkbox"
                               id="branding-allow-background-checkbox"
                               name="allow_background"
                               value="1"
                               {if $_aRequest.blog.allow_background}checked="checked"{/if}>
                    {$aLang.plugin.br.admin_allow_background}
                </label>
            </div>

            <div class="controls">
                <label>
                        <input type="checkbox"
                               id="branding-allow-font-checkbox"
                               name="allow_font"
                               value="1"
                               {if $_aRequest.blog.allow_font}checked="checked"{/if}>
                    {$aLang.plugin.br.admin_allow_font}
                </label>
            </div>

            <div class="controls">
                <label>
                        <input type="checkbox"
                               id="branding-allow-header-checkbox"
                               name="allow_header"
                               value="1"
                               {if $_aRequest.blog.allow_header}checked="checked"{/if}>
                    {$aLang.plugin.br.admin_allow_header}
                </label>
            </div>

            <div class="controls">
                <label>
                        <input type="checkbox"
                               id="branding-allow-step-checkbox"
                               name="allow_step"
                               value="1"
                               {if $_aRequest.blog.allow_step}checked="checked"{/if}>
                    {$aLang.plugin.br.admin_allow_step}
                </label>
            </div>



        </div>


        {******************************************************************************************************}


    <br/><br/>

    <input type="submit" name="submit_branding" value="{$aLang.plugin.br.save}"/>
    <input type="submit" name="cancel" value="{$aLang.plugin.br.cancel}"/>

    </form>
    </div>
    </div>
    </div>
    </div>
{/block}