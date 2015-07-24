<?php

class PluginBetterspoilers_ModuleComment_EntityComment extends PluginBetterspoilers_Inherit_ModuleComment_EntityComment {

    public function getText() {
        return E::Module('PluginBetterspoilers_ModuleBetterspoilers')->MakeCorrection(
            parent::getText()
        );
    }
}