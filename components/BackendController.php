<?php

namespace yz\admin\components;

use yii\web\AccessControl;
use yii\web\AccessRule;
use yz\controllers\Controller as BaseController;


/**
 * Class BackendController
 * @property array $accessRules
 * @package yz\admin\base
 */
class BackendController extends BaseController
{
    public $layout = '@yz/admin/views/layouts/main';

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => $this->getAccessRules(),
            ]
        ];
    }

    /**
     * Returns list of the access rules for {@see AccessControl} filter behavior
     * @return array
     */
    protected function getAccessRules()
    {
        return [
            [
                'allow' => true,
                'matchCallback' => function($rule, $action) {
                        $this->checkAccess($rule, $action);
                    },
            ],
            [
                'allow' => false,
            ]
        ];
    }

    /**
     * Checks access to the action of the controller for currently logged user
     * @param AccessRule $rule
     * @param string $action
     * @return bool
     */
    protected function checkAccess($rule, $action)
    {
        return \Yii::$app->user->checkAccess(AuthManager::getOperationName($this, $action));
    }
} 