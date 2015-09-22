<?php

namespace yz\admin\components;

use yii\web\Controller;
use yz\admin\helpers\Rbac;

/**
 * Class AuthManager
 * * @deprecated Use [[\yz\admin\rbac\AuthManager]] instead
 */
class AuthManager extends \yz\admin\rbac\AuthManager
{
    /**
     * Returns name of the operation based on controller's class and it's action name
     * @param Controller|string $controller
     * @param string $action
     * @return string
     * @deprecated Use [[\yz\admin\helpers\Rbac::getOperationName]]
     */
    public static function getOperationName($controller, $action)
    {
        return Rbac::operationName($controller, $action);
    }

    /**
     * Generates correct auth item name event for long strings
     * @param $authItem
     * @return string
     * @deprecated Use [[\yz\admin\helpers\Rbac::authItemName]]
     */
    public static function authItemName($authItem)
    {
        return Rbac::authItemName($authItem);
    }
} 