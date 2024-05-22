<?php

use backend\models\parts\HtmlParts;
use common\models\Settings;
use frontend\widgets\SocialNetworksWidget;
use frontend\widgets\SubscribeWidget;
use yii\helpers\Html;
use yii\helpers\Url;

$phone = Settings::getValueByKey(Settings::KEY_MAIN_PHONE);
$email = Settings::getValueByKey(Settings::KEY_MAIN_EMAIL);
$location = HtmlParts::getItemByKey(HtmlParts::KEY_MAIN_LOCATION);

?>

<!--========================================================================-->
<!-- |FOOTER|-->
<!--========================================================================-->
<footer class="footer-area bg-dark text-light section-gap mt-5 pt-5" id="contacts">
    <div class="container">
        <div class="row">
            <div class="col-lg-4 col-md-6 col-sm-6">
                <div class="footer-info d-inline-block ms-auto">
                    <h4 class="d-none"><?= Yii::t('frontend', 'Наши контакты') ?></h4>
                    <h4 class="footer-info--title"><?= Yii::t('frontend', 'Связаться с нами') ?></h4>
                    <ul class="nav flex-column footer-info--list p-0">
                        <li class="footer-info--item my-2">
                            <a href="tel:+<?= clear_phone_full($phone); ?>" class="footer-info--link d-flex align-items-center text-secondary text-decoration-none border-bottom-animation-on-hover w-max-content pb-1">
                                <div class="footer-info--icon">
                                    <svg class="footer-info--svg " width="18" height="18" viewbox="0 0 14 16" style="margin-right: 6px"
                                         fill="var(--bs-secondary)" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M1.49949 0.648917L2.37484 0.197016C3.19577 -0.226257 4.20318 0.0517441 4.72882 0.84626L5.77544 2.42914C6.23076 3.1184 6.20807 4.02671 5.71946 4.6741L4.35745 6.47635C4.6204 7.36711 5.09189 8.20675 5.77108 8.9954C6.41886 9.76006 7.21696 10.3891 8.11812 10.8453L9.96483 9.86209C10.6644 9.49042 11.5242 9.62875 12.0963 10.2054L13.4315 11.5483C14.0976 12.2191 14.1897 13.2876 13.6477 14.0487L13.0674 14.8644C12.489 15.6763 11.5316 16.1016 10.5547 15.9793C8.24664 15.692 5.88208 14.0575 3.45754 11.0766C1.0295 8.09097 -0.120188 5.398 0.00992068 3.00085C0.0644357 1.99213 0.630575 1.09708 1.49949 0.648917Z"/>
                                    </svg>
                                </div>
                                <?= $phone; ?>
                            </a>
                        </li>
                        <li class="footer-info--item my-2">
                            <a href="mailto:<?= $email; ?>" class="footer-info--link d-flex align-items-center text-secondary text-decoration-none border-bottom-animation-on-hover w-max-content pb-1">
                                <div class="footer-info--icon">
                                    <svg class="footer-info--svg" width="20" height="16" viewbox="0 0 20 16" style="margin-right: 6px"
                                         fill="var(--bs-secondary)" xmlns="http://www.w3.org/2000/svg">
                                        <path
                                                d="M20 4.608V12.75C20.0001 13.5801 19.6824 14.3788 19.1123 14.9822C18.5422 15.5856 17.7628 15.948 16.934 15.995L16.75 16H3.25C2.41986 16.0001 1.62117 15.6824 1.01777 15.1123C0.414367 14.5422 0.0519987 13.7628 0.00500011 12.934L0 12.75V4.608L9.652 9.664C9.75938 9.72024 9.87879 9.74962 10 9.74962C10.1212 9.74962 10.2406 9.72024 10.348 9.664L20 4.608ZM3.25 2.36051e-08H16.75C17.5556 -9.70147e-05 18.3325 0.298996 18.93 0.839267C19.5276 1.37954 19.9032 2.12248 19.984 2.924L10 8.154L0.016 2.924C0.0935234 2.15431 0.44305 1.43752 1.00175 0.902463C1.56045 0.367409 2.29168 0.049187 3.064 0.00500014L3.25 2.36051e-08H16.75H3.25Z"/>
                                    </svg>
                                </div>
                                <?= $email; ?>
                            </a>
                        </li>
                        <li class="footer-info--item my-2">
                            <p><svg class="footer-info--svg" width="14" height="16" viewbox="0 0 14 16"
                                    fill="var(--bs-white)" xmlns="http://www.w3.org/2000/svg">
                                    <path
                                            d="M7 0C8.72391 0 10.3772 0.693765 11.5962 1.92867C12.8152 3.16358 13.5 4.83848 13.5 6.58491C13.5 9.36745 11.57 12.3999 7.76 15.7146C7.54815 15.899 7.27804 16.0002 6.99875 16C6.71947 15.9998 6.44953 15.898 6.238 15.7133L5.986 15.4917C2.34467 12.2635 0.5 9.30532 0.5 6.58491C0.5 4.83848 1.18482 3.16358 2.40381 1.92867C3.62279 0.693765 5.27609 0 7 0ZM7 4.05225C6.33696 4.05225 5.70107 4.31908 5.23223 4.79405C4.76339 5.26901 4.5 5.9132 4.5 6.58491C4.5 7.25661 4.76339 7.9008 5.23223 8.37576C5.70107 8.85073 6.33696 9.11756 7 9.11756C7.66304 9.11756 8.29893 8.85073 8.76777 8.37576C9.23661 7.9008 9.5 7.25661 9.5 6.58491C9.5 5.9132 9.23661 5.26901 8.76777 4.79405C8.29893 4.31908 7.66304 4.05225 7 4.05225Z"
                                            fill-opacity="1" />
                                </svg> Адрес: г.Ташкент, 100020, Шайхантахурский р-н, ул.2-Бог, 2/1</p>
                            <div class="d-none">
                            <?= !empty($location) ? $location->body : null ?>
                            </div>
                        </li>
                    </ul>
                </div>
                <div class="single-footer-widget d-none">

                    <p>Телефон: <a href="tel:+998 99 824 17 81" class="contacts">+998 99 824 17 81</a></p>
                    <p>Почта: <a href="mailto:info@trustsugurta.uz" class="contacts">info@trustsugurta.uz</a></p>
                    <p>Адрес: г.Ташкент, 100020, Шайхантахурский р-н, ул.2-Бог, 2/1</p>
                    <p class="footer-text">Copyright ©
                        <script>
                            document.write(new Date().getFullYear());
                        </script>
                        Все права защищены
                    </p>
                </div>
            </div>
            <div class="col-lg-5 col-md-6 col-sm-6">
                <div class="single-footer-widget">

                    <div class="" id="mc_embed_signup">
                        <form target="_blank"
                              action="#"
                              method="get" novalidate="true">
                            <div class="input-group w-100">
                                <div class="input-group-btn w-100">
                                    <h4><?= Yii::t('frontend', 'Новостная рассылка') ?></h4>
                                    <p><?= Yii::t('frontend', 'Оставайтесь в курсе последних событий') ?></p>

                                    <?= $this->render('inc/_block_subscribe') ?>
                                </div>
                                <div class="info"></div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6 social-widget">
                <div class="single-footer-widget">
                    <h4 class="footer-info--title"><?= Yii::t('frontend', 'Мы в социальных сетях') ?></h4>
                    <!-- <p>Let us be social</p> -->
                    <div class="d-flex align-items-center g-2 social-icons text-center my-3 mw-250">
                        <?= SocialNetworksWidget::widget(['has_image' => true]) ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mt-5">
            <span class="col-xl-6 col-lg-6 col-md-6 col-sm-12 text-decoration-underline">
                <?= Yii::t('footer', 'При копировании материалов ссылка на сайт обязательна') ?>
            </span>
            <ul class="col-xl-6 col-lg-6 col-md-6 col-sm-12 nav d-flex justify-content-end">
                <li class="nav-item">
                    <a href="<?= Url::to(['/page/view/privacy']) ?>"
                       class="text-decoration-none text-secondary border-bottom-animation-on-hover pb-1"><?= Yii::t('footer', 'Политика конфиденциальности') ?></a>
                </li>
                <li class="nav-item ms-3">
                    <a href="<?= Url::to(['/page/view/terms_of_service']) ?>"
                       class="text-decoration-none text-secondary border-bottom-animation-on-hover pb-1"><?= Yii::t('footer', 'Условия обслуживания') ?></a>
                </li>
            </ul>
        </div>
        <div class="footer-border my-3">
            <div class="footer-border--in"></div>
        </div>
        <div class="row mt-3">
            <p class="col-md-6 footer-sub--title">&copy; <?= Html::encode(Yii::$app->name) ?> <?= date('Y') ?>
                . <?= Yii::t('app', 'Все права защищены.') ?></p>
            <ul class="col-md-6 nav d-flex justify-content-end">
                <li class="nav-item">
                    <a href="http://triger.uz/" class="text-decoration-none text-secondary border-bottom-animation-on-hover pb-1" rel="nofollow"
                       target="_blank"><?= Yii::t('footer', 'Developed by {companyName}', ['companyName' => 'TRIGGER']) ?></a>
                </li>
            </ul>
        </div>
    </div>
</footer>
<!--========== SCROLL UP ==========-->
<a href="#" class="scrollup" id="scroll-up">
    <svg class="scrollUpSvg" width="36px" height="36px" viewbox="0 0 24 24" fill="var(--bs-secondary)"
         xmlns="http://www.w3.org/2000/svg">
        <circle cx="12" cy="12" r="10" stroke="white" stroke-width="2"/>
        <path d="M8 13.5L12 9.5L16 13.5" stroke="white" stroke-width="2" stroke-linecap="round"
              stroke-linejoin="round"/>
    </svg>
</a>