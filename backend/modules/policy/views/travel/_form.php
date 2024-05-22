<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\modules\policy\models\PolicyTravel */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="policy-travel-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'start_date')->textInput() ?>

    <?= $form->field($model, 'end_date')->textInput() ?>

    <?= $form->field($model, 'days')->textInput() ?>

    <?= $form->field($model, 'policy_series')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'policy_number')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'amount_uzs')->textInput() ?>

    <?= $form->field($model, 'amount_usd')->textInput() ?>

    <?= $form->field($model, 'purpose_id')->textInput() ?>

    <?= $form->field($model, 'program_id')->textInput() ?>

    <?= $form->field($model, 'abroad_group')->textInput() ?>

    <?= $form->field($model, 'is_family')->checkbox() ?>

    <?= $form->field($model, 'app_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'app_birthday')->textInput() ?>

    <?= $form->field($model, 'app_pinfl')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'app_pass_sery')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'app_pass_num')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'app_phone')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'app_email')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'app_address')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'source')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'ins_anketa_id')->textInput() ?>

    <?= $form->field($model, 'ins_policy_id')->textInput() ?>

    <?= $form->field($model, 'status')->textInput() ?>


    <div class="form-group">
        <?= Html::submitButton(Yii::t('views', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
