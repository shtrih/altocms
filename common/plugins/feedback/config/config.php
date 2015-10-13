<?php
/**
 * Конфиг
 */

// Если массив пустой, то рассылается администраторам сайта (макс. 10). Можно перечислить id пользователей, которым должны приходить сообщения.
$aConfig['to-user-id'] = [];

$aConfig['from-user-id'] = 2;

// Отправлять ли уведомление на е-мейл.
$aConfig['email-notify'] = true;


$aConfig['action'] = 'feedback';
$aConfig['$root$']['router']['page'][$aConfig['action']] = 'PluginFeedback_ActionFeedback';

return $aConfig;
