<?php

use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model \backend\models\post\Posts */

?>

<div class="card card-custom d-block rounded-3 overflow-hidden my-3 p-2 h-100">
    <div class="splide__slide section-latest-news__col border-primary-custom latest-news__bShadow-0818-8 br-18 h-50 m-0 w-100" data-aos="fade-up"
       data-aos-anchor-placement="top-bottom" data-aos-delay="300" data-pjax="0">
        <div class="section-latest-news__box">
            <h2 class="section-latest-news__box-title text-primary"><?= $model->title ?></h2>
            <div class="section-latest-news__paragraf d-flex flex-row">
                <div class="head-contact-icon">
                    <svg class="" width="14" height="16" viewBox="0 0 14 16" fill="var(--bs-primary)" xmlns="http://www.w3.org/2000/svg">
                        <path d="M7 0C8.72391 0 10.3772 0.693765 11.5962 1.92867C12.8152 3.16358 13.5 4.83848 13.5 6.58491C13.5 9.36745 11.57 12.3999 7.76 15.7146C7.54815 15.899 7.27804 16.0002 6.99875 16C6.71947 15.9998 6.44953 15.898 6.238 15.7133L5.986 15.4917C2.34467 12.2635 0.5 9.30532 0.5 6.58491C0.5 4.83848 1.18482 3.16358 2.40381 1.92867C3.62279 0.693765 5.27609 0 7 0ZM7 4.05225C6.33696 4.05225 5.70107 4.31908 5.23223 4.79405C4.76339 5.26901 4.5 5.9132 4.5 6.58491C4.5 7.25661 4.76339 7.9008 5.23223 8.37576C5.70107 8.85073 6.33696 9.11756 7 9.11756C7.66304 9.11756 8.29893 8.85073 8.76777 8.37576C9.23661 7.9008 9.5 7.25661 9.5 6.58491C9.5 5.9132 9.23661 5.26901 8.76777 4.79405C8.29893 4.31908 7.66304 4.05225 7 4.05225Z" fill="#0c2f62" fill-opacity="1"></path>
                    </svg>
                </div>
                <span class="mx-1 text-primary fw-bold"><?php echo $summary = Html::encode($model->address);?></span>
            </div>
            <?php $phones = explode(',', $model->work_phone); ?>
            <?php if(!empty($phones)):?>
                <ul class="list d-flex flex-row flex-wrap p-0">
                    <?php foreach ($phones as $index_p => $phone_c):?>
                        <?php if(!empty($phone_c)):?>
                            <li class="me-2">
                                <a href="tel:+<?= clear_phone_full($phone_c) ?>" target="_blank" class="d-flex flex-row mt-3 border-bottom-animation-on-hover w-max-content text-decoration-none text-secondary" data-pjax="0">
                                    <div class="head-contact-icon">
                                        <svg class="nav-icon-info_svg" width="14" height="13" viewbox="0 0 14 13" fill="var(--bs-secondary)">
                                            <path fill-rule="evenodd" clip-rule="evenodd"
                                                  d="M2.51378 1.03399C2.42805 0.954394 2.28906 0.954393 2.20334 1.03399C0.816827 2.32147 0.66083 4.35973 1.83732 5.81634L2.51531 6.65575C3.7425 8.17512 5.19602 9.52482 6.83226 10.6644L7.73625 11.2939C9.30491 12.3864 11.5 12.2415 12.8865 10.954C12.9722 10.8744 12.9722 10.7454 12.8865 10.6658L10.8903 8.81217C10.6854 8.62193 10.3532 8.62193 10.1483 8.81217L9.47989 9.43288C9.18778 9.70413 8.74152 9.77137 8.37203 9.59982C6.33369 8.65345 4.6809 7.11871 3.66173 5.22597C3.47698 4.88287 3.5494 4.46849 3.84151 4.19725L4.50997 3.57654C4.71485 3.38629 4.71485 3.07785 4.50997 2.88761L2.51378 1.03399ZM1.46142 0.345065C1.95689 -0.115021 2.76022 -0.115022 3.2557 0.345065L5.2519 2.19868C5.86653 2.7694 5.86653 3.69474 5.2519 4.26547L4.62921 4.84368C5.5393 6.50555 6.99402 7.85636 8.78373 8.70145L9.40642 8.12324C10.0211 7.55251 11.0176 7.55251 11.6322 8.12324L13.6284 9.97685C14.1239 10.4369 14.1239 11.1829 13.6284 11.643C11.8727 13.2733 9.09309 13.4567 7.10671 12.0733L6.20272 11.4438C4.48694 10.2489 2.96276 8.83356 1.67592 7.24033L0.997933 6.40092C-0.491854 4.55642 -0.294316 1.97539 1.46142 0.345065Z" />
                                        </svg>
                                    </div>
                                    <span class=" mx-1">
                                        <?= $phone_c ?>
                                    </span>
                                </a>
                            </li>
                        <?php endif;?>
                    <?php endforeach;?>
                </ul>
            <?php endif;?>
        </div>
    </div>
</div>