<?php

namespace app\modules\backend\controllers;

use Yii;
use yii\web\UploadedFile;
use yii\helpers\FileHelper;
use app\models\Stream;
use app\models\Upload;
use app\modules\backend\models\StreamSearch;
use app\modules\backend\components\BackendController;
use app\modules\backend\actions\StreamDeleteAllAction;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

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
        set_time_limit(0);
        $session = Yii::$app->session;
        $search = $session['search'];
        if($search){
            $condition = ['and'];
            if(@$search['well_no']) $condition[] = 'well_no="'.$search['well_no'].'"';
            if(@$search['type']) $condition[] = 'type="'.$search['type'].'"';
            if(@$search['start_time']) $condition[] = 'start_time >= "'.$search['start_time'].'"';
            if(@$search['end_time']) $condition[] = 'end_time <= "'.$search['end_time'].'"';
        }
        else
            $condition = '';
        $model = Stream::find()->where( $condition)->orderBy(['start_time'=>SORT_ASC])->each(500);
        //$model = Stream::find()->where( $condition)->orderBy(['start_time'=>SORT_ASC])->limit(40000)->all();
        \moonland\phpexcel\Excel::widget([
            'models' => $model,
            'fileName' => 'Data.xlsx',
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
        set_time_limit(0);
        $get = Yii::$app->request->get();
        $session = Yii::$app->session;
        $start_date = date("Y-m-d", strtotime("30 days ago"));
        if(empty($get['StreamSearch'])){
            if(empty($get['type'])){
                $session['StreamSearch'] = ['start_time'=>$start_date];
            }
        }else{
            $session['StreamSearch'] = $get['StreamSearch'];
        }
        $search = $session['StreamSearch'];
        $condition = ['and'];
        if($search){
            if(@$search['well_no']) $condition[] = 'well_no="'.$search['well_no'].'"';
            if(@$search['start_time']) $condition[] = 'start_time >= "'.$search['start_time'].'"';
            if(@$search['end_time']) $condition[] = 'end_time <= "'.$search['end_time'].'"';
        }
        else{
            $condition = 'start_time>="'.$start_date.'"';
        }
        $json = [];
        if(@$get['type']=='w' || empty($get['type'])) $json = Stream::byWeek($condition);
        if(@$get['type']=='m') $json = Stream::byMonth($condition);
        if(@$get['type']=='s') $json = Stream::bySeason($condition);
        $searchModel = new StreamSearch();
        $dataProvider = $searchModel->search($session, 1);
        return $this->render('data', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'json'=>$json
        ]);
    }

    /**
     * Displays a single Content model.
     * @param integer $id
     * @return mixed
     */
    public function actionView()
    {
        set_time_limit(0);



        $count = Stream::initData(337);
        Yii::$app->response->format = yii\web\Response::FORMAT_JSON;
        Yii::$app->response->data = $count;

        /**
        $data = [];
        for($i=0; $i<50000; $i++){
            $item = [];
            $random = rand(1,1000)+rand(1,90)/100;
            $item['uid']=time().'_'.$i;
            $item['type']=rand(0,1)?'出料':'进料';
            $item['start_time']=date('Y-m-d H:i:s',1430002278+$i*2000);
            $item['end_time']=date('Y-m-d H:i:s',1430002278+$i*2000+intval($random*100));
            $item['start_weight']=rand(100,1000)+rand(1,90)/100;
            $item['end_weight']=($item['type'] == '出料')?($item['start_weight']-$random):($item['start_weight']+$random);
            $item['the_weight']=$random;
            $item['property_no']='罐子'.rand(1,4);
            $item['well_no']='川A0X'.rand(15,25);
            $item['team_no']='钻井队'.rand(1,9);
            $item['well_class']='东华公司';
            $data[] = $item;
        }
        file_put_contents('data.json',json_encode($data,true));
/**


        /**
        $data = file_get_contents('data.json');
        $data = json_decode($data,true);
        //Stream::importData($data);
        Stream::initData();
        return $this->render('_search');*/
    }

    /**
     * Creates a new Content model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        set_time_limit(0);
        $model= new Upload();

        if (Yii::$app->request->isPost) {
            $file = UploadedFile::getInstance($model, 'file');
            $path=\Yii::getAlias('@webroot').'/uploads/data/';
            if ($file && $model->validate()) {
                if(!file_exists($path)){
                    FileHelper::createDirectory($path, 0777);
                }
                $fileName = date('Ymd-His') . '.' . $file->getExtension();
                $file->saveAs($path . $fileName);
                $c = file_get_contents($path . $fileName);


                $list = json_decode($c,true);
                Stream::importData($list);
                //return $this->showFlash('上传成功！','success',['create']);
            }
        }
        $count = Stream::find()->where(['is_deal'=>0])->count();
        return $this->render('create', [
            'model' => $model,
            'count' => $count,
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
