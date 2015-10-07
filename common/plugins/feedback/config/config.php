<?php
/**
 * Конфиг
 */

$config = array();

// Переопределить имеющуюся переменную в конфиге:
// Переопределение роутера на наш новый Action - добавляем свой урл  http://domain.com/feedback
// Config::Set('router.page.feedback', 'PluginFeedback_ActionFeedback');

// Добавить новую переменную:
// $config['per_page'] = 15;
// Эта переменная будет доступна в плагине как Config::Get('plugin.feedback.per_page')


static $sAction = 'feedback';
Config::Set('router.page.' . $sAction, 'PluginFeedback_ActionFeedback');

//$aWebpaths = Engine::Module('PluginFeedback_ModuleFeedback')->getFeedbackPaths();
$aConfigWebpaths = Config::ReadPluginConfig('feedback', 'webpaths');
$aUriRules = [];
foreach ($aConfigWebpaths as $iFeedbackId => $sWebpath) {
    $aUriRules['[~^'.preg_quote(trim($sWebpath, '/'), '~').'$~iu]'] = $sAction . '/' . $iFeedbackId;
}

if ($aUriRules) {
    $aOldUriRules = Config::Get('router.uri');
    $aUriRules[Config::KEY_REPLACE] = true;
    Config::Set('router.uri', $aUriRules + $aOldUriRules);
}

return $config;
