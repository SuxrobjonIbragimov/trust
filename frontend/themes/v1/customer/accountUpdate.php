<?php

use yii\helpers\Html;
use kartik\file\FileInput;
use yii\widgets\MaskedInput;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\AccountUpdateForm */

$this->title = Yii::t('frontend', 'Изменить учетную запись');
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="customer-account-update deals">
    <h3 class="w3ls-title"><?= Html::encode($this->title) ?></h3>


    <?php $form = ActiveForm::begin(['id' => 'form-account-update', 'options' => ['enctype' => 'multipart/form-data']]); ?>
    <div class="row">
        <div class="col-md-7">
            <?= $form->field($model, 'first_name') ?>
            <?= $form->field($model, 'last_name') ?>
            <?= $form->field($model, 'phone')->widget(MaskedInput::className(),
                ['mask' => '+\\9\\9899-999-9999']) ?>
            <?= $form->field($model, 'location_id')->dropDownList($model->getLocationsList(),
                ['prompt' => Yii::t('frontend', 'Выбрать ...')]) ?>
            <?= $form->field($model, 'address') ?>

            <?= $form->field($model, 'image')->widget(FileInput::className(), [
                'options' => ['accept' => 'image/*', 'id' => 'photo'],
                'pluginOptions' => [
                    'showCaption' => false,
                    'showRemove' => false,
                    'showUpload' => false,
                    'maxFileSize' => 500,
                    'browseIcon' => '<i class="glyphicon glyphicon-camera"></i> ',
                    'allowedFileExtensions' => ['jpg', 'png'],
                    'initialPreview' => !empty($model->image) ? [$model->image] : [],
                    'initialPreviewAsData' => true,
                ]
            ])
            . $this->registerCss("
                .file-preview { width: 0; display: table; } 
                .file-preview .fileinput-remove { top: 3px; right: 3px; } 
                .file-drag-handle, .kv-file-remove { display: none; } ") ?>

            <?= $form->field($model, 'password')->passwordInput() ?>
            <?= $form->field($model, 'password_new')->passwordInput() ?>
            <?= $form->field($model, 'password_repeat')->passwordInput() ?>

            <?= Html::submitButton(Yii::t('frontend', 'Обновить'), ['class' => 'btn btn-primary pull-right', 'name' => 'update-button']) ?>

        </div>
    </div>
    <?php ActiveForm::end(); ?>
</div>

