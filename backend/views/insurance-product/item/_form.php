<?php

use backend\models\insurance\InsuranceProductItem;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use mihaildev\ckeditor\CKEditor;
use mihaildev\elfinder\ElFinder;
use mihaildev\elfinder\InputFile;

/* @var $this yii\web\View */
/* @var $model backend\models\insurance\InsuranceProductItem */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="insurance-product-item-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'insurance_product_id')->hiddenInput() ?>

    <?= $form->field($model, 'type')->dropDownList(InsuranceProductItem::_getTypeList()) ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'icon')->widget(InputFile::className(), [
        'controller' => 'elfinder',
        'path' => '/insurance-products',
        'filter' => 'image',
        'template' => '<div class="input-group">{input}<span class="input-group-btn">{button}</span></div>',
        'options' => ['class' => 'form-control'],
        'buttonOptions' => ['class' => 'btn btn-warning'],
        'buttonName' => '<i class="fas fa-camera"></i>',
    ]) ?>

    <?= $form->field($model, 'description')->widget(CKEditor::className(), [
        'editorOptions' => ElFinder::ckeditorOptions(
            ['elfinder', 'path' => '/insurance-products'],
            [
                'allowedContent' => true,
                'height' => 500,
                'toolbarGroups' => [
                    'mode', 'undo', 'selection',
                    ['name' => 'clipboard', 'groups' => ['clipboard', 'doctools', 'cleanup']],
                    ['name' => 'basicstyles', 'groups' => ['basicstyles', 'colors']],
                    ['name' => 'paragraph', 'groups' => ['align', 'templates', 'list', 'indent']],
                    'styles', 'insert', 'blocks', 'links', 'find', 'tools', 'about',
                ]
            ]
        ),
    ]) ?>

    <?php if (Yii::$app->user->can('administrator')):?>

        <?php echo $form->field($model, 'weight')->textInput() ?>

        <?= $form->field($model, 'status')->checkbox() ?>

    <?php endif;?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('model', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
