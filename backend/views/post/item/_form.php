<?php

use backend\models\post\PostCategories;
use backend\models\post\Posts;
use kartik\file\FileInput;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\JsExpression;
use yii\widgets\ActiveForm;
use rmrevin\yii\fontawesome\FA;
use mihaildev\elfinder\ElFinder;
use mihaildev\ckeditor\CKEditor;
use mihaildev\elfinder\InputFile;

/* @var $this yii\web\View */
/* @var $model backend\models\post\Posts */
/* @var $modelCategory backend\models\post\PostCategories */
/* @var $form yii\widgets\ActiveForm */

$visible_field_in_types = Posts::_getVisibilityFields();

?>

<div class="posts-form box-body">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-lg-6 col-md-10">
            <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

            <?php if (in_array($model->category_key, $visible_field_in_types['work_position']) || Yii::$app->user->can('administrator')):?>
                <?php echo $form->field($model, 'work_position')->textInput(['maxlength' => true]) ?>
            <?php endif; ?>

        </div>
        <div class="col-lg-6 col-md-10">
            <?php if (in_array($model->category_key, $visible_field_in_types['image']) || Yii::$app->user->can('administrator')):?>

                <?= $form->field($model, 'image')->widget(InputFile::className(), [
                    'controller' => 'elfinder',
                    'path' => '/category/images',
                    'filter' => 'image',
                    'template' => '<div class="input-group">{input}<span class="input-group-btn">{button}</span></div>',
                    'options' => ['class' => 'form-control'],
                    'buttonOptions' => ['class' => 'btn btn-warning'],
                    'buttonName' => '<i class="fas fa-camera"></i>',
                ]) ?>
            <?php endif; ?>
        </div>
        <div class="col-lg-6 col-md-10">
            <?php if (in_array($model->category_key, $visible_field_in_types['file']) || Yii::$app->user->can('administrator')):?>

                <?= $form->field($model, 'file')->widget(InputFile::className(), [
                    'controller' => 'elfinder',
                    'path' => '/category/files',
                    'template' => '<div class="input-group">{input}<span class="input-group-btn">{button}</span></div>',
                    'options' => ['class' => 'form-control'],
                    'buttonOptions' => ['class' => 'btn btn-warning'],
                    'buttonName' => '<i class="fas fa-camera"></i>',
                ]) ?>
            <?php endif; ?>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-10">
            <?php if (in_array($model->category_key, $visible_field_in_types['summary']) || Yii::$app->user->can('administrator')):?>
                <?= $form->field($model, 'summary')->textarea(['rows' => 3]) ?>
            <?php endif; ?>

            <?php if (in_array($model->category_key, $visible_field_in_types['body']) || Yii::$app->user->can('administrator')):?>
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
            <?php endif; ?>

        </div>
    </div>
    <div class="row">
        <div class="col-lg-10">
            <?php if (in_array($model->category_key, $visible_field_in_types['source_link']) || Yii::$app->user->can('administrator')):?>
                <?= $form->field($model, 'source_link')->textInput() ?>
            <?php endif; ?>
        </div>
    </div>
    <?php if ((in_array($modelCategory->key, $visible_field_in_types['files']) && $modelCategory->key == PostCategories::KEY_GALLERY) || Yii::$app->user->can('administrator')):?>
        <div class="row">
            <div class="col-lg-8">
                <div class="form-group kartik-file-input">
                    <?php
                    $initialPreview = [];
                    $initialPreviewConfig = [];
                    if (!empty($images = $model->postFiles)) {
                        foreach ($images as $image) {
                            array_push($initialPreview, $image->path);
                            array_push($initialPreviewConfig, [
                                'caption' => $image->name,
                                'key' => $image->generate_name,
                            ]);
                        }
                    } ?>
                    <?= $form->field($model, 'uploaded_images')->hiddenInput(['id' => 'uploaded_images'])->label(false) ?>
                    <?= $form->field($model, 'deleted_images')->hiddenInput(['id' => 'deleted_images'])->label(false) ?>
                    <?= $form->field($model, 'sorted_images')->hiddenInput(['id' => 'sorted_images'])->label(false) ?>
                    <?php $this->registerJs("
                    var uploadedImages = {}, deletedImages = [],
                    uploaded = document.getElementById('uploaded_images'),
                    deleted = document.getElementById('deleted_images'),
                    sorted = document.getElementById('sorted_images');") ?>
                    <label class="control-label"><?= Yii::t('model', 'Images') ?></label>
                    <?= FileInput::widget([
                        'name' => '_image',
                        'options' => [
                            'accept' => 'image/*',
                            'multiple' => true
                        ],
                        'pluginOptions' => [
                            'uploadUrl' => Url::to(['upload-image']),
                            'deleteUrl' => Url::to(['delete-image']),
                            'maxFileCount' => 10,
                            'showRemove' => false,
                            'showUpload' => false,
                            'showCaption' => false,
                            'initialPreview' => $initialPreview,
                            'initialPreviewAsData' => true,
                            'initialPreviewConfig' => $initialPreviewConfig,
                            'overwriteInitial' => false,
                            'removeIcon' => '<i class="fa fa-trash"></i> ',
                            'browseIcon' => '<i class="glyphicon glyphicon-camera"></i>&nbsp;&nbsp;',
                            'browseLabel' => Yii::t('views', 'Browse …'),
                        ],
                        'pluginEvents' => [
                            'fileuploaded' => new JsExpression('function(event, data, previewId) {
                            uploadedImages[previewId] = data.response;
                            uploaded.value = JSON.stringify(uploadedImages);
                        }'),
                            'filedeleted' => new JsExpression('function(event, key) {
                            deletedImages.push(key);
                            deleted.value = JSON.stringify(deletedImages);
                        }'),
                            'filesuccessremove' => new JsExpression('function(event, previewId) {
                            delete uploadedImages[previewId];
                            uploaded.value = JSON.stringify(uploadedImages);
                        }'),
                            'filesorted' => new JsExpression('function(event, params) {
                            sorted.value = JSON.stringify(params.stack);
                        }')
                        ]
                    ]) ?>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <div class="row">
        <div class="col-lg-8 col-md-8 col-sm-12">
            <?php if (in_array($model->category_key, $visible_field_in_types['address']) || Yii::$app->user->can('administrator')):?>
                <?php echo $form->field($model, 'address')->textInput(['maxlength' => true, 'placeholder' => '']) ?>
            <?php endif; ?>
        </div>
        <div class="clearfix"></div>
        <div class="col-lg-4 col-md-6 col-sm-12">
            <?php if (in_array($model->category_key, $visible_field_in_types['published_date']) || Yii::$app->user->can('administrator')):?>
                <?php echo $form->field($model, 'published_date')->input('date') ?>
            <?php endif; ?>
        </div>
        <div class="col-lg-4 col-md-6 col-sm-12">
            <?php if (in_array($model->category_key, $visible_field_in_types['latitude']) || Yii::$app->user->can('administrator')):?>
                <?php echo $form->field($model, 'latitude')->textInput(['maxlength' => true, 'placeholder' => '41.021545']) ?>
            <?php endif; ?>
        </div>
        <div class="col-lg-4 col-md-6 col-sm-12">
            <?php if (in_array($model->category_key, $visible_field_in_types['longitude']) || Yii::$app->user->can('administrator')):?>
                <?php echo $form->field($model, 'longitude')->textInput(['maxlength' => true, 'placeholder' => '62.56564']) ?>
            <?php endif; ?>
        </div>
        <div class="clearfix"></div>
        <div class="col-lg-4 col-md-6 col-sm-12">
            <?php if (in_array($model->category_key, $visible_field_in_types['work_days']) || Yii::$app->user->can('administrator')):?>
                <?php echo $form->field($model, 'work_days')->textInput(['maxlength' => true, 'placeholder' => 'Четверг c 9.00 до 18 часов']) ?>
            <?php endif; ?>
        </div>
        <div class="col-lg-4 col-md-6 col-sm-12">
            <?php if (in_array($model->category_key, $visible_field_in_types['work_time']) || Yii::$app->user->can('administrator')):?>
                <?php echo $form->field($model, 'work_time')->textInput(['maxlength' => true, 'placeholder' => '9:00 - 18:00']) ?>
            <?php endif; ?>
        </div>
        <div class="clearfix"></div>
        <div class="col-lg-4 col-md-6 col-sm-12">
            <?php if (in_array($model->category_key, $visible_field_in_types['work_phone']) || Yii::$app->user->can('administrator')):?>
                <?php echo $form->field($model, 'work_phone')->textInput(['maxlength' => true]) ?>
            <?php endif; ?>
        </div>
        <div class="col-lg-4 col-md-6 col-sm-12">
            <?php if (in_array($model->category_key, $visible_field_in_types['work_email']) || Yii::$app->user->can('administrator')):?>
                <?php echo $form->field($model, 'work_email')->textInput(['maxlength' => true]) ?>
            <?php endif; ?>
        </div>
        <div class="clearfix"></div>
        <div class="col-lg-4 col-md-6 col-sm-12">
            <?php if (in_array($model->category_key, $visible_field_in_types['work_telegram']) || Yii::$app->user->can('administrator')):?>
                <?php echo $form->field($model, 'work_telegram')->textInput(['maxlength' => true, 'placeholder' => 'https://t.me/username']) ?>
            <?php endif; ?>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-6 col-md-10">
            <?php if ((in_array($model->category_key, $visible_field_in_types['meta_title']) && 0) || Yii::$app->user->can('administrator')):?>

                <?php echo $form->field($model, 'meta_title')->textInput(['maxlength' => true]) ?>

                <?php echo $form->field($model, 'meta_keywords')->textInput(['maxlength' => true]) ?>

                <?php echo $form->field($model, 'meta_description')->textarea(['rows' => 3]) ?>

            <?php endif; ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-2 col-sm-4  col-xs-6">
            <?php if (Yii::$app->user->can('administrator')):?>
                <?= $form->field($model, 'status')->dropDownList($model->getStatusArray()) ?>
            <?php endif; ?>
        </div>
        <div class="col-lg-4 col-md-6 col-sm-12">
            <?php if (Yii::$app->user->can('administrator')):?>
                <?php echo $form->field($model, 'weight')->textInput(['maxlength' => true, 'placeholder' => '0']) ?>
            <?php endif; ?>
        </div>
    </div>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('views', 'Create') : Yii::t('views', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
