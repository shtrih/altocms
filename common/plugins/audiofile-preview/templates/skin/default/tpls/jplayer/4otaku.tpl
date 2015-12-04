{strip}
<div id="jquery_jplayer_{$iTopicId}" class="jp-jplayer"  style="clear: both;"></div>
<div id="jp_container_{$iTopicId}" class="jp-audio" style="clear: both;" role="application" aria-label="media player">
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
{/strip}
