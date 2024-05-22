<?php

use backend\models\review\Contact;
use common\widgets\CustomAlert;
use yii\captcha\Captcha;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;

/* @var $model \backend\models\review\Contact */

?>

<div class="homepage-contact__row">
    <?php Pjax::begin(['id' => 'feedback_pjax', 'enablePushState' => false,
        'options' => [
            'class' => 'v-form homepage-contact__form',
        ]]); ?>
    <?php $form = ActiveForm::begin([
        'action' => '/site/feedback',
        'id' => 'contact-form',
        'options' => [
            'data' => ['pjax' => true],
            'class' => 'v-form homepage-contact__form',
            'novalidate' => 'novalidate',
        ]]); ?>
    <div class="row">
        <div class="homepage-contact__col-flex pt-3 pb-0 col-md-12 col-12">
            <?= $form->field($model, 'full_name', ['options' => ['class' => 'form-group']])->textInput([
                'minlength' => 3,
                'autocomplete' => 'off',
                'class' => 'form-control w-100 my-0 br-4 px-3 latin_letters_no_number',
            ]) ?>

        </div>

        <div class="pt-md-3 pt-0 col-md-12 col-12">

            <?= $form->field($model, 'phone', ['options' => ['class' => 'form-group']])->textInput([
                'type' => 'tel',
                'maxlength' => true,
                'class' => 'mask-phone field--mask form-control w-100 my-0 br-4 px-3',
                'placeholder' => Yii::t('frontend', '+998XX-XXX-XX-XX'),
            ]) ?>
        </div>

        <div class="pt-md-3 pt-0 col-md-12 col-12">

            <?= $form->field($model, 'message', ['options' => ['class' => 'form-group']])->textarea([
                'row' => 5,
                'maxlength' => true,
                'class' => 'form-control w-100 my-0 br-4 px-3',
            ]) ?>
        </div>

    </div>
    <div class="my-1 captcha-block">
        <div class="d-flex align-items-end w-100">
            <?php $label = $model->getAttributeLabel('verifyCode'); ?>
            <?= $form->field($model, 'verifyCode', ['options' => ['class' => 'row form-group align-items-center justify-content-start flex-wrap test'], 'labelOptions' => ['class' => 'col-12 myLabel']])->widget(Captcha::className(), [
                'template' => '
                                                            <div class="col-lg-6 col-md-6 col-12 feed__input">{input}</div>
                                                            <iv class="col-lg-6 col-md-6 col-12 d-flex align-items-center">
                                                                {image} <button type="button" id="captcha_refresh" class="cursor-pointer btn btn-sm btn-primary rounded-2 text-uppercase fs-5">
                                                                            <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor" class="bi bi-arrow-clockwise" viewBox="0 0 16 16">
                                                                                <path fill-rule="evenodd" d="M8 3a5 5 0 1 0 4.546 2.914.5.5 0 0 1 .908-.417A6 6 0 1 1 8 2v1z"/>
                                                                                <path d="M8 4.466V.534a.25.25 0 0 1 .41-.192l2.36 1.966c.12.1.12.284 0 .384L8.41 4.658A.25.25 0 0 1 8 4.466z"/>
                                                                            </svg>
                                                                        </button>
                                                            </iv> 
                                                        
                                                       ',
                'captchaAction' => '/site/captcha',
                'imageOptions' => ['id' => 'captcha_image'],
                'options' => ['class' => 'form-control w-100 my-0 br-4 px-3', 'placeholder' => $label]
            ])->label(false) ?>
            <?php $this->registerJs("jQuery('#captcha_refresh').on('click', function(e){
                                e.preventDefault(); jQuery('#captcha_image').yiiCaptcha('refresh'); })") ?><!--form-control p-3 br-4 my-2 mt-3 w-100-->
        </div>
    </div>
    <div class="d-flex justify-end align-center">
        <?= Html::submitButton(Yii::t('frontend','Отправить'), ['class' => 'media-btn-class btn btn-primary rounded-2 text-uppercase fs-5 fw-bold px-4 py-2 mt-2', 'name' => 'contact-sb-button']) ?>
    </div>

    <?php ActiveForm::end(); ?>
    <?= CustomAlert::widget() ?>
    <?php Pjax::end(); ?>
</div>