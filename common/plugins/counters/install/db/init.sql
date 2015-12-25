--
-- Структура таблицы `prefix_counter`
--

CREATE TABLE IF NOT EXISTS `prefix_counter` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `target_type` varchar(30) NOT NULL,
  `target_id` int(10) unsigned NOT NULL,
  `saction` varchar(30) NOT NULL,
  `counter` bigint(20) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `target_type` (`target_type`,`target_id`),
  KEY `saction` (`saction`),
  KEY `counter` (`counter`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ;