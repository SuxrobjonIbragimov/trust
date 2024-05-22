<?php

use frontend\widgets\SocialNetworksWidget;
use yii\helpers\Html;
use common\models\Settings;
use backend\models\menu\Menus;
use backend\models\parts\HtmlParts;
use yii\helpers\Url;

/* @var $this yii\web\View */
/** @var string $phone */

$phone = Settings::getValueByKey(Settings::KEY_MAIN_PHONE);
$email = Settings::getValueByKey(Settings::KEY_MAIN_EMAIL);
$location = HtmlParts::getItemByKey(HtmlParts::KEY_MAIN_LOCATION);
?>

<?php if ((($menuInfo = Menus::findOne(['key' => 'front_footer', 'status' => Menus::STATUS_ACTIVE])) !== null) && !empty($menuInfo->menuItemsActive)) : ?>
    <?php foreach ($menuInfo->menuItemsActive as $index_m => $menuItems):?>
        <div class="footer__col column__<?=($index_m+2)?>">
            <?php if (!empty($menuItems->menuItemsActive)):?>
                <h2 class="footer__col-title"><?= $menuItems->label ?></h2>
                <ul class="footer__col-list">
                    <?php foreach ($menuItems->menuItemsActive as $item):?>
                        <li class="footer__col-item">
                            <?= Html::a($item->label, [$item->url], ['class' => 'footer__col-link'])?>
                        </li>
                    <?php endforeach;?>
                </ul>
            <?php else:?>
            <?php endif;?>
        </div>
    <?php endforeach;?>
<?php endif; ?>

<div class="footer__col column__4">
    <div class="footer__col-box">
        <h2 class="footer__col-title"><?=Yii::t('frontend','Связаться с нами')?></h2>
        <ul class="footer__col-list">
            <li class="footer__col-item">
                <a href="tel:<?= $phone; ?>" class="footer__col-link d-flex align-items-center">
                    <div class="footer__col-icon">
                        <svg class="footer__col-svg" width="14" height="16" viewbox="0 0 14 16"
                             fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path
                                    d="M1.49949 0.648917L2.37484 0.197016C3.19577 -0.226257 4.20318 0.0517441 4.72882 0.84626L5.77544 2.42914C6.23076 3.1184 6.20807 4.02671 5.71946 4.6741L4.35745 6.47635C4.6204 7.36711 5.09189 8.20675 5.77108 8.9954C6.41886 9.76006 7.21696 10.3891 8.11812 10.8453L9.96483 9.86209C10.6644 9.49042 11.5242 9.62875 12.0963 10.2054L13.4315 11.5483C14.0976 12.2191 14.1897 13.2876 13.6477 14.0487L13.0674 14.8644C12.489 15.6763 11.5316 16.1016 10.5547 15.9793C8.24664 15.692 5.88208 14.0575 3.45754 11.0766C1.0295 8.09097 -0.120188 5.398 0.00992068 3.00085C0.0644357 1.99213 0.630575 1.09708 1.49949 0.648917Z" />
                        </svg>
                    </div>
                    <?= $phone; ?>
                </a>
            </li>
            <li class="footer__col-item">
                <a href="mailto:<?= $email; ?>" class="footer__col-link d-flex align-items-center">
                    <div class="footer__col-icon">
                        <svg class="footer__col-svg" width="20" height="16" viewbox="0 0 20 16"
                             fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path
                                    d="M20 4.608V12.75C20.0001 13.5801 19.6824 14.3788 19.1123 14.9822C18.5422 15.5856 17.7628 15.948 16.934 15.995L16.75 16H3.25C2.41986 16.0001 1.62117 15.6824 1.01777 15.1123C0.414367 14.5422 0.0519987 13.7628 0.00500011 12.934L0 12.75V4.608L9.652 9.664C9.75938 9.72024 9.87879 9.74962 10 9.74962C10.1212 9.74962 10.2406 9.72024 10.348 9.664L20 4.608ZM3.25 2.36051e-08H16.75C17.5556 -9.70147e-05 18.3325 0.298996 18.93 0.839267C19.5276 1.37954 19.9032 2.12248 19.984 2.924L10 8.154L0.016 2.924C0.0935234 2.15431 0.44305 1.43752 1.00175 0.902463C1.56045 0.367409 2.29168 0.049187 3.064 0.00500014L3.25 2.36051e-08H16.75H3.25Z" />
                        </svg>
                    </div>
                    <?= $email; ?>
                </a>
            </li>
        </ul>
    </div>
    <div class="footer__col-box">
        <h2 class="footer__col-title"><?=Yii::t('frontend','Location')?></h2>
        <ul class="footer__col-list">
            <li class="footer__col-item">
                <?= !empty($location) ? $location->body : null?>
            </li>
        </ul>
    </div>

</div>
