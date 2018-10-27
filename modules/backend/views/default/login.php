<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
?>
<div id="container">
	<?php $form = ActiveForm::begin([
		'id' => 'login-form',
		'enableClientValidation' => false,
	]); ?>
		<div class="login"><?= Yii::$app->name ?></div>
		<div class="username-text">登录用户:</div>
		<div class="password-text">登录密码:</div>
		<div class="username-field">
			<input type="text" name="LoginForm[username]" value="<?=@$_COOKIE["username"];?>" />
		</div>
		<div class="password-field">
			<input type="password" name="LoginForm[password]" value="" />
		</div>
		<input type="checkbox" value="1" name="LoginForm[rememberMe]" id="rememberMe" /><label for="rememberMe">下次自动登录</label>
		<div class="forgot-usr-pwd">Forgot <a href="#">username</a> or <a href="#">password</a>?</div>
		<input type="submit" name="submit" value="登录" />
	<?php ActiveForm::end(); ?>
</div>
<div id="footer">
	技术支持 Powered by <a href="http://www.iamlk.cn/" target="_blank" title="LK工作室">LK工作室</a>
</div>