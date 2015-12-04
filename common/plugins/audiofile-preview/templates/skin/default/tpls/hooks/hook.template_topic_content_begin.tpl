{strip}
{$iTopicId = $topic->getId()}
{if $bHasVideo}
    {$oContentType = $topic->getContentType()}
    <!-- TODO: settings -->
    {$oField = $oContentType->getFieldByName('oblozhka')}
    {if $oField}
        <!-- TODO: settings -->
        {$sImagePath = $topic->getSingleImage($oField->getFieldId(), '682x395')}
    {/if}
    {if !$sImagePath}
        {$sImagePath = $topic->getPhotosetMainPhotoUrl(false, '682x395')}
    {/if}
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
                    {if $sImagePath}
                        , poster: '{$sImagePath|escape:'javascript'}'
                    {/if}
                }{if !$oFile@last},{/if}
                {/foreach}
            ], {
                swfPath: "{Plugin::GetUrl('Audiofilepreview')}templates/frontend/vendors/jPlayer-2.9.2/dist/jplayer/",
                {if $bHasVideo}
                    supplied: 'mp3, m4a, webma, webmv, oga, ogv, wav, flv, rtmpa, rtmpv',
                {else}
                    supplied: 'mp3, m4a, webma, oga, wav, rtmpa',
                {/if}
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
                {if $bHasVideo}
                , size: {
                    width: $('.topic-text').width() + 'px',
                    height: 10 + Math.round($('.topic-text').width() / 1.77) + 'px',
                    cssClass: ''
                }
                {/if}
            }
        );
    });
</script>
{$sSkin = Config::Get('plugin.audiofilepreview.player-skin')}
{include file="../jplayer/$sSkin.tpl"}
{/strip}
