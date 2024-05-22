<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\modules\policy\models\PolicyTravelTraveller */

$this->title = Yii::t('views', 'Create Policy Travel Traveller');
$this->params['breadcrumbs'][] = ['label' => Yii::t('views', 'Policy Travel Travellers'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="policy-travel-traveller-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
