<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\insurance\InsuranceProduct */

$this->title = Yii::t('model', 'Create Insurance Product');
$this->params['breadcrumbs'][] = ['label' => Yii::t('model', 'Insurance Products'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="insurance-product-create  box box-default">
    <div class="box-body">
        <?= $this->render('_form', [
            'model' => $model,
        ]) ?>
    </div>
</div>
