<?php
/**
 * Created by PhpStorm.
 * User: shtrih
 * Date: 26.10.13
 * Time: 15:43
 */
include_once './Client4otaku/autoloader.php';

$artList = new Client4otaku\ReadArtList();
$artList
	->setSortBy('random')
	->setPerPage(1)
	->getFilter()
		->is(Client4otaku\FilterList::FILTER_NAME_USER_ID, 9)
		->not(Client4otaku\FilterList::FILTER_NAME_TAG, 'nsfw')
;

try {
	$response = $artList->getResponse();
	var_dump($response);
}
catch (\RequestException $e) {
	var_dump($e->getMessage());
}
catch (\Client4otaku\ReadException $e) {
	var_dump($e->getMessage());
}

