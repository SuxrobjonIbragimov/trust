<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\content\SocialNetwork */

$this->title = Yii::t('app', 'Create Social Network');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Social Networks'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="social-network-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
