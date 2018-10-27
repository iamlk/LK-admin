<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use app\modules\backend\assets\BackendAsset;
use yii\bootstrap\Alert;

BackendAsset::register($this);
$directoryAsset = Yii::$app->assetManager->getPublishedUrl('@vendor/almasaeed2010/adminlte/dist');
?>
<?php if (Yii::$app->controller->action->id === 'login'): ?>
    <?= $this->render('main-login', [
        'content' => $content
    ]) ?>
<?php else: ?>
    <?php $this->beginPage() ?>
    <!DOCTYPE html>
    <html lang="<?= Yii::$app->language ?>">
    <head>
        <meta charset="<?= Yii::$app->charset ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?= Html::csrfMetaTags() ?>
        <title><?= Html::encode($this->title) ?></title>
        <?php $this->head() ?>
    </head>
    <body class="skin-blue sidebar-mini fixed sidebar-mini-expand-feature">
    <?php $this->beginBody() ?>

    <div class="wrap" style="height: auto;">
        <?= $content ?>
    </div>
    <?php $this->endBody() ?>
    </body>
    </html>
    <?php $this->endPage() ?>
<?php endif ?>