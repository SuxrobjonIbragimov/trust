<?php

use backend\models\post\PostCategories;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $model \backend\models\post\PostCategories */
/* @var $modelItems \backend\models\post\Posts */
/* @var $item \backend\models\post\Posts */

$modelItems = $model->getActivePosts()->orderBy(['weight' => SORT_ASC, 'title' => SORT_ASC])->limit(PostCategories::ACTIVE_CHILD_LIMIT)->all();
?>
<?php if (!empty($modelItems)): ?>
    <!--========================================================================-->
    <!-- |PARTNERS| SWIPER| -->
    <!--========================================================================-->
    <section>
        <div class="container py-5 ">
            <h2 class="text-secondary fw-bold text-center text-uppercase mb-4"><?= $model->name; ?></h2>
            <div class="swiper partnersSlider pt-5">
                <div class="swiper-wrapper pt-5">
                    <?php foreach ($modelItems as $item): ?>
                        <div class="swiper-slide">
                            <div class="card partner-card w-100 mx-3">
                                <img src="<?= $item->image; ?>"
                                     class="card-img-top object-fit-contain w-100 h-100 partner-img px-2 py-2"
                                     alt="<?= $item->title; ?>">
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <div
                        class="swiper-button-next swiper-partners-btn-next shadow-hover border border-gray border-1 rounded-3 px-4 py-2 me-3">
                    <i class="bx text-gray bx-right-arrow-alt"></i>
                </div>
                <div
                        class="swiper-button-prev swiper-partners-btn-prev shadow-hover border border-gray border-1 rounded-3 px-4 py-2">
                    <i class="bx text-gray bx-left-arrow-alt"></i>
                </div>
            </div>
        </div>
    </section>
<?php endif; ?>