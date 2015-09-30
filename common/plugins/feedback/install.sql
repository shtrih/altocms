--
-- SQL, которые надо выполнить движку при активации плагина админом. Вызывается на исполнение ВРУЧНУЮ в /common/plugins/PluginAbcplugin.class.php в методе Activate()
-- Например:

-- CREATE TABLE IF NOT EXISTS `prefix_tablename` (
--  `page_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
--  `page_pid` int(11) unsigned DEFAULT NULL,
--  PRIMARY KEY (`page_id`),
--  KEY `page_pid` (`page_pid`),
-- ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

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

INSERT INTO `prefix_feedback` (`feedback_id`, `feedback_title`, `feedback_text`, `feedback_text_source`, `feedback_extra`, `feedback_webpath`, `feedback_active`)
VALUES ('1', 'no', 'no', 'no', '', 'no', '0');
