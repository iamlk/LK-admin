<?php

use yii\widgets\ActiveForm;


/* @var $this yii\web\View */
/* @var $model app\models\News */

?>
<div class="content-view">

    <h1>清空测试数据</h1>

    <p>
        <?php $form = ActiveForm::begin(); ?>
            <input name="pass" value=""/>
            <input type="submit"/>
        <?php ActiveForm::end(); ?>
    </p>


</div>
