CREATE TABLE IF NOT EXISTS `vkwallget` (
  `topic_id` int(10) unsigned NOT NULL,
  `vk_post_id` int(10) unsigned NOT NULL,
UNIQUE (
  `topic_id` ,
  `vk_post_id`
)
) ENGINE=InnoDB;
