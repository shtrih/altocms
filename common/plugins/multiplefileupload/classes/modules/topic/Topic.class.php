<?php

class PluginMultiplefileupload_ModuleTopic extends PluginMultiplefileupload_Inherit_ModuleTopic {

    public function DeleteMresources($aTopics) {
        parent::DeleteMresources($aTopics);

        if (!is_array($aTopics)) {
            $aTopics = array($aTopics);
        }
        /** @var ModuleTopic_EntityTopic $oTopic */
        foreach ($aTopics as $oTopic) {
            E::ModuleMresource()->DeleteMresourcesRelByTarget(PluginMultiplefileupload_ModuleMultiplefileupload::TARGET_TYPE, $oTopic->GetId());
        }
    }
}
