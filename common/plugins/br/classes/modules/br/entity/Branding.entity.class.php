<?php

/**
 * Br.entity.class.php
 * Файл сущности для модуля Br плагина Br
 *
 * @author      Андрей Воронов <andreyv@gladcode.ru>
 * @copyrights  Copyright © 2014, Андрей Воронов
 *              Является частью плагина Br
 * @version     0.0.1 от 03.11.2014 01:59
 */
class PluginBr_ModuleBr_EntityBranding extends EntityORM {

    protected $aRelations = array(
        'user' => array(EntityORM::RELATION_TYPE_BELONGS_TO, 'ModuleUser_EntityUser', 'branding_user_id'),
    );

    public function toRgb($hex) {

        if ($hex == '#000000') {
            return array('r' => 0, 'g' => 0, 'b' => 0);
        }

        if (is_string($hex)) {
            $hex = (int)$hex;
        }

        if (is_int($hex)) {
            $hex = '#' . dechex($hex);
        }

        list($r, $g, $b) = sscanf($hex, "#%02x%02x%02x");

        return array('r' => $r, 'g' => $g, 'b' => $b);
    }

    // Полный вызов методов получения свойств
    public function getBrandingId() { return $this->_aData['branding_id']; }
    public function getBrandingTargetId() { return $this->_aData['branding_target_id']; }
    public function getBrandingTargetType() { return $this->_aData['branding_target_type']; }
    public function getBrandingUserId() { return $this->_aData['branding_user_id']; }
    public function getBrandingBackground() { return $this->_aData['branding_background']; }
    public function getBrandingOpacity() { return $this->_aData['branding_opacity']; }
    public function getBrandingBackgroundColor() {
        if ($this->_aData['branding_background_color']==="0") {
            return '#000000';
        }

        return $this->_aData['branding_background_color'];
    }
    public function getBrandingUseBackgroundColor() { return $this->_aData['branding_use_background_color']; }
    public function getBrandingFontColor() { return ($this->_aData['branding_font_color']?$this->_aData['branding_font_color']:hexdec('#000000')); }
    public function getBrandingHeaderColor() { return $this->_aData['branding_header_color']?$this->_aData['branding_header_color']:hexdec('000000'); }
    public function getBrandingHeaderStep() { return $this->_aData['branding_header_step']?$this->_aData['branding_header_step']:0; }
    public function getBrandingBackgroundType() { return $this->_aData['branding_background_type']?$this->_aData['branding_background_type']:0; }

    // Сокращенный вызов методов получения свойств
    public function getId() { return $this->_aData['branding_id']; }
    public function getTargetId() { return $this->_aData['branding_target_id']; }
    public function getTargetType() { return $this->_aData['branding_target_type']; }
    public function getUserId() { return $this->_aData['branding_user_id']; }
    public function getBackground() { return $this->_aData['branding_background']; }
    public function getOpacity() { return $this->_aData['branding_opacity']; }
    public function getBackgroundColor() {
        if ($this->_aData['branding_background_color']==="0") {
            return '#000000';
        }

        return $this->_aData['branding_background_color'];
    }
    public function getUseBackgroundColor() { return $this->_aData['branding_use_background_color']; }
    public function getFontColor() { return $this->_aData['branding_font_color']?$this->_aData['branding_font_color']:hexdec('#000000'); }
    public function getHeaderColor() { return $this->_aData['branding_header_color']?$this->_aData['branding_header_color']:hexdec('000000'); }
    public function getHeaderStep() { return $this->_aData['branding_header_step']?$this->_aData['branding_header_step']:0; }
    public function getBackgroundType() { return $this->_aData['branding_background_type']?$this->_aData['branding_background_type']:0; }

}