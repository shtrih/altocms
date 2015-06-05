<?php

/**
 * Blog.entity.class.php
 * Файл сущности для модуля Blog плагина Br
 *
 * @author      Андрей Воронов <andreyv@gladcode.ru>
 * @copyrights  Copyright © 2014, Андрей Воронов
 *              Является частью плагина Br
 * @version     0.0.1 от 03.11.2014 16:47
 */
class PluginBr_ModuleBlog_EntityBlog extends PluginBr_Inherit_ModuleBlog_EntityBlog {

    /**
     * Текущий брендинг блога
     * @var bool|PluginBr_ModuleBr_EntityBranding
     */
    protected $_oBranding = FALSE;
    protected $_brandingWasAsked = FALSE;

    /**
     * Будем запрашивать брендинг один раз, а все остальные запросы
     * кэшировать через приватное свойство {@see $_oBranding}
     */
    protected function _askBranding() {

        if (!$this->_brandingWasAsked) {
            $aBranding = $this->PluginBr_Br_GetBrandingItemsByFilter(array(
                'branding_target_id'   => $this->getId(),
                'branding_target_type' => 'blog-branding'
            ));

            if ($aBranding && count($aBranding) > 0) {
                $this->_oBranding = array_shift($aBranding);
            }
        }

        $this->_brandingWasAsked = TRUE;

    }

    /**
     * Обновляет брендинг у блога
     * @param bool|PluginBr_ModuleBr_EntityBranding $oBranding
     */
    public function UpdateBranding($oBranding = FALSE) {

        // Обновим через запрос, напрямую из БД
        if (!$oBranding) {
            $this->_brandingWasAsked = FALSE;
            $this->_askBranding();

            return;
        }

        // Или по переданному объекту
        $this->_oBranding = $oBranding;

    }

    /**
     * Получение фоновой картинки блога
     */
    public function getBackgroundImage() {

        $this->_askBranding();

        if (!$this->_oBranding) {
            return FALSE;
        }

        if ($this->_oBranding->getBackground()) {
            return $this->_oBranding->getBackground();
        }

        return FALSE;
    }

    /**
     * Получение фоновой картинки блога
     */
    public function getBackground() {

        return $this->getBackgroundImage();
    }

    /**
     * Получаем прозрачность фона
     *
     * @return bool
     */
    public function getOpacity() {
        $this->_askBranding();

        if (!$this->_oBranding) {
            return 100;
        }

        if ($this->_oBranding->getOpacity()) {
            return $this->_oBranding->getOpacity();
        }

        return 100;
    }


    public function getTargetId() {
        $this->_askBranding();

        if (!$this->_oBranding) {
            return FALSE;
        }

        if ($this->_oBranding->getTargetId()) {
            return $this->_oBranding->getTargetId();
        }

        return FALSE;
    }


    public function getTargetType() {
        $this->_askBranding();

        if (!$this->_oBranding) {
            return FALSE;
        }

        if ($this->_oBranding->getTargetType()) {
            return $this->_oBranding->getTargetType();
        }

        return FALSE;
    }


    public function getBackgroundColor() {
        $this->_askBranding();

        if (!$this->_oBranding) {
            return FALSE;
        }

        if (!is_null($this->_oBranding->getBackgroundColor())) {
//            return $this->_oBranding->getBackgroundColor();
            if ($this->_oBranding->getBackgroundColor()==="0") {
                return '#000000';
            }
            return '#' . dechex($this->_oBranding->getBackgroundColor());
        }

        return FALSE;
    }


    public function getUseBackgroundColor() {
        $this->_askBranding();

        if (!$this->_oBranding) {
            return FALSE;
        }

        if ($this->_oBranding->getUseBackgroundColor()) {
            return $this->_oBranding->getUseBackgroundColor();
        }

        return FALSE;
    }


    public function getFontColor() {
        $this->_askBranding();

        if (!$this->_oBranding) {
            return FALSE;
        }

        if ($this->_oBranding->getFontColor()) {
            return '#' . dechex($this->_oBranding->getFontColor());
        }

        return FALSE;
    }


    public function getHeaderColor() {
        $this->_askBranding();

        if (!$this->_oBranding) {
            return FALSE;
        }

        if ($this->_oBranding->getHeaderColor()) {
            return '#' . dechex($this->_oBranding->getHeaderColor());
        }

        return FALSE;
    }


    public function getHeaderStep() {
        $this->_askBranding();

        if (!$this->_oBranding) {
            return FALSE;
        }

        return (int)$this->_oBranding->getHeaderStep();
    }


    public function getBrandingTitle() {
        $this->_askBranding();

        if (!$this->_oBranding) {
            return FALSE;
        }

        if ($this->_oBranding->getBrandingTitle()) {
            return $this->_oBranding->getBrandingTitle();
        }

        return FALSE;
    }


    public function getBrandingDescription() {
        $this->_askBranding();

        if (!$this->_oBranding) {
            return FALSE;
        }

        if ($this->_oBranding->getBrandingDescription()) {
            return $this->_oBranding->getBrandingDescription();
        }

        return FALSE;
    }

    public function getBackgroundType() {
        $this->_askBranding();

        if (!$this->_oBranding) {
            return FALSE;
        }

        return $this->_oBranding->getBackgroundType();

        return FALSE;
    }


    /**
     * Получение текущего брендинга блога
     * @return bool|mixed|PluginBr_ModuleBr_EntityBranding
     */
    public function getBranding() {

        $this->_askBranding();

        return $this->_oBranding;

    }
}