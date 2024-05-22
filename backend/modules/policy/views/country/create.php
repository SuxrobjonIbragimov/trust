<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\modules\policy\models\HandbookCountry */

$this->title = Yii::t('views', 'Create Handbook Country');
$this->params['breadcrumbs'][] = ['label' => Yii::t('views', 'Handbook Countries'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="handbook-country-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
