<?php
$config	=	array();

// Массив задает разрешенные для незарегистрированного пользователя екшены / евенты
// В качестве ключа массива указывается необходимый роутер.
// В качестве значений - массив разрешенных евентов указанного роутера
// Если необходимо разрешить все евенты роутера, укажите пустой массив
// Смотрите примеры и не забудьте отключить родной "закрытый режим"

$config = array(
	// Разрешить всё, что указано (или разрешить все ивенты указанного действия — array())
	'allowedelements' => array( // если пустой массив, то всё разрешить
		/*
		'index' 		=> array('index')			,	// Главная страница
		'blog' 		=> array()			,
		'login'			=> array()			,	// Страница авторизации (не рекоммендую закрывать)
		'page'			=> array('about')	,	// Страница about модуля page
		'registration'	=> array()			,	// Страница регистрации (не рекоммендую закрывать)
		'rss'			=> array()	,	// Страница rss-потока
		'error'			=> array()			,	// Страница ошибки (не рекоммендую закрывать)
												// Ваши варианты
		*/
	),
	// Запретить все, что перечислено
	'disallowedelements' => array(
		'profile'		=> array(),
		'comments'		=> array(),
	)
);

return $config;