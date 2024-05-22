<?php

/* @var $this yii\web\View */
/* @var $model backend\models\menu\Menus */

$this->title = Yii::t('views', 'Create Menu');
$this->params['breadcrumbs'][] = ['label' => Yii::t('views', 'Menus'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="menus-create box box-success">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
