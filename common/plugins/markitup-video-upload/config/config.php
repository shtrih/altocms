<?php

$aConfig = [
    // Использовать загрузку видео в топиках
    'use_in_topic'          => true,
    // Использовать загрузку видео в комментариях
    'use_in_comments'       => true,

    // индекс позиций кнопки на панели редактора markItUp!
    'button_index_topic'    => 15,
    'button_index_comments' => 9,
];

$aConfig[Config::KEY_ROOT]['module']['uploader']['video']['webm'] = [
    // TODO: Выводить макс. размеры файлов в модальном диалоге
    //

    'file_maxsize' => '12Mb',
    'url_maxsize' => '10Mb',

    'transform' => [
            'watermark' => [
            'enable' => true,
            'image' => [
                'path' => __DIR__ . '/watermark/',
                'file' => 'webm-logo.png',
                'topleft' => true,
                'position' => '10,10',
            ],
        ],
    ],

    // наследуем из module.uploader.images.video
    /*
    'original' => array(
        'save' => false,            // надо ли сохранять оригинальное изображение
        'suffix' => '-original',    // суффикс оригинального изображения
    ),
    */

    'file_extensions' => [
        'webm',
        //'webma',
        //'webmv',
    ],

    // Дальше ничего не трогать
    'image_extensions' => [],
    Config::KEY_EXTENDS => '___module.uploader.images.video___',
    Config::KEY_RESET => true,
];

$aConfig[Config::KEY_ROOT]['path']['uploads']['webm'] = '___path.uploads.root___/webm/';
$aConfig[Config::KEY_ROOT]['router']['page']['markitup-video-upload'] = 'PluginMarkitupVideoUpload_ActionMarkitupVideoUpload';


return $aConfig;
