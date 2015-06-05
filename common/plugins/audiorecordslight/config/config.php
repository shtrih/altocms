<?php
/*
	Audiorecordslight plugin
	(P) PSNet, 2008 - 2013
	http://psnet.lookformp3.net/
	http://livestreet.ru/profile/PSNet/
	https://catalog.livestreetcms.com/profile/PSNet/
	http://livestreetguide.com/developer/PSNet/
*/

//
// --- Базовые настройки ---
//

// Получать ли данные о загруженных (прикрепленных) файлах от плагина Attachments (http://livestreetcms.com/addons/view/79/)
$config ['Plugin_Attachments_Mode'] = true;

// Получать данные методом анализа текста топика на предмет наличия в нем ссылок на mp3 файлы
$config ['Parse_Topic_Text_Mode'] = true;

// Разрешить ли загрузку файлов на сервер средствами плагина Audiorecordslight
$config ['Allow_Files_Uploads'] = false;

//
// --- Загрузка файлов ---
//

// Максимально допустимый размер файла (по-умолчанию 8Mb)
$config ['Max_File_Size'] = 8 * 1024 * 1024; // bytes

//
// --- Системные настройки ---
//

//
// --- flash player config ---
//

$config ['Flash_Player_Width'] = "250";
$config ['Flash_Player_Height'] = "40";
$config ['Flash_Player_Filename'] = "http://playmp3.org.ua/playmp3/rafrisimplewhite.swf";
$config ['Flash_Player_Flashvars_AudioParam'] = "filename";

//
// --- adding new allowed tag
//

$config ['Tag_Name'] = 'mp3';
/*
// add new tag
$aTagsAllowed = Config::Get ('jevix.default.cfgAllowTags');
$aTagsAllowed [0][0][] = $config ['Tag_Name'];																	// tag to allow
Config::Set ('jevix.default.cfgAllowTags', $aTagsAllowed);

// new tag attributes
$aAttrsAllowed = Config::Get ('jevix.default.cfgAllowTagParams');
$aAttrsAllowed [] = array (
	$config ['Tag_Name'],																											 // tag name
	array (																																		 // list of allowed attributes
		'src' => '#text',
		'title' => '#text',
	)
);
Config::Set ('jevix.default.cfgAllowTagParams', $aAttrsAllowed);

// allow tag to be empty
$aTagsAllowed = Config::Get ('jevix.default.cfgSetTagIsEmpty');
$aTagsAllowed [0][0][] = $config ['Tag_Name'];																	// tag to allow
Config::Set ('jevix.default.cfgSetTagIsEmpty', $aTagsAllowed);
*/
// ---

$config ['url'] = 'audiorecordslight';
$config ['$root$']['router']['page'][$config ['url']] = 'PluginAudiorecordslight_ActionAudiorecordslight';

return $config;
