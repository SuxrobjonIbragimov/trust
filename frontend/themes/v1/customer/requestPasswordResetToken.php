<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\PasswordResetRequestForm */

$this->title = Yii::t('frontend', 'Запросить сброс пароля');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="customer-request-password-reset deals">
    <h3 class="w3ls-title"><?= Html::encode($this->title) ?></h3>

    <p><?= Yii::t('frontend', 'Пожалуйста, заполните свой адрес электронной почты. Здесь будет отправлена ​​ссылка на сброс пароля.') ?></p>

    <div class="row">
        <div class="col-lg-5">
            <?php $form = ActiveForm::begin(['id' => 'request-password-reset-form']); ?>

            <?= $form->field($model, 'email')->textInput(['autofocus' => true]) ?>

            <div class="form-group">
                <?= Html::submitButton(Yii::t('frontend', 'Отправить'), ['class' => 'btn btn-primary']) ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
