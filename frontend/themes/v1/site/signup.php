<?php

/* @var $this yii\web\View */
/* @var $offerText \common\models\Settings */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\SignupForm */

use kartik\file\FileInput;
use yii\bootstrap\Modal;
use yii\captcha\Captcha;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\widgets\MaskedInput;

$this->title = Yii::t('frontend','Зарегистрироваться');
$this->params['breadcrumbs'][] = $this->title;
?>
<?php
$this->registerCss('
.required label:after,
label.required:after {
    content: "*";
    display: inline-block;
    padding-left: 4px;
    color: red;
    font-weight: 700;
}')
?>
<div class="site-signup page-si page-su">
    <div>
        <h3>
            <?= Html::encode($this->title) ?>
        </h3>
        <p>
            <?=Yii::t('frontend','Пожалуйста, заполните следующие поля, чтобы зарегистрироваться:')?>
        </p>
    </div>
    <?php $form = ActiveForm::begin([
        'id' => 'login-form',
        'options' => ['class' => 'form-container form-grid'],
    ]); ?>
        <div class="login">
            <div class="form-grid">
                <?= $form->field($model, 'first_name')->textInput(['autofocus' => true]) ?>

                <?= $form->field($model, 'last_name')->textInput(['maxLength' => true]) ?>
            </div>
            <?= $form->field($model, 'username')->textInput() ?>

            <?= $form->field($model, 'email') ?>

            <div class="form-grid">
                <?= $form->field($model, 'password')->passwordInput() ?>
                <?= $form->field($model, 'password_repeat')->passwordInput() ?>
            </div>
            <?= $form->field($model, 'address')->textInput() ?>

        </div>
        <div class="login-socials">
            <?= $form->field($model, 'phone')->widget(MaskedInput::className(),
                ['mask' => '+\\9\\9899-999-9999']) ?>

            <?php
            echo $form->field($model, 'offer')->checkbox([
                'template' => '{beginLabel}{input} {labelTitle}{endLabel} <a type="button" data-toggle="modal" data-target="#offerModal" class="btn btn-link"><i>«Пользовательского соглашения»</i></a>{error}{hint}'
            ]);

            Modal::begin([
                'id' => 'offerModal',
                'size' => 'modal-lg',
                'header' => '<h4>' . Yii::t('frontend', 'Публичная оферта') . '</h4>',
            ]);
            echo $offerText . '<div class="clearfix"></div>';
            Modal::end(); ?>

            <?= $form->field($model, 'verify_code')->widget(Captcha::className(), [
                'template' => '<div class="row"><div class="col-md-6">{input}</div></div>{image}<button type="button" id="captcha_refresh" class="btn btn-default"><i class="glyphicon glyphicon-refresh"></i></button>',
                'imageOptions' => ['id' => 'captcha_image']
            ]) ?>
            <?php $this->registerJs("jQuery('#captcha_refresh').on('click', function(e){
                e.preventDefault(); jQuery('#captcha_image').yiiCaptcha('refresh'); })") ?>

            <p class="compulsory"><?=Yii::t('frontend','Поля, отмеченные звездочкой (*), обязательны для заполнения.')?></p>
            <div class="form-group">
                <?= Html::submitButton(Yii::t('frontend','Зарегистрироваться'), ['class' => 'log', 'name' => 'signup-button']) ?>
            </div>
            <p><?=Yii::t('frontend','Продолжить c :')?></p>
            <div class="form-grid-social">
                <?= yii\authclient\widgets\AuthChoice::widget([
                    'baseAuthUrl' => ['site/auth'],
                    'popupMode' => true,
                ]) ?>
            </div>

        </div>
    <?php ActiveForm::end(); ?>
</div>
