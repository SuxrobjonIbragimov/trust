<?php

/* @var $this yii\web\View */
/* @var $model backend\models\page\Pages */

$this->title = Yii::t('views', 'Update Page: ') . $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('views', 'Pages'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('views', 'Update');
?>
<div class="pages-update box box-primary">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
