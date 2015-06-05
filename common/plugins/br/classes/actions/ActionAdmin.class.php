<?php

/**
 * ActionAdmin.class.php
 * Файл экшена плагина Br
 *
 * @author      Андрей Воронов <andreyv@gladcode.ru>
 * @copyrights  Copyright © 2014, Андрей Воронов
 *              Является частью плагина Br
 * @version     0.0.1 от 11.11.2014 12:41
 */
class PluginBr_ActionAdmin extends PluginBr_Inherit_ActionAdmin {

    /**
     * Абстрактный метод регистрации евентов.
     * В нём необходимо вызывать метод AddEvent($sEventName,$sEventFunction)
     * Например:
     *      $this->AddEvent('index', 'EventIndex');
     *      $this->AddEventPreg('/^admin$/i', '/^\d+$/i', '/^(page([1-9]\d{0,5}))?$/i', 'EventAdminBlog');
     */
    protected function RegisterEvent() {
        parent::RegisterEvent();
        $this->AddEvent('branding', 'EventBranding');
    }

    /**
     * Админка брендирвоания
     */
    public function EventBranding() {

        $this->Viewer_Assign('sPageTitle', $this->Lang_Get('plugin.br.admin_title'));
        $this->Viewer_Assign('sMainMenuItem', 'content');
        $this->Viewer_AddHtmlTitle($this->Lang_Get('plugin.br.admin_title'));
        $this->SetTemplateAction('content/branding');

        if (getRequest('submit_branding')) {

            $aData = array();

            $aData['plugin.br.blog.allow_background'] = (bool)(getRequest('allow_background', FALSE));
            $aData['plugin.br.blog.allow_font'] = (bool)(getRequest('allow_font', FALSE));
            $aData['plugin.br.blog.allow_header'] = (bool)(getRequest('allow_header', FALSE));
            $aData['plugin.br.blog.allow_step'] = (bool)(getRequest('allow_step', FALSE));
            $aData['plugin.br.blog.allow_blog'] = (
                (bool)(getRequest('allow_background', FALSE)) ||
                (bool)(getRequest('allow_font', FALSE)) ||
                (bool)(getRequest('allow_header', FALSE)) ||
                (bool)(getRequest('allow_step', FALSE)));


            Config::WriteCustomConfig($aData);

            $_REQUEST['blog']['allow_background'] = $aData['plugin.br.blog.allow_background'];
            $_REQUEST['blog']['allow_font'] = $aData['plugin.br.blog.allow_font'];
            $_REQUEST['blog']['allow_header'] = $aData['plugin.br.blog.allow_header'];
            $_REQUEST['blog']['allow_step'] = $aData['plugin.br.blog.allow_step'];


            return FALSE;

        }

        $_REQUEST['blog'] = Config::Get('plugin.br.blog');


        return FALSE;

    }

}