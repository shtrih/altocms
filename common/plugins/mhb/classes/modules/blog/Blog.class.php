<?php

class PluginMHB_ModuleBlog extends PluginMHB_Inherit_ModuleBlog
{

    public function DeleteRelationBlogUser(ModuleBlog_EntityBlogUser $oBlogUser) {
        /** @var $oMhb PluginMhb_ModuleMain_EntityMhb */
        if ($oMhb = E::Module('PluginMHB_ModuleMain')->GetMhbByBlogId($oBlogUser->getBlogId())) {
            if ($oMhb->getCantLeave()) {
                E::ModuleMessage()->AddErrorSingle(E::ModuleLang()->Get('plugin.mhb.mhb_cant_leave_blog'), E::ModuleLang()->Get('attention'));

                return true;
            }
        }

        return parent::DeleteRelationBlogUser($oBlogUser);
    }
}

