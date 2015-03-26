<?php

namespace yz\admin\components;

use backend\base\Controller;
use Yii;
use yii\base\Application;
use yii\base\Object;
use yii\helpers\ArrayHelper;
use yii\helpers\FileHelper;
use yii\helpers\Inflector;
use yii\rbac\Item;
use yz\Module;


/**
 * Class AuthItemsFinder
 */
class AuthItemsFinder extends Object
{
    /**
     * @var Application
     */
    public $app;

    /**
     * Find and saves auth items
     */
    public function findAndSave()
    {
        $authItems = array_merge($this->getAuthItemsFromApp(), $this->getAuthItemsFromModules());

        $authManager = $this->app->authManager;
        $items = [];
        foreach ($authItems as $name => $authItem) {
            /** @var array $authItem [Name, type, [child1, child2, ...]] */
            list($itemDescription, $itemType, $itemChildren) = $authItem;

            $item = null;
            if ($item === null) {
                $item = $authManager->getPermission($name);
            }
            if ($item === null) {
                $item = $authManager->getRole($name);
            }

            if ($item !== null) {
                if ($item->description != $itemDescription) {
                    $item->description = $itemDescription;
                    $authManager->update($name, $item);
                }
            } else {
                $item = new Item();
                $item->type = $itemType;
                $item->name = $name;
                $item->description = $itemDescription;
                $authManager->add($item);
            }
            $items[$name] = $item;
        }
        unset($itemDescription, $itemType, $itemChildren);

        foreach ($authItems as $name => $authItem) {
            /** @var array $authItem [Name, type, [child1, child2, ...]] */
            list($itemDescription, $itemType, $itemChildren) = $authItem;

            $children = ArrayHelper::getColumn($authManager->getChildren($name), 'name');
            foreach ($itemChildren as $childName) {
                if (!in_array($childName, $children))
                    $authManager->addChild($items[$name], $items[$childName]);
            }
        }
        unset($itemDescription, $itemType, $itemChildren);
    }

    /**
     * @return array
     */
    protected function getAuthItemsFromApp()
    {
        $list = [];

        if (!is_dir($this->app->controllerPath)) {
            return $list;
        }

        $moduleAuthItemName = $this->className();

        foreach (FileHelper::findFiles($this->app->controllerPath, ['only' => ['*Controller.php']]) as $file) {
            $relativePath = basename($file);
            $controllerBaseClassName = substr($relativePath, 0, -4); // Removing .php
            $controllerName = substr($controllerBaseClassName, 0, -10); // Removing Controller
            $controllerClassName = ltrim($this->controllerNamespace . '\\' . $controllerBaseClassName);
            if (is_subclass_of($controllerClassName, Controller::className())) {
                $controllerAuthItemName = $controllerClassName;
                $controllerDescription = \Yii::t('yz', 'Access to the section "Application/{controller}"', [
                    'controller' => $controllerName,
                ]);
                $controllerAuthItem = [
                    $controllerAuthItemName => [$controllerDescription, Item::TYPE_PERMISSION, []],
                ];
                $moduleAuthItem[$moduleAuthItemName][2][] = $controllerAuthItemName;

                $controllerInstance = $this->app->createControllerByID(Inflector::camel2id($controllerName));
                $actions = array_keys($controllerInstance->actions());

                $ref = new \ReflectionClass($controllerClassName);
                $methods = $ref->getMethods(\ReflectionMethod::IS_PUBLIC);

                $actionsAuthItems = [];
                foreach (array_merge($actions, $methods) as $method) {
                    if (is_string($method))
                        $action = ucfirst($method);
                    else {
                        /** @var \ReflectionMethod $method */
                        if (!preg_match('/^action([A-Z].*)$/', $method->getName(), $m))
                            continue;
                        $action = $m[1];
                    }
                    $actionAuthItemName = AuthManager::getOperationName($controllerClassName, $action);
                    $actionDescription = \Yii::t('yz', 'Access to the action "Application/{controller}/{action}"', [
                        'action' => $action,
                        'controller' => $controllerName,
                    ]);
                    $actionsAuthItems[$actionAuthItemName] = [$actionDescription, Item::TYPE_PERMISSION, []];
                    $controllerAuthItem[$controllerAuthItemName][2][] = $actionAuthItemName;
                }

                $list = array_merge($list, $controllerAuthItem, $actionsAuthItems);
            }
        }

        return $list;
    }

    /**
     * @return array
     */
    protected function getAuthItemsFromModules()
    {
        $modules = $this->app->getModules();

        $authItems = [];
        foreach ($modules as $id => $config) {
            $module = $this->app->getModule($id);
            if (!($module instanceof Module))
                continue;
            $authItems = array_merge($authItems, $module->getAuthItems());
        }

        return $authItems;
    }
}