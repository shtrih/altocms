<?php
/*
 * Project Name: Multiple File Upload
 * Copyright © 2015 shtrih
 */

$aConfig = array(
    // Сколько файлов показывать до ссылки «Показать остальные».
    'files-show-count' => 3,

    /* Прятать прямые ссылки на файлы.
     *
     * Если true, то ссылки на файлы имеют вид «/multiplefileupload/get/45»,
     *    иначе прямые вида «/uploads/files/00/00/01/2015/08/12/0u73b903ac-78531e29-486b6694.zip»
     */
    'hide-direct-links' => false,

    /* Отдавать файл браузеру напрямую, без диалога сохранения файла. Используется только если 'hide-direct-links' => true.
     *
     * Файлы, размер которых меньше указанного значения, будут отдаваться без заголовка «Content-Disposition: attachment; filename=""»,
     *    что позволит браузеру самому решать, как открыть этот файл: отобразить на экране (если это картинка) или предложить открыть в какой-то программе.
     */
    'attachment-header-max-file-size' => 15 * 1024 * 1024, // 15 * 1024 * 1024 = 15 Мб

    // Начинать загрузку файла сразу после добавления.
    'auto-upload' => false,

    /* Расширения файлов, которые можно прикреплять к топикам.
     * Закомментируйте настройку, чтобы использовать набор расширений, которые указаны в основном конфиге сайта (['module']['uploader']['files']['default'])
     */
    'accept-file-types' => array('zip', 'rar', 'gz', '7z')
);

// Настройки загружаемых файлов
$aConfig[Config::KEY_ROOT]['module']['uploader']['files']['multiple-file-upload'] = array(

    /* Максимальный размер загружаемого файла. Установка нулевого размера отключает лимит.
     * Чтобы использовать значение основного конфига сайта, закомментируйте эту настройку.
     */
    'file_maxsize'    => '10Mb', // Kb, Mb, Gb

    /* Максимальный размер файла, загружаемого по ссылке.
     * Чтобы отключить загрузку по ссылке, пропишите 0 или закомментируйте настройку.
     */
    'url_maxsize'     => '10Mb',


    // Дальше ничего не трогать.
    Config::KEY_EXTENDS => '___module.uploader.files.default___',
    Config::KEY_RESET => true,
    'file_extensions' => empty($aConfig['accept-file-types']) ? array() : $aConfig['accept-file-types'],
);

return $aConfig;
