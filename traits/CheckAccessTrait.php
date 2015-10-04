<?php

namespace yz\admin\traits;
use yii\filters\AccessControl;
use yz\admin\helpers\Rbac;


/**
 * Trait CheckAccessTrait
 */
trait CheckAccessTrait
{
    public function behaviors()
    {
        return [
            'accessControl' => $this->accessControlBehavior(),
        ];
    }

    protected function accessControlBehavior()
    {
        return [
            'class' => AccessControl::className(),
            'rules' => [
                [
                    'allow' => true,
                    'matchCallback' => function ($rule, $action) {
                        return \Yii::$app->user->can(Rbac::operationName($this, $action->id));
                    },
                ],
                [
                    'allow' => false,
                ]
            ],
        ];
    }
}