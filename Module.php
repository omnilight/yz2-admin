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

    public $defaultRoute = 'backend/main';

    /**
     * @inheritdoc
     */
    public function getRoutes()
    {
        if(YZ_APP_TYPE_BACKEND) {
            return [
                'prepend' => [
                    // This is worked in backend
                    '' => 'admin/main/index',
                    'login' => 'admin/main/login',
                    'logout' => 'admin/main/logout',
                ]
            ];
        } else
            return [];
    }


}