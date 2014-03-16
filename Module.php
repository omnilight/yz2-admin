<?php

namespace yz\admin;

use yz\icons\Icons;

/**
 * Class Module
 * @package yz\admin
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
		return \Yii::t('yz/admin', 'Administration module');
	}

	/**
	 * @inheritdoc
	 */
	public function getDescription()
	{
		return \Yii::t('yz/admin', 'Provides administration panel functionality with backend user management');
	}

	/**
	 * @inheritdoc
	 */
	public function getIcon()
	{
		return null;
	}

	/**
	 * @inheritdoc
	 */
	public function getRoutes()
	{
		return [
			'prepend' => [
				// This is worked in backend
				'' => 'admin/main/index',
				'login' => 'admin/main/login',
				'logout' => 'admin/main/logout',
			]
		];
	}

	public function getAdminMenu()
	{
		return [
			[
				'label' => \Yii::t('yz/admin', 'Administrators'),
				'icon' => Icons::o('users'),
				'items' => [
					[
						'label' => \Yii::t('yz/admin', 'List'),
						'icon' => Icons::o('users'),
						'route' => ['/admin/users/index'],
					],
					[
						'label' => \Yii::t('yz/admin', 'Groups'),
						'route' => ['/admin/groups/index'],
					]
				]
			]
		];
	}


}