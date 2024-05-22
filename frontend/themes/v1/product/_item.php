<?php

use yii\helpers\Html;
use rmrevin\yii\fontawesome\FA;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model \backend\models\post\Posts */

?>

<div class="single-blog mb-30">
    <div class="blog-img img-full">
        <?= Html::a(Html::img($model->image, ['alt' => $model->category->name, 'class' => 'img-responsive']) . '<span class="icon-view"></span>',
            ['view', 'slug' => $model->slug], ['class' => 'thumbnail', 'data-pjax' => 0]) ?>
    </div>
    <div class="blog-content">
        <h3 class="blog-title">
            <?= Html::a($model->title, ['view', 'slug' => $model->slug], ['data-pjax' => 0]) ?>
        </h3>
        <div class="blog-meta">
            <p class="author-name"><?=Yii::t('frontend','post at')?>: - <?= Yii::$app->formatter->asDate($model->created_at)?></p>
        </div>
        <div class="blog-des">
            <?php $body = mb_substr(strip_tags($model->body), 0, 200)?>
            <p><?= Html::decode($body)?>...</p>
        </div>
        <a class="read-btn" href="<?= Url::to(['post/view', 'slug' => $model->slug])?>" data-pjax="0"  >
            <?=Yii::t('frontend','Подробнее')?>
        </a>
    </div>
</div>