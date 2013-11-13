<?php

namespace yz\admin;

use yz\base\Module as BaseModule;

/**
 * Class Module
 * @package yz\admin
 */
class Module extends BaseModule
{
    public $adminMenuOrder = 9999;
    /**
     * Base url path for backend
     * @var string
     */
    public $backendUrl = 'backend';

    public $defaultRoute = 'backend/main';

    public function getRoutes()
    {
        return [
            'prepend' => [
                $this->backendUrl => 'admin/backend/main/index',
                $this->backendUrl.'/login' => 'admin/backend/main/login',
                $this->backendUrl.'/logout' => 'admin/backend/main/logout',
                $this->backendUrl.'/<module:\w+>' => '<admin>/backend/settings',
                $this->backendUrl.'/<module:\w+>/<conroller:\w+>' => '<module>/backend/<controller>',
                $this->backendUrl.'/<module:\w+>/<conroller:\w+>/<action:w\+>' => '<module>/backend/<controller>/<action>',
            ]
        ];
    }


}