<?php
class PluginMarkitupVideoUpload_ModuleMresource extends PluginMarkitupVideoUpload_Inherit_Module {

/**
     * Deletes media resources by ID
     *
     * @param $aMresources
     * @param $bDeleteFiles
     * @param $bNoCheckTargets
     *
     * @return bool
     */
    public function DeleteMresources($aMresources, $bDeleteFiles = true, $bNoCheckTargets = false) {

        $aId = $this->_entitiesId($aMresources);
        $bResult = true;

        if ($aId) {
            if ($bDeleteFiles) {
                $aMresources = $this->oMapper->GetMresourcesById($aId);
                if (!$bNoCheckTargets && $aMresources) {
                    /** @var ModuleMresource_EntityMresource $oMresource */
                    foreach ($aMresources as $oMresource) {
                        // Если число ссылок > 0, то не удаляем
                        if ($oMresource->getTargetsCount() > 0) {
                            $iIdx = array_search($oMresource->getId(), $aId);
                            if ($iIdx !== false) {
                                unset($aId[$iIdx]);
                            }
                        }
                    }
                }
            }

            $bResult = $this->oMapper->DeleteMresources($aId);

            if ($bDeleteFiles) {
                if ($bResult && $aMresources && $aId) {
                    // Удаляем файлы
                    foreach ($aId as $nId) {
                        if (isset($aMresources[$nId]) && $aMresources[$nId]->IsFile() && $aMresources[$nId]->CanDelete()) {
                            if ($aMresources[$nId]->IsImage()) {
                                E::ModuleImg()->Delete($aMresources[$nId]->GetFile());

                                // Djn b dcz hfpybwf1

                            } else {
                                F::File_Delete($aMresources[$nId]->GetFile());
                            }
                        }
                    }
                }
            }
        }
        E::ModuleCache()->CleanByTags(array('mresource_update', 'mresource_rel_update'));

        return $bResult;
    }
}
