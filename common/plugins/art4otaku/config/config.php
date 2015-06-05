<?php
/**
 * Created by PhpStorm.
 * User: shtrih
 * Date: 08.12.14
 * Time: 17:49
 */
Config::Set('block.rule_art4otaku', array(
	'action' => array(
		'index'
	),
	'blocks' => array(
		'right' => array(
			'last' => array(
				'params' => array('plugin' => 'art4otaku'),
				'priority' => 51
			)
		)
	),
));
Config::Set('art4otaku.slider.limit', 12);
