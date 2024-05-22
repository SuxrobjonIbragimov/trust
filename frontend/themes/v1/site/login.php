<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = Yii::t('frontend','Вход');
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
<div class="page-si">
    <div>
        <h3>
            <?= Html::encode($this->title) ?>
        </h3>
        <p>
            <?=Yii::t('frontend','Пожалуйста, заполните следующие поля для входа:')?>
        </p>
    </div>
    <?php $form = ActiveForm::begin([
        'id' => 'login-form',
        'options' => ['class' => 'form-container'],
    ]); ?>
        <div class="login">
            <?= $form->field($model, 'username')->textInput(['autofocus' => true]) ?>

            <?= $form->field($model, 'password')->passwordInput() ?>

            <span class="remember">
                <?= $form->field($model, 'rememberMe')->checkbox() ?>
                <div style="">
                    <span>
                    <?=Yii::t('frontend','Забыли пароль?')?> <?= Html::a(Yii::t('frontend',' Восстановить'), ['site/request-password-reset']) ?>.
                    </span>
                </div>
            </span>

        </div>
        <div class="login-socials">
            <div class="form-group">
                <?= Html::submitButton('Войти', ['class' => 'log', 'name' => 'login-button']) ?>
            </div>
            <p><?=Yii::t('frontend','Продолжить c :')?></p>
            <?= yii\authclient\widgets\AuthChoice::widget([
                'baseAuthUrl' => ['site/auth'],
                'popupMode' => true,
            ]) ?>
        </div>
    <?php ActiveForm::end(); ?>
</div>
