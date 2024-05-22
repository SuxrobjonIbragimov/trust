<?php

use backend\models\post\PostCategories;
use yii\helpers\Html;
use yii\helpers\Url;
use backend\modules\news\models\NewsRibbon;

/* @var $model \backend\models\post\PostCategories */
/* @var $modelItems \backend\models\post\Posts */
/* @var $item \backend\models\post\Posts */

$modelItems = $model->getActivePosts()->orderBy(['created_at' => SORT_DESC, 'title' => SORT_ASC])->limit(PostCategories::ACTIVE_CHILD_LIMIT_LATEST_NEWS)->all();
?>
<?php if (!empty($modelItems)): ?>
    <!--========================================================================-->
    <!-- |LATEST NEWS | SWIPER| -->
    <!--========================================================================-->
    <section>
        <div class="container py-5">
            <h2 class="text-primary fw-bold text-center text-uppercase mb-4"><?= Yii::t('app', 'Последние новости') ?></h2>
            <div class="swiper latestNew pt-5">
                <div class="swiper-wrapper pt-5">
                    <?php foreach ($modelItems as $item): ?>
                        <div class="swiper-slide">
                            <div class="card latest-new-card w-100 mx-3 overflow-hidden">
                                <div class="latest-new-img-gen d-inline-block z-index-1">
                                    <img src="<?= $item->image; ?>"
                                         class="card-img-top latest-new-img w-100 h-100 object-fit-cover"
                                         alt="<?= $item->title; ?>">
                                    <div class="position-absolute start-0 top-0 w-auto z-index-2 bg-primary text-white p-2 fs-6 border-bottom-end-6">
                                        <?= Yii::$app->formatter->asDate($item->created_at) ?>
                                    </div>
                                </div>
                                <div class="card-body d-flex flex-column justify-content-between align-items-center z-index-1">

                                    <h5 class="h5 text-uppercase text-primary text-center fw-bold"><?= mb_substr($item->title, 0, 40) ?>...</h5>
                                    <a href="<?= Url::to(['/post/view', 'slug' => $item->slug]) ?>"
                                       class="media-btn-class btn btn-outline-secondary rounded-2 text-uppercase fs-5 fw-bold px-4 py-2">
                                        <?=Yii::t('frontend','Подробнее')?>
                                    </a>
                                </div>
                                <div class="position-absolute bottom-minus-20 end-minus-20 w-200px h-200px z-index-0 animation-rotate only-hover-active">
                                    <img src="/themes/v1/images/logo/oriental.png" alt="">
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <div class="swiper-button-next swiper-new-btn-next shadow-hover border border-gray border-1 rounded-3 px-4 py-2 me-3">
                    <i class="bx text-gray bx-right-arrow-alt"></i>
                </div>
                <div
                        class="swiper-button-prev swiper-new-btn-prev shadow-hover border border-gray border-1 rounded-3 px-4 py-2">
                    <i class="bx text-gray bx-left-arrow-alt"></i>
                </div>
            </div>
        </div>
    </section>
<?php endif; ?>