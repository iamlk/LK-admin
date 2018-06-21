<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\file\FileInput;
/* @var $this yii\web\View */
/* @var $model app\models\News */
/* @var $form yii\widgets\ActiveForm */
if($count){
    $this->registerJsFile('/modules/backend/assets/backend.js');
}
?>

<div class="content-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <div class="row <?=$count?'hide':''?>">
        <div class="col-sm-6">

            <?= $form->field($model, 'file')->widget(
                FileInput::class,
                [
                    'pluginOptions' => [
                        'showUpload' => true,
                        'showPreview'=>false,
                        'showRemove'=>true,
                        'initialPreviewAsData' => true,
                    ],
                ]
            ) ?>

        </div>
        <div class="col-sm-6">
            &nbsp;
        </div>
    </div>
    <div class="row <?=$count?'':'hide'?>">
        <div class="col-sm-6">
            <div class="info-box bg-aqua">
                <span class="info-box-icon"><i class="fa fa-bookmark-o"></i></span>

                <div class="info-box-content">
                    <span class="info-box-text">Bookmarks</span>
                    <span class="info-box-number">41,410</span>

                    <div class="progress">
                        <div class="progress-bar" style="width: 70%"></div>
                    </div>
                    <span class="progress-description">
                    70% Increase in 30 Days
                  </span>
                </div>
                <!-- /.info-box-content -->
            </div>
        </div>

        <div class="col-sm-6">
            &nbsp;
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
