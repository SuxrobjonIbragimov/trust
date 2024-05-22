<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\modules\policy\models\PolicyTravelProgramToCountry */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="policy-travel-program-to-country-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'policy_travel_program_id')->textInput() ?>

    <?= $form->field($model, 'country_id')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('views', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
