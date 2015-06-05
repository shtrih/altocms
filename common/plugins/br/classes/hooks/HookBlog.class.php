<?php

/**
 * Hook.br.class.php
 * Файл хука плагина Br
 *
 * @author      Андрей Воронов <andreyv@gladcode.ru>
 * @copyrights  Copyright © 2014, Андрей Воронов
 *              Является частью плагина Br
 * @version     0.0.1 от 03.11.2014 01:59
 */
class PluginBr_HookBlog extends Hook {


    /**
     * Регистрация хуков
     */
    public function RegisterHook() {

        // Установка фона на блог //layout_head_end
        $this->AddHook('template_layout_head_begin', 'InitActionBlogAfter');
        $this->AddHook('blog_collective_show', 'SetBlogBackgroundList');
        $this->AddHook('topic_show', 'SetBlogBackgroundTopic');
    }

    /**
     * Если выполняется экшен блога, то установим фон этой страницы
     */
    public function InitActionBlogAfter() {

        if (Config::Get('plugin.br.user.allow_user') && in_array(Router::GetAction(), array('settings', 'profile', 'talk'))) {

            $oUser = FALSE;
            if (Router::GetAction() == 'settings' || Router::GetAction() == 'talk') {
                $oUser = E::User();
            } else {
                if (preg_match('/^(id|login)\-(.+)$/i', Router::GetActionEvent(), $aMatches)) {
                    if ($aMatches[1] == 'id') {
                        $oUser = $this->User_GetUserById($aMatches[2]);
                    } else {
                        $oUser = $this->User_GetUserByLogin($aMatches[2]);
                    }
                } else {
                    $oUser = $this->User_GetUserByLogin(Router::GetActionEvent());
                }
            }

            if ($oUser)
                $this->Hook_AddExecFunction(
                    'template_layout_body_end',             // название хука
                    array($this, 'SetBlogBackground'),      // метод этого класса, который будет выводить скрипт
                    -100,                                   // приоритет хука - самый низкий, хотя и не принципиально
                    array('template' => array('user' => $oUser))
                );
        }

        if (!in_array(Router::GetActionEvent(), array(
            'admin',
            'edit',
        ))
        ) return;

        $this->Hook_AddExecFunction(
            'template_layout_body_begin',           // название хука
            array($this, 'SetBlogBackground'),      // метод этого класса, который будет выводить скрипт
            -100                                    // приоритет хука - самый низкий, хотя и не принципиально
        );

    }

    /**
     *  Установка фона страницы
     *
     * @return string|void
     */
    public function SetBlogBackground($data) {
        if (!isset($data['template'])) {
            $sBlogId = Router::GetParam(0);
            /** @var PluginBr_ModuleBlog_EntityBlog $oBlog */
            if (!$oBlog = $this->Blog_GetBlogById($sBlogId)) {
                return;
            }

        } else {
            $oBlog = FALSE;
            if (isset($data['template']['blog'])) {
                $oBlog = $data['template']['blog'];
            }

        }

        // Локальный вьювер
        /** @var ModuleViewer $oLocalViewer */
        $oLocalViewer = $this->Viewer_GetLocalViewer();


        // Если редактируем топик, то брендинг блога не отображаем
        if (isset($data['template']['event']) && $data['template']['event'] == 'edit') {
            return;
        }

        if ($oBlog && Config::Get('plugin.br.blog.allow_blog')) {
            $oLocalViewer->Assign('oBranding', $oBlog->getBranding());
            $oLocalViewer->Assign('oBlog', $oBlog);
            return $oLocalViewer->Fetch(Plugin::GetTemplatePath(__CLASS__) . '/tpls/css.inject.tpl');
        }
    }

    /**
     * Установка фона по хуку
     * @param $data
     * @return string|void
     */
    public function SetBlogBackgroundList($data) {

        /** @var PluginBr_ModuleBlog_EntityBlog $oBlog */
        $oBlog = @$data['oBlog'];

        if (!$oBlog) {
            return;
        }

        $this->Hook_AddExecFunction(
            'template_layout_body_end',             // название хука
            array($this, 'SetBlogBackground'),      // метод этого класса, который будет выводить скрипт
            -100,                                   // приоритет хука - самый низкий, хотя и не принципиально
            array('template' => array('blog' => $oBlog))
        );
    }

    /**
     * Установка фона по хуку на показ топика
     * @param $data
     * @return string|void
     */
    public function SetBlogBackgroundTopic($data) {

        /** @var ModuleTopic_EntityTopic $oBlog */
        $oTopic = @$data['oTopic'];

        if (!$oTopic) {
            return;
        }

        if (!$oBlog = $oTopic->getBlog()) {
            return;
        }

        $this->Hook_AddExecFunction(
            'template_layout_body_end',           // название хука
            array($this, 'SetBlogBackground'),      // метод этого класса, который будет выводить скрипт
            -100,                                   // приоритет хука - самый низкий, хотя и не принципиально
            array('template' => array(
                'topic' => $oTopic,
                'blog'  => $oBlog,
                'event' => Router::GetActionEvent()
            ))
        );
    }


}