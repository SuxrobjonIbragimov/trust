<?php

use kartik\typeahead\Typeahead;
use yii\helpers\Html;
use yii\helpers\Url;
use common\models\Settings;

/** @var string $phone */
/** @var string $email */

$phone = Settings::getValueByKey(Settings::KEY_MAIN_PHONE);
$email = Settings::getValueByKey(Settings::KEY_MAIN_EMAIL);

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
<!-- |SPECIAL OPPORTUNITIES| -->
<!--========================================================================-->
<div class="modal fade" id="specialOpportunities" tabindex="-1" aria-labelledby="specialOpportunitiesLabel"
     aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-4 fw-bold" id="specialOpportunitiesLabel"><?=Yii::t('layouts','Maxsus imkoniyatlar')?></h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-gray">
                <p>
                    <?=Yii::t('layouts','Qulaylik uchun saytning mahsus imkoniyataridan foydalanishingiz mumkin!')?>

                <div class="d-flex align-items-center pt-4">
                    <a class="dropdown-item w-auto noDefalult modeOn" href="#">
                        <div class="position-relative z-index-9 darkmode">
                            <input type="checkbox" id="toggleThemeMode" class="mode__theme" />
                            <label class="label" for="toggleThemeMode"></label>
                        </div>
                    </a>
                    <span class="modal-line"></span>
                    <div class="darkLightMode"><b><?=Yii::t('layouts','kunduzgi/tungi rejim')?></b>
                    </div>
                </div>
                <div class="d-flex align-items-center pt-4">
                    <a class="dropdown-item w-auto noDefalult voiceOn" href="#" data-bs-target="#voiceModal">
                        <!---->
                        <div class="position-relative z-index-9 voicemode">
                            <input type="checkbox" id="toggleVoiceMode" class="mode__voice" />
                            <label class="label" for="toggleVoiceMode"></label>
                        </div>
                    </a>
                    <span class="modal-line"></span>
                    <div class="darkLightMode">
                        <b> <?=Yii::t('layouts','matnni ovozli rejimda o’qish')?></b>
                    </div>
                </div>
                <hr class="my-4 hr-primary">
                <div class="d-flex align-items-center flex-wrap">
                    <div
                            class="d-flex align-items-center justify-content-evenly mb-2 flex-wrap dropdown-not-close z-index-9">
                        <button class="dropdown-item dropdown-item-not-close decremet decreaseFont text-center"
                                value="decrease">A-
                        </button>
                        <button class="dropdown-item dropdown-item-not-close autoSize resetFont text-center mx-2"
                                value="resetFont" style="font-size: 16px;">auto
                        </button>
                        <button class="dropdown-item dropdown-item-not-close increment increaseFont text-center"
                                value="increase">A+
                        </button>
                    </div>
                    <span class="modal-line"></span>
                    <b>
                        <div class="my-size text-center p-1" id="currentSize">16px</div>
                    </b>
                    <div class="darkLightMode w-100">
                        <b><?=Yii::t('layouts','matnni katta/kichik rejimga o’girish')?></b>
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
                <h1 class="modal-title fs-5 text-black" id="headerSearchLabel"><?=Yii::t('layouts','Поиск')?></h1>
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
<nav class="navbar navbar-expand-lg bg-light">
    <div class="container">
        <button class="navbar-toggler d-none" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarSupportedContentTop" aria-controls="navbarSupportedContentTop"
                aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContentTop">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link d-flex align-items-center me-1 fs-7" href="tel:+<?= clear_phone_full($phone) ?>">
                        <i class="me-1 bx bx-phone"></i> <?= mask_to_phone_format($phone) ?></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link d-flex align-items-center me-1 fs-7" href="mailto:info@trustsugurta.uz"><i
                                class="me-1 bx bx-envelope"></i> <?= ($email) ?></a>
                </li>
            </ul>

            <ul class="navbar-nav align-items-center ms-auto mb-2 mb-lg-0">

                <li class="nav-item">
                    <a class="nav-link d-flex align-items-center me-1 fs-7" href="<?= Url::to(['/site/sitemap']) ?>">
                        <i class="me-1 bx bx-sitemap"></i> <?= Yii::t('app', 'Карта сайта') ?></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link d-flex align-items-center me-1 fs-7" data-bs-toggle="modal"
                       data-bs-target="#specialOpportunities" href="#">
                        <i class="me-1 bx bx-show"></i>
                        <?=Yii::t('layouts','Maxsus imkoniyatlar')?>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="search-action nav-link d-flex align-items-center me-1 fs-7" data-bs-toggle="modal"
                       data-bs-target="#headerSearch" href="#"><i class="me-1 bx bx-search-alt"></i> <?=Yii::t('layouts','Поиск')?></a>
                </li>
                <li class="nav-item dropdown">
                    <!-- Language -->
                    <?= \common\widgets\LanguageSwitcherWidget::widget(['is_front' => true, 'flag' => false, 'container_tag' => 'a', 'container_class' => 'dropdown-item dropdown-item-language']) ?>
                    <!-- End Language -->
                </li>
            </ul>
        </div>
    </div>
</nav>
<!--========================================================================-->
<!-- |HEADER| -->
<!--========================================================================-->
<nav class="navbar navbar-expand-lg bg-light" id="myHeader">
    <div class="container">
        <?= Html::a(Html::img(Settings::getLogoValue(),
            ['class' => '', 'alt' => 'logo']),
            [Yii::$app->homeUrl],
            ['class' => 'navbar-brand']) ?>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <?= $this->render('inc/menu-top-main') ?>
        </div>
    </div>
</nav>