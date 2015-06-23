<?php
class PluginGravatar_ModuleGravatar_EntityUser extends PluginGravatar_Inherit_ModuleUser_EntityUser {

    /**
     * Возвращает полный URL до аватары нужного размера
     *
     * @param int|string $xSize - Размер (120 | '120x100')
     *
     * @return  string
     */
    public function getAvatarUrl($xSize) {
        if (!$xSize) {
            $xSize = Config::Get('module.user.profile_avatar_size');
            if (!$xSize) {
                $xSize = self::DEFAULT_AVATAR_SIZE;
            }
        }

        $sPropKey = '_avatar_url_' . $xSize;
        $sUrl = $this->getProp($sPropKey);
        if (is_null($sUrl)) {
            if ($sRealSize = C::Get('module.uploader.images.profile_avatar.size.' . $xSize)) {
                $xSize = $sRealSize;
            }
            $sUrl = $this->_getProfileImageUrl('profile_avatar', $xSize);
            // Old version compatibility
            $sUrl = $this->getProfileAvatar();
            if ($sUrl) {
                if ($xSize) {
                    $sUrl = E::ModuleUploader()->ResizeTargetImage($sUrl, $xSize);
                }
            } else {
                $sUrl = "http://www.gravatar.com/avatar/".md5(strtolower($this->getMail())).".png?size=".$xSize."&d=identicon";
            }
            $this->setProp($sPropKey, $sUrl);
        }
        return $sUrl;
    }
}

