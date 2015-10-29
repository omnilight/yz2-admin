<?php

namespace yz\admin;

use yii\helpers\ArrayHelper;
use yz\icons\Icons;

/**
 * Class Module
 * @method static Module instance()
 */
class Module extends \yz\Module
{
    public $adminMenuOrder = 9999;

    public $defaultRoute = 'admin/main/index';

    /**
     * @var string Name of the GET variable used to create links to backpage
     */
    public $returnUrlVar = '__returnUrl';
    /**
     * @var string Title of the admin panel that is showed in the top of the layout. Default is 'Admin Panel'
     */
    public $adminTitle;
    /**
     * @var bool Defines whether to show system info menu in admin panel
     */
    public $showSystemInfoMenu = true;
    /**
     * @var array Extra menu items
     */
    public $menuItems = [];
    /**
     * @var array Extra auth items
     */
    public $authItems = [];
    /**
     * If true, login by token exchange algorithm will be used
     * @var bool
     */
    public $allowLoginViaToken = false;

    /**
     * @inheritdoc
     */
    public function getVersion()
    {
        return '0.1';
    }

    /**
     * @inheritdoc
     */
    public function getName()
    {
        return \Yii::t('admin/t', 'Administration module');
    }

    /**
     * @inheritdoc
     */
    public function getDescription()
    {
        return \Yii::t('admin/t', 'Provides administration panel functionality with backend user management');
    }

    public function getAdminMenu()
    {
        return ArrayHelper::merge($this->menuItems, [
            [
                'label' => \Yii::t('admin/menu', 'Administrators'),
                'icon' => Icons::o('user'),
                'items' => [
                    [
                        'label' => \Yii::t('admin/menu', 'List'),
                        'icon' => Icons::o('list'),
                        'route' => ['/admin/users/index'],
                    ],
                    [
                        'label' => \Yii::t('admin/menu', 'Roles'),
                        'icon' => Icons::o('list'),
                        'route' => ['/admin/roles/index'],
                    ]
                ],
            ],
        ], $this->showSystemInfoMenu ? [
            [
                'label' => \Yii::t('admin/menu', 'System'),
                'icon' => Icons::o('gear'),
                'items' => [
                    [
                        'label' => \Yii::t('admin/menu', 'Information'),
                        'icon' => Icons::o('info'),
                        'route' => ['/admin/general/info'],
                    ],
                ],
            ]
        ]: []);
    }

    /**
     * @inheritDoc
     */
    public function getAuthItems()
    {
        return array_merge(parent::getAuthItems(), $this->authItems);
    }


}