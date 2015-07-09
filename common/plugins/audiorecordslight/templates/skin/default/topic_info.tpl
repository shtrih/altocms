
	<!-- Audiorecordslight plugin -->
			{*{foreach from=$aMP3Audio key=key item=sMP3File}
				<div>
					<span>
						<object 
							type="application/x-shockwave-flash" 
							align="bottom" 
							data="{cfg name="path.root.web"}/engine/lib/external/musicplayer/player_mp3_maxi.swf"
							width="380" 
							height="16" 
						>
							<param name="movie" value="{cfg name="path.root.web"}/engine/lib/external/musicplayer/player_mp3_maxi.swf" />
							<param name="bgcolor" value="#ffffff" />
							<param name="FlashVars" value="mp3={cfg name="path.root.web"}/uploads/audio/{md5(basename($sMP3File))}.mp3&amp;width=380&amp;height=16&amp;showstop=1&amp;showvolume=1&amp;buttonwidth=20&amp;sliderwidth=15&amp;volumewidth=40" />
						</object>
					</span>
					<small class="audio_preview">
						{substr(basename($sMP3File), 0, -4)|escape:html}
					</small>
				</div>
			{/foreach}*}
	<!-- /Audiorecordslight plugin -->

<script type="text/javascript">
	$(document).ready(function () {
		$('[id^=jp_container_]').css('display', 'block');
		new jPlayerPlaylist({
				jPlayer: "#jquery_jplayer_{$topic_id}",
				cssSelectorAncestor: "#jp_container_{$topic_id}"
			},
			[
				{foreach from=$aMP3Audio item=sMP3File}
				{
					title: '{substr(basename($sMP3File), 0, -4)|escape:javascript}',
					mp3: '{cfg name="path.root.web"}/uploads/audio/{md5(basename($sMP3File))}.mp3',
					free: true
				},
				{/foreach}
			], {
				swfPath: "{cfg name="path.root.engine_lib"}/external/jPlayer-2.9.2/dist/jplayer/",
				supplied: 'mp3',
				solution: 'html, flash',
				useStateClassSkin: true,
				autoBlur: false,
				smoothPlayBar: true,
				keyEnabled: true,
				remainingDuration: true,
				toggleDuration: true,
				preload: 'none',
				globalVolume: true,
				volume: 0.5
			}
		);
	});
</script>
<div id="jquery_jplayer_{$topic_id}" class="jp-jplayer"></div>
<div id="jp_container_{$topic_id}" class="jp-audio" role="application" aria-label="media player">
	<div class="jp-type-playlist">
		<div class="jp-gui jp-interface">
			<div class="jp-controls">
				<button class="jp-previous" role="button" tabindex="0">previous</button>
				<button class="jp-play" role="button" tabindex="0">play</button>
				<button class="jp-next" role="button" tabindex="0">next</button>
				{*<button class="jp-stop" role="button" tabindex="0">stop</button>*}
			</div>
			<div class="jp-toggles">
				<button class="jp-repeat" role="button" tabindex="0">repeat</button>
				<button class="jp-shuffle" role="button" tabindex="0">shuffle</button>
			</div>
			<div class="jp-progress">
				<div class="jp-seek-bar">
					<div class="jp-play-bar"></div>
				</div>
			</div>
			<div class="jp-volume-controls">
				<button class="jp-mute" role="button" tabindex="0">mute</button>
				<div class="jp-volume-bar">
					<div class="jp-volume-bar-value"></div>
				</div>
				<button class="jp-volume-max" role="button" tabindex="0">max volume</button>
			</div>
			<div class="jp-time-holder">
				<span class="jp-current-time" role="timer" aria-label="time">&nbsp;</span> /
				<span class="jp-duration" role="timer" aria-label="duration">&nbsp;</span>
			</div>
		</div>
		<div class="clear"></div>

		<div class="jp-playlist">
			<ul>
				<li>&nbsp;</li>
			</ul>
		</div>
		<div class="jp-no-solution">
			<span>Update Required</span>
			To play the media you will need to either update your browser to a recent version or update your <a
					href="http://get.adobe.com/flashplayer/" target="_blank">Flash plugin</a>.
		</div>
	</div>
</div>