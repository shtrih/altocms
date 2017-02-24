<?php

class PluginMarkitupVideoUpload_ModuleTopic extends PluginMarkitupVideoUpload_Inherit_ModuleTopic {

    public function DeleteMresources($aTopics) {
        //E::ModuleMresource()->GetMresourcesByFilter()

        parent::DeleteMresources($aTopics);

        if (!is_array($aTopics)) {
            $aTopics = [$aTopics];
        }
        /** @var ModuleTopic_EntityTopic $oTopic */
        foreach ($aTopics as $oTopic) {
            E::ModuleMresource()->DeleteMresourcesRelByTarget(PluginMultiplefileupload_ModuleMultiplefileupload::TARGET_TYPE, $oTopic->GetId());
        }
    }
}
