<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\modules\telegram\models\BotUser */
/* @var $modelMessage backend\modules\telegram\models\BotUserMessage */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="bot-user-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-lg-6 col-md-10">
            <?= $form->field($modelMessage, 'message')->textarea(['maxlength' => true]) ?>

            <div class="form-group">
                <?= Html::submitButton(Yii::t('telegram', 'Send'), ['class' => 'btn btn-success']) ?>
            </div>

        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
