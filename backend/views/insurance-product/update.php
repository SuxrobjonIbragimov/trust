<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\insurance\InsuranceProduct */

$this->title = Yii::t('model', 'Update Insurance Product: {name}', [
    'name' => $model->title,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('model', 'Insurance Products'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('model', 'Update');
?>
<div class="insurance-product-update  box box-default">
    <div class="box-body">
        <?= $this->render('_form', [
            'model' => $model,
        ]) ?>
    </div>
</div>
