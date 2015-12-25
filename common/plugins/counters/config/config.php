<?php
/*---------------------------------------------------------------------------
 * @Project: Alto CMS
 * @Project URI: http://altocms.com
 * @PluginId: counters
 * @PluginName: Counters
 * @Description: Counters for topics views
 * @Copyright: Alto CMS Team
 * @License: GNU GPL v2 & MIT
 *----------------------------------------------------------------------------
 */

/**
 * @package plugin Counters
 */

$config['show_topic'] = array(
    'enable' => true,
    'ignore' => array(
        // не считать просмотры админов
        //'admin',
        // не считать просмотры автора топика
        'owner',
        // игнорировать, если User-Agent содержит подстроку
        'agent' => array('Googlebot', 'YandexBot', 'yandex.com/bots', 'StackRambler', 'Yahoo!', 'Mail.RU_Bot', 'msnbot', 'bingbot'),
    ),
    // просмотр засчитывается только один раз за сессию
    'check_session' => true,
);

// EOF