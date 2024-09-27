<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model frontend\models\InsuranceForm */
/* @var $form yii\widgets\ActiveForm */

?>

<div class="container" style="max-width: 80%; margin: 0 auto; padding: 20px;">
    <div class="insurance-form">

        <?php $form = ActiveForm::begin(['options' => ['class' => 'form-horizontal']]); ?>

        <div class="row">
            <!-- Left Column -->
            <div class="col-md-6">
                <h2><?= Yii::t('app', 'Customer Information') ?></h2>

                <?= $form->field($model, 'customer_name', [
                    'options' => ['class' => 'form-group row'],
                    'labelOptions' => ['class' => 'col-md-12 col-form-label'],
                    'template' => "{label}\n<div class=\"col-md-12\">{input}</div>\n{hint}\n{error}"
                ])->textInput(['maxlength' => true, 'class' => 'form-control form-control-sm'])->label(Yii::t('app', 'Customer Name')) ?>

                <?= $form->field($model, 'customer_inn', [
                    'options' => ['class' => 'form-group row'],
                    'labelOptions' => ['class' => 'col-md-12 col-form-label'],
                    'template' => "{label}\n<div class=\"col-md-12\">{input}</div>\n{hint}\n{error}"
                ])->textInput(['maxlength' => true, 'class' => 'form-control form-control-sm'])->label(Yii::t('app', 'INN')) ?>

                <?= $form->field($model, 'customer_oked', [
                    'options' => ['class' => 'form-group row'],
                    'labelOptions' => ['class' => 'col-md-12 col-form-label'],
                    'template' => "{label}\n<div class=\"col-md-12\">{input}</div>\n{hint}\n{error}"
                ])->textInput(['maxlength' => true, 'class' => 'form-control form-control-sm'])->label(Yii::t('app', 'OKED')) ?>

                <?= $form->field($model, 'customer_mfo', [
                    'options' => ['class' => 'form-group row'],
                    'labelOptions' => ['class' => 'col-md-12 col-form-label'],
                    'template' => "{label}\n<div class=\"col-md-12\">{input}</div>\n{hint}\n{error}"
                ])->textInput(['maxlength' => true, 'class' => 'form-control form-control-sm'])->label(Yii::t('app', 'MFO')) ?>

                <?= $form->field($model, 'customer_bank_name', [
                    'options' => ['class' => 'form-group row'],
                    'labelOptions' => ['class' => 'col-md-12 col-form-label'],
                    'template' => "{label}\n<div class=\"col-md-12\">{input}</div>\n{hint}\n{error}"
                ])->textInput(['maxlength' => true, 'class' => 'form-control form-control-sm'])->label(Yii::t('app', 'Bank Name')) ?>

                <?= $form->field($model, 'customer_bank_rs', [
                    'options' => ['class' => 'form-group row'],
                    'labelOptions' => ['class' => 'col-md-12 col-form-label'],
                    'template' => "{label}\n<div class=\"col-md-12\">{input}</div>\n{hint}\n{error}"
                ])->textInput(['maxlength' => true, 'class' => 'form-control form-control-sm'])->label(Yii::t('app', 'Bank RS')) ?>

                <?= $form->field($model, 'customer_address', [
                    'options' => ['class' => 'form-group row'],
                    'labelOptions' => ['class' => 'col-md-12 col-form-label'],
                    'template' => "{label}\n<div class=\"col-md-12\">{input}</div>\n{hint}\n{error}"
                ])->textInput(['maxlength' => true, 'class' => 'form-control form-control-sm'])->label(Yii::t('app', 'Address')) ?>

                <?= $form->field($model, 'customer_phone', [
                    'options' => ['class' => 'form-group row'],
                    'labelOptions' => ['class' => 'col-md-12 col-form-label'],
                    'template' => "{label}\n<div class=\"col-md-12\">{input}</div>\n{hint}\n{error}"
                ])->textInput(['maxlength' => true, 'class' => 'form-control form-control-sm'])->label(Yii::t('app', 'Phone')) ?>

                <?= $form->field($model, 'customer_dir_name', [
                    'options' => ['class' => 'form-group row'],
                    'labelOptions' => ['class' => 'col-md-12 col-form-label'],
                    'template' => "{label}\n<div class=\"col-md-12\">{input}</div>\n{hint}\n{error}"
                ])->textInput(['maxlength' => true, 'class' => 'form-control form-control-sm'])->label(Yii::t('app', 'Director Name')) ?>
            </div>

            <!-- Right Column -->
            <div class="col-md-6">
                <h2><?= Yii::t('app', 'Project Information') ?></h2>

                <?= $form->field($model, 'lot_id', [
                    'options' => ['class' => 'form-group row'],
                    'labelOptions' => ['class' => 'col-md-12 col-form-label'],
                    'template' => "{label}\n<div class=\"col-md-12\">{input}</div>\n{hint}\n{error}"
                ])->textInput(['maxlength' => true, 'class' => 'form-control form-control-sm'])->label(Yii::t('app', 'Lot ID')) ?>

                <?= $form->field($model, 'dog_num', [
                    'options' => ['class' => 'form-group row'],
                    'labelOptions' => ['class' => 'col-md-12 col-form-label'],
                    'template' => "{label}\n<div class=\"col-md-12\">{input}</div>\n{hint}\n{error}"
                ])->textInput(['maxlength' => true, 'class' => 'form-control form-control-sm'])->label(Yii::t('app', 'Contract Number')) ?>

                <?= $form->field($model, 'dog_date', [
                    'options' => ['class' => 'form-group row'],
                    'labelOptions' => ['class' => 'col-md-12 col-form-label'],
                    'template' => "{label}\n<div class=\"col-md-12\">{input}</div>\n{hint}\n{error}"
                ])->textInput(['type' => 'date', 'class' => 'form-control form-control-sm'])->label(Yii::t('app', 'Contract Date')) ?>

                <?= $form->field($model, 'stroy_name', [
                    'options' => ['class' => 'form-group row'],
                    'labelOptions' => ['class' => 'col-md-12 col-form-label'],
                    'template' => "{label}\n<div class=\"col-md-12\">{input}</div>\n{hint}\n{error}"
                ])->textInput(['maxlength' => true, 'class' => 'form-control form-control-sm'])->label(Yii::t('app', 'Project Name')) ?>

                <?= $form->field($model, 'stroy_price', [
                    'options' => ['class' => 'form-group row'],
                    'labelOptions' => ['class' => 'col-md-12 col-form-label'],
                    'template' => "{label}\n<div class=\"col-md-12\">{input}</div>\n{hint}\n{error}"
                ])->textInput(['type' => 'number', 'class' => 'form-control form-control-sm'])->label(Yii::t('app', 'Project Price')) ?>

                <?= $form->field($model, 'current_year_price', [
                    'options' => ['class' => 'form-group row'],
                    'labelOptions' => ['class' => 'col-md-12 col-form-label'],
                    'template' => "{label}\n<div class=\"col-md-12\">{input}</div>\n{hint}\n{error}"
                ])->textInput(['type' => 'number', 'class' => 'form-control form-control-sm'])->label(Yii::t('app', 'Current Year Price')) ?>
            </div>

            <!-- Insurance Information -->
            <div class="col-md-6">
                <h2><?= Yii::t('app', 'Insurance Information') ?></h2>

                <?= $form->field($model, 'claim_id', [
                    'options' => ['class' => 'form-group row'],
                    'labelOptions' => ['class' => 'col-md-12 col-form-label'],
                    'template' => "{label}\n<div class=\"col-md-12\">{input}</div>\n{hint}\n{error}"
                ])->textInput(['maxlength' => true, 'class' => 'form-control form-control-sm'])->label(Yii::t('app', 'Claim ID')) ?>

                <?= $form->field($model, 'ins_sum_otv', [
                    'options' => ['class' => 'form-group row'],
                    'labelOptions' => ['class' => 'col-md-12 col-form-label'],
                    'template' => "{label}\n<div class=\"col-md-12\">{input}</div>\n{hint}\n{error}"
                ])->textInput(['type' => 'number', 'class' => 'form-control form-control-sm'])->label(Yii::t('app', 'Insurance Sum')) ?>

                <?= $form->field($model, 'current_year_sum_otv', [
                    'options' => ['class' => 'form-group row'],
                    'labelOptions' => ['class' => 'col-md-12 col-form-label'],
                    'template' => "{label}\n<div class=\"col-md-12\">{input}</div>\n{hint}\n{error}"
                ])->textInput(['type' => 'number', 'class' => 'form-control form-control-sm'])->label(Yii::t('app', 'Current Year Insurance Sum')) ?>

                <?= $form->field($model, 's_date', [
                    'options' => ['class' => 'form-group row'],
                    'labelOptions' => ['class' => 'col-md-12 col-form-label'],
                    'template' => "{label}\n<div class=\"col-md-12\">{input}</div>\n{hint}\n{error}"
                ])->textInput(['type' => 'date', 'class' => 'form-control form-control-sm'])->label(Yii::t('app', 'Start Date')) ?>

                <?= $form->field($model, 'e_date', [
                    'options' => ['class' => 'form-group row'],
                    'labelOptions' => ['class' => 'col-md-12 col-form-label'],
                    'template' => "{label}\n<div class=\"col-md-12\">{input}</div>\n{hint}\n{error}"
                ])->textInput(['type' => 'date', 'class' => 'form-control form-control-sm'])->label(Yii::t('app', 'End Date')) ?>
            </div>

            <!-- Agent Information -->
            <div class="col-md-6">
                <h2><?= Yii::t('app', 'Agent Information') ?></h2>

                <?= $form->field($model, 'agent_inn', [
                    'options' => ['class' => 'form-group row'],
                    'labelOptions' => ['class' => 'col-md-12 col-form-label'],
                    'template' => "{label}\n<div class=\"col-md-12\">{input}</div>\n{hint}\n{error}"
                ])->textInput(['maxlength' => true, 'class' => 'form-control form-control-sm'])->label(Yii::t('app', 'INN')) ?>

                <?= $form->field($model, 'agent_name', [
                    'options' => ['class' => 'form-group row'],
                    'labelOptions' => ['class' => 'col-md-12 col-form-label'],
                    'template' => "{label}\n<div class=\"col-md-12\">{input}</div>\n{hint}\n{error}"
                ])->textInput(['maxlength' => true, 'class' => 'form-control form-control-sm'])->label(Yii::t('app', 'Agent Name')) ?>
            </div>
        </div>

        <!-- Submit Button -->
        <div class="form-group">
            <div class="col-md-12">
                <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
            </div>
        </div>

        <?php ActiveForm::end(); ?>

    </div>
</div>
