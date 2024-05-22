<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\modules\policy\models\PolicyTravelProgramToRisk */

$this->title = Yii::t('views', 'Create Policy Travel Program To Risk');
$this->params['breadcrumbs'][] = ['label' => Yii::t('views', 'Policy Travel Program To Risks'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="policy-travel-program-to-risk-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
