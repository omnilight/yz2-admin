<?php

namespace yz\admin\controllers;

use Yii;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yz\admin\contracts\AccessControlInterface;
use yz\admin\forms\ChangeUserPasswordForm;
use yz\admin\models\search\UserSearch;
use yz\admin\models\User;
use yz\admin\models\UserCreate;
use yz\admin\traits\CheckAccessTrait;
use yz\admin\traits\CrudTrait;
use yz\widgets\ActiveForm;
use yz\Yz;

/**
 * UsersController implements the CRUD actions for User model.
 */
class UsersController extends Controller implements AccessControlInterface
{
    use CrudTrait, CheckAccessTrait;

    public function behaviors()
    {
        return ArrayHelper::merge(parent::behaviors(), [
            'accessControl' => $this->accessControlBehavior(),
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
        $model = new UserCreate;

        if (\Yii::$app->request->isAjax) {
            \Yii::$app->response->format = Response::FORMAT_JSON;
            $model->load(\Yii::$app->request->post());
            return ActiveForm::validate($model);
        }

        if ($model->load(\Yii::$app->request->post()) && $model->save()) {
            \Yii::$app->session->setFlash(Yz::FLASH_SUCCESS, \Yii::t('admin/t', 'Record was successfully created'));
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
        $passwordForm->askOldPassword = false;

        if (\Yii::$app->request->isAjax) {
            \Yii::$app->response->format = Response::FORMAT_JSON;
            $model->load(\Yii::$app->request->post());
            return ActiveForm::validate($model);
        }

        if ($model->load(\Yii::$app->request->post()) && $model->save()) {
            \Yii::$app->session->setFlash(Yz::FLASH_SUCCESS, \Yii::t('admin/t', 'Record was successfully updated'));
            return $this->getCreateUpdateResponse($model);
        } elseif ($passwordForm->load(\Yii::$app->request->post()) && $passwordForm->process()) {
            \Yii::$app->session->setFlash(Yz::FLASH_SUCCESS, \Yii::t('admin/t', 'Password was successfully changed'));
            return $this->getCreateUpdateResponse($model);
        } elseif (Yii::$app->request->post('__action') == 'reset_access_token') {
            $model->access_token = \Yii::$app->security->generateRandomString(User::ACCESS_TOKEN_LENGTH);
            if ($model->save())
                \Yii::$app->session->setFlash(Yz::FLASH_SUCCESS, \Yii::t('admin/t', 'Access token was successfully changed'));
            else
                \Yii::$app->session->setFlash(Yz::FLASH_SUCCESS, \Yii::t('admin/t', 'Unknown error'));
            return $this->redirect('');
        } else {
            return $this->render('update', [
                'model' => $model,
                'passwordForm' => $passwordForm,
            ]);
        }
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
        if (($model = User::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
     * Deletes an existing User model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param mixed $id
     * @return mixed
     */
    public function actionDelete(array $id)
    {
        if (is_array($id)) {
            $message = \Yii::t('admin/t', 'Records were successfully deleted');
        } else {
            $id = (array)$id;
            $message = \Yii::t('admin/t', 'Record was successfully deleted');
        }

        foreach ($id as $id_)
            $this->findModel($id_)->delete();

        \Yii::$app->session->setFlash(Yz::FLASH_SUCCESS, $message);

        return $this->redirect(['index']);
    }
}
