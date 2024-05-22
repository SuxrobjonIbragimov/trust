<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\modules\policy\models\PolicyTravelPurpose */

$this->title = Yii::t('views', 'Update Policy Travel Purpose: {name}', [
    'name' => $model->id,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('views', 'Policy Travel Purposes'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('views', 'Update');
?>
<div class="policy-travel-purpose-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
