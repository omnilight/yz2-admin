<?php

namespace yz\admin\controllers;

use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yz\admin\forms\ChangeUserPasswordForm;
use yz\admin\models\User;
use yz\admin\traits\CrudTrait;
use yz\widgets\ActiveForm;


/**
 * Class ProfileController
 * @package \yz\admin\controllers
 */
class ProfileController extends Controller
{
    use CrudTrait;

    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(parent::behaviors(), [
            'accessControl' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'allow' => false,
                    ],
                ]
            ]
        ]);
    }


    public function actionIndex()
    {
        /** @var User $model */
        $model = \Yii::$app->user->identity;
        $passwordForm = new ChangeUserPasswordForm($model);

        if (\Yii::$app->request->isAjax) {
            \Yii::$app->response->format = Response::FORMAT_JSON;
            $model->load(\Yii::$app->request->post());
            return ActiveForm::validate($model);
        }

        if ($model->load(\Yii::$app->request->post()) && $model->save()) {
            \Yii::$app->session->setFlash(\yz\Yz::FLASH_SUCCESS, \Yii::t('admin/t', 'Your profile was successfully updated'));
            return $this->getCreateUpdateResponse($model);
        } elseif ($passwordForm->load(\Yii::$app->request->post()) && $passwordForm->process()) {
            \Yii::$app->session->setFlash(\yz\Yz::FLASH_SUCCESS, \Yii::t('admin/t', 'Your password was successfully changed'));
            return $this->getCreateUpdateResponse($model);
        } else {
            return $this->render('index', [
                'model' => $model,
                'passwordForm' => $passwordForm,
            ]);
        }
    }
} 