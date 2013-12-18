<?php

namespace yz\admin\widgets;


use yii\base\Widget;
use yii\helpers\Html;
use yz\admin\components\AuthManager;
use yz\base\Module as YzModule;

/**
 * Class MainMenu renders main administration menu in the admin panel
 * @package yz\admin\widgets
 */
class MainMenu extends Widget
{
    const ADMIN_MENU_ITEMS_KEY = 'AdminMenuItemsKey';

    public function run()
    {
		MainMenuAsset::register($this->getView());

        echo $this->render('mainMenu',[
            'menuItems' => $this->getMenuItems(),
        ]);
    }

    protected function getMenuItems()
    {
        if(($menuItems = \Yii::$app->cache->get(static::ADMIN_MENU_ITEMS_KEY)) === false) {
			$menuItems = [];
            foreach(\Yii::$app->getModules() as $id => $module) {
                if(is_array($module)) {
                    $module = \Yii::$app->getModule($id);
                }
                if($module instanceof YzModule) {
                    $moduleMenu = $module->getAdminMenu();

                    foreach($moduleMenu as $group) {
                        $groupItems = [];
                        foreach($group['items'] as $item) {
                            if(isset($item['authItem']))
                                $hasAccess = \Yii::$app->user->checkAccess($item['authItem']);
                            elseif(isset($item['route']) && is_array($item['route']))
                                $hasAccess = $this->checkAccessByRoute($item['route'][0]);
                            else
                                $hasAccess = true;

                            if($hasAccess) {
                                $groupItems[] = $item;
                            }
                        }
                        if(count($groupItems) > 0) {
                            $group['items'] = $groupItems;
                            $menuItems[$module->adminMenuOrder][] = $group;
                        }
                    }
                }
            }

            ksort($menuItems);

            if(!empty($menuItems))
                $menuItems = call_user_func_array('array_merge', $menuItems);

            \Yii::$app->cache->set(static::ADMIN_MENU_ITEMS_KEY, $menuItems, 10000);
        }

        return $menuItems;
    }

    protected function checkAccessByRoute($route)
    {
        static $_routes = [];

        if(isset($_routes[$route]))
            return $_routes[$route];

        if(($ca = \Yii::$app->createController($route)) === false) {
            return true;
        }

        list($controller, $action) = $ca;

        $operation = AuthManager::getOperationName($controller, $action);

        return ($_routes[$route] = \Yii::$app->user->checkAccess($operation));
    }
} 