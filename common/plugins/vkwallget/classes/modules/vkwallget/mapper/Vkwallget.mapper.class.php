<?php

class PluginVkwallget_ModuleVkwallget_MapperVkwallget extends Mapper {

	public function GetPostExists($iVKPostId) {
		return sizeof((array)$this->oDb->selectRow("SELECT 42 FROM `vkwallget` WHERE `vk_post_id` = ?d", $iVKPostId));
	}

	public function AddRelation($iTopicId, $iVKPostId) {
		return $this->oDb->query(
			"INSERT INTO `vkwallget` (`topic_id`, `vk_post_id`) VALUES (?d, ?d)",
			$iTopicId,
			$iVKPostId
		);
	}
}