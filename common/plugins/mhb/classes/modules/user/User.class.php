<?php

class PluginMHB_ModuleUser extends PluginMHB_Inherit_ModuleUser
{

    public function Add(ModuleUser_EntityUser $oUser) {
        if ($nUser = parent::Add($oUser)) {
            $sId = $nUser->getId();

            $aMhb = E::Module('PluginMHB_ModuleMain')->GetAllMhb();

            /** @var $oMhb PluginMhb_ModuleMain_EntityMhb */
            foreach ($aMhb as $oMhb) {
                if ($oMhb->getAutoJoin()) {
                    if ($oBlog = E::ModuleBlog()->GetBlogById($oMhb->getBlogId())) {
                        /** @var $oBlogUserNew ModuleBlog_EntityBlogUser */
                        $oBlogUserNew = Engine::GetEntity('Blog_BlogUser');
                        $oBlogUserNew->setUserId($sId);
                        $oBlogUserNew->setUserRole(ModuleBlog::BLOG_USER_ROLE_USER);
                        $oBlogUserNew->setBlogId($oBlog->getId());
                        $bResult = E::ModuleBlog()->AddRelationBlogUser($oBlogUserNew);
                        if ($bResult) {
                            $oBlog->setCountUser($oBlog->getCountUser() + 1);
                            E::ModuleBlog()->UpdateBlog($oBlog);
                            E::ModuleStream()->write($sId, 'join_blog', $oBlog->getId());
                            E::ModuleUserfeed()->subscribeUser($sId, ModuleUserfeed::SUBSCRIBE_TYPE_BLOG, $oBlog->getId());
                        }
                    }
                }
            }

            return $nUser;
        }

        return false;
    }
}
