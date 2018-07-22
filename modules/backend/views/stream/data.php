<?php

use yii\helpers\Html;
use app\modules\backend\widgets\DataView;
use yii\data\ActiveDataProvider;
use app\models\StreamType;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\backend\models\NewsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $pagination yii\data\Pagination */

$this->title = '进出料统计图';
$this->params['breadcrumbs'][] = $this->title;

$this->registerJs('$(function () {
        "use strict";
        var bar = new Morris.Bar({
            element: \'bar-chart\',
            resize: true,
            data: '.$json.',
            barColors: [\'#00c0ef\', \'#dd4b39\'],
            xkey: \'y\',
            ykeys: [\'a\', \'b\'],
            labels: [\'进料\', \'出料\'],
            hideHover: \'auto\'
        });
        bar.redraw();
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
                        'attribute' => 'well_no',
                        //'filter'=>StreamType::GetList(StreamType::WELL),
                        'options' => ['style' => 'width:120px'],
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
                <h3 class="box-title">统计图表</h3>

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