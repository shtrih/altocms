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
        E::ModuleViewer()->Assign('header', $oFeedback->getTitle());
        E::ModuleViewer()->AddHtmlTitle($oFeedback->getTitle());

        if ($this->isPost()) {
            if (!E::ModuleSecurity()->ValidateSecurityKey()) {
                E::ModuleMessage()->AddError('Что-то пошло не так. Ты что, хакер?');

                return null;
            }

            if (E::ModuleCaptcha()->Verify(F::GetPostStr('captcha'))) {
                E::ModuleMessage()->AddError('Проверка каптча не пройдена.');

                return null;
            }

            $bError = false;
            $sMessage = '';

            if ($oUser = E::ModuleUser()->GetUserCurrent()) {
                $sMessage .= sprintf(
                    '<strong>Отправлено пользователем</strong>: <a href="%susers-list/profile/%3$d/">%s</a> (ID: %d)',
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
                $sFieldsValues = (array)F::GetPost('fields');
                $sValue = isset($sFieldsValues[$oField->getFieldId()]) ? $sFieldsValues[$oField->getFieldId()] : false;
                if ($sValue) {
                    $sMessage .= nl2br(htmlspecialchars($sValue));
                }
                elseif ($oField->getFieldRequired()) {
                    E::ModuleMessage()->AddError(sprintf('Поле «%s» должно быть обязательно заполнено.', htmlspecialchars($oField->getFieldName())));

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
                    'Сообщение со страницы обратной связи (' . $oFeedback->getFeedbackWebpath() . ')',
                    $sMessage,
                    Config::Get('plugin.feedback.from-user-id'),
                    $aAdmins,
                    Config::Get('plugin.feedback.email-notify')
                );
                E::ModuleMessage()->AddNotice('Сообщение успешно отправлено.', null, true);

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
