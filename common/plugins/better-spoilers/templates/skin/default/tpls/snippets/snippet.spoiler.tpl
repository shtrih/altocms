{strip}
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
        <div class="btrsplr-text">
            <span>{$aParams.snippet_text}</span>
        </div>
    </div>
</div>
{/strip}
