<?php
/**
 * Конфиг
 */

$config = array(
    // The percentage of pixelation to perform, a value between 0 and 1
    'value' => 0.08,
    // Reveal the image on hover and remain revealed if clicked
    'reveal' => 'false',
    // Reveal the image on click. When combined with reveal, it will remain revealed after being clicked.
    'revealonclick' => 'false'
);

// Переопределить имеющуюся переменную в конфиге:
// Переопределение роутера на наш новый Action - добавляем свой урл  http://domain.com/pixelatensfw
// Config::Set('router.page.pixelatensfw', 'PluginPixelatensfw_ActionPixelatensfw');

// Добавить новую переменную:
// $config['per_page'] = 15;
// Эта переменная будет доступна в плагине как Config::Get('plugin.pixelatensfw.per_page')

return $config;
