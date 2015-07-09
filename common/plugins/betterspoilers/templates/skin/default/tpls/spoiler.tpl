<div class="betterspoiler">
    <input type="checkbox" tabindex="-1" >
    <div class="box">
        <span class="trigger">
            <span style="padding-left:23px">
                {if $sTitle}
                    {$sTitle}
                {else}
                    {$aLang.plugin.betterspoilers.trigger_text}
                {/if}
            </span>
        </span>
        <span class="text">
            <span>{$sText}</span>
        </span>
    </div>
</div>
