<?php

namespace yz\admin\controllers;

use backend\base\Controller;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\rbac\Item;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use Yii;
use yz\admin\models\AuthItem;
use yz\admin\models\Role;
use yz\admin\models\search\RoleSearch;
use yz\admin\widgets\ActiveForm;
use yz\Module;
use yz\Yz;

/**
 * RolesController implements the CRUD actions for Role model.
 */
class RolesController extends Controller
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
     * Lists all Role models.
     * @param string $export Set export type
     * @return mixed
     */
    public function actionIndex($export = null)
    {
        $searchModel = new RoleSearch;
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    /**
     * Creates a new Role model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Role;

        if (\Yii::$app->request->isAjax) {
            \Yii::$app->response->format = Response::FORMAT_JSON;
            $model->load($_POST);
            return ActiveForm::validate($model);
        }

        if ($model->load(\Yii::$app->request->post()) && $model->save()) {
            \Yii::$app->session->setFlash(\yz\Yz::FLASH_SUCCESS, \Yii::t('admin/t', 'Record was successfully created'));
            return $this->getCreateUpdateResponse($model);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Role model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if (\Yii::$app->request->isAjax) {
            \Yii::$app->response->format = Response::FORMAT_JSON;
            $model->load(\Yii::$app->request->post());
            return ActiveForm::validate($model);
        }

        if ($model->load(\Yii::$app->request->post()) && $model->save()) {
            \Yii::$app->session->setFlash(\yz\Yz::FLASH_SUCCESS, \Yii::t('admin/t', 'Record was successfully updated'));
            return $this->getCreateUpdateResponse($model);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }


    /**
     * Deletes an existing Role model.
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

        \Yii::$app->session->setFlash(\yz\Yz::FLASH_SUCCESS, $message);

        return $this->redirect(['index']);
    }

    public function actionDiscoverAuthItems()
    {
        $modules = Yii::$app->getModules();

        $authItems = [];
        foreach ($modules as $id => $config) {
            $module = Yii::$app->getModule($id);
            if (!($module instanceof Module))
                continue;
            $authItems = array_merge($authItems, $module->getAuthItems());
        }

        $authManager = Yii::$app->authManager;
        $items = [];
        foreach ($authItems as $name => $authItem) {
            /** @var array $authItem [Name, type, [child1, child2, ...]] */
            list($itemDescription, $itemType, $itemChildren) = $authItem;

            $item = null;
            if ($item === null) {
                $item = $authManager->getPermission($name);
            }
            if ($item === null) {
                $item = $authManager->getRole($name);
            }

            if ($item !== null) {
                if ($item->description != $itemDescription) {
                    $item->description = $itemDescription;
                    $authManager->update($name, $item);
                }
            } else {
                $item = new Item();
                $item->type = $itemType;
                $item->name = $name;
                $item->description = $itemDescription;
                $authManager->add($item);
            }
            $items[$name] = $item;
        }
        unset($itemDescription, $itemType, $itemChildren);

        foreach ($authItems as $name => $authItem) {
            /** @var array $authItem [Name, type, [child1, child2, ...]] */
            list($itemDescription, $itemType, $itemChildren) = $authItem;

            $children = ArrayHelper::getColumn($authManager->getChildren($name), 'name');
            foreach ($itemChildren as $childName) {
                if (!in_array($childName, $children))
                    $authManager->addChild($items[$name], $items[$childName]);
            }
        }
        unset($itemDescription, $itemType, $itemChildren);

        Yii::$app->session->setFlash(Yz::FLASH_SUCCESS, Yii::t('admin/t', 'Tasks and operations were successfully discovered'));

        $this->redirect(['index']);
    }

    public function actionDeletePermissions()
    {
        AuthItem::deleteAll('type in (:permissions)', [
            ':permissions' => Item::TYPE_PERMISSION,
        ]);

        Yii::$app->session->setFlash(Yz::FLASH_SUCCESS, Yii::t('admin/t', 'Permissions were successfully deleted'));

        $this->redirect(['index']);
    }

    /**
     * Finds the Role model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Role the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if ($id !== null && ($model = Role::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
