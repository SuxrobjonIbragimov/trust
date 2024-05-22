<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use backend\assets\AppAsset;
use backend\extensions\adminlte\components\AdminLteHelper;
use yii\helpers\Url;

AppAsset::register($this);

?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <link rel="shortcut icon" type="image/png" href="<?= Url::base() ?>/favicon.png">
    <?php $this->head() ?>
    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">

</head>

<?php if (Yii::$app->controller->action->id === 'login') { ?>

    <body class="login-page">
    <?php $this->beginBody() ?>

    <?= $this->render('login.php', ['content' => $content]) ?>

    <?php $this->endBody() ?>
    </body>

<?php } else { ?>

    <?php $sidebar = Yii::$app->session['sidebar'] ? ' sidebar-collapse' : ''; ?>
    <body class="<?= AdminLteHelper::skinClass() . $sidebar ?> sidebar-mini">
    <?php $this->beginBody() ?>
    <div class="wrapper">

        <?= $this->render('header.php') ?>
        <?= $this->render('left.php') ?>
        <?= $this->render('content.php', ['content' => $content]) ?>

    </div>
    <div id="overlay"></div>
    <?php $this->endBody() ?>
    </body>

<?php } ?>

</html>
<?php $this->endPage() ?>
