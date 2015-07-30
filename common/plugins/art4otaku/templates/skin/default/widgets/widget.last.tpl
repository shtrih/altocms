{if !$sError}
<section class="block">
	<header class="block-header">
		<h3><a href="http://art.4otaku.org/">Последние арты</a></h3>
	</header>

	<script>
		$(function () {
			$(".art-slider").jCarouselLite({
				btnNext: ".art-slider .next",
				btnPrev: ".art-slider .prev",
				visible: 1
			});
		});
	</script>
	<div class="block-content art-slider">
		<ul>
		{foreach from = $aItems item=oFile}
			<li><a href="http://art.4otaku.org/{$oFile->id}" style="background-image: url(http://images.4otaku.org/art/{$oFile->md5}_thumb.jpg)"></a></li>
		{/foreach}
		</ul>
		<div class="controls">
			<a href="#" class="prev"></a>
			<a href="#" class="next"></a>
		</div>
	</div>
</section>
{else}
	<div class="hidden">{$sError}</div>
{/if}