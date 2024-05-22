<?php

/* @var $this yii\web\View */
/* @var $logo yii\web\View */
/* @var $message array */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\ContactForm */
/* @var $modelPage \backend\models\page\Pages */

use common\widgets\CustomAlert;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\captcha\Captcha;
use yii\widgets\Pjax;

$this->title = Yii::t('frontend','Связаться с нами');
$this->params['breadcrumbs'][] = $this->title;

?>
<?php if (empty($ajax)):?>
        <div class="homepage-contact__row">
<?php endif;?>

            <?php if (!empty($message)):?>
                <?php
                $type = $message['type']; $swall_message = $message['message'];

                ?>
            <?php endif;?>
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
                'class' => 'form-control border-primary w-100 my-0 br-4 px-3 latin_letters_no_number',
                'placeholder' => Yii::t('frontend', 'Ф.И.О.'),
            ]) ?>

        </div>

        <div class="pt-md-3 pt-0 col-md-12 col-12">

            <?= $form->field($model, 'phone', ['options' => ['class' => 'form-group']])->textInput([
                'type' => 'tel',
                'maxlength' => true,
                'class' => 'mask-phone field--mask form-control border-primary w-100 my-0 br-4 px-3',
                'placeholder' => Yii::t('frontend', '+998XX-XXX-XX-XX'),
            ]) ?>
        </div>

        <div class="pt-md-3 pt-0 col-md-12 col-12">

            <?= $form->field($model, 'message', ['options' => ['class' => 'form-group']])->textarea([
                'row' => 5,
                'maxlength' => true,
                'class' => 'form-control border-primary w-100 my-0 br-4 px-3',
                'placeholder' => Yii::t('frontend', 'Комментарий'),
            ]) ?>
        </div>

    </div>
    <div class="my-1 captcha-block">
        <div class="d-flex align-items-end w-100">
            <?php $label = $model->getAttributeLabel('verifyCode'); ?>
            <?= $form->field($model, 'verifyCode', ['options' => ['class' => 'row form-group align-items-end justify-content-start flex-wrap'], 'labelOptions' => ['class' => 'col-12 myLabel']])->widget(Captcha::className(), [
                'template' => '
                                                            <div class="col-lg-6 col-md-6 col-12 feed__input">{input}</div>
                                                            <iv class="col-lg-6 col-md-6 col-12 d-flex align-items-center">
                                                                {image} <button type="button" id="captcha_refresh" class="btn cursor-pointer ">
                                                                            <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor" class="bi bi-arrow-clockwise" viewBox="0 0 16 16">
                                                                                <path fill-rule="evenodd" d="M8 3a5 5 0 1 0 4.546 2.914.5.5 0 0 1 .908-.417A6 6 0 1 1 8 2v1z"/>
                                                                                <path d="M8 4.466V.534a.25.25 0 0 1 .41-.192l2.36 1.966c.12.1.12.284 0 .384L8.41 4.658A.25.25 0 0 1 8 4.466z"/>
                                                                            </svg>
                                                                        </button>
                                                            </iv> 
                                                        
                                                       ',
                'captchaAction' => '/site/captcha',
                'imageOptions' => ['id' => 'captcha_image'],
                'options' => ['class' => 'form-control border-primary p-3 br-4 my-2 mt-3 w-100', 'placeholder' => $label]
            ])->label(false) ?>
            <?php $this->registerJs("jQuery('#captcha_refresh').on('click', function(e){
                                e.preventDefault(); jQuery('#captcha_image').yiiCaptcha('refresh'); })") ?>
        </div>
    </div>
    <div class="d-flex justify-end align-center">
        <?= Html::submitButton(Yii::t('frontend','Отправить'), ['class' => 'btn btn-primary cursor-pointer mt-2', 'name' => 'contact-sb-button']) ?>
    </div>

            <?php ActiveForm::end(); ?>
            <?= CustomAlert::widget() ?>
<?php
$JS=<<<JS

    $(document).ready(function () {
        swal({
          title: "{$swall_message}",
          text: "",
          icon: "{$type}",
          timer: 70000,
        });
    })
JS;
$this->registerJs($JS);
?>
<?php if (!empty($ajax)):?>
        </div>
<?php endif;?>