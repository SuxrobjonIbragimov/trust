<?php

/* @var $this yii\web\View */
/* @var $model backend\models\menu\MenuItems */

$this->title = Yii::t('views', 'Update Item: ') . $model->label;

$this->params['breadcrumbs'][] = ['label' => Yii::t('views', 'Menus'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->menu->name, 'url' => ['view', 'id' => $model->menu_id]];
$this->params['breadcrumbs'][] = ['label' => $model->label, 'url' => ['view-item', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('views', 'Update');
?>
<div class="menu-items-update box box-primary">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
