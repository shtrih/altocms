<?php

$config['widgets'][] = array(
    'name'     => 'unattached.tpl',
    'group'    => 'mfu-after-file-list',
    'priority' => 0,
    'plugin'   => 'multiplefileupload',
    'action'   => array(
        'content' => array('add', 'edit'),
    ),
);
