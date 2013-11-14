<?php

namespace yz\admin\controllers\backend;
use yii\helpers\ArrayHelper;
use yii\web\AccessControl;
use yii\web\HttpException;
use yii\web\VerbFilter;
use yz\admin\components\BackendController;
use app\models\backend\Users;

/**
 * Class UsersController
 */
class UsersController extends BackendController
{
    public function behaviors()
    {
        return ArrayHelper::merge(parent::behaviors(),[
            'verb' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post']
                ]
            ]
        ]);
    }


    public function actionIndex()
    {
        $searchModel = new Users();
        $dataProvider = $searchModel->search($_GET);

        return $this->render('index',[
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    public function actionCreate()
    {

    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load($_POST) && $model->save()) {
            return $this->redirect(['view']);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    public function actionDelete()
    {

    }

    /**
     * Finds Users model
     * @param int $id
     * @return Users
     * @throws \yii\web\HttpException
     */
    protected function findModel($id)
    {
        if (($model = Users::find(['id' => $id])) !== null) {
            return $model;
        } else {
            throw new HttpException(404, \Yii::t('admin','The requested model does not exist.'));
        }
    }
} 