<?php

namespace yz\admin\rbac;

use yii\helpers\Inflector;
use yii\rbac\DbManager;
use yii\web\Controller;
use yz\admin\helpers\Rbac;
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
} 