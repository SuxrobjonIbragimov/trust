<?php

/* @var $this yii\web\View */
/* @var $model backend\models\sliders\Sliders */

$this->title = Yii::t('views', 'Create Slider');
$this->params['breadcrumbs'][] = ['label' => Yii::t('views', 'Sliders'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sliders-create box box-success">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
