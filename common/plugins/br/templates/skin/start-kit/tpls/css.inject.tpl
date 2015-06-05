{if $oBranding && (($oBlog && Config::Get('plugin.br.blog.allow_blog')))}
    <div id="plugin-branding-blog-styles">
        <style>
            {if ($oBlog && Config::Get('plugin.br.blog.allow_background'))}
            body {
                {if !is_null($oBranding->getBackgroundColor())}
                    {$aColors=$oBranding->toRgb($oBranding->getBackgroundColor())}
                    /*background-image: none;*/
                    /*background: none;*/
                    background-color: rgba({$aColors.r}, {$aColors.g}, {$aColors.b}, {$oBranding->getOpacity()/100});
                {/if}


                {if !$oBranding->getUseBackgroundColor()}
                    {if $oBranding->getBackground()}
                        /*background: none;*/
                        /*background-color: transparent;*/
                        background-image: url("{$oBranding->getBackground()}");
                    {*{else}*}
                        {*background-color: transparent;*}
                    {/if}
                {else}
                    background-image: none;
                    /*background: none;*/
                {/if}

                {if $oBranding->getBrandingBackgroundType()}
                    {if $oBranding->getBrandingBackgroundType() != 0}
                        background-repeat: no-repeat;
                    {/if}

                    {$sXPosition = false}
                    {$sYPosition = false}

                    {if $oBranding->getBrandingBackgroundType() == 1 || $oBranding->getBrandingBackgroundType() == 2 || $oBranding->getBrandingBackgroundType() == 3}
                    {*background-position-y: {Config::Get('plugin.br.themes.brand.background_top_padding')}px;*}
                    {$sYPosition = "{Config::Get('plugin.br.themes.startkit.background_top_padding')}px"}
                    {/if}
                    {if $oBranding->getBrandingBackgroundType() == 5 || $oBranding->getBrandingBackgroundType() == 6 || $oBranding->getBrandingBackgroundType() == 7}
                        /*background-position-y: 100%;*/
                    {$sYPosition = '100%'}
                    {/if}
                    {if $oBranding->getBrandingBackgroundType() == 4}
                        /*background-position-y: 50%;*/
                    {$sYPosition = '50%'}
                    {/if}
                    {if $oBranding->getBrandingBackgroundType() == 1 || $oBranding->getBrandingBackgroundType() == 5}
                        /*background-position-x: 50%;*/
                    {$sXPosition = "50%"}
                    {/if}
                    {if $oBranding->getBrandingBackgroundType() == 2 || $oBranding->getBrandingBackgroundType() == 6}
                        /*background-position-x: 0;*/
                    {$sXPosition = "0"}
                    {/if}
                    {if $oBranding->getBrandingBackgroundType() == 3 || $oBranding->getBrandingBackgroundType() == 7}
                        /*background-position-x: 100%;*/
                    {$sXPosition = "100%"}
                    {/if}
                    {if $oBranding->getBrandingBackgroundType() == 4}
                        /*background-position-x: 50%;*/
                    {$sXPosition = "50%"}
                    {/if}
                    {*{if $sXPosition && $sYPosition}*}
                        background-position: {$sXPosition} {$sYPosition};
                    {*{/if}*}
                    {if $oBranding->getBrandingBackgroundType() == 8}
                        background-size: 100%; -moz-background-size: 100%; -webkit-background-size: 100%; -o-background-size: 100%;
                    {/if}
                {/if}

            }
            {/if}



            {if ($oBlog && Config::Get('plugin.br.blog.allow_font'))}
                {if !is_null($oBranding->getFontColor())}
            #content-container *, .topic-text, .topic-text input, select *, textarea, .input-group *, .selecter *, .selecter, .selecter .selecter-selected, .text{
                    color: {if $oBranding->getFontColor()==0}#000000{else}#{dechex($oBranding->getFontColor())};{/if}
                }
                .branding-panel input, .branding-panel select *, .branding-panel textarea, .branding-panel .input-group *, .branding-panel .selecter * {
                    color: #333;
                }
                {/if}
            {/if}

            {if ($oBlog && Config::Get('plugin.br.blog.allow_header'))}
                {if !is_null($oBranding->getHeaderColor())}
                    #container  h1, #container  h1 * { color: {if $oBranding->getHeaderColor()==0}#000000{else}#{dechex($oBranding->getHeaderColor())};{/if} }
                    #container  h2, #container  h2 * { color: {if $oBranding->getHeaderColor()==0}#000000{else}#{dechex($oBranding->getHeaderColor())};{/if} }
                    #container  h3, #container  h3 * { color: {if $oBranding->getHeaderColor()==0}#000000{else}#{dechex($oBranding->getHeaderColor())};{/if} }
                    #container  h4, #container  h4 * { color: {if $oBranding->getHeaderColor()==0}#000000{else}#{dechex($oBranding->getHeaderColor())};{/if} }
                    #container  h5, #container  h5 * { color: {if $oBranding->getHeaderColor()==0}#000000{else}#{dechex($oBranding->getHeaderColor())};{/if} }
                    #container  h6, #container  h6 * { color: {if $oBranding->getHeaderColor()==0}#000000{else}#{dechex($oBranding->getHeaderColor())};{/if} }
                {/if}
            {/if}

            {if ($oBlog && Config::Get('plugin.br.blog.allow_step'))}
                {if $oBranding->getHeaderStep()}
                    #container, #footer {
                        position: relative;
                        top: {$oBranding->getHeaderStep()}px;
                    }
                {/if}
            {/if}

        </style>
    </div>
{/if}
