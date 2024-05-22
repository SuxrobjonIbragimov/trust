<?php

/* @var $this yii\web\View */
/* @var $model backend\models\page\Pages */

$this->title = Yii::t('views', 'Create Page');
$this->params['breadcrumbs'][] = ['label' => Yii::t('views', 'Pages'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pages-create box box-success">
    <div class="box-body">
        <?= $this->render('_form', [
            'model' => $model,
        ]) ?>
    </div>

</div>
