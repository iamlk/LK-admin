<?php

namespace app\modules\backend\controllers;

use Yii;
use app\modules\backend\models\AdminUser;
use app\modules\backend\models\AdminUserSearch;
use app\modules\backend\components\BackendController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use mdm\admin\models\searchs\AuthItem;
use mdm\admin\models\Assignment;

/**
 * AdminUserController implements the CRUD actions for AdminUser model.
 */
class AdminUserController extends BackendController
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all AdminUser models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new AdminUserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $this->module->params['pageSize']);
        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single AdminUser model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new AdminUser model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new AdminUser(['scenario' => 'create']);

        //DROP LIST
        $role = new AuthItem(['type' => 1]);
        $role = $role->search([]);
        $role = $role->getModels();
        $dropList = [];
        foreach($role as $name=>$d){
            if($name=='Administrator' || $name=='Visitor' || $name=='管理员') continue;
            $dropList[$name] = $d->name;
        }

        $post = Yii::$app->request->post();
        if($post){
            $items = [];
            $items[] = $post['AdminUser']['role'];
            $model->role = $post['AdminUser']['role'];
            $model->created_at = time();
            $model->updated_at = time();
            $model->auth_key = "1";
            $model->email = "1";
        }
        if ($model->load($post) && $model->save()) {
            $assignment = new Assignment($model->id);
            $assignment->assign($items);
            return $this->showFlash('添加成功', 'success',['index']);
        } else {
            return $this->render('create', [
                'model' => $model,
                'dropList' => $dropList,
            ]);
        }
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        //DROP LIST
        $role = new AuthItem(['type' => 1]);
        $role = $role->search([]);
        $role = $role->getModels();
        $dropList = [];
        foreach($role as $name=>$d){
            if($name=='Administrator' || $name=='Visitor' || $name=='管理员') continue;
            $dropList[$name] = $d->name;
        }

        $post = Yii::$app->request->post();
        if($post){
            $items = [];
            $items[] = $post['AdminUser']['role'];
            $model->role = $post['AdminUser']['role'];
            $model->updated_at = time();
        }
        if ($model->load($post) && $model->save()) {
            $assignment = new Assignment($model->id);
            $assignment->assign($items);
            return $this->showFlash('修改成功', 'success',['index']);
        } else {
            return $this->render('update', ['model' => $model, 'dropList' => $dropList]);
        }
    }

    /**
     * Updates an existing AdminUser model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionReset($id)
    {
        $model = $this->findModel($id);
        $model->scenario = 'update';
        $model->updated_at = time();
        $model->password_hash = AdminUser::createPassword('123456');
        $model->save();
        return $this->showFlash('密码已重置为123456', 'success',['index']);
    }

    /**
     * Deletes an existing AdminUser model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        if($model->id==Yii::$app->user->id){
            return $this->showFlash('不可删除自己当前使用的账户','warning', Yii::$app->getUser()->getReturnUrl());
        }
        if($model->delete()) {
            $assignment = new Assignment($model->id);
            $assignment->assign([]);
            return $this->showFlash('删除成功','success',['index']);
        }else{
            return $this->showFlash('删除失败','danger', Yii::$app->getUser()->getReturnUrl());
        }
    }

    /**
     * Finds the AdminUser model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return AdminUser the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = AdminUser::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
