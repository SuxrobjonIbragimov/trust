<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\page\SourceCounter */

$this->title = Yii::t('model', 'Update Source Counter: {name}', [
    'name' => $model->name,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('model', 'Source Counters'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('model', 'Update');
?>
<div class="source-counter-update box box-success">

    <div class="box-body">
        <?= $this->render('_form', [
            'model' => $model,
        ]) ?>
    </div>

</div>
