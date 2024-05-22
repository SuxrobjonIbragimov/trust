<?php

use backend\models\post\PostCategories;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $model \backend\models\comment\Comments */
/* @var $item \backend\models\comment\Comments */

?>
<?php if (!empty($model)): ?>
    <!--========================================================================-->
    <!-- |COMENT | SWIPER| -->
    <!--========================================================================-->
    <section class="bg-primary position-relative" id="#e">
        <div class="container py-5 position-relative z-index-9">
            <h2 class="text-white  fw-bold text-center text-uppercase mb-4"><?= Yii::t('app', 'Что наши клиенты говорят о нас') ?></h2>
            <div class="swiper commentsUsers pt-5">
                <div class="swiper-wrapper bg-secondary pt-5">
                    <?php foreach ($model as $item): ?>
                        <div class="swiper-slide bg-transparent">
                            <div class="card comment-user-card w- mx-3">
                                <div class="card-body d-flex flex-column justify-content-between">
                                    <p class="card-text text-gray text-start"><?= Html::encode($item->text); ?></p>
                                    <div class="d-flex align-items-start justify-content-between">
                                        <div class="group-image w-60px h-60px">
                                            <img class="w-100 h-100 object-fit-cover" src="<?= $item->author_image ?>" alt="<?= $item->author_name ?>">
                                        </div>
                                        <div class="group-text">
                                            <h5 class="h5 text-uppercase text-primary text-end fw-bold"> <?= $item->author_name ?></h5>
                                            <h6 class="h6 text-lowercase text-muted text-end fw-light"> <?= $item->author_position ?></h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <div class="swiper-button-next bg-white swiper-new-btn-next shadow-hover border border-gray border-1 rounded-3 px-4 py-2 me-3 shadow-lg">
                    <i class="bx text-gray bx-right-arrow-alt"></i>
                </div>
                <div class="swiper-button-prev bg-white swiper-new-btn-prev shadow-hover border border-gray border-1 rounded-3 px-4 py-2 shadow-lg">
                    <i class="bx text-gray bx-left-arrow-alt"></i>
                </div>
            </div>
        </div>
        <div id="particles-js" class="position-absolute top-0 start-0 z-index-8"></div>
    </section>
<?php endif; ?>