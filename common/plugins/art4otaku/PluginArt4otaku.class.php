<?php
/**
 * Created by PhpStorm.
 * User: shtrih
 * Date: 08.12.14
 * Time: 16:35
 */
class PluginArt4otaku extends Plugin {
	/**
	 * Активация плагина
	 */
	public function Activate() {
		return true;
	}

	/**
	 * Инициализация плагина
	 */
	public function Init() {
		$sPath = Plugin::GetTemplatePath(__CLASS__);
		$this->Viewer_AppendScript($sPath . 'js/jquery.jcarousellite-1.0.1.js');
		$this->Viewer_AppendStyle($sPath . 'css/style.css');
	}

	/**
	 * Деактивация плагина
	 * В принципе, тут тоже ничего не нужно делать
	 */
	public function Deactivate() {
		return true;
	}
}