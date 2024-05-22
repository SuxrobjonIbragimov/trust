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
<footer class="footer-area bg-dark text-light section-gap mt-5 pt-5 pb-5" id="contacts">
    <div class="container">
        <div class="row">
            <div class="col-lg-5 col-md-6 col-sm-6">
                <div class="single-footer-widget">
                    <h5><?= Yii::t('footer', 'Наши контакты') ?></h5>
                    <p><?= Yii::t('footer','Телефон'); ?>: <a href="tel:+<?= clear_phone_full($phone); ?>" class="contacts"><?= $phone; ?></a></p>
                    <p><?= Yii::t('footer','Почта'); ?>: <a href="mailto:<?= $email; ?>" class="contacts"><?= $email; ?></a></p>
                    <p><?= Yii::t('footer','Адрес'); ?>: <?= !empty($location) ? $location->body : null ?></p>
                    <p class="footer-text"><?= Yii::t('footer','Copyright ©'); ?>
                        <script>
                            document.write(new Date().getFullYear());
                        </script>
                        <?= Yii::t('footer','Все права защищены'); ?>
                    </p>
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
    </div>
</footer>

<!--========================================================================-->
<!-- |Scroll Up| -->
<!--========================================================================-->
<a href="#" class="scrollup z-index-999" id="scroll-up">
    <i class="bx bxs-chevron-up"></i>
</a>