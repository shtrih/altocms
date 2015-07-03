<?php
/**
 * Конфиг
 */

$config = array(
    // скрывать ли текст одобренных нсфв-топиков от неавторизованных юзеров
    'hide_nsfw_topics_4guests' => true,
);

// Переопределить имеющуюся переменную в конфиге:
// Переопределение роутера на наш новый Action - добавляем свой урл  http://domain.com/customtemplates
// Config::Set('router.page.customtemplates', 'PluginCustomtemplates_ActionCustomtemplates');

// Добавить новую переменную:
// $config['per_page'] = 15;
// Эта переменная будет доступна в плагине как Config::Get('plugin.customtemplates.per_page')

return $config;
