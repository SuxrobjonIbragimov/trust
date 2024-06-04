<?php

use backend\modules\policy\models\PolicyOsgo;
use frontend\modules\policy\assets\OsgoAsset;
use yii\helpers\Html;
use yii\widgets\Breadcrumbs;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\modules\policy\models\PolicyOsgo */
/* @var $modelPaymentForm \frontend\models\PaymentForm */

OsgoAsset::register($this);
$this->title = Yii::t('policy', 'Проверьте данные');
//$this->params['breadcrumbs'][] = ['label' => Yii::t('policy', 'Calculate Policy'), 'url' => ['calculate']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);

?>

<?php if (!empty($modelPage->body)):?>
    <?php $this->params['footer_text'] = $modelPage->body; ?>
<?php endif;?>

<?php if (!empty($modelPage->image)):?>
    <?php $this->params['header_bg_image'] = $modelPage->image; ?>
<?php endif;?>

<main class="middle">
    <div class="policy-page">
        <div class="container">

            <div class="flex-center-block mb-3">

                <div class="policy-driver-view calculator-card mt-5 overflow-hidden">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered detail-view">
                            <tr>
                                <th colspan="2" class="text-center fs-md"><?=Yii::t('policy','Ваши данные')?></th>
                            </tr>
                            <tr>
                                <th>
                                    <?=Yii::t('policy','Полное имя')?>
                                </th>
                                <td>
                                    <?= $model->appFullName; ?>
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    <?=Yii::t('policy','Паспорт / ID карта')?>
                                </th>
                                <td>
                                    <?= $model->appFullPassNumber; ?>
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    <?=Yii::t('policy','ПИНФЛ')?>
                                </th>
                                <td>
                                    <?= $model->app_pinfl; ?>
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    <?=Yii::t('policy','Номер телефона')?>
                                </th>
                                <td>
                                    <?= mask_to_phone_format($model->app_phone); ?>
                                </td>
                            </tr>

                            <tr>
                                <th colspan="2" class="text-center fs-md"><?=Yii::t('policy','Владелец транспортного средства')?></th>
                            </tr>
                            <tr>
                                <th>
                                    <?=Yii::t('policy','Полное имя')?>
                                </th>
                                <td>
                                    <?= $model->owner_orgname; ?>
                                </td>
                            </tr>
                            <?php if ($model->owner_fy == PolicyOsgo::LEGAL_TYPE_FIZ):?>
                                <tr>
                                    <th>
                                        <?=Yii::t('policy','Паспорт / ID карта')?>
                                    </th>
                                    <td>
                                        <?= $model->ownerFullPassNumber; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        <?=Yii::t('policy','ПИНФЛ')?>
                                    </th>
                                    <td>
                                        <?= $model->owner_pinfl; ?>
                                    </td>
                                </tr>
                            <?php else:?>
                                <tr>
                                    <th>
                                        <?=Yii::t('policy','Паспорт / ID карта')?>
                                    </th>
                                    <td>
                                        <?= $model->ownerFullPassNumber; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        <?=Yii::t('policy','ИНН')?>
                                    </th>
                                    <td>
                                        <?= $model->owner_inn; ?>
                                    </td>
                                </tr>
                            <?php endif;?>


                            <tr>
                                <th colspan="2" class="text-center fs-md"><?=Yii::t('policy','Информация о транспортном средстве')?></th>
                            </tr>
                            <tr>
                                <th>
                                    <?=Yii::t('policy','Тип, марка и модель ТС')?>
                                </th>
                                <td>
                                    <?= $model->getVehicleTypesListOsgop($model->vehicle_type_id); ?>, <?= $model->vehicle_model_name?>
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    <?=Yii::t('policy','Год изготовления')?>
                                </th>
                                <td>
                                    <?= $model->vehicle_issue_year; ?>
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    <?=Yii::t('policy','Государственный регистрационный номер')?>
                                </th>
                                <td>
                                    <?= $model->vehicle_gov_number; ?>
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    <?=Yii::t('policy','Номер кузова (шасси) ТС')?>
                                </th>
                                <td>
                                    <?= $model->vehicle_body_number; ?>
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    <?=Yii::t('policy','Номер двигателя ТС')?>
                                </th>
                                <td>
                                    <?= $model->vehicle_engine_number; ?>
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    <?=Yii::t('policy','Технический паспорт	')?>
                                </th>
                                <td>
                                    <?= $model->fullTechPassNumber; ?> (<?= date('d.m.Y', strtotime($model->tech_pass_issue_date))?>)
                                </td>
                            </tr>

                            <tr>
                                <th>
                                    <?= $model->getAttributeLabel('vehicle_seats_count')?>
                                </th>
                                <td>
                                    <?= $model->vehicle_seats_count; ?>
                                </td>
                            </tr>

                        </table>

                    </div>

                    <div class="row align-items-center mx-0 modal__container mt-5 w-100 "><!--modal__mid-->
                        <div class="col-xl-5 col-lg-5 col-md-5 col-12 field  modal__field d-flex justify-content-center me-auto p-0"><!--modal__field-col-->
                            <?= Html::a(Yii::t('policy', 'Изменить данные'), ['/policy/osgop/form', 'h' => _model_encrypt($model)], ['class' => 'btn btn-default w-100 border border-1']) ?><!--s-custom-btn s-custom-link cursor-pointer btn-default-->
                        </div>

                        <div class="col-xl-5 col-lg-5 col-md-5 field col-12  modal__field ms-auto p-0 "><!--modal__field-col-->
                            <?= Html::a(Yii::t('policy', 'Перейти к оплате'), ['/policy/osgop/confirm', 'h' => _model_encrypt($model)], ['class' => 'btn btn-success w-100']) ?> <!--s-custom-btn s-custom-primary mt-md-2 w-100-->
                        </div>
                    </div>
                </div>
            </div>
        </div>
</main>
