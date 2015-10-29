<?php

namespace yz\admin\controllers;

use yii\filters\auth\CompositeAuth;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\auth\QueryParamAuth;
use yii\rest\Controller;
use yii\web\NotFoundHttpException;
use yz\admin\AdminModuleTrait;
use yz\admin\contracts\AccessControlInterface;
use yz\admin\models\User;
use yz\admin\tokens\AdminLoginToken;
use yz\admin\traits\CheckAccessTrait;


/**
 * Class TokenExchangeController
 */
class TokenExchangeController extends Controller implements AccessControlInterface
{
    use CheckAccessTrait, AdminModuleTrait;

    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(parent::behaviors(), [
            'auth' => [
                'class' => CompositeAuth::class,
                'authMethods' => [
                    HttpBearerAuth::class,
                    QueryParamAuth::class,
                ]
            ],
            'accessControl' => $this->accessControlBehavior(),
        ]);
    }

    /**
     * @inheritDoc
     */
    public function beforeAction($action)
    {
        if (self::getAdminModule()->allowLoginViaToken == false) {
            throw new NotFoundHttpException();
        }

        return parent::beforeAction($action);
    }


    public function actionExchange()
    {
        /** @var User $user */
        $user = \Yii::$app->user->identity;
        $token = AdminLoginToken::createToken($user->id);
        \Yii::$app->user->logout();
        return [$user->id, $token->token];
    }
}