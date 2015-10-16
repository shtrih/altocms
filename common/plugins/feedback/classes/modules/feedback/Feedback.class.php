<?php

class PluginFeedback_ModuleFeedback extends Module {

    /**
     * @var PluginFeedback_ModuleFeedback_MapperFeedback
     */
    protected $oMapper;
    protected $aWebpaths;

    public function Init() {
        $this->oMapper = E::GetMapper(__CLASS__);
    }

    public function getFeedbackPaths() {
        if (!$this->aWebpaths)
            $this->aWebpaths = $this->oMapper->getFeedbackWebpaths();

        return $this->aWebpaths;
    }

    public function updateFeedback(PluginFeedback_ModuleFeedback_EntityFeedback $oFeedback) {
        $oFeedback->setFeedbackWebpath($this->normalizeWebpath($oFeedback->getFeedbackWebpath()));

        //region Записываем в WritePluginConfig, потому что только так мы сможем прочитать урлы при загрузке конфига и задать правила роутинга
        $sPluginName = strtolower(Plugin::GetPluginName(__CLASS__));
        $aConfigRoot = Config::ReadPluginConfig($sPluginName, Config::KEY_ROOT);
        if (!isset($aConfigRoot['router.uri'])) {
            $aConfigRoot['router.uri'] = [];
        }
        $aConfigWebpaths = &$aConfigRoot['router.uri'];

        $sActionRegex = '[~^'.preg_quote(trim($oFeedback->getFeedbackWebpath(), '/'), '~').'$~iu]';
        if ($oFeedback->getFeedbackActive()) {
            $aConfigWebpaths[ $sActionRegex ] = Config::Get('plugin.feedback.action') . '/' . $oFeedback->getFeedbackId();
        }
        else {
            unset($aConfigWebpaths[ $sActionRegex ]);
        }
        unset($aConfigWebpaths);
        Config::WritePluginConfig($sPluginName, [Config::KEY_ROOT => $aConfigRoot]);

        $aConfigWebpaths = (array)Config::ReadPluginConfig($sPluginName);
        //endregion

        $this->oMapper->updateFeedback(
            $oFeedback->getFeedbackId(),
            $oFeedback->getFeedbackWebpath(),
            $oFeedback->getFeedbackActive(),
            $oFeedback->getFeedbackTitle(),
            $oFeedback->getFeedbackText(),
            $oFeedback->getFeedbackTextSource()
        );
    }

    public function getFeedbackById($iItemId) {
        return $this->oMapper->getFeedbackById($iItemId);
    }

    public function addField(ModuleTopic_EntityField $oField) {
        return $this->oMapper->addField($oField);
    }

    public function getFields($iFeedbackId) {
        return $this->oMapper->getFields($iFeedbackId);
    }

    public function getField($iFieldId) {
        return $this->oMapper->getField($iFieldId);
    }

    public function updateField(ModuleTopic_EntityField $oField) {
        return $this->oMapper->updateField($oField);
    }

    public function removeField($iFieldId) {
        return $this->oMapper->removeField($iFieldId);
    }

    protected function normalizeWebpath($sWebpath) {
        return '/' . trim($sWebpath, '/');
    }
}
