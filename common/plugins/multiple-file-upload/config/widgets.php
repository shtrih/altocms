<?php

$config['widgets']['unattached.tpl'] = array(
    'name'     => 'widgets/widget.unattached.tpl',
    'group'    => 'mfu-after-file-list',
    'priority' => 0,
    'plugin'   => 'multiplefileupload',
    'action'   => [
        'content' => ['add', 'edit'],
    ],
);
