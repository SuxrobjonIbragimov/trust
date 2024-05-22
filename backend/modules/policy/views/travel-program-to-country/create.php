<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\modules\policy\models\PolicyTravelProgramToCountry */

$this->title = Yii::t('views', 'Create Policy Travel Program To Country');
$this->params['breadcrumbs'][] = ['label' => Yii::t('views', 'Policy Travel Program To Countries'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="policy-travel-program-to-country-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
