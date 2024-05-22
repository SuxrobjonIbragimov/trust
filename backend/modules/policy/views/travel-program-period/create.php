<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\modules\policy\models\PolicyTravelProgramPeriod */

$this->title = Yii::t('views', 'Create Policy Travel Program Period');
$this->params['breadcrumbs'][] = ['label' => Yii::t('views', 'Policy Travel Program Periods'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="policy-travel-program-period-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
