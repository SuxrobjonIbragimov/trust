<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\modules\telegram\models\BotUser */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="bot-user-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-md-4 col-sm-12">
            <?= $form->field($model, 't_id')->textInput() ?>
        </div>
        <div class="col-md-4 col-sm-12">
            <?= $form->field($model, 'first_name')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md-4 col-sm-12">
            <?= $form->field($model, 'last_name')->textInput(['maxlength' => true]) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4 col-sm-12">
            <?= $form->field($model, 't_username')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md-4 col-sm-12">
            <?= $form->field($model, 'phone')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md-4 col-sm-12">
            <?= $form->field($model, 'language_code')->textInput(['maxlength' => true]) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4 col-sm-12">
            <?= $form->field($model, 'is_premium')->checkbox() ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4 col-sm-12">

            <?php if (Yii::$app->user->can('accessAdministrator')):?>
                <?= $form->field($model, 'is_admin')->checkbox() ?>
            <?php endif;?>

        </div>
    </div>
    <div class="form-group">
        <?= Html::submitButton(Yii::t('telegram', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
