{strip}
{$iTopicId = $topic->getId()}
{$oContentType = $oTopic->getContentType()}
{$oField = $oContentType->getFieldByName('oblozhka')}
{if $oField}
    <!-- TODO: settings -->
    {$sImagePath = $topic->getSingleImage($oField->getFieldId(), '682x395')}
{/if}
{if !$sImagePath}
    {$sImagePath = $topic->getPhotosetMainPhotoUrl(false, '682x395')}
{/if}
<!-- TODO: отличать видео от аудио -->
{$aFormats = ['webm' => 'webmv', 'mp4' => 'm4v']}
{* http://jplayer.org/latest/developer-guide/
   http://jplayer.org/latest/demo-02-jPlayerPlaylist/
*}
<script type="text/javascript">
    $(document).ready(function () {
        $('[id^=jp_container_]').css('display', 'block');
        new jPlayerPlaylist({
                jPlayer: "#jquery_jplayer_{$iTopicId}",
                cssSelectorAncestor: "#jp_container_{$iTopicId}"
            },
            [
                {foreach $aFiles as $oFile}
                {
                    title: '{substr($oFile->name, 0, -(strlen($oFile->extension) + 1))|escape:javascript}',
                    {* artist:"The Artist", // Optional *}
                    {if isset($aFormats[$oFile->extension])}
                        {$aFormats[$oFile->extension]}
                    {else}
                        {$oFile->extension|escape:javascript}
                    {/if}: '{$oFile->url|escape:javascript}',
                    /* TODO: settings */
                    free: true
                    {if $sImagePath && $bHasVideo}
                        , poster: '{$sImagePath|escape:'javascript'}'
                    {/if}
                }{if !$oFile@last},{/if}
                {/foreach}
            ], {
                swfPath: "{Plugin::GetUrl('Audiofilepreview')}templates/frontend/vendors/jPlayer-2.9.2/dist/jplayer/",
                supplied: 'mp3, m4a, webma, webmv, oga, ogv, wav, fla, flv, rtmpa, rtmpv',
                solution: 'html, flash',
                useStateClassSkin: true,
                autoBlur: false,
                smoothPlayBar: true,
                keyEnabled: true,
                remainingDuration: true,
                toggleDuration: true,
                preload: 'none',
                globalVolume: true,
                volume: 0.5,
                size: {
                    width: $('.topic-text').width() + 'px',
                    height: 10 + Math.round($('.topic-text').width() / 1.77) + 'px',
                    cssClass: ''
                }
            }
        );
    });
</script>
<div id="jquery_jplayer_{$iTopicId}" class="jp-jplayer"></div>
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
