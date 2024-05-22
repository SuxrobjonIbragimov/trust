<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\insurance\InsuranceProductItem */

$this->title = Yii::t('model', 'Update Insurance Product Item: {name}', [
    'name' => $model->title,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('model', 'Insurance Product Items'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view-item', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('model', 'Update');
?>
<div class="insurance-product-item-update  box box-primary">
    <div class="box-body">
        <?= $this->render('_form', [
            'model' => $model,
        ]) ?>
    </div>

</div>
