<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\modules\policy\models\PolicyTravelAgeGroup */

$this->title = Yii::t('views', 'Create Policy Travel Age Group');
$this->params['breadcrumbs'][] = ['label' => Yii::t('views', 'Policy Travel Age Groups'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="policy-travel-age-group-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
