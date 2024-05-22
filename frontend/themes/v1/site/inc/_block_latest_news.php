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
    <section class="latest-news-section" id="#">
        <div class="container py-5">
            <h2 class="text-secondary fw-bold text-center text-uppercase mb-4"><?= Yii::t('app', 'Последние новости') ?></h2>
            <div class="swiper latestNew pt-5">
                <div class="swiper-wrapper pt-5">
                    <?php foreach ($modelItems as $item): ?>
                        <div class="swiper-slide">
                            <div class="card latest-new-card w-100">
                                <div class="latest-new-img-gen d-inline-block">
                                    <img src="<?= $item->image; ?>"
                                         class="card-img-top latest-new-img w-100 h-100 object-fit-cover"
                                         alt="<?= $item->title; ?>">
                                </div>
                                <div class="card-body d-flex flex-column justify-content-between align-items-center">
                                    <h5 class="h5 text-uppercase text-secondary text-center fw-bold"><?= mb_substr($item->title, 0, 40) ?>...</h5>
                                    <a href="<?= Url::to(['/post/view', 'slug' => $item->slug]) ?>"
                                            class="btn btn-outline-primary rounded-2 text-uppercase fs-5 fw-bold px-4 py-2">
                                        <?=Yii::t('frontend','Подробнее')?>
                                    </a>
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