<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\file\FileInput;
/* @var $this yii\web\View */
/* @var $model app\models\News */
/* @var $form yii\widgets\ActiveForm
if($count){
    $this->registerJsFile('/dist/js/create.js',['depends'=>['app\modules\backend\assets\BackendAsset']]);
} */
?>

<div class="content-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <div class="row">
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

    <?php ActiveForm::end(); ?>

</div>
