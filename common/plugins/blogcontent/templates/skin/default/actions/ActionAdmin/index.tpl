{assign var="bNoSidebar" value=true}
{include file='header.tpl'}
<h1>{$aLang.plugin.blogcontent.types_title}</h1>
<form action="" method="post">


<table class="table">
    <thead>
    <td>{$aLang.plugin.blogcontent.blogs}</td>
{foreach from=$aTypes item=sType}
    <td>
        {$sType}
    </td>
{/foreach}
    </thead>
    <tr>
        <td>{$aLang.plugin.blogcontent.defaults} <sub>{$aLang.plugin.blogcontent.defaults_notice}</sub></td>
    {foreach from=$aTypes item=sType}
        <td>
            <input type="checkbox" name="config[default][]" value="{$sType}" {if in_array($sType,$aConfig['default'])} checked {/if}>
        </td>
    {/foreach}
    </tr>
    <tr>
        <td>{$aLang.plugin.blogcontent.personal}</td>
    {foreach from=$aTypes item=sType}
        <td>
            <input type="checkbox" name="config[personal][]" value="{$sType}" {if in_array($sType,$aConfig['personal'])} checked {/if}>
        </td>
    {/foreach}
    </tr>
{foreach from=$aBlogs item=oBlog}
        <tr>
             <td>{$oBlog->getTitle()}</td>
            {foreach from=$aTypes item=sType}
            <td>
                <input type="checkbox" name="config[{$oBlog->getId()}][]" value="{$sType}" {if in_array($sType,$aConfig[$oBlog->getId()])} checked {/if}>
            </td>
            {/foreach}
        </tr>
    {/foreach}
</table>
  <input type="submit" value="{$aLang.plugin.blogcontent.save}" name="save">
  <input type="hidden" name="security_ls_key" value="{$LIVESTREET_SECURITY_KEY}" />
</form>

{include file='footer.tpl'}

