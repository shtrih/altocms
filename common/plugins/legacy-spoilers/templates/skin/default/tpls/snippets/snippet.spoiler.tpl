{strip}{*{strip}
<div class="spoiler">
    <div class="spoiler-title">
        {if $aParams.title}{$aParams.title}{/if}
        <div class="spoiler-slider">
            {$aLang.spoiler_toggle_show}
        </div>
    </div>
    <div class="spoiler-text">
        {$aParams.snippet_text}
    </div>
</div>
{/strip}*}
<div class="betterspoiler">
    <input type="checkbox" tabindex="-1" />
    <div class="btrsplr-box">
        <span class="btrsplr-trigger">
            <span>
                {if $aParams.title}
                    {$aParams.title}
                {else}
                    {$aLang.plugin.betterspoilers.trigger_text}
                {/if}
            </span>
        </span>
        <span class="btrsplr-text">
            <span>{$aParams.snippet_text}</span>
        </span>
    </div>
</div>
{/strip}
