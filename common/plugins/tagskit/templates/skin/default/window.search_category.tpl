<div class="modal modal-tk-white-list" id="modal_tk_search_category">
    <header class="modal-header">
        <h3>{$aLang.plugin.tagskit.tags_search_category.title}</h3>
        <a href="#" class="close jqmClose"></a>
    </header>

    <div class="modal-content">

        <div id="tk-white-tags-area" style="min-height: 30px;">
            {foreach $aTagsTkWhiteCategory as $aCategory}
                <div>
                    <h4>{$aCategory.title}</h4>
                    {foreach $aCategory.tags as $oTagWhite}
                        <span class="tk-tags-white" onclick="ls.plugin.tagskit.clickSearchCategoryTag(this);">{$oTagWhite->getText()|escape:'html'}</span>
                    {/foreach}
                </div>
            {/foreach}
        </div>

        <br/>
        <button type="submit"  class="button" onclick="ls.plugin.tagskit.submitSearchCategoryTags({if $aTagsTkBlog}{$aTagsTkBlog->getId()}{/if});">{$aLang.plugin.tagskit.tags_search_category.submit}</button>
        <button type="submit"  class="button fl-r jqmClose">{$aLang.plugin.tagskit.close}</button>
        <br/>
    </div>
</div>

<script type="text/javascript">
    jQuery(function($){
        ls.plugin.tagskit.initFormSearchCategory();
    });
</script>