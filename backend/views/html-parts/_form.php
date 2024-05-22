<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use trntv\aceeditor\AceEditor;

/* @var $this yii\web\View */
/* @var $model backend\models\parts\HtmlParts */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="html-parts-form box-body">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-lg-6 col-md-10">
            <?= $form->field($model, 'key')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-10">
            <?= $form->field($model, 'body')->widget(AceEditor::className(), [
                'mode' => 'html',
                'theme' => 'iplastic',
                'containerOptions' => [
                    'style' => 'min-height: 800px; font-size: 14px;'
                ]
            ]) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-10">
            <?= $form->field($model, 'summary')->textarea() ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-2 col-sm-4  col-xs-6">
            <?= $form->field($model, 'status')->dropDownList($model->getStatusArray()) ?>
        </div>
    </div>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('views', 'Create') : Yii::t('views', 'Update'),
            ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
