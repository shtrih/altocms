<?php

/**
 * Uploader.class.php
 * Файл модуля Uploader плагина Br
 *
 * @author      Андрей Воронов <andreyv@gladcode.ru>
 * @copyrights  Copyright © 2014, Андрей Воронов
 *              Является частью плагина Br
 * @version     0.0.1 от 03.11.2014 22:29
 */
class PluginBr_ModuleUploader extends PluginBr_Inherit_ModuleUploader {

    /**
     * Получает путь к картинкам брендинга
     * @param int    $sTargetId Идентификатор целевого объекта
     * @param string $sType     Что за картинка
     * @return string
     */
    public function GetUploadDir($sTargetId, $sType) {

        $nMaxLen = 6;
        $nSplitLen = 2;
        $sPath = join('/', str_split(str_pad($sTargetId, $nMaxLen, '0', STR_PAD_LEFT), $nSplitLen));
        $sResult = F::File_NormPath(F::File_RootDir() . Config::Get('path.uploads.root') . "/{$sType}/" . $sPath);
        F::File_CheckDir($sResult, TRUE);

        return $sResult;

    }

    /**
     * Проверяет доступность того или иного целевого объекта, переопределяется
     * плагинами. По умолчанию всё грузить запрещено.
     * Если всё нормально и пользователю разрешено сюда загружать картинки,
     * то метод возвращает целевой объект, иначе значение FALSE.
     *
     * @param $sTarget
     * @param $sTargetId
     * @return bool
     */
    public function CheckAccessAndGetTarget($sTarget, $sTargetId) {
        if ($sTarget == 'blog-branding') {
            if (Config::Get('plugin.br.blog.allow_blog') == FALSE) {
                return FALSE;
            }

            if (!$oBlog = $this->Blog_GetBlogById($sTargetId)) {
                return FALSE;
            }

            // Проверям, авторизован ли пользователь
            if (!E::IsUser()) {
                return FALSE;
            }

            // Проверка на право редактировать блог
            if (!$this->ACL_IsAllowEditBlog($oBlog, E::User())) {
                return FALSE;
            }

            return $oBlog;
        }

//        return parent::CheckAccess($sTarget, $sTargetId);
        return FALSE;
    }

    /**
     * Получает урл цели
     *
     * @param $sTargetId
     * @param $sTarget
     * @return string
     */
    public function GetTargetUrl($sTargetId, $sTarget) {

        if ($sTarget == 'blog-branding') {
            /** @var $oBlog ModuleBlog_EntityBlog */
            if (!$oBlog = $this->Blog_GetBlogById($sTargetId)) {
                return '';
            }

            return $oBlog->getUrlFull();
        }

        return '';
    }

    /**
     * Получает урл изображения целевого объекта
     *
     * @param      $sTargetId
     * @param      $sTarget
     * @param bool|string $xSize
     * @return mixed|string
     */
    public function GetTargetImageUrl($sTargetId, $sTarget, $xSize=FALSE) {

        $aMResourceRel = $this->Mresource_GetMresourcesRelByTarget($sTarget, $sTargetId);
        if ($aMResourceRel) {
            $oMResource = array_shift($aMResourceRel);

            $sUrl = str_replace('@', Config::Get('path.root.web'), $oMResource->getPathUrl());

            if (!$xSize) {
                return $sUrl;
            }

            return $this->ResizeTargetImage($sUrl, $xSize);
        }

        return '';

    }

    /**
     * Возвращает урл изображения по новому размеру
     *
     * @param string $sOriginalPath
     * @param string    $xSize
     *
     * @return string
     */
    public function ResizeTargetImage($sOriginalPath, $xSize) {

        $sModSuffix = F::File_ImgModSuffix($xSize, pathinfo($sOriginalPath, PATHINFO_EXTENSION));
        $sUrl = $sOriginalPath . $sModSuffix;

        if (Config::Get('module.image.autoresize')) {
            $sFile = $this->Uploader_Url2Dir($sUrl);
            if (!F::File_Exists($sFile)) {
                $this->Img_Duplicate($sFile);
            }
        }

        return $sUrl;

    }
}