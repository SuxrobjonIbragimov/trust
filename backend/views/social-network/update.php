<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\content\SocialNetwork */

$this->title = Yii::t('app', 'Update Social Network: {name}', [
    'name' => $model->name,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Social Networks'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="social-network-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
