<?php

/**
 * Br.class.php
 * Файл модуля Br плагина Br
 *
 * @author      Андрей Воронов <andreyv@gladcode.ru>
 * @copyrights  Copyright © 2014, Андрей Воронов
 *              Является частью плагина Br
 * @version     0.0.1 от 03.11.2014 01:59
 */
class PluginBr_ModuleBr extends ModuleORM {
    /**
     * Маппер модуля
     * @var
     */
    protected $oBrMapper;

    /**
     * Текущий пользователь
     * @var ModuleUser_EntityUser
     */
    protected $oUserCurrent = NULL;

    /**
     * Инициализация модуля
     */
    public function Init() {

        parent::Init();

        // Получение текущего пользователя
        $this->oUserCurrent = $this->User_GetUserCurrent();

        // Получение мапперов
        $this->oBrMapper = Engine::GetMapper('PluginBr_ModuleBr', 'Br');
    }

}