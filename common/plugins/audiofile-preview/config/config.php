<?php

$config = [
    /**
     * Внешний вид плеера. Допустимые значения: blue.monday, pink.flag.
     * Папка со скинами: common/plugins/audiofile-preview/templates/frontend/vendors/jPlayer-2.9.2/dist/skin
     */
    'player-skin' => '4otaku',
    /*
     * HTML5: mp3, mp4 (AAC/H.264), ogg (Vorbis/Theora), webm (Vorbis/VP8), wav
     * Flash: mp3, mp4 (AAC/H.264), rtmp, flv
     */
    'audio-extensions' => [
        'mp3',
        'mp4',
        'ogg',
        'oga',
        'webm',
        'wav',
        'rtmp',
        'flv',
        'flac'
    ],
    'video-extensions' => [
        'mp4',
        'm4v',
        'ogv',
//        'ogg',
        'webm',
        'flv',
        'rtmp',
    ]
];

return $config;
