<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\policy\models\PolicyTravel */

$this->title = Yii::t('policy', 'Update Policy Travel: {name}', [
    'name' => $model->id,
]);
$this->params['breadcrumbs'][] = ['label' => \app\widgets\BlocksWidget::widget(['slug' => 'Policy Travels']), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = \app\widgets\BlocksWidget::widget(['slug' => 'Update']);
?>
<div class="policy-travel-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
