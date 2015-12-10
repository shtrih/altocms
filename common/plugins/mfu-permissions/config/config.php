<?php
/**
 * Конфиг
 */

/* Переопределить имеющуюся переменную в конфиге:
 *    Переопределение роутера на наш новый Action: добавляем свой урл  http://domain.com/mfu-permissions
 *      $aConfig['$root$']['router']['page']['mfu-permissions'] = 'PluginMfuPermissions_ActionMfuPermissions';
 *      или Config::Set('router.page.mfu-permissions', 'PluginMfuPermissions_ActionMfuPermissions');
 *
 *
 * Добавить новую переменную:
 *    $aConfig['per_page'] = 15;
 *    Эта переменная будет доступна в плагине как Config::Get('plugin.mfu-permissions.per_page')
 */

$aConfig = array(

);

return $aConfig;
