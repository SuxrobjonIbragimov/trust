<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\modules\policy\models\PolicyTravelRisk */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('views', 'Policy Travel Risks'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="policy-travel-risk-view">

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
            'name_ru',
            'name_uz',
            'name_en',
//            'parent_id',
//            'ins_id',
//            'weight',
//            'status',
            'created_at:datetime',
            'updated_at:datetime',
        ],
    ]) ?>

</div>
