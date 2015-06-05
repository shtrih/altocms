<br/>
{* Название блока *}
<span class="badge blog-background-opacity-label">
    {$aLang.plugin.br.blog_background_opacity}
    <span class="js-branding-blog-opacity-slider-label"></span>
    {*, цвет <span class="js-branding-blog-color-slider-label">#BBAAFF</span>*}
</span>
<br/>
<input id="blog-opacity-range" class="js-branding-blog-opacity-slider" type="range" min="0" max="100" step="1"
       value="{$oBlog->getOpacity()}" data-rangeslider="1">
<br/><br/>
<div class="form-group">
    <div class="input-group">
        <span class="input-group-addon">
            {$aLang.plugin.br.blog_background_color}
        </span>
        <input id="blog-background-color-picker" class="js-branding-blog-color-picker input-text form-control" autocomplete="off"  name="color"/>
    </div>
</div>
