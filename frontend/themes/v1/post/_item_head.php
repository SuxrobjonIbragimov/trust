<?php

use common\models\Settings;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model \backend\models\post\Posts */

$work_phone = !empty($model->work_phone) ? $model->work_phone : Settings::getValueByKey(Settings::KEY_MAIN_PHONE);
$work_email = !empty($model->work_email) ? $model->work_email : Settings::getValueByKey(Settings::KEY_MAIN_EMAIL);
$work_telegram = !empty($model->work_telegram) ? $model->work_telegram : Settings::getValueByKey(Settings::KEY_MAIN_TELEGRAM);
?>


<div class="single-blog files-item my-3 bgColor-White">
    <div class="w-100 row align-items-center justify-content-center border-primary-custom br-18 px-3 py-4 m-0 bg-light latest-news__bShadow-0818-8" data-aos="fade-up"
       data-aos-anchor-placement="top-bottom" data-aos-delay="300" data-pjax="0">
        <div class="col-sm-12 col-md-12 col-lg-3">
            <div class="head-image img-responsive mb-3">
                <img src="<?= $model->image; ?>" alt="<?= $model->title ?>" class="img-responsive w-100 br-30">

            </div>
        </div>
        <div class="col-sm-12 col-md-12 col-lg-9 mt-lg-0 mt-md-2">
            <div class="section-head__box d-flex flex-column">
                <div class="file-title-block">
                    <h4 class="section-file-title fw-bold"><?= $model->title ?></h4>
                    <p class="section-latest-news__paragraf mt-2">
                        <small><?= $model->work_position; ?></small>
                    </p>
                    <p class="summary mt-2">
                        <span><?= Html::encode($model->summary) ?></span>
                    </p>
                    <p class="mt-3">
                        <b><?=Yii::t('app','Дни приема:')?></b>
                        <span><?= $model->work_days ?></span>
                    </p>
                </div>
            </div>
            <div class="file-link-block w-100 mt-3">
                <ul class="d-flex flex-wrap flex-row justify-content-between list-style-none ps-0">
                    <li>
                        <a href="tel:<?= $work_phone ?>" target="_blank" class="d-flex flex-row text-secondary text-decoration-none border-bottom-animation-on-hover" data-pjax="0">
                            <div class="head-contact-icon icon-width">
                                <svg class="nav-icon-info_svg" width="20" height="14" viewbox="0 0 14 13" fill="var(--bs-secondary)">
                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                          d="M2.51378 1.03399C2.42805 0.954394 2.28906 0.954393 2.20334 1.03399C0.816827 2.32147 0.66083 4.35973 1.83732 5.81634L2.51531 6.65575C3.7425 8.17512 5.19602 9.52482 6.83226 10.6644L7.73625 11.2939C9.30491 12.3864 11.5 12.2415 12.8865 10.954C12.9722 10.8744 12.9722 10.7454 12.8865 10.6658L10.8903 8.81217C10.6854 8.62193 10.3532 8.62193 10.1483 8.81217L9.47989 9.43288C9.18778 9.70413 8.74152 9.77137 8.37203 9.59982C6.33369 8.65345 4.6809 7.11871 3.66173 5.22597C3.47698 4.88287 3.5494 4.46849 3.84151 4.19725L4.50997 3.57654C4.71485 3.38629 4.71485 3.07785 4.50997 2.88761L2.51378 1.03399ZM1.46142 0.345065C1.95689 -0.115021 2.76022 -0.115022 3.2557 0.345065L5.2519 2.19868C5.86653 2.7694 5.86653 3.69474 5.2519 4.26547L4.62921 4.84368C5.5393 6.50555 6.99402 7.85636 8.78373 8.70145L9.40642 8.12324C10.0211 7.55251 11.0176 7.55251 11.6322 8.12324L13.6284 9.97685C14.1239 10.4369 14.1239 11.1829 13.6284 11.643C11.8727 13.2733 9.09309 13.4567 7.10671 12.0733L6.20272 11.4438C4.48694 10.2489 2.96276 8.83356 1.67592 7.24033L0.997933 6.40092C-0.491854 4.55642 -0.294316 1.97539 1.46142 0.345065Z" />
                                </svg>
                            </div>
                            <span class=" mx-1">
                                <?= $work_phone  ?>
                            </span>
                        </a>
                    </li>
                    <li>
                        <a href="mailto:<?= $model->work_email ?>" target="_blank" download class="d-flex flex-row text-secondary text-decoration-none border-bottom-animation-on-hover" data-pjax="0">
                            <div class="head-contact-icon icon-width mx-1">
                                <svg class="nav-icon-info_svg" width="20" height="14" viewbox="0 0 14 10" fill="var(--bs-secondary)">
                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                          d="M3.67576 2.80855e-07H10.3242C11.0751 -1.03104e-05 11.6803 -1.88385e-05 12.1563 0.0597559C12.6504 0.121816 13.0665 0.254582 13.397 0.563257C13.7274 0.871932 13.8696 1.26059 13.936 1.72218C14 2.16678 14 2.73206 14 3.43338V6.56662C14 7.26794 14 7.83323 13.936 8.27782C13.8696 8.73942 13.7274 9.12807 13.397 9.43674C13.0665 9.74542 12.6504 9.87819 12.1563 9.94024C11.6803 10 11.0751 10 10.3242 10H3.67577C2.92493 10 2.31973 10 1.84375 9.94024C1.34957 9.87819 0.93348 9.74542 0.603016 9.43674C0.272552 9.12807 0.130414 8.73942 0.0639739 8.27782C-2.01683e-05 7.83322 -1.10382e-05 7.26793 3.00681e-07 6.5666V3.4334C-1.10382e-05 2.73207 -2.01683e-05 2.16678 0.0639739 1.72218C0.130414 1.26059 0.272552 0.871932 0.603016 0.563257C0.93348 0.254582 1.34957 0.121816 1.84375 0.0597559C2.31973 -1.88385e-05 2.92492 -1.03104e-05 3.67576 2.80855e-07ZM1.95348 0.822127C1.55062 0.872719 1.33729 0.965255 1.18534 1.10719C1.03339 1.24911 0.934322 1.44838 0.88016 1.82468C0.824404 2.21204 0.82353 2.72543 0.82353 3.46154V6.53846C0.82353 7.27457 0.824404 7.78796 0.88016 8.17532C0.934322 8.55162 1.03339 8.75089 1.18534 8.89282C1.33729 9.03475 1.55062 9.12728 1.95348 9.17787C2.36818 9.22995 2.91781 9.23077 3.70588 9.23077H10.2941C11.0822 9.23077 11.6318 9.22995 12.0465 9.17787C12.4494 9.12728 12.6627 9.03475 12.8147 8.89282C12.9666 8.75089 13.0657 8.55162 13.1198 8.17532C13.1756 7.78796 13.1765 7.27457 13.1765 6.53846V3.46154C13.1765 2.72543 13.1756 2.21204 13.1198 1.82468C13.0657 1.44838 12.9666 1.24911 12.8147 1.10719C12.6627 0.965255 12.4494 0.872719 12.0465 0.822127C11.6318 0.770048 11.0822 0.769231 10.2941 0.769231H3.70588C2.91781 0.769231 2.36818 0.770048 1.95348 0.822127Z" />
                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                          d="M6.07927 5.76923L0.227619 3.03632L0.595912 2.3483L6.44756 5.08121C6.79533 5.24363 7.20467 5.24363 7.55244 5.08121L13.4041 2.3483L13.7724 3.03632L7.92073 5.76923C7.34112 6.03993 6.65888 6.03993 6.07927 5.76923Z" />
                                </svg>
                            </div>
                            <?= $work_email ?>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>