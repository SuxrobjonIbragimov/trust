<?php

/* @var $this yii\web\View */
/* @var $model backend\models\parts\HtmlParts */

$this->title = Yii::t('views', 'Create Block');
$this->params['breadcrumbs'][] = ['label' => Yii::t('views', 'Blocks'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="html-parts-create box box-success">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
