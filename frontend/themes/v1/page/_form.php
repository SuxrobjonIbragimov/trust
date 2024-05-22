<?php

use yii\captcha\Captcha;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model \backend\models\review\Contact */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="check-form small-block">

    <?php $form = ActiveForm::begin(); ?>

    <div class="d-flex flex-wrap align-items-start align-items-start mt-4 justify-content-center"><!--col-right-padding-0-->
        <div class="col-lg-3 col-md-12 col-12 field modal__field "><!--check__policy-pad-->
            <?php $label = $model->getAttributeLabel('id');?>
            <label for="contact-id" class="col-12 myLabel">
                <?= Yii::t('frontend','ID raqamingiz'); ?>
            </label>
            <div class="col-12">
                <?= Html::activeInput('text', $model, 'id', ['class' => 'form-control border-primary section-check__input w-100 my-3 br-4 px-4 fs-4']) ?>
            </div>
        </div>
        <div class="col-lg-7 col-md-12 col-12 captcha-block">
            <div class="d-flex flex-wrap mt-0"><!--row align-items-start-->
                <?= $form->field($model, 'verifyCode',['options' => ['class' => 'row align-items-center justify-content-start col-lg-9 col-md-9 mt-0 mx-0 px-0 py-0'], 'labelOptions' => ['class' => 'col-12 py-0 myLabel']])->widget(Captcha::className(), [
                    'template' => '
                                                <div class="col-lg-5 col-md-5 col-12">{input}</div>
                                                <div class="col-lg-7 col-md-7 col-12 d-flex align-items-center justify-content-around">
                                                    {image} <button type="button" id="captcha_refresh" class="btn cursor-pointer btn-primary btn-md">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor" class="bi bi-arrow-clockwise" viewBox="0 0 16 16">
                                                                    <path fill-rule="evenodd" d="M8 3a5 5 0 1 0 4.546 2.914.5.5 0 0 1 .908-.417A6 6 0 1 1 8 2v1z"/>
                                                                    <path d="M8 4.466V.534a.25.25 0 0 1 .41-.192l2.36 1.966c.12.1.12.284 0 .384L8.41 4.658A.25.25 0 0 1 8 4.466z"/>
                                                                </svg>
                                                            </button>
                                                </div> 
                                            
                                           ',
                    'imageOptions' => ['id' => 'captcha_image'],
                    'options' => ['class' => 'form-control border-primary section-check__input w-100 br-4 my-3 fs-4']/*p-3*/,

                ]) ?>
                <?php $this->registerJs("jQuery('#captcha_refresh').on('click', function(e){
                    e.preventDefault(); jQuery('#captcha_image').yiiCaptcha('refresh'); })") ?>
                <div class="form-group col-lg-3 col-md-3 col-12 text-center">
                    <label class="col-12 py-0 myLabel invisible" for="">Btn</label>
                    <?= Html::submitButton(Yii::t('policy','Проверит'), ['class' => 'cursor-pointer btn-lg btn btn-primary', 'style' =>'margin-top:17px;']) ?>
                    <?php ActiveForm::end(); ?>
                    <!--Проверка полиса -->
                </div>
            </div>

        </div>
    </div>
</div>


