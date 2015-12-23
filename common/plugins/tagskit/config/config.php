<?php

/**
 * Максимальное кличество тегов, которые учитываются в поиске по тегам
 */
$config['search_tags_max'] = 4;
/**
 * Режим работы создания тегов при редактирование топиков
 *
 * standart - стандартный режим LS, пользователь может вводить любые теги
 * white - белый список, пользователь может вводить теги только из белого списка
 * white_category - белый список c разделением по категориям, пользователь может вводить теги только из белого списка
 * black - черный список, пользователь может вводить любые теги, кроме тех, что находятся в черном списке
 */
$config['type_tags_create'] = 'white_category';
/**
 * Белый список тегов
 */
$config['tags_list_white'] = array(
    'apple',
    'google',
    'ios',
    'android'
);
/**
 * Белый список тегов по категроиям
 */
$config['tags_list_white_category'] = array(
    array(
        'title' => 'Обивка',
        'tags'  => array(
            'Ткань', 'Натуральная кожа'
        ),
    ),
    array(
        'title' => 'Механизм трансформации',
        'tags'  => array(
            'Высоковыкатной', 'Седафлекс'
        ),
    ),
);
/**
 * Сортировка белого списка тегов
 *
 * text - сортировка по тексту тега
 * count - сортировка по частоте использования
 */
$config['white_list_sort'] = 'text';
/**
 * Количество тегов на страницу в белом списке
 */
$config['white_list_per_page'] = 20;
/**
 * Максимальное число тегов для автоподбора
 */
$config['auto_search_tags_max'] = 6;
/**
 * Черный список тегов
 */
$config['tags_list_black'] = array(
    'бля',
    'нах'
);


if ($config['type_tags_create'] == 'white_category') {

    $config['$root$']['block']['tagskit_search_category'] = array(
        'action' => array(
            'index',
            'blog' => array('{blog}'),
        ),
        'blocks' => array(
            'right' => array('searchCategory' => array('priority' => 300, 'params' => array('plugin' => 'tagskit')))
        ),
        'clear'  => false,
    );
}

/**
 * Системные настройки
 */
$config['$root$']['db']['table']['tagskit_main_tag'] = '___db.table.prefix___tk_tag';
$config['$root$']['router']['page']['tk_admin']      = 'PluginTagskit_ActionTkadmin';

return $config;
