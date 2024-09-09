<?php

use kartik\select2\Select2;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use mihaildev\ckeditor\CKEditor;
use mihaildev\elfinder\ElFinder;
use mihaildev\elfinder\InputFile;

/* @var $this yii\web\View */
/* @var $model backend\models\insurance\InsuranceProduct */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="insurance-product-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'subtitle')->textInput(['maxlength' => true]) ?>

    <div class="row">
        <div class="col-lg-6 col-md-10">
            <?= $form->field($model, 'legal_type_ids')->widget(Select2::className(), [
                'data' => $model->getLegalTypeList(),
                'theme' => Select2::THEME_DEFAULT,
                'options' => [
                    'placeholder' => '',
                    'multiple' => true
                ],
            ]) ?>
        </div>
    </div>

    <?= $form->field($model, 'summary')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'icon')->widget(InputFile::className(), [
        'controller' => 'elfinder',
        'path' => '/insurance-products',
        'filter' => 'image',
        'template' => '<div class="input-group">{input}<span class="input-group-btn">{button}</span></div>',
        'options' => ['class' => 'form-control'],
        'buttonOptions' => ['class' => 'btn btn-warning'],
        'buttonName' => '<i class="fas fa-camera"></i>',
    ]) ?>

    <?= $form->field($model, 'image')->widget(InputFile::className(), [
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

    <?php echo $form->field($model, 'is_main')->checkbox() ?>

    <?php if (Yii::$app->user->can('administrator')):?>

        <?php echo $form->field($model, 'meta_title')->textInput(['maxlength' => true]) ?>

        <?php echo $form->field($model, 'meta_keywords')->textInput(['maxlength' => true]) ?>

        <?php echo $form->field($model, 'meta_description')->textarea(['rows' => 3]) ?>

        <?php echo $form->field($model, 'weight')->textInput() ?>

        <?php echo $form->field($model, 'status')->checkbox() ?>

    <?php endif;?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('model', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
