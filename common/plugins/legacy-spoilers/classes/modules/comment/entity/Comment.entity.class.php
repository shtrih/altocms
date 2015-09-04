<?php

class PluginLegacyspoilers_ModuleComment_EntityComment extends PluginLegacyspoilers_Inherit_ModuleComment_EntityComment {

    public function getText() {
        return E::Module('PluginLegacyspoilers_ModuleLegacyspoilers')->MakeCorrection(
            parent::getText()
        );
    }
}
