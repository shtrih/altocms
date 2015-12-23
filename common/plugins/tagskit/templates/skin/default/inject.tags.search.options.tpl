<div class="js-tagskit-search">
	{if $oBlog}
		<div style="margin-bottom: 10px;">
			<label><input type="checkbox" name="blog_id" value="{$oBlog->getId()}" checked> &mdash; {$aLang.plugin.tagskit.tags_search_category.in_blog} &laquo;{$oBlog->getTitle()|escape}&raquo;</label>
		</div>
	{/if}
	<fieldset>
        <legend>{$aLang.plugin.tagskit.tags_search.how.title}</legend>
        <label><input type="radio" name="tk_how" value="or" {if $_aRequest.tk_how=='or' or !$_aRequest.tk_how}checked="checked" {/if}> &mdash; {$aLang.plugin.tagskit.tags_search.how.or}</label>
        <label><input type="radio" name="tk_how" value="and" {if $_aRequest.tk_how=='and'}checked="checked" {/if}> &mdash; {$aLang.plugin.tagskit.tags_search.how.and}</label>
    </fieldset>

    <fieldset>
        <legend>{$aLang.plugin.tagskit.tags_search.where.title}</legend>
        <label><input type="radio" name="tk_where" value="all" {if $_aRequest.tk_where=='all' or !$_aRequest.tk_where}checked="checked" {/if}> &mdash; {$aLang.plugin.tagskit.tags_search.where.all}</label>
        {if $aPluginActive.company}
			<label><input type="radio" name="tk_where" value="corp" {if $_aRequest.tk_where=='corp'}checked="checked" {/if}> &mdash; {$aLang.plugin.tagskit.tags_search.where.corp}</label>
		{/if}
		{if !$aPluginActive.dpb}
        	<label><input type="radio" name="tk_where" value="pers" {if $_aRequest.tk_where=='pers'}checked="checked" {/if}> &mdash; {$aLang.plugin.tagskit.tags_search.where.pers}</label>
		{/if}
        <label><input type="radio" name="tk_where" value="other" {if $_aRequest.tk_where=='other'}checked="checked" {/if}> &mdash; {$aLang.plugin.tagskit.tags_search.where.other}</label>
		<button type="button" class="button button-primary" onclick="ls.plugin.tagskit.searchButton();">{$aLang.plugin.tagskit.tags_search.submit}</button>
	</fieldset>

	<hr/>
</div>

<script type="text/javascript">
	jQuery(function($){
        ls.plugin.tagskit.init();
	});
</script>
