<?php
/**
 * Конфиг
 */

/* Переопределить имеющуюся переменную в конфиге:
 *    Переопределение роутера на наш новый Action: добавляем свой урл  http://domain.com/legacy-webm
 *      $aConfig['$root$']['router']['page']['legacy-webm'] = 'PluginLegacyWebm_ActionLegacyWebm';
 *      или Config::Set('router.page.legacy-webm', 'PluginLegacyWebm_ActionLegacyWebm');
 *
 *
 * Добавить новую переменную:
 *    $aConfig['per_page'] = 15;
 *    Эта переменная будет доступна в плагине как Config::Get('plugin.legacy-webm.per_page')
 */

$aConfig = array(

);

return $aConfig;
