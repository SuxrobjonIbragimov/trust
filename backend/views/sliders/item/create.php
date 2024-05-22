<?php

/* @var $this yii\web\View */
/* @var $model backend\models\sliders\SliderItems */

$this->title = Yii::t('views', 'Create Item');
$this->params['breadcrumbs'][] = ['label' => Yii::t('views', 'Sliders'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="slider-items-create box box-success">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
