<?php

/* ---------------------------------------------------------------------------
 * @Plugin Name: Greeting
 * @Plugin Id: greeting
 * @Plugin URI:
 * @Description: 
 * @Author: stfalcon-studio
 * @Author URI: http://stfalcon.com
 * @LiveStreet Version: 0.4.2
 * @License: GNU GPL v2, http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 * ----------------------------------------------------------------------------
 */

$config = array();

// id юзера от которого будут приходить сообщения
$config['from_user_id'] = 1;

// имя страницы ссылка на которую будет подставляться в шаблон сообщения вместо %%url%%
$config['page_name']    = 'about';

// подставляется в Talk, необходио так же для непоказа автору рассылки писем без ответов
$config['ip_sender'] = '255.255.255.255';

return $config;
