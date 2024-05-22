<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\insurance\InsuranceProductItem */

$this->title = Yii::t('model', 'Create Insurance Product Item');
$this->params['breadcrumbs'][] = ['label' => Yii::t('model', 'Insurance Product Items'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="insurance-product-item-create box box-success">
    <div class="box-body">
        <?= $this->render('_form', [
            'model' => $model,
        ]) ?>
    </div>
</div>
