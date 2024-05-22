<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\modules\policy\models\PolicyTravelProgramPeriod */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="policy-travel-program-period-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'policy_travel_program_id')->textInput() ?>

    <?= $form->field($model, 'day_min')->textInput() ?>

    <?= $form->field($model, 'day_max')->textInput() ?>

    <?= $form->field($model, 'value')->textInput() ?>

    <?= $form->field($model, 'is_fixed')->checkbox() ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('views', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
