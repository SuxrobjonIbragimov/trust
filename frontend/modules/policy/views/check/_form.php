<?php

use yii\captcha\Captcha;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model \backend\modules\policy\models\CheckPolicy */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="check-form small-block">

    <?php $form = ActiveForm::begin(['options' => ['class' => 'text-center mt-3']]); ?>

    <div class="row align-items-start justify-content-center">
        <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6 col-12 mt-xl-0 mt-lg-2 mt-md-2 mt-sm-2 mt-2">
            <?php $label = $model->getAttributeLabel('policy_series');?>
            <?= $form->field($model, 'policy_series')->textInput([
                'maxlength' => true,
                'class' => 'form-control',
                'oninput' => 'this.value = this.value.toUpperCase()',
                'placeholder' => Yii::t('frontend','{label}',
                    ['label' => $label])
            ])->label(false) ?>
        </div>

        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12 mt-xl-0 mt-lg-2 mt-md-2 mt-sm-2 mt-2">
            <?php $label = $model->getAttributeLabel('policy_number');?>
            <?= $form->field($model, 'policy_number')
                ->textInput(['maxlength' => true, 'class' => 'form-control',
                    'placeholder' => Yii::t('frontend','{label}ни киритинг',
                        ['label' => $label])
                ])->label(false) ?>
        </div>
        <div class="col-xl-3 col-lg-3 col-md-12 col-sm-12 col-12 mt-xl-0 mt-lg-2 mt-md-2 mt-sm-2 mt-2">
            <?= Html::submitButton(Yii::t('policy','Проверит'), ['class' => 'btn btn-primary cursor-pointer w-100 px-3']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>
</div>
