<?php

class PluginFeedback_ActionFeedback extends ActionPlugin {

    /**
     * Инициализация экшена
     */
    public function Init() {
        $this->SetDefaultEvent('index');
    }

    /**
     * Регистрируем евенты
     */
    protected function RegisterEvent() {
        $this->AddEvent('index', 'index');
        $this->AddEventPreg('~^\d+$~', 'showFeedbackPage');
    }

    protected function index() {
        return parent::EventNotFound();
    }

    protected function showFeedbackPage() {
        $this->SetTemplateAction('index');

        // У нас в ивенте передаётся идентификатор
        $iFeedbackId = $this->sCurrentEvent;

        /**
         * @var PluginFeedback_ModuleFeedback_EntityFeedback $oFeedback
         */
        $oFeedback = E::Module('PluginFeedback_ModuleFeedback')->getFeedbackById($iFeedbackId);

        if (!$oFeedback) {
            return parent::EventNotFound();
        }

        $aFields = $oFeedback->getFields();
        E::ModuleViewer()->Assign('aFields', $aFields);
        E::ModuleViewer()->Assign('oFeedback', $oFeedback);
        E::ModuleViewer()->AddHtmlTitle($oFeedback->getTitle());

        $oUserFrom = E::ModuleUser()->GetUserById(Config::Get('plugin.feedback.from-user-id'));
        if (!$oUserFrom) {
            E::ModuleMessage()->AddError(E::ModuleLang()->Get('plugin.feedback.error_config_user_to'));
            return null;
        }

        if ($this->isPost()) {
            if (!E::ModuleSecurity()->ValidateSecurityKey()) {
                E::ModuleMessage()->AddError(E::ModuleLang()->Get('plugin.feedback.error_security_key'));

                return null;
            }

            if (E::ModuleCaptcha()->Verify(F::GetPostStr('captcha'))) {
                E::ModuleMessage()->AddError(E::ModuleLang()->Get('plugin.feedback.error_captcha'));

                return null;
            }

            $bError = false;
            $sMessage = '';

            if ($oUser = E::ModuleUser()->GetUserCurrent()) {
                $sMessage .= sprintf(
                    E::ModuleLang()->Get('plugin.feedback.talk_user'),
                    R::GetPath('admin'),
                    $oUser->getUserLogin(),
                    $oUser->getId()
                );
                $sMessage .= "<br />\n";
            }

            /** @var ModuleTopic_EntityField $oField */
            foreach ($aFields as $oField) {
                $sMessage .= '<strong>';
                $sMessage .= htmlspecialchars($oField->getFieldName());
                $sMessage .= '</strong>: ';
                $aFieldsValues = (array)F::GetPost('fields');
                $sValue = isset($aFieldsValues[$oField->getFieldId()]) ? $aFieldsValues[$oField->getFieldId()] : false;
                if ($sValue) {
                    $sMessage .= nl2br(htmlspecialchars($sValue));
                }
                elseif ($oField->getFieldRequired()) {
                    E::ModuleMessage()->AddError(E::ModuleLang()->Get('plugin.feedback.error_field_required', ['field' => htmlspecialchars($oField->getFieldName())]));

                    $bError = true;
                    break;
                }
                $sMessage .= "<br />\n";
            }

            if (!$bError) {
                $aAdmins = Config::Get('plugin.feedback.to-user-id');
                if (!$aAdmins) {
                    $aAdmins = E::ModuleUser()->GetUsersByFilter(['admin' => true], ['user_id' => 'asc'], 1, 10)['collection'];
                }

                E::ModuleTalk()->SendTalk(
                    E::ModuleLang()->Get('plugin.feedback.talk_header', ['webpath' => htmlspecialchars($oFeedback->getFeedbackWebpath())]),
                    $sMessage,
                    Config::Get('plugin.feedback.from-user-id'),
                    $aAdmins,
                    Config::Get('plugin.feedback.email-notify')
                );
                E::ModuleMessage()->AddNotice(E::ModuleLang()->Get('plugin.feedback.success_sent'), null, true);

                R::Location(R::GetPathWebCurrent());
            }
        }

        return true;
    }

    /**
     * Завершение работы экшена
     */
    public function EventShutdown() {

    }
}
