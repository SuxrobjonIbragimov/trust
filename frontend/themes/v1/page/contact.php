<?php

use yii\helpers\Html;
use yii\captcha\Captcha;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $modelForm \frontend\models\ContactForm */
/* @var $model \backend\models\page\Pages */
/* @var $logo string */

$this->title = $model->name;
$this->params['breadcrumbs'][] = $this->title;

$this->params['meta_type'] = 'page';
$this->params['meta_url'] = Yii::$app->request->hostInfo . '/page/' . $model->url;
$this->params['meta_image'] = $logo;
if ($model->meta_keywords)
    $this->params['meta_keywords'] = $model->meta_keywords;
if ($model->meta_description)
    $this->params['meta_description'] = $model->meta_description;

$this->params['body_class'] = 'static-page';
$this->params['container_class'] = 'about-page';

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

<div class="row">
    <div class="col-sm-12 col-md-6 mb-3">
        <div class="contact-text">
            <?= $model->body ?>
            <div class="add-comment">
                <?php $form = ActiveForm::begin(['id' => 'contact-form']); ?>

                <?= $form->field($modelForm, 'name') ?>

                <?= $form->field($modelForm, 'phone') ?>

                <?= $form->field($modelForm, 'email') ?>

                <?= $form->field($modelForm, 'subject') ?>

                <?= $form->field($modelForm, 'body')->textarea(['rows' => 6]) ?>

                <?= $form->field($modelForm, 'verifyCode')->widget(Captcha::className(), [
                    'template' => '<div class="row"><div class="col-md-6">{input}</div></div>{image}<button type="button" id="captcha_refresh" class="btn btn-default"><i class="glyphicon glyphicon-refresh"></i></button>',
                    'imageOptions' => ['id' => 'captcha_image']
                ]) ?>
                <?php $this->registerJs("jQuery('#captcha_refresh').on('click', function(e){
                    e.preventDefault(); jQuery('#captcha_image').yiiCaptcha('refresh'); })") ?>
                <div class="form-group">
                    <?= Html::submitButton(Yii::t('frontend', 'Отправить сообщение'), ['class' => 'btn btn-primary-buy', 'name' => 'contact-button']) ?>
                </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
    <div class="col-sm-12 col-md-6">
        <div class="map">
            <div style="position:relative;overflow:hidden;"><a
                        href="https://yandex.uz/maps/org/112720412206/?utm_medium=mapframe&utm_source=maps"
                        style="color:#eee;font-size:12px;position:absolute;top:0px;">Қишлоқ қурилиш банк</a><a
                        href="https://yandex.uz/maps/10335/tashkent/category/bank/184105398/?utm_medium=mapframe&utm_source=maps"
                        style="color:#eee;font-size:12px;position:absolute;top:14px;">Bank Toshkentda</a>
                <iframe src="https://yandex.uz/map-widget/v1/-/CCUZFMgx0C" width="560" height="400" frameborder="1"
                        allowfullscreen="true" style="position:relative;"></iframe>
            </div>
        </div>
    </div>
</div>
