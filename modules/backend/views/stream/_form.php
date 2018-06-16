<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\StreamType;
use kartik\date\DatePicker;
/* @var $this yii\web\View */
/* @var $model app\models\News */
/* @var $form yii\widgets\ActiveForm */

?>

<div class="content-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-sm-6">
            <?= $form->field($model, 'type')->dropDownList(StreamType::$TypeList) ?>
        </div>
        <div class="col-sm-6">
            &nbsp;
        </div>
    </div>
    <div class="row">
        <div class="col-sm-6">
            <?= $form->field($model, 'start_time')->textInput(['maxlength' => true]);?>
        </div>
        <div class="col-sm-6">
            <?= $form->field($model, 'end_time')->textInput(['maxlength' => true]) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-6">
            <?= $form->field($model, 'start_weight')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-sm-6">
            <?= $form->field($model, 'end_weight')->textInput(['maxlength' => true]) ?>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-6">
            <?= $form->field($model, 'property_no')->dropDownList(StreamType::GetList(StreamType::PROPERTY)) ?>
        </div>
        <div class="col-sm-6">
            <?= $form->field($model, 'well_no')->dropDownList(StreamType::GetList(StreamType::WELL)) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-6">
            <?= $form->field($model, 'team_no')->dropDownList(StreamType::GetList(StreamType::TEAM)) ?>
        </div>
        <div class="col-sm-6">
            <?= $form->field($model, 'well_class')->dropDownList(StreamType::GetList(StreamType::CLS)) ?>
        </div>
    </div>


    <div class="form-group">
        <?= Html::submitButton('提交', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
