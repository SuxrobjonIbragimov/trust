<?php

use yii\captcha\Captcha;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model \backend\modules\policy\models\CheckPolicy */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="check-form small-block">

    <?php $form = ActiveForm::begin(); ?>

    <div class="flex-row">
        <div class="field modal__field">
            <?php $label = $model->getAttributeLabel('policy_series');?>
            <?= $form->field($model, 'policy_series',['options' => ['class' => 'field__row form-group']])->dropDownList($model->getPolicySeriesList(),['maxlength' => true, 'class' => 'field__input',
                'placeholder' => Yii::t('policy','{label}ни танланг',
                    ['label' => $label])
            ])->label(false) ?>
        </div>

        <div class="field modal__field ml-1">
            <?php $label = $model->getAttributeLabel('policy_number');?>
            <?= $form->field($model, 'policy_number',['options' => ['class' => 'field__row form-group']])
                ->textInput(['maxlength' => true, 'class' => 'field__input',
                    'placeholder' => Yii::t('policy','{label}ни киритинг',
                        ['label' => $label])
                ])->label(false) ?>
        </div>

        <div class="form-group">
            <?= Html::submitButton(Yii::t(['slug','Check']), ['class' => 'btn btn-success ml-1']) ?>
        </div>

    </div>
    <div class="flex-row">

        <div class="field modal__field ml-1">
            <?php $label = $model->getAttributeLabel('reCaptcha');?>

            <?= $form->field($model, 'reCaptcha')->widget(
                \himiklab\yii2\recaptcha\ReCaptcha3::className(),
                [
                    'siteKey' => '6LciwvQcAAAAAO1DlyRI-MkcA-O8zVbQv7eAUTDP', // unnecessary is reCaptcha component was set up
                    'action' => 'homepage',
                ]
            )->label(false) ?>

        </div>

    </div>
    <?php ActiveForm::end(); ?>

</div>
