<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\News */

$this->title = '导入数据';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="content-create">
    <div class="nav-tabs-custom">
        <ul class="nav nav-tabs" role="tablist">
            <li role="presentation"><?= Html::a('出入料管理', ['index']) ?></li>
            <li role="presentation"><?= Html::a('进出料统计图', ['data']) ?></li>
            <li role="presentation" class="active"><?= Html::a($this->title, ['create']) ?></li>
        </ul>
        <div class="tab-content">
            <?= $this->render('_create', [
                'model' => $model,
                'count' => $count,
            ]) ?>
        </div>
    </div>
</div>
