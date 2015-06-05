<script>
    $(function(){
        ls.lang.load({lang_load name="plugin.br.blog_resize_window_title,plugin.br.blog_resize_window_help"});
    })
</script>

{* БЛОК БРЕНДИРОВАНИЯ ФОНОВОГО ИЗОБРАЖЕНИЯ БЛОГА *}
<div class="blog-background-container js-alto-uploader"
     data-target="blog-branding"
     data-target-id="{$oBlog->getId()}"
     data-title="plugin.br.blog_resize_window_title"
     data-help="plugin.br.blog_resize_window_help"
     data-empty="{asset file="assets/images/empty_blog_image.png" plugin="br"}"
     data-preview-crop="400fit"
     data-crop="yes">

    {* Лоадер *}
    <div class="blog-background-image-container">
        <i class="fa fa-circle-o-notch js-branding-blog-loader blog-background-loader" style="display: none;"></i>

        {* Картинка фона блога *}
        <img src="{if $oBlog->getBackgroundImage()}{$oBlog->getBackgroundImage()}{else}{asset file="assets/images/empty_blog_image.png" plugin="br"}{/if}"
             alt="blog background image"
             {*style="opacity: 0"*}
             class="thumbnail blog-background-image js-uploader-image js-branding-blog-background"/>
    </div>

    {* Положение фона *}
    <div class="form-group mab12 uploader-blog-type" {if $oBlog->getUseBackgroundColor()}style="display: none;"{/if}>
        <div class="input-group">
            <label class="input-group-addon" for="uploader-blog-type">{$aLang.plugin.br.blog_background_type}</label>
            <select name="uploader-blog-type" id="uploader-blog-type" class="form-control js-uploader-blog-type">
                <option value="0" {if $oBlog->getBackgroundType()=='0'}selected{/if}>{$aLang.plugin.br.blog_background_type_0}</option>
                <option value="1" {if $oBlog->getBackgroundType()=='1'}selected{/if}>{$aLang.plugin.br.blog_background_type_1}</option>
                <option value="2" {if $oBlog->getBackgroundType()=='2'}selected{/if}>{$aLang.plugin.br.blog_background_type_2}</option>
                <option value="3" {if $oBlog->getBackgroundType()=='3'}selected{/if}>{$aLang.plugin.br.blog_background_type_3}</option>
                <option value="4" {if $oBlog->getBackgroundType()=='4'}selected{/if}>{$aLang.plugin.br.blog_background_type_4}</option>
                <option value="5" {if $oBlog->getBackgroundType()=='5'}selected{/if}>{$aLang.plugin.br.blog_background_type_5}</option>
                <option value="6" {if $oBlog->getBackgroundType()=='6'}selected{/if}>{$aLang.plugin.br.blog_background_type_6}</option>
                <option value="7" {if $oBlog->getBackgroundType()=='7'}selected{/if}>{$aLang.plugin.br.blog_background_type_7}</option>
                <option value="8" {if $oBlog->getBackgroundType()=='8'}selected{/if}>{$aLang.plugin.br.blog_background_type_8}</option>
            </select>
        </div>
    </div>

    {* Название блока *}
    <span class="blog-background-label badge">{$aLang.plugin.br.blog_background}</span>

    {* Меню управления картинкой фона блога *}
    <div class="blog-background-menu">

        {* Кнопка загрузки картинки *}
        <a class="link link-lead link-blue link-clear mat8 js-uploader-button-upload" {if $oBlog->getUseBackgroundColor()}style="display: none"{/if} href="#" onclick="return false;">
            <i class="fa fa-upload"></i>&nbsp;
            {if $oBlog->getBackgroundImage()}
                {$aLang.plugin.br.blog_photo_change}
            {else}
                {$aLang.plugin.br.blog_photo_upload}
            {/if}
        </a>

        {* Кнопка удаления картинки *}
        <a href="#" onclick="return false;" class="link link-lead link-red-blue link-clear js-uploader-button-remove"
           {if !$oBlog->getBackgroundImage() || $oBlog->getUseBackgroundColor()}style="display: none;"{/if}>
            <i class="fa fa-times"></i>&nbsp;{$aLang.plugin.br.blog_photo_delete}
        </a>

        <a href="#" onclick="return false;" {if !$oBlog->getUseBackgroundColor()}style="display: none;"{/if} class="link link-lead link-blue link-clear js-blog-branding-both">
           {$aLang.plugin.br.blog_both}
        </a>
        <a href="#" onclick="return false;" {if $oBlog->getUseBackgroundColor()}style="display: none;"{/if}  class="link link-lead link-blue link-clear js-blog-branding-color-only">
           {$aLang.plugin.br.blog_color_only}
        </a>

        {* Файл для загрузки *}
        <input type="file" id="blog-photo-file" name="uploader-upload-image" class="js-uploader-file" data-blog_id="{$oBlog->getId()}">

    </div>

    {* Форма обрезки картинки при ее загрузке *}
    {include_once file="modals/modal.crop_img.tpl"}

</div>