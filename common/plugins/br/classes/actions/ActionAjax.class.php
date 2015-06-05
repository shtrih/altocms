<?php

/**
 * ActionAjax
 * Файл экшена плагина Br
 *
 * @author      Андрей Воронов <andreyv@gladcode.ru>
 * @copyrights  Copyright © 2014, Андрей Воронов
 *              Является частью плагина Br
 * @version     0.0.1 от 10.11.2014 13:05
 */
class PluginBr_ActionAjax extends PluginBr_Inherit_ActionAjax {

    /**
     * Абстрактный метод инициализации экшена
     *
     */
    public function Init() {
        parent::Init();
    }

    /**
     * Абстрактный метод регистрации евентов.
     * В нём необходимо вызывать метод AddEvent($sEventName,$sEventFunction)
     * Например:
     *      $this->AddEvent('index', 'EventIndex');
     *      $this->AddEventPreg('/^admin$/i', '/^\d+$/i', '/^(page([1-9]\d{0,5}))?$/i', 'EventAdminBlog');
     */
    protected function RegisterEvent() {
        parent::RegisterEvent();
        $this->AddEvent('branding-remove-blog', 'BrandingRemoveBlog');
        $this->AddEvent('branding-save-blog', 'BrandingSaveBlog');
    }

    public function BrandingRemoveBlog() {

        // Пришли ли настройки
        $aOptions = getRequest('options', FALSE);
        if (!$aOptions) {
            return;
        }

        if (!isset($aOptions['brandingTargetId'])) {
            return;
        }
        if (!isset($aOptions['brandingTargetType']) && in_array($aOptions['brandingTargetType'], array('blog-branding'))) {
            return;
        }

        /** @var PluginBr_Inherit_ModuleBlog_EntityBlog $oBlog */
        if (!$oBlog = $this->Uploader_CheckAccessAndGetTarget($aOptions['brandingTargetType'], $aOptions['brandingTargetId'])) {
            return;
        }

        /** @var PluginBr_ModuleBr_EntityBranding $oBranding */
        $oBranding = $oBlog->getBranding();
        if ($oBranding) {

            $oBranding->Delete();

        }


        $this->Message_AddNoticeSingle($this->Lang_Get('plugin.branding.blog_removed'), $this->Lang_Get('attention'));
    }

    public function BrandingSaveBlog() {

        // Пришли ли настройки
        $aOptions = getRequest('options', FALSE);
        if (!$aOptions) {
            return;
        }

        if (!isset($aOptions['brandingTargetId'])) {
            return;
        }
        if (!isset($aOptions['brandingTargetType']) && in_array($aOptions['brandingTargetType'], array('blog-branding'))) {
            return;
        }

        /** @var PluginBr_Inherit_ModuleBlog_EntityBlog $oBlog */
        if (!$oBlog = $this->Uploader_CheckAccessAndGetTarget($aOptions['brandingTargetType'], $aOptions['brandingTargetId'])) {
            return;
        }

        /** @var PluginBr_ModuleBr_EntityBranding $oBranding */
        $oBranding = $oBlog->getBranding();
        if ($oBranding) {

            $oBranding->setBrandingBackground(isset($aOptions['brandingBackground']) ? $aOptions['brandingBackground'] : NULL);
            $oBranding->setBrandingOpacity(isset($aOptions['brandingOpacity']) ? $aOptions['brandingOpacity'] : 100);
            $oBranding->setBrandingBackgroundColor(isset($aOptions['brandingBackgroundColor']) ? hexdec($aOptions['brandingBackgroundColor']) : NULL);
            $oBranding->setBrandingFontColor(isset($aOptions['brandingFontColor']) ? hexdec($aOptions['brandingFontColor']) : NULL);
            $oBranding->setBrandingHeaderColor(isset($aOptions['brandingHeaderColor']) ? hexdec($aOptions['brandingHeaderColor']) : NULL);
            $oBranding->setBrandingHeaderStep(isset($aOptions['brandingHeaderStep']) ? $aOptions['brandingHeaderStep'] : NULL);
            $oBranding->setBrandingUseBackgroundColor((isset($aOptions['brandingUseBackgroundColor']) && $aOptions['brandingUseBackgroundColor'] == "true") ? 1 : NULL);
            $oBranding->setBrandingBackgroundType(isset($aOptions['brandingBackgroundType']) ? $aOptions['brandingBackgroundType'] : 0);
            $oBranding->Update();

        } else {

            /** @var PluginBr_ModuleBr_EntityBranding $oBranding */
            $oBranding = Engine::GetEntity('PluginBr_ModuleBr_EntityBranding', array(
                'branding_target_id'            => $oBlog->getId(),
                'branding_target_type'          => $aOptions['brandingTargetType'],
                'branding_user_id'              => E::UserId(),
                'branding_background'           => isset($aOptions['brandingBackground']) ? $aOptions['brandingBackground'] : NULL,
                'branding_opacity'              => isset($aOptions['brandingOpacity']) ? $aOptions['brandingOpacity'] : 100,
                'branding_background_color'     => isset($aOptions['brandingBackgroundColor']) ? hexdec($aOptions['brandingBackgroundColor']) : NULL,
                'branding_font_color'           => isset($aOptions['brandingFontColor']) ? hexdec($aOptions['brandingFontColor']) : NULL,
                'branding_header_color'         => isset($aOptions['brandingHeaderColor']) ? hexdec($aOptions['brandingHeaderColor']) : NULL,
                'branding_header_step'          => isset($aOptions['brandingHeaderStep']) ? $aOptions['brandingHeaderStep'] : NULL,
                'branding_use_background_color' => (isset($aOptions['brandingUseBackgroundColor']) && $aOptions['brandingUseBackgroundColor'] == "true") ? 1 : NULL,
                'branding_background_type'      => isset($aOptions['brandingBackgroundType']) ? $aOptions['brandingBackgroundType'] : 0,
            ));

            $oBranding->Add();

        }


        $this->Message_AddNoticeSingle($this->Lang_Get('plugin.branding.blog_saved'), $this->Lang_Get('attention'));
    }
}