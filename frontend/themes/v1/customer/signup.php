<?php

use yii\helpers\Html;
use yii\captcha\Captcha;
use yii\bootstrap\Modal;
use kartik\file\FileInput;
use yii\widgets\MaskedInput;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\SignupForm */
/* @var $offerText string */

$this->title = Yii::t('frontend', 'Зарегистрироваться');
$this->params['breadcrumbs'][] = $this->title;

$this->params['container_class'] = 'page-si page-su';
?>
<div class="customer-signup deals">

    <p><?= Yii::t('frontend', 'Пожалуйста, заполните следующие поля для регистрации:') ?></p>

    <?php $form = ActiveForm::begin(['id' => 'form-signup', 'options' => ['enctype' => 'multipart/form-data']]); ?>
    <div class="row">
        <div class="col-md-6 col-sm-8">
            <?= $form->field($model, 'email') ?>
            <?= $form->field($model, 'password')->passwordInput() ?>
            <?= $form->field($model, 'password_repeat')->passwordInput() ?>
            <?= $form->field($model, 'first_name') ?>
            <?= $form->field($model, 'last_name') ?>

            <?= $form->field($model, 'phone')->widget(MaskedInput::className(),
                ['mask' => '+\\9\\9899-999-9999']) ?>
        </div>
        <div class="col-md-6 col-sm-8">
            <?= $form->field($model, 'image')->widget(FileInput::className(), [
                'options' => ['accept' => 'image/*', 'id' => 'photo'],
                'pluginOptions' => [
                    'showCaption' => false,
                    'showRemove' => false,
                    'showUpload' => false,
                    'maxFileSize' => 500,
                    'browseIcon' => '<i class="glyphicon glyphicon-camera"></i> ',
                    'allowedFileExtensions' => ['jpg', 'png']
                ]
            ]) ?>
            <?php $this->registerCss(".file-preview { width: 0; display: table; }") ?>

            <?= $form->field($model, 'location_id')->dropDownList($model->getLocationsList(),
                ['prompt' => Yii::t('frontend', 'Выбрать ...')]) ?>
            <?= $form->field($model, 'address') ?>

            <?php
            echo $form->field($model, 'offer')->checkbox([
                'template' => '{beginLabel}{input} {labelTitle}{endLabel} <button type="button" data-toggle="modal" data-target="#offerModal" class="btn btn-link"><i>«Пользовательского соглашения»</i></button>{error}{hint}'
            ]);

            Modal::begin([
                'id' => 'offerModal',
                'size' => 'modal-lg',
                'header' => '<h4>' . Yii::t('frontend', 'Публичная оферта интернет-магазина') . '</h4>',
            ]);
            echo $offerText . '<div class="clearfix"></div>';
            Modal::end(); ?>

            <?= $form->field($model, 'verify_code')->widget(Captcha::className(), [
                'template' => '<div class="row"><div class="col-md-6">{input}</div></div>{image}<button type="button" id="captcha_refresh" class="btn btn-default"><i class="glyphicon glyphicon-refresh"></i></button>',
                'imageOptions' => ['id' => 'captcha_image']
            ]) ?>
            <?php $this->registerJs("jQuery('#captcha_refresh').on('click', function(e){
                    e.preventDefault(); jQuery('#captcha_image').yiiCaptcha('refresh'); })") ?>
        </div>
    </div>
    <div class="row">
        <div class="form-group col-md-12">
            <?= Html::submitButton(Yii::t('frontend', 'Зарегистрироваться'), ['class' => 'btn btn-primary pull-right', 'name' => 'signup-button']) ?>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
</div>
