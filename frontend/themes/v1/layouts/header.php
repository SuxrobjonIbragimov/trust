<?php

use common\widgets\LanguageSwitcherWidget;
use kartik\typeahead\Typeahead;
use yii\helpers\Html;
use yii\helpers\Url;
use common\models\Settings;

/** @var string $phone */
/** @var string $email */

$phone = Settings::getValueByKey(Settings::KEY_MAIN_PHONE);
$phone = !empty($phone) ? str_replace(['(',')',' ','-'],'',$phone)  : null;
$email = Settings::getValueByKey(Settings::KEY_MAIN_EMAIL);
$logo  = Settings::getLogoValue();
?>


<!--========================================================================-->
<!-- BUTTON HIGHGROUND TEXT READ (VOICES) -->
<!--========================================================================-->
<select class="voiceModeControl" id="voiceSelect" hidden></select>
<button class="btn-voice shadow" id="voice">
    <div class="icon-voice d-flex align-items-center justify-content-center">
        <i class="bx bx-volume-full bx-sm"></i>
    </div>
</button>

<!--========================================================================-->
<!-- SITE MENU MODAL -->
<!--========================================================================-->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-fullscreen-lg-down">
        <div class="modal-content">
            <?= $this->render('./inc/menu-top-mobile') ?>
        </div>
    </div>
</div>

<!--========================================================================-->
<!-- |SPECIAL OPPORTUNITIES| -->
<!--========================================================================-->
<div class="modal fade" id="specialOpportunities" tabindex="-1" aria-labelledby="specialOpportunitiesLabel"
     aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-4 fw-bold"
                    id="specialOpportunitiesLabel"><?= Yii::t('header', 'Maxsus imkoniyatlar'); ?></h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-gray">
                <p><?= Yii::t('header', 'Qulaylik uchun saytning mahsus imkoniyataridan foydalanishingiz mumkin !'); ?>

                <div class="d-flex align-items-center pt-4">
                    <a class="dropdown-item w-auto noDefalult" href="#">
                        <div class="position-relative z-index-9 darkmode">
                            <input type="checkbox" id="toggleThemeMode" class="mode__theme"/>
                            <label class="label" for="toggleThemeMode"></label>
                        </div>
                    </a>
                    <span class="modal-line"></span>
                    <div class="darkLightMode"><b><?= Yii::t('header', 'kunduzgi/tungi rejim'); ?></b>
                    </div>
                </div>
                <div class="d-flex align-items-center pt-4">
                    <a class="dropdown-item w-auto noDefalult voiceOn" href="#" data-bs-target="#voiceModal">
                        <!---->
                        <div class="position-relative z-index-9 voicemode">
                            <input type="checkbox" id="toggleVoiceMode" class="mode__voice"/>
                            <label class="label" for="toggleVoiceMode"></label>
                        </div>
                    </a>
                    <span class="modal-line"></span>
                    <div class="darkLightMode">
                        <b> <?= Yii::t('header', 'matnni ovozli rejimda o’qish'); ?></b>
                    </div>
                </div>
                <hr class="my-4 hr-primary">
                <div class="d-flex align-items-center flex-wrap">
                    <div
                            class="d-flex align-items-center justify-content-evenly mb-2 flex-wrap dropdown-not-close z-index-9">
                        <button class="dropdown-item dropdown-item-not-close decremet decreaseFont text-center"
                                value="decrease"><?= Yii::t('header','A-'); ?>
                        </button>
                        <button class="dropdown-item dropdown-item-not-close autoSize resetFont text-center mx-2"
                                value="resetFont" style="font-size: 16px;"><?= Yii::t('header','auto'); ?>
                        </button>
                        <button class="dropdown-item dropdown-item-not-close increment increaseFont text-center"
                                value="increase"><?= Yii::t('header','A+'); ?>
                        </button>
                    </div>
                    <span class="modal-line"></span>
                    <b>
                        <div class="my-size text-center p-1" id="currentSize"><?= Yii::t('header','16px'); ?></div>
                    </b>
                    <div class="darkLightMode w-100">
                        <b><?= Yii::t('header','matnni katta/kichik rejimga o’girish'); ?></b>
                    </div>
                </div>
                </p>
            </div>
            <div class="modal-footer">
                <!-- <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button> -->
            </div>
        </div>
    </div>
</div>

<!--========================================================================-->
<!-- |HEADER TOP SEARCH| -->
<!--========================================================================-->
<div class="modal fade modal-shortcut" id="headerSearch" tabindex="-1" aria-labelledby="headerSearchLabel"
     aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5 text-black" id="headerSearchLabel"><?= Yii::t('header','Izlash'); ?></h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <?= Html::beginForm(['post/search'], 'get', ['class' => 'd-flex py-4']) ?>
                    <?= Typeahead::widget([
                        'name' => 'text',
                        'options' => [
                            'placeholder' => Yii::t('app', 'Поиск...'),
                            'required' => true,
                            'autocomplete' => 'off',
                            'type' => 'search',
                            'class' => 'search-action-input form-control me-2',
                            'style' => 'height: auto;',
                        ],
                        'scrollable' => true,
                        'pluginOptions' => [
                            'highlight' => true,
                            'minLength' => 2,
                        ],
                        'dataset' => [
                            [
                                'display' => 'value',
                                'limit' => 1000,
                                'remote' => [
                                    'url' => Url::to(['post/post-list']) . '?q=%QUERY',
                                    'wildcard' => '%QUERY'
                                ]
                            ]
                        ],
                        'pluginEvents' => [
                            'typeahead:select' => 'function(event, data) {
                                            location.href = "/post/view/" + data["key"];
                                        }',
                        ]
                    ]) ?>
                <?= Html::submitButton(Yii::t('app', '<i class="bx bx-search-alt bx-sm"></i>'),
                    ['class' => 'btn d-flex justify-content-center align-items-center btn-outline-primary rounded-2 d-inline-block rounded-3 text-uppercase fs-5 fw-bold ms-2']) ?>
                <?= Html::endForm() ?>
            </div>
        </div>
    </div>
</div>

<!--========================================================================-->
<!-- |HEADER TOP| -->
<!--========================================================================-->
<nav class="pt-2 d-none d-lg-block nav-top" style="background-color: #f0f4f8">
    <div class="container position-relative">
        <div class="d-flex align-items-center justify-content-between w-100" id="navbarSupportedContentTop">
            <?= $this->render('./inc/menu-top-main') ?>
            <div class="d-flex align-items-center pb-2">
                <a class="btn btn-secondary text-white d-flex fw-bold align-items-center justify-content-center me-3 fs-7 px-xl-4 px-3 py-2"
                   data-bs-toggle="modal"
                   data-bs-target="#specialOpportunities" href="#"><i class="me-1 bx bx-show"></i>
                    <span class="d-none d-xl-block text-nowrap"><?= Yii::t('header','Maxsus imkoniyatlar'); ?></span></a>
                <?php if (!empty($phone)): ?>
                    <a class="btn btn-primary fs-7 fw-bold px-xl-4 px-3 py-2 d-flex align-items-center justify-content-center"
                        href="tel:<?= $phone ?>"><i class='bx bx-phone me-1'></i><span class="d-none d-xl-block text-nowrap"><?= Yii::t('header','Обратная связь'); ?></span></a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</nav>

<!--========================================================================-->
<!-- |HEADER| -->
<!--========================================================================-->
<nav class="navbar navbar-expand-lg bg-light" id="sticky-menu">
    <div class="container">
        <?php if (!empty($logo)): ?>
            <a class="navbar-brand me-0" href="<?= Yii::$app->homeUrl ?>">
                <img src="<?= $logo ?>" class="w-100" alt="Logo">
            </a>
        <?php endif; ?>
        <ul class="d-lg-flex d-none align-items-center column-gap-3 ms-4 me-auto">
            <li><a href="<?= Url::to(['/product','type' => 1]) ?>" class="fs-6 fw-normal text-secondary"><?= Yii::t('header','Физическое лицо'); ?></a></li>
            <li><a href="<?= Url::to(['/product','type' => 2]) ?>" class="fs-6 fw-normal text-secondary"><?= Yii::t('header','Юридическое лицо'); ?></a></li>
        </ul>
        <div class="d-flex align-items-center">
            <a class="search-action nav-link d-flex align-items-center d-none-450px fs-7" data-bs-toggle="modal"
               data-bs-target="#headerSearch" href="#"><i class="me-1 bx bx-search-alt"></i> <?= Yii::t('header','Izlash'); ?></a>
            <a class="btn btn-secondary fs-7 fw-bold px-xl-4 px-3 py-2 ms-3 text-white d-none-500px d-flex align-items-center justify-content-center"
               href="<?= Url::to(['/customer/login']) ?>"><i class='bx bx-user me-1'></i><span class="d-none d-lg-block"><?= Yii::t('header','Личный кабинет'); ?></span></a>

            <?= LanguageSwitcherWidget::widget(['is_front' => true, 'flag' => false, 'container_tag' => 'a', 'container_class' => 'dropdown-item dropdown-item-language']) ?>

            <div class="d-flex d-lg-none align-items-center column-gap-3">
                <a class="btn btn-secondary text-white d-flex fw-bold align-items-center justify-content-center fs-7 px-xl-4 px-3 py-2"
                   data-bs-toggle="modal"
                   data-bs-target="#specialOpportunities" href="#"><i class="me-1 bx bx-show"></i>
                    <span class="d-none d-xl-block"><?= Yii::t('header','Maxsus imkoniyatlar'); ?></span></a>
                <a class="btn btn-primary fs-7 fw-bold px-xl-4 px-3 py-2 d-none d-md-flex align-items-center justify-content-center"
                   href="#"><i class='bx bx-phone me-1'></i><span class="d-none d-xl-block"><?= Yii::t('header','Обратная связь'); ?></span></a>
            </div>

            <button class="border-0 bg-transparent align-items-center menu-item-burger sticky-menu-btn"
                    data-bs-toggle="modal" data-bs-target="#exampleModal"
                    type="button"><i class='bx bx-menu fs-4 text-gray'></i></button>
        </div>
    </div>
</nav>