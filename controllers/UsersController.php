<?php

namespace yz\admin\controllers;

use backend\base\Controller;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\web\VerbFilter;
use Yii;
use yz\admin\forms\ChangeUserPasswordForm;
use yz\admin\models\search\UserSearch;
use yz\admin\models\User;
use yz\widgets\ActiveForm;

/**
 * UsersController implements the CRUD actions for User model.
 */
class UsersController extends Controller
{
    public function behaviors()
    {
        return ArrayHelper::merge(parent::behaviors(), [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ]);
    }

    /**
     * Lists all User models.
     * @param string $export Set export type
     * @return mixed
     */
    public function actionIndex($export = null)
    {
        $searchModel = new UserSearch;
        $dataProvider = $searchModel->search($_GET);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    /**
     * Creates a new User model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new User;

        if (\Yii::$app->request->isAjax) {
            \Yii::$app->response->format = Response::FORMAT_JSON;
            $model->load(\Yii::$app->request->post());
            return ActiveForm::validate($model);
        }

        if ($model->load(\Yii::$app->request->post()) && $model->save()) {
            \Yii::$app->session->setFlash(\yz\Yz::FLASH_SUCCESS, \Yii::t('yz/admin', 'Record was successfully created'));
            return $this->getCreateUpdateResponse($model);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing User model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $passwordForm = new ChangeUserPasswordForm($model);

        if (\Yii::$app->request->isAjax) {
            \Yii::$app->response->format = Response::FORMAT_JSON;
            $model->load(\Yii::$app->request->post());
            return ActiveForm::validate($model);
        }

        if ($model->load(\Yii::$app->request->post()) && $model->save()) {
            \Yii::$app->session->setFlash(\yz\Yz::FLASH_SUCCESS, \Yii::t('yz/admin', 'Record was successfully updated'));
            return $this->getCreateUpdateResponse($model);
        } elseif ($passwordForm->load(\Yii::$app->request->post())) {
        } else {
            return $this->render('update', [
                'model' => $model,
                'passwordForm' => $passwordForm,
            ]);
        }
    }

    /**
     * Deletes an existing User model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        \Yii::$app->session->setFlash(\yz\Yz::FLASH_SUCCESS, \Yii::t('yz/admin', 'Record was successfully deleted'));
        return $this->redirect(['index']);
    }

    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = User::find($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
