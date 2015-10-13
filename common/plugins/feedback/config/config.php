<?php
/**
 * Конфиг
 */

$aConfig['action'] = 'feedback';
$aConfig['$root$.router.page.' . $aConfig['action']] = 'PluginFeedback_ActionFeedback';

return $aConfig;
