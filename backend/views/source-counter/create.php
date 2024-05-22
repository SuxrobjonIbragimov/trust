<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\page\SourceCounter */

$this->title = Yii::t('model', 'Create Source Counter');
$this->params['breadcrumbs'][] = ['label' => Yii::t('model', 'Source Counters'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="source-counter-create box box-success">

    <div class="box-body">
        <?= $this->render('_form', [
            'model' => $model,
        ]) ?>
    </div>

</div>
