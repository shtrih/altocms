<?php
/*---------------------------------------------------------------------------
 * @Project: Alto CMS
 * @Project URI: http://altocms.com
 * @Plugin Name: Similar Topics
 * @Plugin ID: similartopics
 * @Description: Similar Topics (defined by tags)
 * @Copyright: Alto CMS Team
 * @License: GNU GPL v2 & MIT
 *----------------------------------------------------------------------------
 */

// Настройки виджета в подвале топика
$config['widget_showtopic'] = array(
    'limit'       => 4,         // максимальное число выводимых похожих статей
    'text_maxlen' => 180,       // максимальная длина текста
    'preview'     => array(
        'enable' => true,       // true - вывод превью включен, false - выключен
        'size'   => array(
            'default' => '50crop',
        ),
    ),
    'display'     => true,      // true - показывать виджет, false - не показывать
);

// EOF