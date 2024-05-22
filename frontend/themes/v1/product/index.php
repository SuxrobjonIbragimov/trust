<?php

use backend\models\insurance\InsuranceProduct;
use backend\modules\handbook\models\HandbookLegalType;
use yii\helpers\Url;

/* @var $legal_type_list HandbookLegalType */
/* @var $legal_type HandbookLegalType */
/* @var $items InsuranceProduct */
/* @var $item InsuranceProduct */
/* @var $type integer */

$tab_index = Yii::$app->request->get('entity') ? intval(Yii::$app->request->get('entity')) : 0;

$this->title = Yii::t('title', 'Все виды страхования');
?>
<?php if (!empty($legal_type_list)): ?>
    <section class="section-plan mt-3" id="choosePlan">
        <div class="container mt-3">
            <div class=" mt-2" data-aos="fade-up" data-aos-delay="1000">
                <h2 class="sections-primary-title text-center"><?= Yii::t('app', 'Выберите тип полиса') ?></h2>
                <form action="" class="d-flex align-items-center justify-content-center">
                    <ul class="nav nav-pills d-inline-flex  align-items-center justify-content-center bg-white my-3 mx-auto rounded-5 p-2 border-custom-secondary"
                        id="pills-tab"
                        role="tablist">
                        <?php foreach ($legal_type_list as $key => $legal_type): ?>
                            <li class="nav-item <?= ($legal_type->id == $type) ? 'me-2 active' : 'ms-2' ?>" role="presentation">
                                <label for="t<?= ($key + 1) ?>"
                                       class="nav-link rounded-5 <?= ($legal_type->id == $type) ? 'active' : '' ?>"
                                       id="pills-<?= ($key) ?>-tab" data-bs-toggle="pill"
                                       data-bs-target="#pills-<?= ($key) ?>" type="button" role="tab"
                                       aria-controls="pills-<?= ($key) ?>"
                                       aria-selected="<?= ($legal_type->id == $type) ? 'true' : 'false' ?>">
                                    <?= $legal_type->nameLocale; ?>
                                </label>
                            </li>
                            <?php if ($key == 0): ?>
                                <span class="button2"></span>
                            <?php endif; ?>
                        <?php endforeach; ?>
                        <div class="blob"></div>
                    </ul>
                </form>

                <div class="tab-content" id="pills-tabContent">
                    <?php foreach ($legal_type_list as $key => $legal_type): ?>
                        <div class="tab-pane fade show <?= ($legal_type->id == $type) ? 'active' : '' ?>"
                             id="pills-<?= ($key) ?>" role="tabpanel" aria-labelledby="pills-<?= ($key) ?>-tab">
                            <div class="row justify-content-md-between justify-content-center gy-4">
                                <?php $items = $legal_type->getProducts()->orderBy(['weight' => SORT_ASC, 'title' => SORT_ASC])->all(); ?>
                                <?php if (!empty($items)): ?>
                                    <?php foreach ($items as $key_i => $item): ?>
                                        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-10 col-12">
                                            <div class="tab-pane__box card-custom card card-image-hover h-100 d-inline-flex rounded-3 overflow-hidden shadow-hover-scale position-relative text-decoration-none p-2 w-100">
                                                <!--<img class="card-custom--icon" src="<? /*= $item->image; */ ?>" alt="">-->
                                                <div class="d-inline-block h-150px z-index-1">
                                                    <img class="card-custom--icon w-100 h-100 object-fit-cover"
                                                         src="<?= '/themes/v1/img/product_background.png' ?>" alt="">
                                                </div>
                                                <div class="card-body d-flex flex-column justify-content-between align-items-center h-100 z-index-1 px-1">
                                                    <h4 class="position-relative text-primary z-index-2 text-start fw-bold "><?= $item->title; ?></h4>
                                                    <div class="d-flex justify-content-between align-items-center w-100">
                                                        <a href="<?= !empty($item->calc_link) ? $item->calc_link : '#'?>"
                                                           class="btn btn-primary text-center d-block mt-auto <?= (empty($item->calc_link) || $item->calc_link == '#') ? 'disabled' : ''?> "><?= Yii::t('frontend', 'Купить') ?></a>
                                                        <a href="<?= Url::to(['product/view', 'slug' => $item->slug]) ?>"
                                                           class="btn btn-primary text-center d-block mt-auto"><?= Yii::t('frontend', 'Подробнее') ?></a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </section>
<?php endif; ?>