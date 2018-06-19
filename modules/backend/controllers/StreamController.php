<?php

namespace app\modules\backend\controllers;

use app\models\StreamType;
use Yii;
use app\models\Stream;
use app\modules\backend\models\StreamSearch;
use app\modules\backend\components\BackendController;
use app\modules\backend\actions\StreamDeleteAllAction;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;

/**
 * ContentController implements the CRUD actions for Content model.
 */
class StreamController extends BackendController
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

    public function actions()
    {
        return array_merge(parent::actions(),[
            'delete-all'=>[
                'class'=>StreamDeleteAllAction::className()
            ]
        ]);
    }

    public function actionExport()
    {
        $session = Yii::$app->session;
        $model = Stream::find()->all();
        \moonland\phpexcel\Excel::widget([
            'models' => $model,
            'mode' => 'export', //default value as 'export'
            'columns' => [
                'type',
                ['attribute'=>'start_time','format'=>'datetime'],
                ['attribute'=>'end_time','format'=>'datetime'],
                'start_weight',
                'end_weight',
                'the_weight',
                'total_weight',
                'property_no',
                'well_no',
                'team_no',
                'well_class'
                ],
        ]);
    }
    /**
     * Lists all Content models.
     * @return mixed
     */
    public function actionIndex()
    {
        $get = Yii::$app->request->get();
        $session = Yii::$app->session;
        if(empty($get['StreamSearch'])){
            $session['search'] = [];
        }else{
            $session['search'] = $get['StreamSearch'];
        }
        $searchModel = new StreamSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $this->module->params['pageSize']);
        //$dataProvider->setSort(false);
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider
        ]);
    }

    public function actionData()
    {
        $searchModel = new StreamSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, 1);
        return $this->render('data', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider
        ]);
    }

    /**
     * Displays a single Content model.
     * @param integer $id
     * @return mixed
     */
    public function actionView()
    {

        /**
        for($i=0; $i<5000; $i++){
            $model = new Stream();
            $model->uid = time().'_'.$i;
            $model->type = rand(0,1)?'出料':'进料';
            $model->start_time = date('Y-m-d H:i:s',1520002278+$i*3000);
            $model->end_time = date('Y-m-d H:i:s',1520002278+$i*3500);
            $model->start_weight = rand(100,1000)+rand(1,90)/100;
            $random = rand(1,1000)+rand(1,90)/100;
            $model->end_weight = ($model->type == '出料')?($model->start_weight-$random):($model->start_weight+$random);
            $model->property_no = '罐子'.rand(1,4);
            $model->well_no = '川A'.rand(1000,9999);
            $model->team_no = '钻井队'.rand(1,9);
            $model->well_class = '东华公司';
            $model->save();
        }
        $list = Stream::find()->all();
        $data = [];
        foreach($list as $li){
            $data[] = $li->attributes;
        }
        file_put_contents('data.json',json_encode($data,true));*/

        $data = file_get_contents('data.json');
        $data = json_decode($data,true);
        //Stream::importData($data);
        Stream::initData();
        return $this->render('_search');
    }

    /**
     * Creates a new Content model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new News();
        $post = Yii::$app->request->post();
        if ($post) {
            if ($model->load($post) && $model->save()) {
                return $this->showFlash('添加成功','success');
            }
        }
        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Content model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->showFlash('修改数据成功','success');
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Content model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        return $this->showFlash('删除成功','success',['index']);
        if($this->findModel($id)->delete()){
            return $this->showFlash('删除成功','success',['index']);
        }
        return $this->showFlash('删除失败','danger',Yii::$app->getUser()->getReturnUrl());
    }

    /**
     * Finds the Content model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return News the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Stream::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
