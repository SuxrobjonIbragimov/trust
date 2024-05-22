<?php

use mihaildev\elfinder\InputFile;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use mihaildev\ckeditor\CKEditor;
use mihaildev\elfinder\ElFinder;

/* @var $this yii\web\View */
/* @var $model backend\models\page\Pages */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="pages-form box-body">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-lg-6 col-md-10">
            <?= $form->field($model, 'url')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'image')->widget(InputFile::className(), [
                'language'      => _lang(),
                'controller'    => 'elfinder', // вставляем название контроллера, по умолчанию равен elfinder
                'path'          => '/content/pages',
                'filter'        => 'image',    // фильтр файлов, можно задать массив фильтров https://github.com/Studio-42/elFinder/wiki/Client-configuration-options#wiki-onlyMimes
                'template'      => '<div class="input-group">{input}<span class="input-group-btn">{button}</span></div>',
                'options'       => ['class' => 'form-control'],
                'buttonOptions' => ['class' => 'btn btn-default'],
                'buttonName'    => '<i class="fa fa-camera"></i>',
                'multiple'      => false       // возможность выбора нескольких файлов
            ]);?>

            <?php if (Yii::$app->user->can('administrator')):?>

                <?php echo $form->field($model, 'meta_title')->textInput(['maxlength' => true]) ?>

                <?php echo $form->field($model, 'meta_keywords')->textInput(['maxlength' => true]) ?>

                <?php echo $form->field($model, 'meta_description')->textarea(['rows' => 3]) ?>

            <?php endif;?>

        </div>
    </div>

    <div class="row">
        <div class="col-lg-10">
            <?= $form->field($model, 'body')->widget(CKEditor::className(), [
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
        </div>
    </div>

    <?php if (Yii::$app->user->can('administrator')):?>

        <div class="row">
            <div class="col-md-2 col-sm-4  col-xs-6">
                <?= $form->field($model, 'status')->dropDownList($model->getStatusArray()) ?>
            </div>
        </div>
    <?php endif;?>


    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('views', 'Create') : Yii::t('views', 'Update'),
            ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
