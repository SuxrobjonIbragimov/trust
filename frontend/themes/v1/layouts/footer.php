<?php

use backend\models\parts\HtmlParts;
use common\models\Settings;
use frontend\widgets\SocialNetworksWidget;
use frontend\widgets\SubscribeWidget;
use yii\helpers\Html;
use yii\helpers\Url;

$phone = Settings::getValueByKey(Settings::KEY_MAIN_PHONE);
$phone2 = '+998 71 255 66 00';
$email = Settings::getValueByKey(Settings::KEY_MAIN_EMAIL);
$location = HtmlParts::getItemByKey(HtmlParts::KEY_MAIN_LOCATION);
?>


<!--========================================================================-->
<!-- |FOOTER|-->
<!--========================================================================-->
<footer class="footer-area bg-dark text-light section-gap mt-5 pt-5 pb-3" id="contacts">
    <div class="container">
        <div class="row">
            <div class="col-lg-5 col-md-6 col-sm-6">
                <div class="single-footer-widget">
                    <h5><?= Yii::t('footer', 'Наши контакты') ?></h5>
                    <p class="contacts mt-3"><?= Yii::t('footer','Телефон'); ?>:
                        <br>
                        <a href="tel:+<?= clear_phone_full($phone); ?>" class="contacts"><?= $phone; ?></a>
                        <br>
                        <a href="tel:+<?= clear_phone_full($phone2); ?>" class="contacts"><?= $phone2; ?></a>
                    </p>
                    <p><?= Yii::t('footer','Почта'); ?>: <a href="mailto:<?= $email; ?>" class="contacts"><?= $email; ?></a></p>
                    <p><?= Yii::t('footer','Адрес'); ?>: <?= !empty($location) ? $location->body : null ?></p>

                </div>
            </div>
            <div class="col-lg-5 col-md-6 col-sm-6">
                <div class="single-footer-widget">
                    <h5><?= Yii::t('footer','Новостная рассылка'); ?></h5>
                    <p><?= Yii::t('footer','Оставайтесь в курсе последних событий'); ?></p>
                    <?= $this->render('inc/_block_subscribe') ?>
                </div>
            </div>
            <div class="col-lg-2 col-md-6 col-sm-6 social-widget mt-3 mt-sm-0">
                <div class="single-footer-widget">
                    <h5 class="text-center text-sm-start mb-2"><?= Yii::t('footer','Мы в соц сетях'); ?></h5>
                    <?= SocialNetworksWidget::widget(['has_image' => false,'tag_class' => 'footer-social d-flex align-items-center justify-content-sm-between justify-content-around']) ?>
                </div>
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-lg-6 col-md-6 col-sm-6">
                <div class="single-footer-widget">
                    <p class="footer-text"><?= Yii::t('footer','Copyright ©'); ?>
                        <script>
                            document.write(new Date().getFullYear());
                        </script>
                        <?= Yii::t('footer','Все права защищены'); ?>
                    </p>
                </div>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6">
                <div class="d-flex justify-content-end w-100">
                    <a href="http://triger.uz/"
                       class="primary-hover transition fs-7 fw-bold text-white"
                       rel="nofollow"
                       target="_blank"><?= Yii::t('frontend', 'Developed by {companyName}', ['companyName' => 'TRIGGER']) ?></a>
                </div>
            </div>
        </div>
    </div>
</footer>

<!--========================================================================-->
<!-- |Scroll Up| -->
<!--========================================================================-->
<a href="#" class="scrollup z-index-999" id="scroll-up">
    <i class="bx bxs-chevron-up"></i>
</a>