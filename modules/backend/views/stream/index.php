<?php

use yii\helpers\Html;
use app\modules\backend\widgets\GridView;
use yii\grid\CheckboxColumn;
use app\models\StreamType;
use yii\web\View;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\backend\models\NewsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $pagination yii\data\Pagination */

$this->registerJsFile('/dist/js/jquery.jqprint-0.3.js',['depends'=>['app\modules\backend\assets\BackendAsset']]);
$this->registerJsFile('/dist/js/jquery-migrate-1.2.1.min.js',['depends'=>['app\modules\backend\assets\BackendAsset']]);
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
</style>
<div class="content-index">
    <div class="nav-tabs-custom">
        <ul class="nav nav-tabs" role="tablist">
            <li role="presentation" class="active"><?= Html::a($this->title, ['index']) ?></li>
            <li role="presentation"><?= Html::a('进出料统计图', ['data']) ?></li>
            <li role="presentation"><?= Html::a('导入数据', ['create']) ?></li>
        </ul>
        <div class="tab-content">
            <?php
            $params = [
                    'caption'=>$total,
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,
                    'columns' => [
                        ['class' => CheckboxColumn::className()],
                        [
                            'attribute' => 'id',
                            'filter' => '',
                            'options' => ['style' => 'width:60px']
                        ],
                        [
                            'attribute' => 'type',
                            'filter'=>StreamType::$TypeList,
                            'options' => ['style' => 'width:100px'],
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