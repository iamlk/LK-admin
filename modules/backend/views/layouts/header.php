<?php
use yii\helpers\Html;
use yii\helpers\Url;
use app\widgets\Nav;
/* @var $this \yii\web\View */
/* @var $content string */
?>
<style>
    .navbar-nav>.user-menu>.dropdown-menu>.user-footer{
        background-color: #8aa4af;
        padding: 10px;
    }
</style>
<header class="main-header">

    <?= Html::a('<span class="logo-mini">CMS</span><span class="logo-lg">' . Yii::$app->name . '</span>', ['/backend/'], ['class' => 'logo']) ?>

    <nav class="navbar navbar-static-top" role="navigation">

        <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
            <span class="sr-only">Toggle navigation</span>
        </a>

        <div class="navbar-custom-menu">

            <ul class="nav navbar-nav">
                <!-- User Account: style can be found in dropdown.less
                <li>
                    <a href="<?=Url::to(['/backend/default/clear-cache'])?>" role="button" title="清理缓存">
                        <span class="fa fa-trash-o"></span>
                    </a>
                </li>
                -->
                <li>
                    <a href="<?=Yii::$app->homeUrl?>" role="button" title="返回首页">
                        <span class="fa fa-home"></span>
                    </a>
                </li>
                <li class="dropdown user user-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <img src="<?= $directoryAsset ?>/img/user2-160x160.jpg" class="user-image" alt="User Image"/>
                        <span class="hidden-xs"><?=isset(Yii::$app->user->identity->username)?Yii::$app->user->identity->username:''?></span>
                    </a>
                    <ul class="dropdown-menu">
<!--                         User image -->
                        <li class="user-header" style="height:auto;">
                            <img src="<?= $directoryAsset ?>/img/user2-160x160.jpg" class="img-circle"
                                 alt="User Image"/>
                        </li>
<!--                         Menu Body -->
<!--                        <li class="user-body">-->
<!--                            <div class="col-xs-4 text-center">-->
<!--                                <a href="#">Followers</a>-->
<!--                            </div>-->
<!--                            <div class="col-xs-4 text-center">-->
<!--                                <a href="#">Sales</a>-->
<!--                            </div>-->
<!--                            <div class="col-xs-4 text-center">-->
<!--                                <a href="#">Friends</a>-->
<!--                            </div>-->
<!--                        </li>-->
<!--                         Menu Footer-->
                        <li class="user-footer">
                            <div class="pull-left">
                                <a href="<?=Url::to(['/backend/default/edit-password'])?>" class="btn btn-default btn-flat">修改密码</a>
                            </div>
                            <div class="pull-right">
                                <?= Html::a(
                                    '退出',
                                    ['/backend/default/logout'],
                                    ['data-method' => 'post', 'class' => 'btn btn-default btn-flat']
                                ) ?>
                            </div>
                        </li>
                    </ul>
                </li>

            </ul>
        </div>
    </nav>
</header>
