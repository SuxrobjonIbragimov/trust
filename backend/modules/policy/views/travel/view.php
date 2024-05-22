<?php

use backend\modules\policy\models\PolicyTravel;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\modules\policy\models\PolicyTravel */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('views', 'Policy Travels'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="policy-travel-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('views', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('views', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('views', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'start_date',
            'end_date',
            'days',
            'policy_series',
            'policy_number',
            'amount_uzs',
            [
                'attribute' => 'purpose_id',
                'format' => 'raw',
                'value' => function (PolicyTravel $data) {
                    $full_str = $data->purpose->name_ru ?? $data->purpose_id;
                    return $full_str;
                },
            ],
            [
                'attribute' => 'program_id',
                'format' => 'raw',
                'value' => function (PolicyTravel $data) {
                    $full_str = $data->program->name_ru ?? $data->program_id;
                    return $full_str;
                },
            ],
            'abroad_group',
            'is_family:boolean',
            'app_name',
            'app_birthday',
            'app_pinfl',
            'app_pass_sery',
            'app_pass_num',
            [
                'attribute' => 'app_phone',
                'format' => 'raw',
                'value' => function (PolicyTravel $data) {
                    $full_str = '+'.$data->app_phone;
                    return mask_to_phone_format($full_str);
                },
            ],
//            'app_email:email',
            'app_address',
//            'source',
//            'ins_anketa_id',
//            'ins_policy_id',
//            'status',
//            'created_by',
//            'updated_by',
            'created_at:datetime',
            'updated_at:datetime',
        ],
    ]) ?>

</div>
