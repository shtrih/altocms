<?php
/**
 * config.php
 * Файл конфигурационных параметров плагина Br
 *
 * @author      Андрей Воронов <andreyv@gladcode.ru>
 * @copyrights  Copyright © 2014, Андрей Воронов
 *              Является частью плагина Br
 * @version     0.0.1 от 03.11.2014 01:59
 */

/**
 * Таблицы БД плагина
 */
//Config::Set('db.table.tablename', '___db.table.prefix___tablename');

/**
 * Роутеры плагина
 */
Config::Set('router.page.uploader', 'PluginBr_ActionUploader'); // Админка

/**
 * Параметры плагина
 */
$aConfig = array(

    'themes' => array(
        'brand' => array(
            'background_top_padding' => 270,
        ),
        'default' => array(
            'background_top_padding' => 0,
        ),
        'experience' => array(
            'background_top_padding' => 140,
        ),
        'startkit' => array(
            'background_top_padding' => 372,
        ),
    ),

    // Разрешения на брендинг блога
    'blog' => array(

        // Разрешено ли вообще оформлять блог
        'allow_blog' => TRUE,

        // Можно ли менять фон сайта
        'allow_background' => TRUE,

        // Имеет ли пользователь право менять шрифт страниц блога
        'allow_font' => TRUE,

        // Разрешается ли менять цвет заголовков блога
        'allow_header' => TRUE,

        // Будет ли у блога отступ шапки
        'allow_step' => TRUE,
    ),



);

return $aConfig;