<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use rmrevin\yii\fontawesome\FA;
use mihaildev\ckeditor\CKEditor;
use mihaildev\elfinder\ElFinder;
use mihaildev\elfinder\InputFile;

/* @var $this yii\web\View */
/* @var $model backend\models\post\PostCategories */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="post-categories-form box-body">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-lg-6 col-md-10">

            <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'image')->widget(InputFile::className(), [
                'controller' => 'elfinder',
                'path' => '/category',
                'filter' => 'image',
                'template' => '<div class="input-group t">{input}<span class="input-group-btn">{button}</span></div>',
                'options' => ['class' => 'form-control'],
                'buttonOptions' => ['class' => 'btn btn-warning'],
                'buttonName' => '<i class="fas fa-camera"></i>',
            ]) ?>

            <?= $form->field($model, 'description')->widget(CKEditor::className(), [
                'editorOptions' => ElFinder::ckeditorOptions(
                    ['elfinder', 'path' => '/'],
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

                <?php echo $form->field($model, 'meta_title')->textInput(['maxlength' => true]) ?>

                <?php echo $form->field($model, 'meta_keywords')->textInput(['maxlength' => true]) ?>

                <?php echo $form->field($model, 'meta_description')->textarea(['rows' => 3]) ?>

                <?php echo $form->field($model, 'status')->dropDownList($model->getStatusArray()) ?>

            <?php endif;?>


        </div>
    </div>

    <div class="row">
        <div class="col-md-2 col-sm-4  col-xs-6">
        </div>
    </div>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('views', 'Create') : Yii::t('views', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
