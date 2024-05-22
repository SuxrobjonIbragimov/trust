<?php

/* @var $this yii\web\View */
/* @var $logo yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\ContactForm */
/* @var $modelPage \backend\models\page\Pages */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\captcha\Captcha;

$this->title = Yii::t('frontend','Связаться с нами');
$this->params['breadcrumbs'][] = $this->title;


$this->params['meta_type'] = 'page';
$this->params['meta_url'] = Yii::$app->request->hostInfo . '/page/' . $modelPage->url;
$this->params['meta_image'] = $logo;
if ($modelPage->meta_keywords)
    $this->params['meta_keywords'] = $modelPage->meta_keywords;
if ($modelPage->meta_description)
    $this->params['meta_description'] = $modelPage->meta_description;

?>
<div class="sp-title">
    <h1><?= Html::encode($this->title) ?></h1>
</div>
<div class="map">
    <iframe src="https://yandex.ru/map-widget/v1/?um=constructor%3Ad8d805c66967f9b1fc2c9304baf9de1341af7c134a88ef76272f697da2a8ee76&amp;source=constructor" width="1218" height="480" frameborder="0"></iframe>
</div>
<div class="contact-text">
    <?= $modelPage->body ?>
    <div class="add-comment">
        <?php $form = ActiveForm::begin(['id' => 'contact-form']); ?>

        <?= $form->field($model, 'name')->textInput(['autofocus' => true]) ?>

        <?= $form->field($model, 'email') ?>

        <?= $form->field($model, 'phone') ?>

        <?= $form->field($model, 'subject') ?>

        <?= $form->field($model, 'body')->textarea(['rows' => 6]) ?>

        <?= $form->field($model, 'verifyCode')->widget(Captcha::className(), [
            'template' => '<div class="row"><div class="col-lg-3">{image}</div><div class="col-lg-6">{input}</div></div>',
        ]) ?>

        <div class="form-group">
            <?= Html::submitButton('Отправить сообщение', ['class' => '', 'name' => 'contact-button']) ?>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>
