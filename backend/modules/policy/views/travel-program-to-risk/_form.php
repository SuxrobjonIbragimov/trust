<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\modules\policy\models\PolicyTravelProgramToRisk */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="policy-travel-program-to-risk-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'policy_travel_program_id')->textInput() ?>

    <?= $form->field($model, 'policy_travel_risk_id')->textInput() ?>

    <?= $form->field($model, 'value')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('views', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
