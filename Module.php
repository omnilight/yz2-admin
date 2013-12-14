<?php

namespace yz\admin;

use yii\base\InvalidConfigException;
use yz\base\Module as BaseModule;

/**
 * Class Module
 * @package yz\admin
 */
class Module extends BaseModule
{
    public $adminMenuOrder = 9999;

    public function init()
    {
        if(YZ_APP_TYPE_BACKEND) {
            $this->defaultRoute = 'backend/main';
        }
        parent::init();
    }


    /**
     * @inheritdoc
     */
    public function getRoutes()
    {
        switch(true) {
            default:
            case YZ_APP_TYPE_FRONTEND:
                return [];
            case YZ_APP_TYPE_BACKEND:
                return [
                    'prepend' => [
                        // This is worked in backend
                        '' => 'admin/main/index',
                        'login' => 'admin/main/login',
                        'logout' => 'admin/main/logout',
                    ]
                ];
            case YZ_APP_TYPE_CONSOLE:
                return [];
        }
    }

}