<?php

use backend\modules\policy\models\PolicyTravel;
use backend\modules\policy\models\PolicyTravelProgram;
use backend\modules\policy\models\PolicyTravelPurpose;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\policy\models\search\PolicyTravelSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('views', 'Policy Travels');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="policy-travel-index">

    <p>
        <?= Html::a(Yii::t('views', 'Create'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
                'attribute' => 'id',
                'format' => 'raw',
                'visible' => (Yii::$app->user->can('administrator')),
            ],
            'start_date',
            'end_date',
//            'days',
            'policy_series',
            'policy_number',
            'amount_uzs',
            //'amount_usd',
            [
                'attribute' => 'purpose_id',
                'format' => 'raw',
                'filter' => PolicyTravelPurpose::_getItemsList(),
                'value' => function (PolicyTravel $data) {
                    $full_str = $data->purpose->name_ru ?? $data->purpose_id;
                    return $full_str;
                },
            ],
            [
                'attribute' => 'program_id',
                'format' => 'raw',
                'filter' => PolicyTravelProgram::_getItemsList(),
                'value' => function (PolicyTravel $data) {
                    $full_str = $data->program->name_ru ?? $data->program_id;
                    return $full_str;
                },
            ],
            //'abroad_group',
            //'is_family:boolean',
            'app_name',
            //'app_birthday',
            //'app_pinfl',
            //'app_pass_sery',
            //'app_pass_num',
            [
                'attribute' => 'app_phone',
                'format' => 'raw',
                'value' => function (PolicyTravel $data) {
                    $full_str = '+'.$data->app_phone;
                    return mask_to_phone_format($full_str);
                },
            ],
            //'app_email:email',
            //'app_address',
            //'source',
            //'ins_anketa_id',
            //'ins_policy_id',
            //'status',
            //'created_by',
            //'updated_by',
            'created_at:datetime',
            //'updated_at',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
