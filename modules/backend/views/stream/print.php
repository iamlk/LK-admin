<?php

use yii\helpers\Html;
use app\modules\backend\widgets\GridView;
use yii\grid\CheckboxColumn;
use app\models\StreamType;
use yii\widgets\ActiveForm;
use yii\web\View;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\backend\models\NewsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $pagination yii\data\Pagination */

$this->registerJsFile('/dist/js/jquery.jqprint-0.3.js',['depends'=>['app\modules\backend\assets\BackendAsset']]);
$this->registerJsFile('/dist/js/jquery-migrate-1.2.1.min.js',['depends'=>['app\modules\backend\assets\BackendAsset']]);
$this->registerJsFile('/dist/js/bootstrap-datepicker.min.js',['depends'=>['app\modules\backend\assets\BackendAsset']]);
$this->registerCssFile('/dist/css/print.css',['media'=>'print']);



$this->title = '出入料管理';
$this->params['breadcrumbs'][] = $this->title;
?>
<style>
    .pagination {
        display: inline-block;
        padding-left: 0;
        margin: 0;
        border-radius: 4px;
    }
    th > a{
        font-size:12px;
    }
</style>
<div class="content-index">
    <div class="nav-tabs-custom">
        <div class="tab-content">
            <!-- /.box-body -->
            <?php
            $params = [
                'tableOptions'=>['id'=>'table','class'=>"table table-striped table-bordered",'border'=>'1','cellspacing'=>'0'],
                'caption'=>$total,
                'dataProvider' => $dataProvider,
                'filterModel' => null,
                'columns' => [
                    ['class' => CheckboxColumn::className()],
                    [
                        'class' => 'yii\grid\SerialColumn',
                        'header' => '<a href="#">序号</a>'
                    ],
                    [
                        'attribute' => 'type',
                        'filter'=>StreamType::$TypeList,
                        'options' => ['style' => 'width:120px'],
                        'format' => 'html',
                        'value' => function ($item) {
                            if($item['type'] == StreamType::IN) {
                                return '<span class="badge bg-green">' . $item['type'] . '</span>';
                            }else{
                                return '<span class="badge bg-red">' . $item['type'] . '</span>';
                            }
                        },
                        'filterInputOptions' => ['prompt'=>'进出流水','class'=>'form-control'],
                    ],
                    [
                        'filterType'=>'date',
                        'attribute' => 'start_time',
                        //'format' => 'datetime',
                        'options' => ['style' => 'width:160px']
                    ],
                    [
                        'filterType'=>'date',
                        'attribute' => 'end_time',
                        //'format' => 'datetime',
                        'options' => ['style' => 'width:160px']
                    ],
                    'start_weight',
                    'end_weight',
                    'the_weight',
                    'total_weight',
                    //'property_no',
                    [
                        'attribute' => 'well_no',
                        //'filter'=>StreamType::GetList(StreamType::WELL),
                        'options' => ['style' => 'width:100px'],
                        'format' => 'html',
                        'filterInputOptions' => ['prompt'=>'全部','class'=>'form-control'],
                    ],
                    'team_no',
                    'well_class',
                    //'start_time:datetime',
                    ['class' => 'yii\grid\ActionColumn', 'template' => '{update} {delete}'],
                ],
            ]
            ?>

            <?= GridView::widget($params); ?>
        </div>
    </div>
</div>