<?php

use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model \backend\models\post\Posts */

?>

<div class="card latest-new-card w-100 overflow-hidden"><!--mx-3-->
    <a href="<?= Url::to(['/post/view', 'slug' => $model->slug]) ?>"
       class="splide__slide section-latest-news__col m-0 w-100 h-100 card text-decoration-none" data-aos="fade-up"
       data-aos-anchor-placement="top-bottom" data-aos-delay="300" data-pjax="0">
        <div class="latest-new-img-gen d-inline-block z-index-1">
            <img src="<?= $model->image; ?>"
                 class="card-img-top latest-new-img w-100 h-100 object-fit-cover"
                 alt="<?= $model->title; ?>">
            <div class="position-absolute start-0 top-0 w-auto z-index-2 bg-primary text-white p-2 fs-6 border-bottom-end-6">
                <?= Yii::$app->formatter->asDate($model->created_at) ?>
            </div>
        </div>
        <div class="card-body d-flex flex-column justify-content-start align-items-center z-index-1">
            <h5 class="h5 text-uppercase text-decoration-none text-primary text-center fw-bold"><?= mb_substr($model->title, 0, 40) ?><?= mb_strlen($model->title) > 40 ? '...' : '' ?></h5>
            <p class="text-decoration-none text-gray">
                <?php $summary = Html::encode($model->summary); ?>
                <?= mb_substr($summary, 0, 65); ?>...
            </p>
        </div>
        <div class="position-absolute bottom-minus-20 end-minus-20 w-200px h-200px z-index-0 animation-rotate only-hover-active d-none">
            <img class="w-100 h-100" src="/themes/v1/images/logo/oriental.png" alt="">
        </div>
    </a>
</div>