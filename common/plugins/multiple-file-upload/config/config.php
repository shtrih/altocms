<?php
/*
 * Project Name: Multiple File Upload
 * Copyright © 2015 shtrih
 */

/* Для изменения настроек, не нужно вносить правки в этом файле, ибо он может перезаписаться с обновлением плагина.
 * Скопируйте его в папку «/app/plugins/multiple-file-upload/config/» и редактируйте там всё, что надо.
 *
 * Чтобы переопределить языковые переменные, скопируйте языковой файл из common/plugins/multiple-file-upload/templates/language
 *     в папку «app/plugins/multiple-file-upload/templates/language/».
 */
$aConfig = [
    // Сколько файлов показывать до ссылки «Показать остальные».
    'files-show-count' => 3,

    /* Прятать прямые ссылки на файлы.
     *
     * Если true, то ссылки на файлы имеют вид «/multiplefileupload/get/45»,
     *    иначе прямые вида «/uploads/files/00/00/01/2015/08/12/0u73b903ac-78531e29-486b6694.zip»
     *
     * Обратите внимание, что если прямые ссылки на файлы прячутся, то файлы отдаются скриптом.
     *    Это сильно повышает нагрузку на сервер, возрастают расходы памяти, скачивание больших файлов может обрываться.
     *    Для того, чтобы обойти эти неприятные последствия, можно настроить отдачу файлов через XSendFile (см. опции apache2-xsendfile, nginx-xsendfile).
     */
    'hide-direct-links' => false,

    /* Отдавать файл браузеру напрямую, без диалога сохранения файла. Используется только если 'hide-direct-links' => true.
     *
     * Файлы, размер которых меньше указанного значения, будут отдаваться без заголовка «Content-Disposition: attachment; filename=""»,
     *    что позволит браузеру самому решать, как открыть этот файл: отобразить на экране (если это картинка) или предложить открыть в какой-то программе.
     */
    'attachment-header-max-file-size' => '0Mb', // Kb, Mb, Gb
    // Расширения файлов, которые нужно отдавать браузеру напрямую
    'attachment-header-extensions' => array('png', 'jpg', 'jpeg', 'gif', 'webm', 'webp'),

    // Начинать загрузку файла сразу после добавления.
    'auto-upload' => false,

    /* Расширения файлов, которые можно прикреплять к топикам.
     * Закомментируйте настройку, чтобы использовать набор расширений, которые указаны в основном конфиге сайта (['module']['uploader']['files']['default'])
     */
    'accept-file-types' => array('zip'),

    /* Использовать ли XSendFile для отдачи файлов. Установите true напротив опции соответствующего сервера.
     * Подробнее http://wiki.nginx.org/NginxXSendfile или https://tn123.org/mod_xsendfile/
     * (!) Прежде чем включать настройку, нужно сначала настроить сервер. Как это сделать, см. приведенные выше ссылки, инструкции в интернете.
     */
    // Если установлен и настроен мод mod_xsendfile для Apache
    'apache2-xsendfile' => false,
    // Если настроен мод XSendFile для nginx
    'nginx-xsendfile' => false,
];

// Настройки загружаемых файлов
$aConfig[Config::KEY_ROOT]['module']['uploader']['files']['multiple-file-upload'] = [

    /* Максимальный размер загружаемого файла. Установка нулевого размера отключает лимит.
     * Чтобы использовать значение основного конфига сайта, закомментируйте эту настройку.
     */
    'file_maxsize'    => '10Mb', // Kb, Mb, Gb

    /* Максимальный размер файла, загружаемого по ссылке.
     * Чтобы отключить загрузку по ссылке, пропишите 0 или закомментируйте настройку.
     */
    'url_maxsize'     => '10Mb',


    /* Дальше ничего не трогать. */
    Config::KEY_EXTENDS => '___module.uploader.files.default___',
    Config::KEY_RESET => true,
    'file_extensions' => empty($aConfig['accept-file-types']) ? array() : $aConfig['accept-file-types'],
];

Config::Set(
    'router.uri',
    [
        '[~^(multiplefileupload/get/\d+/.+)$~i]' => '$1',
        Config::KEY_REPLACE                      => true,
    ] + Config::Get('router.uri')
);

return $aConfig;
