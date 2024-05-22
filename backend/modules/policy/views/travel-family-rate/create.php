<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\modules\policy\models\PolicyTravelFamilyRate */

$this->title = Yii::t('views', 'Create Policy Travel Family Rate');
$this->params['breadcrumbs'][] = ['label' => Yii::t('views', 'Policy Travel Family Rates'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="policy-travel-family-rate-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
