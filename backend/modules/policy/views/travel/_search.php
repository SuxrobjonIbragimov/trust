<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\modules\policy\models\search\PolicyTravelSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="policy-travel-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'start_date') ?>

    <?= $form->field($model, 'end_date') ?>

    <?= $form->field($model, 'days') ?>

    <?= $form->field($model, 'policy_series') ?>

    <?php // echo $form->field($model, 'policy_number') ?>

    <?php // echo $form->field($model, 'amount_uzs') ?>

    <?php // echo $form->field($model, 'amount_usd') ?>

    <?php // echo $form->field($model, 'purpose_id') ?>

    <?php // echo $form->field($model, 'program_id') ?>

    <?php // echo $form->field($model, 'abroad_group') ?>

    <?php // echo $form->field($model, 'is_family')->checkbox() ?>

    <?php // echo $form->field($model, 'app_name') ?>

    <?php // echo $form->field($model, 'app_birthday') ?>

    <?php // echo $form->field($model, 'app_pinfl') ?>

    <?php // echo $form->field($model, 'app_pass_sery') ?>

    <?php // echo $form->field($model, 'app_pass_num') ?>

    <?php // echo $form->field($model, 'app_phone') ?>

    <?php // echo $form->field($model, 'app_email') ?>

    <?php // echo $form->field($model, 'app_address') ?>

    <?php // echo $form->field($model, 'source') ?>

    <?php // echo $form->field($model, 'ins_anketa_id') ?>

    <?php // echo $form->field($model, 'ins_policy_id') ?>

    <?php // echo $form->field($model, 'status') ?>

    <?php // echo $form->field($model, 'created_by') ?>

    <?php // echo $form->field($model, 'updated_by') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('views', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('views', 'Reset'), ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
