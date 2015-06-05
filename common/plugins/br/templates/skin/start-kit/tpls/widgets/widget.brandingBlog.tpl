{if Config::Get('plugin.br.blog.allow_blog') == TRUE}
<script>
    $(function () {
        ls.lang.load({lang_load name="plugin.br.blog_image_loading_good,plugin.br.blog_saved,plugin.br.blog_image_loading_revert,plugin.br.scroll_to_intensive,plugin.br.blog_branding_removed"});
    })
</script>

<div class="panel panel-default sidebar widget widget-blog {if Router::GetActionEvent()!='add'}js-branding-blog-panel js-branding-panel{/if} branding-panel"
     data-lang-blog-image-loading-good="plugin.br.blog_image_loading_good"
     data-lang-blog-opacity-loading-good="plugin.br.blog_saved"
     data-lang-blog-image-loading-revert="plugin.br.blog_image_loading_revert"
     data-lang-blog-branding-removed="plugin.br.blog_branding_removed"
     data-theme-background-top-padding="{Config::Get('plugin.br.themes.startkit.background_top_padding')}px"

     data-content-selector="#container"
{if $oBlog}
     data-branding-has="1"
     data-branding-target-id="{$oBlog->getId()}"
     data-branding-target-type="blog-branding"
     data-branding-background="{$oBlog->getBackground()}"
     data-branding-opacity="{if $oBlog->getOpacity()}{$oBlog->getOpacity()}{else}100{/if}"
     data-branding-background-color="{if !is_null($oBlog->getBackgroundColor())}{if $oBlog->getBackgroundColor()=='#0'}#000000{else}{$oBlog->getBackgroundColor()}{/if}{else}#FFFFFF{/if}"
     data-branding-use-background-color="{$oBlog->getUseBackgroundColor()}"
     data-branding-font-color="{if !is_null($oBlog->getFontColor())}{if $oBlog->getFontColor()=='0'}#000000{else}{$oBlog->getFontColor()}{/if}{else}#000000{/if}"
     data-branding-header-color="{if !is_null($oBlog->getHeaderColor())}{if $oBlog->getHeaderColor()=='0'}#000000{else}{$oBlog->getHeaderColor()}{/if}{else}#000000{/if}"
     data-branding-header-step="{if $oBlog->getHeaderStep()}{$oBlog->getHeaderStep()}{else}0{/if}"
     data-branding-title="{$oBlog->getBrandingTitle()}"
     data-branding-description="{$oBlog->getBrandingDescription()}"
     data-branding-background-type="{if $oBlog->getBackgroundType()}{$oBlog->getBackgroundType()}{else}0{/if}"
{/if}
     data-palette="{asset file="assets/images/HueSaturation.png" plugin="br"}"
     >

    <div class="panel-body pab24">
        <h4 class="panel-header">
            <i class="fa fa-file-image-o"></i>
            {$aLang.plugin.br.branding}
        </h4>

        <div class="panel-content">
            {if Router::GetActionEvent()=='add'}
                <div>
                    {$aLang.plugin.br.branding_save_notice}
                </div>
            {else}
                {* Отступ шапки *}
                {if Config::Get('plugin.br.blog.allow_step')}{include file="{$sTemplatePathBr}/tpls/widgets/blog_parts/header-slider.tpl" oBlog=$oBlog}{/if}
                {* Брендирование фона страниц блога *}
                {if Config::Get('plugin.br.blog.allow_background')}{include file="{$sTemplatePathBr}/tpls/widgets/blog_parts/upload-blog-image.tpl"}{/if}

                {* Расположение фона *}
                {if Config::Get('plugin.br.blog.allow_background')}{include file="{$sTemplatePathBr}/tpls/widgets/blog_parts/background-type.tpl"}{/if}

                {* Слайдер прозрачности *}
                {if Config::Get('plugin.br.blog.allow_background')}{include file="{$sTemplatePathBr}/tpls/widgets/blog_parts/opacity-slider.tpl" oBlog=$oBlog}{/if}

                {* Цвет шрифта *}
                {if Config::Get('plugin.br.blog.allow_font')}{include file="{$sTemplatePathBr}/tpls/widgets/blog_parts/font-color-picker.tpl"}{/if}

                {* Цвет заголовков *}
                {if Config::Get('plugin.br.blog.allow_header')}{include file="{$sTemplatePathBr}/tpls/widgets/blog_parts/header-color-picker.tpl"}{/if}

            {/if}
        </div>

        {if $oBlog}
            <div class="clearfix">
                <a class="btn btn-success   corner-no js-blog-save-branding" href="#" onclick="return false;">
                    <i class="glyphicon glyphicon-upload"></i>&nbsp;&nbsp;{$aLang.plugin.br.branding_save_blog}
                </a>
                <a class="btn btn-danger  corner-no pull-right  js-blog-delete-branding" {if !$oBlog->getTargetId()}style="display: none"{/if}
                   data-toggle="tooltip" data-placement="left" title="{$aLang.plugin.br.remove_branding}"
                   href="#"
                   onclick="return false;">
                    <i class="glyphicon glyphicon-remove"></i>
                </a>
            </div>
        {/if}
    </div>

</div>
{/if}