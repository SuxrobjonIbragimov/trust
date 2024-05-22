<?php

/* @var $this yii\web\View */
/* @var $model backend\models\menu\Menus */

$this->title = Yii::t('views', 'Update Menu: ') . $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('views', 'Menus'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('views', 'Update');
?>
<div class="menus-update box box-primary">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
