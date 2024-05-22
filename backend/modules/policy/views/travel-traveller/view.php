<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\modules\policy\models\PolicyTravelTraveller */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('views', 'Policy Travel Travellers'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="policy-travel-traveller-view">

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
            'policy_travel_id',
            'first_name',
            'surname',
            'birthday',
            'pass_sery',
            'pass_num',
            'pinfl',
            'phone',
            'email:email',
            'address',
            'created_at',
            'updated_at',
        ],
    ]) ?>

</div>
