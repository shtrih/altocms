<?php

/**
 * https://vk.com/dev/wall.get
 */
$config['vk']['wallget']['api']['owner_id'] = '';
// из какой группы вк брать посты
$config['vk']['wallget']['api']['domain']   = 'moeshumi';
$config['vk']['wallget']['api']['offset']   = '';
$config['vk']['wallget']['api']['count']    = '10';
$config['vk']['wallget']['api']['filter']   = 'owner';
$config['vk']['wallget']['api']['extended'] = '';
$config['vk']['wallget']['api']['v']        = '5.21';

// в какой блог постить
$config['vk']['wallget']['to_blog_id'] = 854;
// от имени какого юзера постить
$config['vk']['wallget']['from_user_id'] = 801;
// отключить комментирование постов
$config['vk']['wallget']['forbid_comment'] = false;
// публиковать (иначе сохранение в черновики)
$config['vk']['wallget']['topic_publish'] = false;


return $config;
