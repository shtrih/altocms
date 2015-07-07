SET @field_id := (SELECT `field_id` FROM `content_field` WHERE `field_unique_name` = 'nsfw');

INSERT INTO `content_values` (`id`, `target_id`, `target_type`, `field_id`, `field_type`, `value`, `value_source`)
SELECT  NULL, `topic_id`, 'topic', @field_id, 'checkbox', IF(`nsfw`, 'checked', ''), IF(`nsfw`, '1', '')
  FROM `topic`
  WHERE `topic_type` = 'topic';

DELETE FROM `content_values`
  WHERE `field_type` = 'checkbox' AND `value` = '';

UPDATE  `topic` SET  `topic_type` =  'nsfw_toggleable', `topic_publish_index` = IF(`approved`, 1, 0)
  WHERE  `topic_type` = 'topic';

UPDATE `content` SET `content_active` = 0 WHERE `content_id` = 1;