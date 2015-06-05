<?php
if (!class_exists('Plugin')) {
	die('');
}

/**
 * Created by PhpStorm.
 * User: shtrih
 * Date: 11.05.14
 * Time: 17:38
 *
 * Плагин предназначен для репостинга сообщений со стены вконтакте
 */
class PluginVkwallget extends Plugin {
	/**
	 * Активация плагина
	 */
	public function Activate() {
		$this->ExportSQL(dirname(__FILE__).'/sql_dumps/dump.sql');
		return true;
	}

	/**
	 * Инициализация плагина
	 */
	public function Init() {
	}

	/**
	 * Деактивация плагина
	 * В принципе, тут тоже ничего не нужно делать
	 */
	public function Deactivate() {
		return true;
	}
}