<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\file\FileInput;
/* @var $this yii\web\View */
/* @var $model app\models\News */
/* @var $form yii\widgets\ActiveForm */
if($count){
    $this->registerJsFile('/dist/js/create.js',['depends'=>['app\modules\backend\assets\BackendAsset']]);
}
?>

<div class="content-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <div class="row <?=$count?'hide':''?>">
        <div class="col-sm-6">

            <?= $form->field($model, 'file[]')->widget(
                FileInput::class,
                [
                        'options'=>[
                            'multiple'=>true,
                        ],
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
                    <span class="info-box-text">正在处理数据....</span>
                    <span class="info-box-number counts"><?=$count;?></span>

                    <div class="progress">
                        <div id="progress" data-count="<?=$count;?>" class="progress-bar sm" style="width: 1%"></div>
                    </div>
                    <span class="progress-description">
                    当前还需要处理的数据总计为<span class="counts"><?=$count;?></span>条。
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
