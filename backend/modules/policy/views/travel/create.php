<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\modules\policy\models\PolicyTravel */

$this->title = Yii::t('views', 'Create Policy Travel');
$this->params['breadcrumbs'][] = ['label' => Yii::t('views', 'Policy Travels'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="policy-travel-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
