<?php

namespace yz\admin;

use yz\icons\Icons;

/**
 * Class Module
 * @package yz\admin
 */
class Module extends \yz\base\Module
{
    public $adminMenuOrder = 9999;

	/**
	 * @var string Name of the GET variable used to create links to backpage
	 */
	public $returnUrlVar = '__returnUrl';

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

	public function getAdminMenu()
	{
		return [
			[
				'label' => \Yii::t('yz/admin','Administrators'),
				'icon' => Icons::o('users'),
				'items' => [
					[
						'label' => \Yii::t('yz/admin','List'),
						'icon' => Icons::o('users'),
						'route' => ['/admin/users/index'],
					],
					[
						'label' => \Yii::t('yz/admin','Groups'),
						'route' => ['/admin/groups/index'],
					]
				]
			]
		];
	}


}