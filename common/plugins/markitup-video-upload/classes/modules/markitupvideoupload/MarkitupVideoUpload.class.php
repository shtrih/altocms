<?php

class PluginMarkitupVideoUpload_ModuleMarkitupVideoUpload extends Module {

    public function Init() {

    }

    public function storeVideo($sVideoFile, $oUser, $sType, $aOptions = array()) {
        $sExtension = F::File_GetExtension($sVideoFile, true);
        $aConfig = E::ModuleUploader()->GetConfig($sVideoFile, 'video.' . $sType);
        if ($aOptions) {
            $aConfig['transform'] = F::Array_Merge($aConfig['transform'], $aOptions);
        }

        $sSuffix = (!empty($aConfig['original']['suffix']) ? $aConfig['original']['suffix'] : '-original');
        $sOriginalScreenshotFile = $sVideoFile . $sSuffix . '.jpg';

        $sFfmpegResult = shell_exec('ffmpeg -n -i ' . escapeshellarg($sVideoFile)
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
}
