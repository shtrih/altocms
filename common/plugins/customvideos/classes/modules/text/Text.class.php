<?php

class PluginCustomvideos_ModuleText extends PluginCustomvideos_Inherit_ModuleText {

    /**
     * Парсинг текста на предмет видео
     * Находит теги <pre><video></video></pre> и реобразовываетих в видео
     *
     * @param string $sText    Исходный текст
     *
     * @return string
     */
    public function VideoParser($sText) {

        $aConfig = E::ModuleUploader()->GetConfig('*', 'images.video');
        if (!empty($aConfig['transform']['max_width'])) {
            $iWidth = intval($aConfig['transform']['max_width']);
        } else {
            $iWidth = 640;
        }
        $nRatio = E::ModuleUploader()->GetConfigAspectRatio('*', 'video');
        if ($nRatio) {
            $iHeight = $iWidth / $nRatio;
        } else {
            $iHeight = intval($aConfig['transform']['max_width']);
        }
        if (!empty($aConfig['transform']['max_height'])) {
            if ($iHeight > intval($aConfig['transform']['max_width'])) {
                $iHeight = intval($aConfig['transform']['max_width']);
            }
        }
        if (!$iHeight) {
            $iHeight = 380;
        }

        $sIframeAttr = 'frameborder="0" webkitAllowFullScreen mozallowfullscreen allowfullscreen="allowfullscreen"';
        $sYoutubeReplacement = '<iframe width="' . $iWidth . '" height="' . $iHeight . '" src="" ' . $sIframeAttr . '></iframe>
<div data-youtube-id="$1"
     style="background-image: url(//i3.ytimg.com/vi/$1/hqdefault.jpg);">
    <div></div>
</div>';


        /**
         * youtube.com
         */
        /*$sText = preg_replace(
            '/<video>http(?:s|):\/\/(?:www\.|m.|)youtube\.com\/watch\?v=([a-zA-Z0-9_\-]+)(&.+)?<\/video>/Ui',
            '<iframe width="' . $iWidth . '" height="' . $iHeight . '" src="http://www.youtube.com/embed/$1" ' . $sIframeAttr . '></iframe>',
            $sText
        );*/
        /**
         * youtu.be
         */
        /*$sText = preg_replace(
            '/<video>http(?:s|):\/\/(?:www\.|m.|)youtu\.be\/([a-zA-Z0-9_\-]+)(&.+)?<\/video>/Ui',
            '<iframe width="' . $iWidth . '" height="' . $iHeight . '" src="http://www.youtube.com/embed/$1" ' . $sIframeAttr . '></iframe>',
            $sText
        );*/

        /**
         * youtube.com
         */
        $sText = preg_replace('#<video>https?://(?:www\.|m\.|)youtube\.com/watch[^<]*(?:\?|&)v=([a-zA-Z0-9_-]+)(?:&[^<]*)?</video>#Ui', $sYoutubeReplacement, $sText);
        /**
         * youtu.be
         */
        $sText = preg_replace('#<video>https?://(?:www\.|m\.|)youtu\.be/([a-zA-Z0-9_-]+)(?:&[^<]*)?</video>#Ui', $sYoutubeReplacement, $sText);

        /**
         * vimeo.com
         */
        $sText = preg_replace(
            '/<video>http(?:s|):\/\/(?:www\.|)vimeo\.com\/(\d+).*<\/video>/i',
            '<iframe src="https//player.vimeo.com/video/$1" width="' . $iWidth . '" height="' . $iHeight . '" ' . $sIframeAttr . '></iframe>',
            $sText
        );
        /**
         * rutube.ru
         */
        $sText = preg_replace(
            '/<video>http(?:s|):\/\/(?:www\.|)rutube\.ru\/tracks\/(\d+)\.html.*<\/video>/Ui',
            '<iframe src="//rutube.ru/play/embed/$1" width="' . $iWidth . '" height="' . $iHeight . '" ' . $sIframeAttr . '></iframe>',
            $sText
        );

        $sText = preg_replace(
            '/<video>http(?:s|):\/\/(?:www\.|)rutube\.ru\/video\/(\w+)\/?<\/video>/Ui',
            '<iframe src="//rutube.ru/play/embed/$1" width="' . $iWidth . '" height="' . $iHeight . '" ' . $sIframeAttr . '></iframe>',
            $sText
        );

        /**
         * coub.com
         */
        $sText = preg_replace(
            '#<video>https?://(?:www\.|)coub\.com/view/([\w\d]+)/?</video>#i',
            '<iframe src="//coub.com/embed/$1?muted=false&amp;autostart=false&originalSize=false&hideTopBar=false&noSiteButtons=false&startWithHD=false" allowfullscreen="true" frameborder="0" width="' . $iWidth . '" height="' . $iHeight . '"></iframe>',
            $sText
        );

        /**
         * Amwnews.ru
         */
        $sText = preg_replace(
            '#<video>https?://(?:www\.|)amvnews\.ru/index\.php\?go=Files.*&id=(\d+).*</video>#i',
            '<iframe width="' . $iWidth . '" height="' . $iHeight . '" src="http://amvnews.ru/index.php?go=Files&file=embed&id=$1&pic=poster" frameborder="0" allowfullscreen></iframe>',
            $sText
        );


        /**
         * video.yandex.ru - closed
         */
        return $sText;
    }
}
