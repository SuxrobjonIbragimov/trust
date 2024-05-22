 <?php

use app\common\widgets\CustomAlert;
use app\widgets\ActionsWidget;
use yii\helpers\Html;
use yii\widgets\Breadcrumbs;

/* @var $this yii\web\View */
/* @var $payment_type_list array */
/* @var $program array */
/* @var $model frontend\modules\policy\models\PolicyTravel */

$this->title = Yii::t('policy','Payment');
$this->params['breadcrumbs'][] = ['label' => Yii::t('policy','Calculate Policy'), 'url' => ['calculate']];
$this->params['breadcrumbs'][] = $this->title;
?>

<main class="middle">
    <div class="policy-travel-create">

        <div class="container">


            <div class="flex-center-block contact-us-page__container mb-3">
                <div class="contact-us-page__container mb-3 flex-column min-with-contain" style="">
                    <div class="contact-us-page flex-row">
                        <div class="contact-us-page__info mr-2">
                            <div class="field modal__field">
                                <div class="contact-us-page__label">
                                    <?= Yii::t('policy','FIO');?>
                                </div>
                                <div class="contact-us-page__text">
                                    <span><?= $model->app_name .' '. $model->app_surname ?></span>
                                </div>
                            </div>

                            <div class="field modal__field">
                                <div class="contact-us-page__label">
                                    <?= Yii::t('policy','PHONE');?>
                                </div>
                                <div class="contact-us-page__text">
                                    <span><?= $model->app_phone ?></span>
                                </div>
                            </div>

                        </div>
                        <div class="contact-us-page__info mr-2">

                            <?php if (!empty($program['name'])):?>
                                <div class="field modal__field">
                                    <div class="contact-us-page__label">
                                        <?= Yii::t('policy','Program');?>
                                    </div>
                                    <div class="contact-us-page__text">
                        <span>
                            <?= mb_strtoupper($program['name']) ?: 'Program not selected' ?>
                        </span>
                                    </div>
                                </div>
                            <?php endif;?>

                            <div class="field modal__field">
                                <div class="contact-us-page__label">
                                    <?= Yii::t('policy','Полис нархи');?>
                                </div>
                                <div class="contact-us-page__text">
                                    <span id="policy_price"><?= number_format($model->amount_uzs, 2, '.', ' ')?></span>
                                    <span class="currency"><?= Yii::t('policy','сўм');?></span>
                                    <span class="small">
                                        (<span id="policy_price_usd small"><?= number_format($model->amount_usd, 2, '.', ' ')?></span>
                                        <span class="currency small"><?= Yii::t('policy','USD');?></span>)
                                    </span>
                                </div>
                            </div>


                            <div class="field modal__field">
                                <div class="contact-us-page__label">
                                    <?= Yii::t('policy','Period');?>
                                </div>
                                <div class="contact-us-page__text">
                                    <span><?= date('d.m.Y', strtotime($model->start_date))?> - <?= date('d.m.Y', strtotime($model->end_date))?></span>
                                </div>
                            </div>


                        </div>
                    </div>

                    <div class="modal__container payment-list">
                        <div>
                            <div class="contact-us-page__label">
                                <p><?= Yii::t('policy','Выберите способ оплаты');?></p>
                            </div>
                            <div class="flex-row">

                                <?php foreach ($payment_type_list as $key => $item ): ?>
                                    <div class="field  modal__field modal__field-col">
                                        <?= Html::a($item['name'],[$item['url'],'h' => $item['h']])?>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
</main>
