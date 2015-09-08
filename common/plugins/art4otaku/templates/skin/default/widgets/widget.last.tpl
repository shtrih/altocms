{if !$sError}
<script>
$(function () {
    $(".art-slider").jCarouselLite({
        btnNext: ".art-next",
        btnPrev: ".art-prev",
        visible: 1
    });
});
</script>
<div class="panel panel-default sidebar flat widget">
    <div class="panel-body">
        <div class="panel-header">
            <i class="fa fa-picture-o"></i>
            <a href="http://art.4otaku.org/" title="Весь эфир">Последние арты</a>
        </div>
        <div class="panel-content art-slider">
            <ul>
                {foreach from = $aItems item=oFile}
                    <li><a href="http://art.4otaku.org/{$oFile->id}" style="background-image: url(http://images.4otaku.org/art/{$oFile->md5}_thumb.jpg)"></a></li>
                {/foreach}
            </ul>
        </div>
    </div>
    <div class="panel-footer">
        <a class="link link-dual link-lead link-clear art-prev" href="#"><i class="fa fa-arrow-circle-left"></i></a>
        <a class="link link-dual link-lead link-clear pull-right art-next" href="#"><i class="fa fa-arrow-circle-right"></i></a>
    </div>
</div>
{else}
    <div class="hidden">{$sError}</div>
{/if}
