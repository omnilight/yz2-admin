<?php

namespace yz\admin\helpers;
use yii\helpers\Inflector;
use yii\web\Controller;


/**
 * Class Rbac
 */
class Rbac
{
    /**
     * Returns name of the operation based on controller's class and it's action name
     * @param Controller|string $controller
     * @param string $action
     * @return string
     */
    public static function getOperationName($controller, $action)
    {
        if (is_object($controller)) {
            $controller = $controller->className();
        }
        /** @var string $controller */
        return self::authItemName($controller . ':' . Inflector::id2camel($action));
    }

    /**
     * Generates correct auth item name event for long strings
     * @param $authItem
     * @return string
     */
    public static function authItemName($authItem)
    {
        if (strlen($authItem) > 32) {
            return sprintf('%x', crc32($authItem)) . '_' . substr($authItem, -(32-9));
        }

        return $authItem;
    }
}