<?php
/**
 * Конфиг
 */

$aConfig['action'] = 'feedback';
$aConfig['$root$.router.page.' . $aConfig['action']] = 'PluginFeedback_ActionFeedback';

$aConfigWebpaths = Config::ReadPluginConfig('feedback');
var_dump(Config::Get('router.uri'), $aConfigWebpaths);

return $aConfig;
