<?php
/**
 * Created by PhpStorm.
 * User: shtrih
 * Date: 26.10.13
 * Time: 23:34
 */

spl_autoload_register(function ($class) {
	$folders = explode('\\', $class);

	if ($folders[0] === 'Client4otaku')
		array_shift($folders);

	$className = array_pop($folders);
	$className = preg_split('/(?<!^)(?=[A-Z])/', $className);
	$folders = array_merge($folders, $className);

	include_once(join(DIRECTORY_SEPARATOR, $folders) . '.php');
});
