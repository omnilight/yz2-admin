<?php

namespace yz\admin\components;

use yii\rbac\DbManager;
use yz\interfaces\User;

/**
 * Class AuthManager
 * @package yz\admin\components
 */
class AuthManager extends DbManager
{
    public $itemTable = '{{%admin_auth_item}}';
    public $itemChildTable = '{{%admin_auth_item_child}}';
    public $assignmentTable = '{{%admin_auth_assignment}}';

    /**
     * @inheritdoc
     */
    public function checkAccess($userId, $itemName, $params = [])
    {
        /** @var User $user */
        $user = \Yii::$app->user->getIdentity();
        if (($user instanceof User) && $user->is_super_admin) {
            return true;
        } else
            return parent::checkAccess($userId, $itemName, $params);
    }

    /**
     * Returns name of the operation based on controller's class and it's action name
     * @param BackendController|string $controller
     * @param string $action
     * @return string
     */
    public static function getOperationName($controller, $action)
    {
        if (is_object($controller))
            $controller = $controller->className();
        /** @var string $controller */
        return $controller . ':' . $action;
    }

} 