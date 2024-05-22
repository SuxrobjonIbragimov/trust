<?php

use app\common\widgets\CustomAlert;
use app\widgets\ActionsWidget;
use yii\helpers\Html;
use yii\widgets\Breadcrumbs;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\modules\policy\models\PolicyTravel */

$this->title = Yii::t('policy','Проверьте данные');
$this->params['breadcrumbs'][] = ['label' => Yii::t('policy','Calculate Policy'), 'url' => ['calculate']];
$this->params['breadcrumbs'][] = $this->title;

$countries_list = $model->getCountriesList(null,'en');
$programs_list = $model->getProgramsList();
$purposes_list = $model->getPurposesList();

$countries = null;
if (!empty($model->policyTravelToCountries)) {
    foreach ($model->policyTravelToCountries as $trcountrymodel) {
        $countries[] = $trcountrymodel->country->localeName;
    }
}
?>

<?php if (!empty($modelPage->body)):?>
    <?php $this->params['footer_text'] = $modelPage->body; ?>
<?php endif;?>

<?php if (!empty($modelPage->image)):?>
    <?php $this->params['header_bg_image'] = $modelPage->image; ?>
<?php endif;?>

<main class="middle mt-5 pt-5 fs-4">
    <div class="policy-page mt-5 pt-5">

        <div class="container">
            <div class="flex-center-block mb-3">

                <div class="policy-driver-view calculator-card mt-5 overflow-hidden">
                    <div class="table-responsive">
                        <h3 class="text-center fs-3 mt-4 mb-4"><?= Yii::t('policy', 'Детали путешествия') ?></h3>
                        <?= DetailView::widget([
                            'model' => $model,
                            'attributes' => [
                                [
                                    'attribute' => 'start_date',
                                    'format' => 'raw',
                                    'value' => (!empty($model->start_date)) ? date('d.m.Y', strtotime($model->start_date)) : null,
                                ],
                                [
                                    'attribute' => 'end_date',
                                    'format' => 'raw',
                                    'value' => (!empty($model->end_date)) ? date('d.m.Y', strtotime($model->end_date)) : null,
                                ],
                                'days',
                                [
                                    'attribute' => 'country_ids',
                                    'format' => 'raw',
                                    'value' => (!empty($countries)) ? implode(', ', $countries) : null,
                                ],
                                [
                                    'attribute' => 'purpose_id',
                                    'format' => 'raw',
                                    'value' => (!empty($purposes_list[$model->purpose_id])) ? ($purposes_list[$model->purpose_id]) : null,
                                ],
                                [
                                    'attribute' => 'program_id',
                                    'format' => 'raw',
                                    'value' => (!empty($programs_list[$model->program_id])) ? ($programs_list[$model->program_id]) : null,
                                ],
                                'app_name',
                                'app_surname',
                                [
                                    'attribute' => 'app_birthday',
                                    'format' => 'raw',
                                    'value' => (!empty($model->app_birthday)) ? date('d.m.Y', strtotime($model->app_birthday)) : null,
                                ],
                                'app_pass_sery',
                                'app_pass_num',
                                'app_phone',
                                'app_email:email',
                                'app_address',
                            ],
                        ]) ?>

                        <?php if (!empty($model->policyTravelTravellers)) :?>
                            <h3 class="text-center fs-3 mt-4 mb-4"><?= Yii::t('policy','Travellers')?></h3>
                            <table class="table table-striped table-bordered detail-view bottom-table">
                                <tbody>
                                    <?php foreach ($model->policyTravelTravellers as $key=> $traveller):?>
                                        <tr>
                                            <th colspan="2" class="text-center">
                                                <h3 class="text-center fs-3 mt-4 mb-4"><?=Yii::t('policy','Traveller <span class="index">{0}</span>',[$key+1])?></h3>
                                            </th>
                                        </tr>
                                        <tr>
                                            <th><?= $traveller->getAttributeLabel('first_name') ?></th>
                                            <td><?= $traveller->first_name ?></td>
                                        </tr>
                                        <tr>
                                            <th><?= $traveller->getAttributeLabel('surname') ?></th>
                                            <td><?= $traveller->surname ?></td>
                                        </tr>
                                        <tr>
                                            <th><?= $traveller->getAttributeLabel('birthday') ?></th>
                                            <td><?= (!empty($traveller->birthday)) ? date('d.m.Y', strtotime($traveller->birthday)) : null ?></td>
                                        </tr>
                                        <tr>
                                            <th><?= Yii::t('policy','Passport')?></th>
                                            <td><?= $traveller->fullPassNumber ?></td>
                                        </tr>
                                    <?php endforeach;?>
                                </tbody>
                            </table>
                        <?php endif;?>

                    </div>

                    <div class="row align-items-center mx-0 modal__container mt-5 w-100"><!--modal__mid-->
                        <div class="col-xl-6 col-lg-6 col-md-6 col-12 field  modal__field d-flex justify-content-center me-auto"><!--modal__field-col-->
                            <?= Html::a(Yii::t('policy', 'Изменить данные'), ['/policy/travel/form', 'h' => _model_encrypt($model)], ['class' => 's-custom-btn s-custom-link cursor-pointer btn-default']) ?>
                        </div>

                        <div class="col-xl-6 col-lg-6 col-md-6 field col-12  modal__field ms-auto"><!--modal__field-col-->
                            <?= Html::a(Yii::t('policy', 'Перейти к оплате'), ['/policy/travel/confirm', 'h' => _model_encrypt($model)], ['class' => 's-custom-btn s-custom-primary btn-success mt-md-2 w-100']) ?>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</main>
