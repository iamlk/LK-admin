<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
?>
<div id="container">
		<?php $form = ActiveForm::begin([
            'id' => 'login-form',
            'enableClientValidation' => false,
        ]); ?>
		<div class="login">LOGIN</div>
		<div class="username-text">Username:</div>
		<div class="password-text">Password:</div>
		<div class="username-field">
			<input type="text" name="username" value="" />
		</div>
		<div class="password-field">
			<input type="password" name="password" value="" />
		</div>
		<input type="checkbox" name="rememberMe" id="rememberMe" /><label for="rememberMe">下次自动登录</label>
		<div class="forgot-usr-pwd">Forgot <a href="#">username</a> or <a href="#">password</a>?</div>
		<input type="submit" name="submit" value="GO" />
	
        <?php ActiveForm::end(); ?>
</div>
<div id="footer">
	Web 2.0 Login More Templates <a href="http://www.cssmoban.com/" target="_blank" title="模板之家">模板之家</a>
</div>