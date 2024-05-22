<?php

/* @var $this \yii\web\View */
/* @var $content string */

use common\models\Settings;
use yii\helpers\Html;
use yii\helpers\Url;

\frontend\assets\V1Asset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" class="dataFont">
<head>
    <meta charset="<?= Yii::$app->charset ?>">

    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= !empty($this->title) ? Html::encode($this->title) : Settings::getValueByKey(Settings::KEY_SITE_NAME) ?></title>
    <?php
    if (isset($this->params['meta_keywords']))
        $this->registerMetaTag(['name' => 'keywords', 'content' => $this->params['meta_keywords']]);
    if (isset($this->params['meta_description']))
        $this->registerMetaTag(['name' => 'description', 'content' => $this->params['meta_description']]);

    $this->registerMetaTag(['property' => 'og:site_name', 'content' => Yii::$app->name]);
    $this->registerMetaTag(['property' => 'og:title', 'content' => $this->title]);
    $this->registerMetaTag(['property' => 'og:locale', 'content' => Yii::$app->language]);
    if (isset($this->params['meta_type']))
        $this->registerMetaTag(['property' => 'og:type', 'content' => $this->params['meta_type']]);
    if (isset($this->params['meta_url']))
        $this->registerMetaTag(['property' => 'og:url', 'content' => $this->params['meta_url']]);
    if (isset($this->params['meta_image']))
        $this->registerMetaTag(['property' => 'og:image', 'content' => $this->params['meta_image']]);
    if (isset($this->params['meta_description']))
        $this->registerMetaTag(['property' => 'og:description', 'content' => $this->params['meta_description']]);
    ?>
    <link rel="shortcut icon" href="<?= '/themes/v1/images/logo/favicon.png' ?>" type="image/x-icon">
    <!--========== CDN FANCYBOX ANIMATION INTRO EFECT ==========-->
    <link href="https://cdn.jsdelivr.net/npm/@fancyapps/ui@4.0/dist/fancybox.css" rel="stylesheet">
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

    <?php $this->head() ?>
</head>
<body  id="body" onload="" class="<?= (is_front()) ? 'front' : 'not-front'; ?> <?= (isset($this->params['body_class'])) ? $this->params['body_class'] : ''; ?> <?= Yii::$app->controller->module->id ?>-<?= Yii::$app->controller->id ?>  <?= (Yii::$app->controller->id == 'page') ? 'static-page' : '' ?> ">
<?php $this->beginBody() ?>
<!-- header -->
<?= $this->render('header') ?>
<!-- //header -->

<!-- content -->
<?= $this->render('content', ['content' => $content]) ?>
<div class="overlay">
    <div class="spinner"></div>
</div>
<!-- //content -->

<!-- footer -->
<?= $this->render('footer') ?>
<!-- //footer -->

<?php $this->endBody() ?>


<!--========== CDN AOS FANCYBOX ==========-->
<script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui@4.0/dist/fancybox.umd.js"></script>
<script>
    /*NiceSelect.bind(document.getElementById("#selectTEST"));*/
</script>
<script>
    $(function () {
        if ($(".nice-select").length) {
            $(document).ready(function () {
                $('.nice-select').niceSelect();
            });
        }
        if ($(".advantages-article-title > span").length) {
            $('.advantages-article-title > span').counterUp({
                delay: 10,
                time: 1500,
                triggerOnce: true,
            });
        }
    })
</script>
</body>
</html>
<?php $this->endPage() ?>
