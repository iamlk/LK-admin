<?php

use yii\helpers\Html;
use app\modules\backend\widgets\DataView;
use yii\grid\CheckboxColumn;
use app\models\StreamType;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\backend\models\NewsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $pagination yii\data\Pagination */

$this->title = '数据报表';
$this->params['breadcrumbs'][] = $this->title;

$this->registerJs('$(function () {
        "use strict";
        var bar = new Morris.Bar({
            element: \'bar-chart\',
            resize: true,
            data: [
                {y: \'2006\', a: 100, b: 90},
                {y: \'2007\', a: 75, b: 65},
                {y: \'2008\', a: 50, b: 40},
                {y: \'2009\', a: 75, b: 65},
                {y: \'2010\', a: 50, b: 40},
                {y: \'2011\', a: 75, b: 65},
                {y: \'2012\', a: 100, b: 90}
            ],
            barColors: [\'#00a65a\', \'#dd4b39\'],
            xkey: \'y\',
            ykeys: [\'a\', \'b\'],
            labels: [\'进料\', \'出料\'],
            hideHover: \'auto\'
        });
    });');
$this->registerCss('.table-striped>tbody>tr{
        display:none;
    }');
?>
<div class="content-index">
    <div class="nav-tabs-custom">
        <ul class="nav nav-tabs" role="tablist">
            <li role="presentation"><?= Html::a('出入料管理', ['index']) ?></li>
            <li role="presentation" class="active"><?= Html::a($this->title, ['data']) ?></li>
            <li role="presentation"><?= Html::a('导入数据', ['create']) ?></li>
        </ul>
        <div class="tab-content">
            <?php
            $params = [
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'columns' => [
                    [
                        'attribute' => 'type',
                        'filter'=>StreamType::$TypeList,
                        'options' => ['style' => 'width:80px'],
                        'format' => 'html',
                        'value' => function ($item) {
                            if($item['type'] == StreamType::IN) {
                                return '<span class="badge bg-green">' . $item['type'] . '</span>';
                            }else{
                                return '<span class="badge bg-red">' . $item['type'] . '</span>';
                            }
                        },
                        'filterInputOptions' => ['prompt'=>'全部','class'=>'form-control'],
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
                    [
                        'attribute' => 'property_no',
                        'filter'=>StreamType::GetList(StreamType::PROPERTY),
                        'options' => ['style' => 'width:80px'],
                        'format' => 'html',
                        'filterInputOptions' => ['prompt'=>'全部','class'=>'form-control'],
                    ],
                    [
                        'attribute' => 'well_no',
                        'filter'=>StreamType::GetList(StreamType::WELL),
                        'options' => ['style' => 'width:80px'],
                        'format' => 'html',
                        'filterInputOptions' => ['prompt'=>'全部','class'=>'form-control'],
                    ],
                    [
                        'attribute' => 'team_no',
                        'filter'=>StreamType::GetList(StreamType::TEAM),
                        'options' => ['style' => 'width:80px'],
                        'format' => 'html',
                        'filterInputOptions' => ['prompt'=>'全部','class'=>'form-control'],
                    ],
                    [
                        'attribute' => 'well_class',
                        'filter'=>StreamType::GetList(StreamType::CLS),
                        'options' => ['style' => 'width:80px'],
                        'format' => 'html',
                        'filterInputOptions' => ['prompt'=>'全部','class'=>'form-control'],
                    ],
                ],
            ]
            ?>

            <?= DataView::widget($params); ?>
        </div>


        <!-- BAR CHART -->
        <div class="box box-success">
            <div class="box-header with-border">
                <h3 class="box-title">Bar Chart</h3>

                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                </div>
            </div>
            <div class="box-body chart-responsive">
                <div class="chart" id="bar-chart" style="height: 300px;"></div>
            </div>
            <!-- /.box-body -->
        </div>
        <!-- /.box -->
    </div>
</div>