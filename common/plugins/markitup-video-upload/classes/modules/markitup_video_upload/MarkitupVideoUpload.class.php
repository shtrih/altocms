<?php

class PluginMarkitupVideoUpload_ModuleMarkitupVideoUpload extends Module {

    public function Init() {

    }

    public function storeVideo($sVideoFile, $oUser, $sType, $aOptions = array()) {
        $sExtension = F::File_GetExtension($sVideoFile, true);
        $aConfig = $this->GetConfig($sVideoFile, $sType);
        if ($aOptions) {
            $aConfig['transform'] = F::Array_Merge($aConfig['transform'], $aOptions);
        }

        $sSuffix = $this->GetFileSuffix($sVideoFile, $sType);
        $sOriginalScreenshotFile = $sVideoFile . $sSuffix . '.jpg';

        $sFfmpegPath = Config::Get('plugin.markitup_video_upload.ffmpeg_static_build_path');
        if (!$sFfmpegPath) {
            $sFfmpegPath = 'ffmpeg';
        }
        $sFfmpegResult = shell_exec(escapeshellarg($sFfmpegPath) . ' -n -i ' . escapeshellarg($sVideoFile)
                                    . ' -vframes 1 ' . escapeshellarg($sOriginalScreenshotFile)
                                    . ' </dev/null 2>&1 1>/dev/null');
        if ($sFfmpegResult) {
            E::ModuleLogger()->LogError($sFfmpegResult/* . sprintf(' (%s)', $sVideoFile)*/);
        }

        // Transform image before saving
        $sFileTmp = E::ModuleImg()->TransformFile($sOriginalScreenshotFile, $aConfig['transform']);
        if ($sFileTmp) {
            $sDirUpload = E::ModuleUploader()->GetUserImageDir($oUser->getId(), true, $sType);
            $oStoredScreen = E::ModuleUploader()->Store($sFileTmp, $sDirUpload);
            if ($oStoredScreen) {
                E::ModuleUploader()->Move($sVideoFile, $oStoredScreen->GetFile() . $sSuffix . '.' . $sExtension);

                if (!empty($aConfig['original']['save'])) {
                    E::ModuleUploader()->Move($sOriginalScreenshotFile, $oStoredScreen->GetFile() . $sSuffix . '.jpg');
                }

                return $oStoredScreen->GetUrl();
            }
        }

        return false;
    }

    public function GetConfig($sFile, $sType) {
        return E::ModuleUploader()->GetConfig($sFile, 'video.' . $sType);
    }

    public function GetFileSuffix($sFile, $sType) {
        $aConfig = $this->GetConfig($sFile, $sType);

        return (!empty($aConfig['original']['suffix']) ? $aConfig['original']['suffix'] : '-original');
    }

    public function removeVideo($sPictureFile, $sType) {
        $aConfig = $this->GetConfig($sPictureFile, $sType);
        $aExtensions = array_unique($aConfig['file_extensions']);
        $sFilePath = $sPictureFile . $this->GetFileSuffix($sPictureFile, $sType) . '.';
        foreach ($aExtensions as $sExtension) {
            F::File_Delete($sFilePath . $sExtension);
        }
    }
}
