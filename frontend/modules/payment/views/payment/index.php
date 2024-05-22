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

                <?php if (0) : ?>
                <div class="payment-page__container" style="">
                    <div class="payment-page">
                        <div class="payment-page__info modal-cont-alt">

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
                                    <span><?= $model->appFullName ?></span>
                                </div>
                            </div>

                            <div class="calculator-card__item">
                                <div class="calculator-card__label">
                                    <?= Yii::t('policy','PHONE');?>
                                </div>
                                <div class="calculator-card__value">
                                    <span>+<?= $model->app_phone ?></span>
                                </div>
                            </div>

                        </div>
                        <div class="payment-page__info modal-cont-alt">

                            <?php if (!empty($program['name'])):?>
                                <div class="calculator-card__item">
                                    <div class="calculator-card__label">
                                        <?= Yii::t('policy','Program');?>
                                    </div>
                                    <div class="calculator-card__value">
                                        <span>
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
                                    <span id="policy_price"><?= number_format($model->amount_uzs, 2, '.', ' ')?></span>
                                    <span class="currency"><?= Yii::t('policy','сўм');?></span>
                                </div>
                            </div>


                            <div class="calculator-card__item">
                                <div class="calculator-card__label">
                                    <?= Yii::t('policy','Period');?>
                                </div>
                                <div class="calculator-card__value">
                                    <span><?= date('d.m.Y', strtotime($model->start_date))?> - <?= date('d.m.Y', strtotime($model->end_date))?></span>
                                </div>
                            </div>


                        </div>
                    </div>

                    <div class="modal__container">
                        <div>
                            <div class="text-center payment-page__label">
                                <p><?= Yii::t('policy','Оплатите через');?>:</p>
                            </div>
                            <div class="flex-row d-flex align-items-center">
                                <?php foreach ($payment_type_list as $key => $item ): ?>
                                    <div class="field  modal__field modal__field-col modal-img">
                                        <?= Html::a($item['name'],[$item['url'],'h' => $item['h']])?>
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
