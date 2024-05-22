<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\modules\policy\models\PolicyTravelProgramToRisk */

$this->title = Yii::t('views', 'Update Policy Travel Program To Risk: {name}', [
    'name' => $model->id,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('views', 'Policy Travel Program To Risks'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('views', 'Update');
?>
<div class="policy-travel-program-to-risk-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
