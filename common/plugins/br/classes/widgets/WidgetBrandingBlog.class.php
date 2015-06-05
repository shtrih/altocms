<?php

/**
 * WidgetBr.php
 * Файл виджета плагина Br
 *
 * @author      Андрей Воронов <andreyv@gladcode.ru>
 * @copyrights  Copyright © 2014, Андрей Воронов
 *              Является частью плагина Br
 * @version     0.0.1 от 03.11.2014 01:59
 */
class PluginBr_WidgetBrandingBlog extends Widget {

    /**
     * Обработка отображения виджета
     */
    public function Exec() {

        if (Config::Get('plugin.br.blog.allow_blog') == FALSE) {
            return;
        }

        $sBlogId = Router::GetParam(0);
        $oBlog = $this->Blog_GetBlogById($sBlogId);


        $this->Viewer_Assign("oBlog", $oBlog);
        $this->Viewer_Assign("sTemplatePathBr", Plugin::GetTemplatePath(__CLASS__));

    }
}