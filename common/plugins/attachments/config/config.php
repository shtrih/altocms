<?php
//
//  Attachments plugin
//  (P) Rafrica.net Studio, 2010 - 2012
//  http://we.rafrica.net/
//

$config = array ();

//
// --- создание топика ---
//

// место (положение) формы загрузки файлов, возможные значения "before_text" (над полем набора текста), "sidebar" (в сайдбаре)
$config ['AttachmentsFileFormPlace'] = 'before_text';

// разрешенные для загрузки расширения файлов
// оставить пустым если разрешается загружать ЛЮБЫЕ файлы или заполнить расширениями файлов, прописанными через ПРОБЕЛ ("zip tag jpg gif txt torrent")
$config ['valid_extensions'] = 'jpg jpeg png gif mp3 7z zip rar txt doc docx odt pdf torrent';

// показывать или нет прикрепленные к топику файлы обычным пользователям
// детальнее - а файле features.txt
$config ['ShowAttachedFiles'] = true;

// показывать или нет прикрепленные к топику файлы администраторам
// детальнее - а файле features.txt
$config ['ShowAttachedFilesForAdmins'] = true;

//
// --- загрузка и контроль файлов ---
//

// каталог загрузки и хранения файлов
// создайте каталог и поставьте 777 права
$config ['uploads_dir'] = '/uploads/files';

// Максимальный размер файла для загрузки
// Не забывайте что сам сервер также имеет ограничение на размер файла
// Чтобы посмотреть настройки вашего сервера - читайте что пишет плагин при активации
$config ['max_filesize_limit'] = 2048 * 1024 * 1024; // 500 Mbytes

// максимальное количество файлов на топик
$config ['max_files_per_topic'] = 500;

// максимальное количество файлов не прикрепленных ни к одному топику для пользователя
// не должно быть меньше значения 'max_files_per_topic'
// примечание: такое возможно если пользователь загрузил файлы в новый топик, но не сохранил его.
// такие файлы остаются на сервере и выдаются списком в сайдбаре, напоминающим о необходимости сделать что-то с этими файлами (удалить, прикрепить)
$config ['max_unattached_files_per_user'] = 12;

// минимальный рейтинг пользователя для добавления файлов в топик
$config ['min_rating_to_post_files'] = 0;

//
// --- дальше трогать ничего не надо ---
//

$config ['debug_mode'] = false;

$config ['url'] = 'attachments';

Config::Set('router.page.' . $config ['url'], 'PluginAttachments_ActionAttachments');

Config::Set('block.rule_topic_attachments',array(
	'action'  => array( 
//		'link'     => array('add','edit'),
//		'question' => array('add','edit'),
		'topic'    => array('add','edit'),
//		'photoset'    => array('add','edit')
	),
	'blocks'  => array( 'right' => array('attachments'=>array('params'=>array('plugin'=>'attachments')),'unlinked'=>array('params'=>array('plugin'=>'attachments'))) ),
	));
return $config;
