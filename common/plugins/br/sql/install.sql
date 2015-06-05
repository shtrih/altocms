-- ----------------------------------------------------------------------------------------------
-- install.sql
-- Файл таблиц баз данных плагина Br
--
-- @author      Андрей Воронов <andreyv@gladcode.ru>
-- @copyrights  Copyright © 2014, Андрей Воронов
--              Является частью плагина Br
-- @version     0.0.1 от 03.11.2014 01:59
-- ----------------------------------------------------------------------------------------------



CREATE TABLE `prefix_branding` (
  `branding_id`                   INT(11)                                                  NOT NULL AUTO_INCREMENT,
  `branding_target_id`            INT(11)                                                  NOT NULL,
  `branding_target_type`          ENUM('blog-branding', 'topic-branding', 'user-branding') NOT NULL,
  `branding_user_id`              INT(11)                                                  NOT NULL,
  `branding_background`           VARCHAR(200)                                             NULL,
  `branding_opacity`              INT(11)                                                  NULL,
  `branding_background_color`     INT(11)                                                  NULL,
  `branding_background_type`      TINYINT(1)                                               NULL,
  `branding_modify`               TINYINT(1)                                               NULL,
  `branding_use_background_color` TINYINT(1)                                               NULL,
  `branding_font_color`           INT(11)                                                  NULL,
  `branding_header_color`         INT(11)                                                  NULL,
  `branding_header_step`          INT(11)                                                  NULL,
  PRIMARY KEY (`branding_id`),
  UNIQUE KEY `branding_index` (`branding_target_id`, `branding_target_type`),
  KEY `user_index` (`branding_user_id`)
)
  ENGINE =MyISAM
  DEFAULT CHARSET =utf8
  AUTO_INCREMENT =1;