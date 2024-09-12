<?php

use backend\models\sliders\SliderItems;
use backend\models\sliders\Sliders;
use backend\modules\policy\models\CheckPolicy;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model CheckPolicy */
/* @var $form yii\widgets\ActiveForm */
/* @var $slider Sliders */
/* @var $sliderItems SliderItems */
/* @var $sliderItem SliderItems */

?>
<!--========================================================================-->
<!-- |HEADER INTRO | SLIDER| -->
<!--========================================================================-->
<section class="section-intro" id="section-bg">
    <div class="swiper headerIntro">
        <div class="swiper-wrapper text-white">
            <?php $sliderItems = $slider->getSliderActiveItems()->limit(SliderItems::LIMIT_SLIDER_ITEMS)->all(); ?>
            <?php if (!empty($sliderItems)): ?>
                <?php foreach ($sliderItems as $index => $sliderItem): ?>
                    <div class="swiper-slide">
                        <img class="header-intro-bg" src="<?= $sliderItem->image ?>">
                        <div class="card bg-transparent border-0">
                            <div class="card-body">
                                <div class="rellax_DESC"
                                     data-rellax-speed="3"
                                     data-rellax-xs-speed="1"
                                     data-rellax-mobile-speed="2"
                                     data-rellax-tablet-speed="3"
                                     data-rellax-desktop-speed="4"
                                     data-rellax-percentage="0.7">
                                    <h5 class="card-title display-6 fw-bold">
                                        <?= strip_tags($sliderItem->title); ?>
                                    </h5>
                                    <p class="card-text h3 fw-normal">
                                        <?= strip_tags($sliderItem->subtitle); ?>
                                    </p>
                                    <a href="<?= $sliderItem->link ?>"
                                       target=""
                                       class="btn btn-primary rounded-2 d-inline-block rounded-0 text-uppercase fs-5 fw-bold px-4 py-2 mt-2">
                                        <?= Yii::t('frontend', 'Подробнее') ?>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        <div class="swiper-pagination"></div>
    </div>
</section>