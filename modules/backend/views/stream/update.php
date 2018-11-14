<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\News */

$this->title = '修改数据: ';
$this->params['breadcrumbs'][] = ['label' => '出入料管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = '修改';
?>
<div class="content-update">
    <div class="nav-tabs-custom">
        <ul class="nav nav-tabs" role="tablist">
            <li role="presentation"><?= Html::a('出入料管理', ['index']) ?></li>
            <li role="presentation"><?= Html::a('进出料统计图', ['data']) ?></li>
            <li role="presentation"><?= Html::a('导入数据', ['create']) ?></li>
            <li role="presentation" class="active"><?= Html::a('修改数据', '#') ?></li>
        </ul>
        <div class="tab-content">
            <?= $this->render('_form', [
                'model' => $model,
            ]) ?>
        </div>
    </div>
</div>