<?php

use backend\modules\policy\models\PolicyOsgo;
use frontend\modules\policy\assets\OsgoAsset;
use yii\helpers\Html;
use yii\widgets\Breadcrumbs;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\modules\policy\models\PolicyOsgo */

OsgoAsset::register($this);
$this->title = Yii::t('policy', 'Проверьте данные');
//$this->params['breadcrumbs'][] = ['label' => Yii::t('policy', 'Calculate Policy'), 'url' => ['calculate']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);

?>

<?php if (!empty($modelPage->image)):?>
    <?php $this->params['header_bg_image'] = $modelPage->image; ?>
<?php endif;?>

<main class="middle">
    <div class="policy-page">
        <div class="container">

            <div class="flex-center-block mb-3">

                <div class="policy-driver-view calculator-card mt-5">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered detail-view">
                            <tr>
                                <th colspan="2" class="text-center"><?=Yii::t('policy','Ваши данные')?></th>
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
                                    +<?= $model->app_phone; ?>
                                </td>
                            </tr>

                            <tr>
                                <th colspan="2" class="text-center"><?=Yii::t('policy','Владелец транспортного средства')?></th>
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
                                <th colspan="2" class="text-center"><?=Yii::t('policy','Информация о транспортном средстве')?></th>
                            </tr>
                            <tr>
                                <th>
                                    <?=Yii::t('policy','Тип, марка и модель ТС')?>
                                </th>
                                <td>
                                    <?= $model->getVehicleTypesList($model->vehicle_type_id); ?>, <?= $model->vehicle_model_name?>
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

                        </table>

                        <?php if (!empty($model->policyOsgoDrivers)) :?>
                            <h6 class="mid-3 text-center fw-bold"><?= ($model->driver_limit_id == PolicyOsgo::DRIVER_UNLIMITED) ? Yii::t('policy','Родственники') : Yii::t('policy','Водители')?></h6>
                            <div class="table-box">
                                <table class="table table-striped table-bordered detail-view">
                                    <thead>
                                    <tr>
                                        <th><?= Yii::t('policy', '№')?></th>
                                        <th><?= Yii::t('policy', 'Full name')?></th>
                                        <th><?= Yii::t('policy', 'Date_birth')?></th>
                                        <th><?= Yii::t('policy', 'Passport number')?></th>
                                        <th><?= Yii::t('policy', 'License number')?></th>
                                        <th><?= Yii::t('policy', 'Relationship')?></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php /** @var \backend\modules\policy\models\PolicyOsgoDriver $driver */
                                    foreach ($model->policyOsgoDrivers as $key=> $driver):?>
                                        <tr>
                                            <td><?= ($key+1) ?></td>
                                            <td><?= $driver->fullName ?></td>
                                            <td><?= (!empty($driver->birthday)) ? date('d.m.Y', strtotime($driver->birthday)) : null ?></td>
                                            <td><?= $driver->fullPassNumber ?></td>
                                            <td><?= $driver->fullLicenseNumber ?></td>
                                            <td><?= $driver->_getRelationshipName() ?></td>
                                        </tr>
                                    <?php endforeach;?>
                                    </tbody>
                                </table>
                            </div>
                        <?php endif;?>

                    </div>

                    <div class="row align-items-center modal__container mx-0 mt-3 mt-5 w-100">
                        <div class="col-sm-5 col-12 field  modal__field d-flex justify-content-center me-auto mt-md-0 mt-2 mb-md-0 mb-3  p-0">
                            <?= Html::a(Yii::t('policy', 'Изменить данные'), ['/policy/osgo/form', 'h' => _model_encrypt($model)], ['class' => 'btn btn-light border-light-subtle w-100']) ?>
                        </div>

                        <div class="col-sm-5 field col-12 modal__field ms-auto mt-md-0 mt-2 mb-md-0 mb-3 p-0">
                            <?= Html::a(Yii::t('policy', 'Перейти к оплате'), ['/policy/osgo/confirm', 'h' => _model_encrypt($model)], ['class' => 'btn btn-primary w-100']) ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
</main>
