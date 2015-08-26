<?php

class PluginMHB_ModuleBlog extends PluginMHB_Inherit_ModuleBlog
{

    public function DeleteRelationBlogUser(ModuleBlog_EntityBlogUser $oBlogUser) {
        if ($oMhb = $this->PluginMHB_ModuleMain_GetMhbByBlogId($oBlogUser->getBlogId())) {
            if (!$oMhb->getCantLeave()) {
                return parent::DeleteRelationBlogUser($oBlogUser);
            }
            else {
                E::ModuleMessage()->AddErrorSingle(E::ModuleLang()->Get('plugin.mhb.mhb_cant_leave_blog'), E::ModuleLang()->Get('attention'));

                return true;
            }
        }

        return parent::DeleteRelationBlogUser($oBlogUser);
    }
}

