--
-- SQL, которые надо выполнить движку при активации плагина админом. Вызывается на исполнение ВРУЧНУЮ в /common/plugins/PluginAbcplugin.class.php в методе Activate()

CREATE TABLE IF NOT EXISTS `prefix_feedback_fields` (
  `field_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `feedback_id` int(11) NOT NULL,
  `field_unique_name` varchar(255) DEFAULT NULL,
  `field_sort` int(11) NOT NULL DEFAULT '0',
  `field_type` varchar(30) NOT NULL DEFAULT 'input',
  `field_name` varchar(50) NOT NULL,
  `field_description` varchar(255) NOT NULL,
  `field_options` text,
  `field_required` tinyint(1) NOT NULL DEFAULT '0',
  `field_postfix` text,
  PRIMARY KEY (`field_id`),
  UNIQUE KEY `feedback_id2` (`feedback_id`,`field_unique_name`),
  KEY `feedback_id` (`feedback_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `prefix_feedback` (
  `feedback_id` int(11) unsigned NOT NULL,
  `feedback_webpath` VARCHAR( 255 ) NOT NULL,
  `feedback_active` TINYINT( 1 ) NOT NULL DEFAULT  '0',
  `feedback_title` varchar(128) NOT NULL,
  `feedback_text` longtext NOT NULL,
  `feedback_text_source` longtext NOT NULL,
  `feedback_extra` text NOT NULL,
  PRIMARY KEY (`feedback_id`),
  UNIQUE KEY (`feedback_webpath`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT IGNORE INTO `prefix_feedback_fields` (`field_id`, `feedback_id`, `field_unique_name`, `field_sort`, `field_type`, `field_name`, `field_description`, `field_options`, `field_required`, `field_postfix`) VALUES
(1, 1, NULL, 3, 'input', 'Ваш e-mail', 'Чтобы мы могли связаться с вами', NULL, 1, NULL),
(2, 1, NULL, 2, 'select', 'Тема', 'Выберите наиболее близкую тему', 'a:1:{s:6:"select";s:108:"Выберите вариант\r\nНашёл ошибку\r\nПредлагаю\r\nПомогите\r\nДругое";}', 0, NULL),
(3, 1, NULL, 1, 'textarea', 'Текст сообщения', '', NULL, 1, NULL);

INSERT IGNORE INTO `prefix_feedback` (`feedback_id`, `feedback_title`, `feedback_text`, `feedback_text_source`, `feedback_extra`, `feedback_webpath`, `feedback_active`) VALUES
(1, 'Обратиться к администрации', 'Детально опишите вашу проблему. Если хотите получить ответ, заполните поле «е-мейл».', 'Детально опишите вашу проблему. Если хотите получить ответ, заполните поле «е-мейл».', '', '/feedback', 0);
