<?php

class PluginBetterspoilers_ModuleText extends PluginBetterspoilers_Inherit_ModuleText {

    /**
     * Загружает конфиг Jevix'а
     *
     * @param string $sType     Тип конфига
     * @param bool   $bClear    Очищать предыдущий конфиг или нет
     */
    public function LoadJevixConfig($sType = 'default', $bClear = true) {
        parent::LoadJevixConfig($sType, $bClear);
        if ('default' == $sType) {
            $this->oJevix->cfgAllowTags(array('spoiler', 'hide'));
            $this->oJevix->cfgAllowTagParams('spoiler', array('name'));
        }
    }
}