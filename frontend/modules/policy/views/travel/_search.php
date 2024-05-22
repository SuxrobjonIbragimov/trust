<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\policy\models\PolicyTravelSEarch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="policy-travel-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'start_date') ?>

    <?= $form->field($model, 'end_date') ?>

    <?= $form->field($model, 'days') ?>

    <?= $form->field($model, 'policy_number') ?>

    <?php // echo $form->field($model, 'amount_uzs') ?>

    <?php // echo $form->field($model, 'amount_usd') ?>

    <?php // echo $form->field($model, 'country_ids') ?>

    <?php // echo $form->field($model, 'purpose_id') ?>

    <?php // echo $form->field($model, 'program_id') ?>

    <?php // echo $form->field($model, 'abroad_group') ?>

    <?php // echo $form->field($model, 'app_name') ?>

    <?php // echo $form->field($model, 'app_birthday') ?>

    <?php // echo $form->field($model, 'app_pinfl') ?>

    <?php // echo $form->field($model, 'app_pass_sery') ?>

    <?php // echo $form->field($model, 'app_pass_num') ?>

    <?php // echo $form->field($model, 'app_phone') ?>

    <?php // echo $form->field($model, 'app_email') ?>

    <?php // echo $form->field($model, 'app_address') ?>


    <div class="form-group">
        <?= Html::submitButton(Yii::t('policy','Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('policy','Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
