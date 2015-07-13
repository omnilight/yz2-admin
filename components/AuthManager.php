<?php

namespace yz\admin\components;

use yii\helpers\Inflector;
use yii\rbac\DbManager;
use yii\web\Controller;
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