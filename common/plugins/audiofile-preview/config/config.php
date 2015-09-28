<?php
/**
 * Конфиг
 */

$config = array(
    /*
     * HTML5: mp3, mp4 (AAC/H.264), ogg (Vorbis/Theora), webm (Vorbis/VP8), wav
     * Flash: mp3, mp4 (AAC/H.264), rtmp, flv
     */
    'audio-extensions' => array(
        'mp3',
        'mp4',
        'ogg',
        'oga',
        'webm',
        'wav',
        'rtmp',
        'flv',
        'flac'
    ),
    'video-extensions' => array(
        'mp4',
        'm4v',
        'ogv',
//        'ogg',
        'webm',
        'flv',
        'rtmp',
    )
);

// Переопределить имеющуюся переменную в конфиге:
// Переопределение роутера на наш новый Action - добавляем свой урл  http://domain.com/audiofilepreview
// Config::Set('router.page.audiofilepreview', 'PluginAudiofilepreview_ActionAudiofilepreview');

// Добавить новую переменную:
// $config['per_page'] = 15;
// Эта переменная будет доступна в плагине как Config::Get('plugin.audiofilepreview.per_page')

return $config;
