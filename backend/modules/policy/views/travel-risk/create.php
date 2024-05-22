<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\modules\policy\models\PolicyTravelRisk */

$this->title = Yii::t('views', 'Create Policy Travel Risk');
$this->params['breadcrumbs'][] = ['label' => Yii::t('views', 'Policy Travel Risks'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="policy-travel-risk-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
