<?php

use backend\models\insurance\InsuranceProductItem;

/* @var $this yii\web\View */
?>

<div class="product-info-block mt-3">
    <div class="main-slide__linksWrap linksWrap aos-init aos-animate" data-aos="fade-up" data-aos-delay="150" data-aos-offset="0">
        <div class="main-slide__links links aos-init aos-animate" data-aos="fade-up" data-aos-offset="0">
            <div class="online">
                <div class="online__list aos-init aos-animate row justify-content-between" data-aos="fade-left" data-aos-delay="150" data-aos-offset="0">
                    <div class="online__item">
                        <img class="section-plan__img plan-img__mb-24" src="/themes/v1/images/progress/calc.png" alt="">
                        <span class="online__caption"><?=Yii::t('product','Моментальный расчет')?></span>
                        <div class="arrow-right"></div>
                    </div>
                    <div class="online__item">
                        <img class="section-plan__img plan-img__mb-24" src="/themes/v1/images/progress/fast.png" alt="">
                        <span class="online__caption"><?=Yii::t('product','Быстрое оформление')?></span>
                        <div class="arrow-right"></div>
                    </div>
                    <div class="online__item">
                        <img class="section-plan__img plan-img__mb-24" src="/themes/v1/images/progress/online-pay.png" alt="">
                        <span class="online__caption"><?=Yii::t('product','Оплата онлайн')?></span>
                        <div class="arrow-right"></div>
                    </div>
                    <div class="online__item">
                        <img class="section-plan__img plan-img__mb-24" src="/themes/v1/images/progress/download.png" alt="">
                        <span class="online__caption"><?=Yii::t('product','Скачать электронный полис')?></span>
                        <div class="arrow-right"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
