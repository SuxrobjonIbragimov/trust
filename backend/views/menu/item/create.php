<?php

/* @var $this yii\web\View */
/* @var $model backend\models\menu\MenuItems */

$this->title = Yii::t('views', 'Create Menu Item');
$this->params['breadcrumbs'][] = ['label' => Yii::t('views', 'Menus'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->menu->name, 'url' => ['view', 'id' => $model->menu_id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="menu-items-create box box-success">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
