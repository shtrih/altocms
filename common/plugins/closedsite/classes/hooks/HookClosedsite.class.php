<?php

class PluginClosedsite_HookClosedsite extends Hook
{

    public function RegisterHook() {
        $this->AddHook('init_action', 'HookInitAction');
    }

    /**
     * Обрабатываем хук, который вызывается перед выполнением любого екшена
     * В случае отсутствия екшена в списке разрешенных, возвращаем пользователя
     * на страницу авторизации
     */
    public function HookInitAction() {
        if (E::IsUser()) {
            return true;
        }

        $sCurrentAction = Router::GetAction();
        $sCurrentEvent  = Router::GetActionEvent();

        if (is_null($sCurrentEvent)) {
            $sCurrentEvent = 'index';
        }

        $aAllowedElements = Config::Get('plugin.closedsite.allowedelements');
        $aAllowedActions  = array_keys($aAllowedElements);

        $aDisallowedElements = Config::Get('plugin.closedsite.disallowedelements');
        $aDisallowedActions  = array_keys($aDisallowedElements);

        // Текущее действие не в списке разрешенных
        // Или в списке запрещенных
        if ($aAllowedActions && !in_array($sCurrentAction, $aAllowedActions) || $aDisallowedActions && in_array($sCurrentAction, $aDisallowedActions)) {
            return Router::Action('login');
        }

        $aAllowedEvents    = isset($aAllowedElements[$sCurrentAction]) ? $aAllowedElements[$sCurrentAction] : array();
        $aDisallowedEvents = isset($aDisallowedElements[$sCurrentAction]) ? $aDisallowedElements[$sCurrentAction] : array();

        // Есть событие, которое не разрешено
        // Или запрещено
        // И это не постраничная навигация
        if ((!empty($aAllowedEvents) && !in_array($sCurrentEvent, $aAllowedEvents)
             || !empty($aDisallowedEvents) && in_array($sCurrentEvent, $aDisallowedEvents))
            && !preg_match('/^page\d+$/', $sCurrentEvent)
        ) {
            return Router::Action('login');
        }

        return true;
    }
}
