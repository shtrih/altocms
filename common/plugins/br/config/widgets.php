<?php
/**
 * widgets.php
 * Файл конфигурационных параметров виджетов плагина Br
 *
 * @author      Андрей Воронов <andreyv@gladcode.ru>
 * @copyrights  Copyright © 2014, Андрей Воронов
 *              Является частью плагина Br
 * @version     0.0.1 от 03.11.2014 01:59
 */


// Виджет брендирования страницы блога
$config['widgets'][] = array(
    'name'     => 'brandingBlog',
    'group'    => 'right',
    'priority' => 100,
    'plugin'   => 'br',
    'on'       => 'blog',
    'action'   => array(
        'blog' => array('add', 'edit', 'admin'),
    ),
);



