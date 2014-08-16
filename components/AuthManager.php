<?php

namespace yz\admin\components;

use backend\base\Controller;
use yii\helpers\VarDumper;
use yii\rbac\DbManager;
use yz\admin\models\User;

/**
 * Class AuthManager
 * @package yz\admin\components
 */
class AuthManager extends DbManager
{
    public $itemTable = '{{%admin_auth_item}}';
    public $itemChildTable = '{{%admin_auth_item_child}}';
    public $assignmentTable = '{{%admin_auth_assignment}}';
    public $ruleTable = '{{%admin_auth_rule}}';

    /**
     * @inheritdoc
     */
    public function checkAccess($userId, $permissionName, $params = [])
    {
        $user = User::findOne($userId);
        if (($user instanceof User) && $user->is_super_admin) {
            return true;
        } else
            return parent::checkAccess($userId, $permissionName, $params);
    }

    /**
     * Returns name of the operation based on controller's class and it's action name
     * @param Controller|string $controller
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