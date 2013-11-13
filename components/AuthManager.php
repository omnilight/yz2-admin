<?php

namespace yz\admin\components;

use yii\rbac\DbManager;
use yz\admin\components\BackendController;
use yz\admin\models\BaseUsers as BackendUser;

/**
 * Class AuthManager
 * @package yz\admin\components
 */
class AuthManager extends DbManager
{
    /**
     * @inheritdoc
     */
    public function checkAccess($userId, $itemName, $params = [])
    {
        /** @var BackendUser $user */
        if(($user = \Yii::$app->user->getIdentity()) instanceof BackendUser && $user->is_super_admin) {
            return true;
        } else
            return parent::checkAccess($userId, $itemName, $params);
    }

    /**
     * Returns name of the operation based on controller's class and it's action name
     * @param BackendController $controller
     * @param string $action
     * @return string
     */
    public static function getOperationName($controller, $action)
    {
        return $controller->className() . '.' . $action;
    }

} 