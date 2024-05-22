<?php

/* @var $this yii\web\View */
/* @var $model backend\models\sliders\Sliders */

$this->title = Yii::t('views', 'Update Slider: ') . $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('views', 'Sliders'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('views', 'Update');
?>
<div class="sliders-update box box-primary">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
