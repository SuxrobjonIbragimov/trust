<?php

use backend\models\insurance\InsuranceProduct;
use backend\modules\handbook\models\HandbookLegalType;
use yii\helpers\Url;

/* @var $model \backend\models\insurance\InsuranceProduct */
/* @var $items \backend\models\insurance\InsuranceProduct */
/* @var $item \backend\models\insurance\InsuranceProduct */

?>
<?php if (!empty($model)): ?>
    <!--========================================================================-->
    <!-- |INSURANCE TYPE | SWIPER"| -->
    <!--========================================================================-->
    <section class="insurance-section">
        <div class="container py-5">
            <h2 class="text-uppercase text-primary text-center fw-bold mt-5">Sug’urta turlari</h2>
            <div class="swiper insuranceType pt-5">
                <div class="swiper-wrapper pt-5">
                    <?php foreach ($model as $key_i => $item): ?>
                        <div class="swiper-slide h-100">
                            <div class="card insurance-card w-100 mx-3 overflow-hidden">
                                <div class="insurance-img-gen d-inline-block z-index-1">
                                    <img src="<?= $item->image; ?>"
                                         class="card-img-top insurance-img object-fit-cover"
                                         alt="<?= $item->title; ?>">

                                </div>
                                <div class="card-body d-flex flex-column justify-content-between align-items-center z-index-1">
                                    <h5 class="h5 text-uppercase text-primary text-center fw-bold">
                                        <?= $item->title; ?>
                                    </h5>
                                    <div class="d-flex justify-content-between align-items-center w-100 media-btn-class-gen">
                                        <a href="<?= !empty($item->calc_link) ? $item->calc_link : '#'?>"
                                           class="media-btn-class btn btn-primary rounded-2 text-center text-uppercase fs-5 fw-bold px-4 py-2 d-block mt-auto <?= (empty($item->calc_link) || $item->calc_link == '#') ? 'disabled' : ''?> "><?= Yii::t('frontend', 'Купить') ?></a>
                                        <a href="<?= Url::to(['product/view', 'slug' => $item->slug]) ?>"
                                           class="media-btn-class btn btn-secondary rounded-2 text-center text-uppercase fs-5 fw-bold px-4 py-2 d-block mt-auto"><?= Yii::t('frontend', 'Подробнее') ?></a>
                                    </div>
                                </div>
                                <div class="position-absolute bottom-minus-20 end-minus-20 w-200px h-200px z-index-0 animation-rotate only-hover-active">
                                    <img src="/themes/v1/images/logo/oriental.png" alt="">
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <div
                        class="swiper-button-next swiper-insurance-btn-next shadow-hover border border-gray border-1 rounded-3 px-4 py-2 me-3">
                    <i class="bx text-gray bx-right-arrow-alt"></i>
                </div>
                <div
                        class="swiper-button-prev swiper-insurance-btn-prev shadow-hover border border-gray border-1 rounded-3 px-4 py-2">
                    <i class="bx text-gray bx-left-arrow-alt"></i>
                </div>
            </div>
        </div>
    </section>

<?php endif; ?>