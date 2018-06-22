<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\backend\models\AdminUserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '管理员管理';
$this->params['breadcrumbs'][] = $this->title;
$data = [
    'dataProvider' => $dataProvider,
    'columns' => [
        ['class' => 'yii\grid\SerialColumn'],

        [
            'attribute' => 'id',
            'options' => ['style' => 'width:50px']
        ],
        'username',
        'role',
        [
            'attribute' => 'created_at',
            'format' => 'datetime',
        ],
        [
            'attribute' => 'updated_at',
            'format' => 'datetime',
        ],
        [
            'header'=>'操作',
            'class' => 'yii\grid\ActionColumn',
            'template' => '{update} {delete}',
            'options' => ['style' => 'width:100px'],
            'buttons' => ['update'=>
                function ($url, $model, $key) {
                    $options = ['onclick'=>'if(confirm(\'确定要将该用户密码重置?\')==false)return false;'];
                    return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, $options);
                }
            ]
        ]
    ]
];
?>
<div class="admin-user-index">
    <div class="nav-tabs-custom">
        <ul class="nav nav-tabs" role="tablist">
            <li role="presentation" class="active"><?= Html::a('管理员管理', ['index']) ?></li>
            <li role="presentation"><?= Html::a('添加管理员', ['create']) ?></li>
        </ul>
        <div class="tab-content">
            <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
            <?= GridView::widget($data); ?>
        </div>
    </div>
</div>
