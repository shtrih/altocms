<br/>
{* Название блока *}
<span class="badge blog-background-opacity-label">
    {$aLang.plugin.br.blog_header_step}
    <span class="js-branding-blog-header-step-label">0</span>
</span>
<br/>
<input id="blog-header-step-range" class="js-branding-blog-header-step-slider" type="range" min="0" max="380" step="1"
       value="{if $oBlog->getHeaderStep()}{$oBlog->getHeaderStep()}{else}0{/if}" data-rangeslider="1">
<br/><br/><br/>
