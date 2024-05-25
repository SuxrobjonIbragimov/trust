<?php

use yii\helpers\Html;
use yii\widgets\Breadcrumbs;

/* @var $this yii\web\View */
/* @var $payment_type_list array */
/* @var $program array */
/* @var $model backend\modules\policy\models\PolicyOsgo */

$this->title = Yii::t('policy','Выберите способ оплаты');
$product_name = $model->policyOrder->productName;
$this->params['breadcrumbs'][] = ['label' => Yii::t('policy','Calculate Policy'), 'url' => ['/policy/'.mb_strtolower($product_name).'/calculate']];
$this->params['breadcrumbs'][] = $this->title;
?>

<main class="middle">
    <div class="policy-payment">
        <div class="container mt-3 pt-3">

            <div class="flex-center-block payment-page__container mb-3">

                <?php if (1) : ?>
                <div class="payment-page__container" style="">
                    <div class="row row-gap-3">
                        <div class="col-xl-3 col-lg-4 col-sm-6 col-12">

                            <div class="calculator-card__item">
                                <div class="calculator-card__label">
                                    <?= Yii::t('policy','ORDER ID');?>
                                </div>
                                <div class="calculator-card__value">
                                    <span><b><?= ($model->formName() == 'PolicyOsgo') ? $model->ins_anketa_id : $model->policyOrder->id ?></b></span>
                                </div>
                            </div>

                            <div class="calculator-card__item">
                                <div class="calculator-card__label">
                                    <?= Yii::t('policy','FIO');?>
                                </div>
                                <div class="calculator-card__value">
                                    <span class="fw-bold"><?= $model->appFullName ?></span>
                                </div>
                            </div>

                            <div class="calculator-card__item">
                                <div class="calculator-card__label">
                                    <?= Yii::t('policy','PHONE');?>
                                </div>
                                <div class="calculator-card__value">
                                    <span class="fw-bold">+<?= $model->app_phone ?></span>
                                </div>
                            </div>

                        </div>
                        <div class="col-xl-3 col-lg-4 col-sm-6 col-12">

                            <?php if (!empty($program['name'])):?>
                                <div class="calculator-card__item">
                                    <div class="calculator-card__label">
                                        <?= Yii::t('policy','Program');?>
                                    </div>
                                    <div class="calculator-card__value">
                                        <span class="fw-bold">
                                            <?= ($program['name']) ?: Yii::t('policy','Program not selected') ?>
                                        </span>
                                    </div>
                                </div>
                            <?php endif;?>

                            <div class="calculator-card__item">
                                <div class="calculator-card__label">
                                    <?= Yii::t('policy','Полис нархи');?>
                                </div>
                                <div class="calculator-card__value">
                                    <span class="fw-bold" id="policy_price"><?= number_format($model->amount_uzs, 2, '.', ' ')?></span>
                                    <span class="currency fw-bold"><?= Yii::t('policy','сўм');?></span>
                                </div>
                            </div>


                            <div class="calculator-card__item">
                                <div class="calculator-card__label">
                                    <?= Yii::t('policy','Period');?>
                                </div>
                                <div class="calculator-card__value">
                                    <span class="fw-bold"><?= date('d.m.Y', strtotime($model->start_date))?> - <?= date('d.m.Y', strtotime($model->end_date))?></span>
                                </div>
                            </div>


                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div>
                            <div class="text-start contact-us-page__label">
                                <p class="mt-2"><?= Yii::t('policy','Оплатите через');?>:</p>
                            </div>
                            <div class="row">
                                <?php foreach ($payment_type_list as $key => $item ): ?>
                                    <div class="col-6 col-md-4 col-lg-4">
                                        <?= Html::a($item['name'],[$item['url'],'h' => $item['h']], ['class' => 'd-flex align-items-center border border-2 rounded-3 bg-gray p-3 payme h-100'])?>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>

                </div>
                <?php else :?>
                    <h4>To'lov tizimida texnik ishlar olib borilmoqda</h4>
                <?php endif;?>

            </div>
        </div>
</main>
