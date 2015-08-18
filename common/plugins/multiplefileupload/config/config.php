<?php
/*
 * 
 * Project Name : Multiple File Upload
 * Copyright © 2015 shtrih. All rights reserved.
 * License: GNU GPL v2, http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 *
 */

$aConfig = array(
    // Сколько файлов показывать до ссылки «Показать остальные»
    'files-show-count' => 3,

    /* Прятать прямые ссылки на файлы.
     *
     * Если true, то ссылки на файлы имеют вид «/multiplefileupload/get/45»,
     *    иначе прямые вида «/uploads/mfu-files/00/00/01/2015/08/12/0u73b903ac-78531e29-486b6694.zip»
     */
    'hide-direct-links' => false,

    /* Отдавать файл браузеру напрямую, без диалога сохранения файла.
     *
     * Файлы, размер которых меньше указанного значения, будут отдаваться без заголовка «Content-Disposition: attachment; filename=""»,
     *    что позволит браузеру самому решать, как открыть этот файл: отобразить на экране (если это картинка) или предложить открыть в какой-то программе.
     */
    'attachment-header-max-file-size' => 15 * 1024 * 1024, // 15 Mb

    // Начинать загрузку файла сразу после добавления.
    'auto-upload' => false,
);


// Настройки загружаемых файлов
$aConfig['$root$']['module']['uploader']['files']['multiple-file-upload'] = array(
    '$extends$' => '___module.uploader.files.default___',
    // максимальный размер загружаемого файла
    'file_maxsize'    => '10Kb',
    'url_maxsize'     => '10Kb',
    //расширения файлов, которые можно прикреплять к топикам
    'file_extensions' => array(
        'zip','rar','gz','mp3',
        //'doc', 'docx', 'xls', 'xlsx', 'pdf','djv','djvu',
        //'gif', 'png', 'jpg', 'jpeg',
    ),
);

return $aConfig;
