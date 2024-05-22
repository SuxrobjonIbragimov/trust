<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use trntv\aceeditor\AceEditor;

/* @var $this yii\web\View */
/* @var $model \backend\modules\telegram\models\BotUser */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="telegram-default-index">
    <h1><?= $this->context->action->uniqueId ?></h1>
    <p>
        This is the view content for action "<?= $this->context->action->id ?>".
    </p>

    <?php if (!empty($model) && Yii::$app->user->can('administrator')):?>
        <div class="bot-form box-body">

            <?php $form = ActiveForm::begin(); ?>

            <div class="row">
                <div class="col-lg-6 col-md-10">
                    <?= $form->field($model, 'base_url')->textInput(['maxlength' => true]) ?>
                </div>
            </div>

            <div class="form-group">
                <?= Html::submitButton(Yii::t('views', 'Save'), ['class' => 'btn btn-success']) ?>
            </div>

            <?php ActiveForm::end(); ?>

        </div>
    <?php endif;?>

</div>
