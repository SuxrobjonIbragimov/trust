<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\modules\telegram\models\BotUserSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="bot-user-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 't_id') ?>

    <?= $form->field($model, 'is_bot')->checkbox() ?>

    <?= $form->field($model, 'first_name') ?>

    <?= $form->field($model, 'last_name') ?>

    <?php // echo $form->field($model, 't_username') ?>

    <?php // echo $form->field($model, 'phone') ?>

    <?php // echo $form->field($model, 'language_code') ?>

    <?php // echo $form->field($model, 'callback_data') ?>

    <?php // echo $form->field($model, 'current_product') ?>

    <?php // echo $form->field($model, 'current_step_type') ?>

    <?php // echo $form->field($model, 'current_step_val') ?>

    <?php // echo $form->field($model, 'message_id_l') ?>

    <?php // echo $form->field($model, 'message_id_d') ?>

    <?php // echo $form->field($model, 'message_id_e') ?>

    <?php // echo $form->field($model, 'is_premium') ?>

    <?php // echo $form->field($model, 'info') ?>

    <?php // echo $form->field($model, 'is_admin')->checkbox() ?>

    <?php // echo $form->field($model, 'status') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('telegram', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('telegram', 'Reset'), ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
