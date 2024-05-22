<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */


$this->title = Yii::t('frontend', 'Вход');
$this->params['breadcrumbs'][] = $this->title;

$this->params['container_class'] = 'page-si';
?>
<div class="customer-login deals">
<!--    <h3 class="w3ls-title">--><?php //= Html::encode($this->title) ?><!--</h3>-->

    <p><?= Yii::t('frontend', 'Пожалуйста, заполните следующие поля для входа:') ?></p>

    <div class="row">
        <div class="col-lg-5">
            <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>

            <?= $form->field($model, 'username')->textInput(['autofocus' => true]) ?>
            <?= $form->field($model, 'password')->passwordInput() ?>
            <?= $form->field($model, 'rememberMe')->checkbox(['template' => '<label class="checkbox">{input}<i></i>{labelTitle}</label>']) ?>

            <p><?= Html::a(Yii::t('frontend', 'Забыли пароль?'), ['request-password-reset']) ?></p><br>

            <div class="form-group">
                <?= Html::submitButton(Yii::t('frontend', 'Войти'), ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
