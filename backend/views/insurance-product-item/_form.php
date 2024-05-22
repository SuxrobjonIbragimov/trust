<?php

use backend\models\insurance\InsuranceProductItem;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\insurance\InsuranceProductItem */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="insurance-product-item-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'insurance_product_id')->textInput() ?>

    <?= $form->field($model, 'type')->dropDownList(InsuranceProductItem::_getTypeList()) ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>


    <div class="form-group">
        <?= Html::submitButton(Yii::t('model', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
