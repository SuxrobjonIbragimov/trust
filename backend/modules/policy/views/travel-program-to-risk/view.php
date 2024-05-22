<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\modules\policy\models\PolicyTravelProgramToRisk */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('views', 'Policy Travel Program To Risks'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="policy-travel-program-to-risk-view">

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
            'policy_travel_program_id',
            'policy_travel_risk_id',
            'value',
//            'weight',
//            'status',
            'created_at:datetime',
        ],
    ]) ?>

</div>
