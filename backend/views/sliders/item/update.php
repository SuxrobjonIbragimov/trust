<?php

/* @var $this yii\web\View */
/* @var $model backend\models\sliders\SliderItems */

$this->title = Yii::t('views', 'Update Item: ') . $model->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('views', 'Sliders'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->slider->name, 'url' => ['view', 'id' => $model->slider_id]];
$this->params['breadcrumbs'][] = Yii::t('views', 'Update');
?>
<div class="slider-items-update box box-primary">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
