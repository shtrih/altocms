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
        $sTemplateDir = Plugin::GetTemplateDir(__CLASS__);
        E::ModuleViewer()->AppendScript($sTemplateDir . 'js/jquery.jcarousellite-1.0.1.js');
        E::ModuleViewer()->AppendStyle($sTemplateDir . 'css/style.css');
	}

	/**
	 * Деактивация плагина
	 * В принципе, тут тоже ничего не нужно делать
	 */
	public function Deactivate() {
		return true;
	}
}