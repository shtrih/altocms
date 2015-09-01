{extends file="_index.tpl"}

{block name="layout_vars"}
    {$sMainMenuItem='settings'}
{/block}

{block name="content-bar"}{/block}

{block name="content-body"}
<div class="b-wbox">
    <div class="b-wbox-content mhb form">
        <form action="" method="post" enctype="multipart/form-data">
            <input type="hidden" name="security_ls_key" value="{$ALTO_SECURITY_KEY}"/>

            <table class="table table-plugins">
                <thead>
                <tr>
                    <th>{$aLang.plugin.mhb.mhb_blog_title}</th>
                    <th>{$aLang.plugin.mhb.mhb_auto_join_title}</th>
                    <th>{$aLang.plugin.mhb.mhb_cant_leave_title}</th>
                </tr>
                </thead>

                <tbody>
                {foreach from=$aData item=data}
                    <tr>
                        <td>{if $data.closed}<span class="icon icon-lock" title="{$aLang.plugin.mhb.mhb_blog_closed}"></span> {/if}{$data.title|escape:html}</td>
                        <td class="mhb_checkbox cell-checkbox">
                            <input type="checkbox" name="mhb_auto_join_{$data.blog_id}" class="checkbox" {if $data.auto_join}checked{/if} />
                        </td>
                        <td class="mhb_checkbox cell-checkbox">
                            <input type="checkbox" name="mhb_cant_leave_{$data.blog_id}" class="checkbox" {if $data.cant_leave}checked{/if} />
                        </td>
                    </tr>
                {/foreach}
                </tbody>
            </table>
            <input type="submit" class="btn btn-default" name="submit_mhb" value="{$aLang.plugin.mhb.mhb_submit}"/>
        </form>
    </div>

    <div class="b-wbox-header">
        <h3 class="b-wbox-header-title">{$aLang.plugin.mhb.mhb_info_header}</h3>
    </div>
    <div class="b-wbox-content">
        Plugin: {$aLang.plugin.mhb.mhb_info_pluginname}<br/>
        Version: {$aLang.plugin.mhb.mhb_info_version}<br/>
        Author: {$aLang.plugin.mhb.mhb_info_author}<br/>
        Contact e-mail: {$aLang.plugin.mhb.mhb_info_email}<br/>
        Website: <a href="{$aLang.plugin.mhb.mhb_info_url}" title="Website">{$aLang.plugin.mhb.mhb_info_url}</a><br/>
        GitHub: <a href="https://github.com/kerbylav/mhb">mhb</a><br/><br/>
        {$aLang.plugin.mhb.mhb_donate}
    </div>
</div>
{/block}
