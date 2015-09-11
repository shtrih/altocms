<?php
/*
 * Этот файл является частью плагина Multiple File Upload
 * Copyright © 2015 https://github.com/shtrih
 * Распространение, продажа, обмен и передача плагина третьим лицам запрещено, за исключением когда третье лицо занимается разработкой вашего проекта.
 */

class PluginAudiofilepreview_ModuleMultiplefileupload extends PluginAudiofilepreview_ModuleMultiplefileupload_Inherits_PluginMultiplefileupload_ModuleMultiplefileupload {

    /**
     * @param $iTopicId int Идентификатор топика. 0, если надо получить неприкреплённые файлы.
     * @param null $iUserId
     * @return array Массив с объектами файлов
     */
    public function getAttachedFiles($iTopicId, $iUserId = null) {
        $aResult = array();
        $aFiles = parent::getAttachedFiles($iTopicId, $iUserId);
        $aExtensions = Config::Get('plugin.audiofilepreview.audio-extensions');

        // исключаем из списка прикреплённых музыкальные файлы везде, кроме редактирования страницы
        if ($iTopicId && 'edit' != Router::GetActionEventName()) {
            foreach ($aFiles as $oFile) {
                if (!in_array(F::File_GetExtension($oFile->name, true), $aExtensions))
                    $aResult[] = $oFile;
            }
        }
        // неприкреплённые ($iTopicId == 0) отдаем как есть
        else {
            $aResult = $aFiles;
        }

        return $aResult;
    }
}
