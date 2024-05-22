<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\modules\policy\models\PolicyTravelFamilyRate */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="policy-travel-family-rate-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'member_min')->textInput() ?>

    <?= $form->field($model, 'member_max')->textInput() ?>

    <?= $form->field($model, 'rate')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('views', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
