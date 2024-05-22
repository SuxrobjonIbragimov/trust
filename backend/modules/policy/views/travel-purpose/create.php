<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\modules\policy\models\PolicyTravelPurpose */

$this->title = Yii::t('views', 'Create Policy Travel Purpose');
$this->params['breadcrumbs'][] = ['label' => Yii::t('views', 'Policy Travel Purposes'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="policy-travel-purpose-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
