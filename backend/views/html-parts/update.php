<?php

/* @var $this yii\web\View */
/* @var $model backend\models\parts\HtmlParts */

$this->title = Yii::t('views', 'Update Block: ') . $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('views', 'Blocks'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('views', 'Update');
?>
<div class="html-parts-update box box-primary">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
