<?php

/*
 *
 * Project Name : Must Have Blogs
 * Copyright (C) 2011-2012 Alexei Lukin. All rights reserved.
 * License: GNU GPL v2, http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 *
 */


class PluginMHB_ActionAdmin extends PluginMHB_Inherit_ActionAdmin
{

    protected function RegisterEvent() {
        $this->AddEvent('settings-mhb', 'EventMHB');
        parent::RegisterEvent();
    }

    public function EventMHB() {
        $aBlogs = E::ModuleBlog()->GetBlogs();

        if (isPost('submit_mhb')) {
            E::ModuleSecurity()->ValidateSendForm();
            E::Module('PluginMHB_ModuleMain')->DeleteAllMhb();

            foreach ($_REQUEST as $key => $var) {
                $oMhb = null;

                if (strpos($key, 'mhb_auto_join_') === 0) {
                    $iBlogId = substr($key, 14);

                    /** @var $oMhb PluginMhb_ModuleMain_EntityMhb */
                    $oMhb    = E::Module('PluginMHB_ModuleMain')->GetMhbByBlogId($iBlogId);
                    if (!$oMhb) {
                        $oMhb = Engine::GetEntity('PluginMHB_Main_Mhb');
                        $oMhb->setBlogId($iBlogId);
                        $oMhb->setAutoJoin(1);
                        $oMhb->setCantLeave(0);
                        E::Module('PluginMHB_ModuleMain')->AddMhb($oMhb);
                    }
                    else {
                        $oMhb->setAutoJoin(1);
                        E::Module('PluginMHB_ModuleMain')->UpdateMhb($oMhb);
                    }
                }

                if (strpos($key, 'mhb_cant_leave_') === 0) {
                    $iBlogId = substr($key, 15);
                    /** @var $oMhb PluginMhb_ModuleMain_EntityMhb */
                    $oMhb    = E::Module('PluginMHB_ModuleMain')->GetMhbByBlogId($iBlogId);
                    if (!$oMhb) {
                        $oMhb = Engine::GetEntity('PluginMHB_Main_Mhb');
                        $oMhb->setBlogId($iBlogId);
                        $oMhb->setAutoJoin(0);
                        $oMhb->setCantLeave(1);
                        E::Module('PluginMHB_ModuleMain')->AddMhb($oMhb);
                    }
                    else {
                        $oMhb->setCantLeave(1);
                        E::Module('PluginMHB_ModuleMain')->UpdateMhb($oMhb);
                    }
                }
            }
        }

        $aMhb = E::Module('PluginMHB_ModuleMain')->GetAllMhb();

        $aData = array();
        foreach ($aBlogs as $oBlog) {
            $data['blog_id']    = $oBlog->getId();
            $data['title']      = $oBlog->getTitle();
            $data['closed']     = $oBlog->getType() == 'close';
            $data['auto_join']  = false;
            $data['cant_leave'] = false;

            if (isset($aMhb[$oBlog->getId()])) {
                $data['auto_join']  = $aMhb[$oBlog->getId()]->getAutoJoin();
                $data['cant_leave'] = $aMhb[$oBlog->getId()]->getCantLeave();
            }
            $aData[] = $data;
        }
        E::ModuleViewer()->Assign("aData", $aData);
    }
}
