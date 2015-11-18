<?php

namespace yz\admin\widgets;

use Yii;
use yii\base\Widget;
use yz\admin\helpers\Rbac;

/**
 * Class MainMenu renders main administration menu in the admin panel
 * @package yz\admin\widgets
 */
class MainMenu extends Widget
{
    /**
     * @var string the route used to determine if a menu item is active or not.
     * If not set, it will use the route of the current request.
     * @see params
     * @see isItemActive()
     */
    public $route;
    /**
     * @var array the parameters used to determine if a menu item is active or not.
     * If not set, it will use `$_GET`.
     * @see route
     * @see isItemActive()
     */
    public $params;

    public function run()
    {
        if ($this->route === null && Yii::$app->controller !== null) {
            $this->route = Yii::$app->controller->getRoute();
        }
        if ($this->params === null) {
            $this->params = Yii::$app->request->getQueryParams();
        }
        echo $this->render('mainMenu', [
            'menuItems' => $this->getMenuItems(),
        ]);
    }

    protected function getMenuItems()
    {
        $menuItems = [];
        foreach (Yii::$app->getModules() as $id => $module) {
            if (is_array($module)) {
                $module = Yii::$app->getModule($id);
            }
            if ($module instanceof \yz\Module) {
                $moduleMenu = $module->getAdminMenu();

                foreach ($moduleMenu as $group) {
                    if (isset($group['items'])) {
                        $groupItems = [];
                        foreach ($group['items'] as $item) {
                            if ( $this->checkItemAccess($item)) {
                                $groupItems[] = $item;
                            }
                        }
                        if (count($groupItems) > 0) {
                            $group['items'] = $groupItems;
                            $menuItems[$module->adminMenuOrder][] = $group;
                        }
                    } else {
                        if ($this->checkItemAccess($group)) {
                            $menuItems[$module->adminMenuOrder][] = $group;
                        }
                    }
                }
            }
        }

        ksort($menuItems);

        if (!empty($menuItems))
            $menuItems = call_user_func_array('array_merge', $menuItems);

        foreach ($menuItems as &$group) {
            $hasActive = false;
            $group['active'] = $this->isItemActive($group);
            if (isset ($group['items'])) {
                foreach ($group['items'] as &$item) {
                    $item['active'] = $this->isItemActive($item);
                    $hasActive = $item['active'] ? true : $hasActive;
                }
                $group['active'] = $hasActive;
            }
        }

        return $menuItems;
    }

    protected function checkAccessByRoute($route)
    {
        static $_routes = [];

        if (isset($_routes[$route]))
            return $_routes[$route];

        $operation = Rbac::routeToOperation($route);

        if ($operation === null) {
            return true;
        }

        return ($_routes[$route] = Yii::$app->user->can($operation));
    }

    /**
     * Checks whether a menu item is active.
     * This is done by checking if [[route]] and [[params]] match that specified in the `url` option of the menu item.
     * When the `url` option of a menu item is specified in terms of an array, its first element is treated
     * as the route for the item and the rest of the elements are the associated parameters.
     * Only when its route and parameters match [[route]] and [[params]], respectively, will a menu item
     * be considered active.
     * @param array $item the menu item to be checked
     * @return boolean whether the menu item is active
     */
    protected function isItemActive($item)
    {
        if (isset($item['route']) && is_array($item['route']) && isset($item['route'][0])) {
            $route = $item['route'][0];
            if ($route[0] !== '/' && Yii::$app->controller) {
                $route = Yii::$app->controller->module->getUniqueId() . '/' . $route;
            }
            if (ltrim($route, '/') !== $this->route) {
                return false;
            }
            unset($item['route']['#']);
            if (count($item['route']) > 1) {
                foreach (array_splice($item['route'], 1) as $name => $value) {
                    if ($value !== null && (!isset($this->params[$name]) || $this->params[$name] != $value)) {
                        return false;
                    }
                }
            }

            return true;
        }

        return false;
    }

    /**
     * @param array $item
     * @return bool
     */
    protected function checkItemAccess($item)
    {
        if (isset($item['authItem'])) {
            return Yii::$app->user->can($item['authItem']);
        } elseif (isset($item['roles'])) {
            foreach ($item['roles'] as $role) {
                if (Yii::$app->user->can($role)) {
                    return true;
                }
            }
            return false;
        } elseif (isset($item['route']) && is_array($item['route'])) {
            return $this->checkAccessByRoute($item['route'][0]);
        }

        return true;
    }
} 