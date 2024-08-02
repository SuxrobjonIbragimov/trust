<?php

use yii\helpers\Html;
use yii\captcha\Captcha;
use yii\bootstrap\ActiveForm;
use yii\web\View;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $modelForm \backend\models\review\Contact */
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

<?php
$path = 'styles/product.css';
$v = '?v=0.0.1';
$file_path = Yii::getAlias('@webroot/themes/v1/' . $path);
if (file_exists($file_path)) {
    $v = '?v=' . filemtime($file_path);
}
$path .= $v;
$this->registerCssFile("@web/themes/v1/{$path}", [
    'depends' => [\frontend\assets\V1Asset::className()],
], 'css-dd-theme-product');

?>
<div class="row">
    <div class="col-sm-12 col-md-12 mb-3">
        <div class="contact-text mt-2">
            <?= $model->body ?>
            <div class="add-comment mt-3">
                <?php $form = ActiveForm::begin(['id' => 'claim-form', 'options' => ['class' => 'row']]); ?>

                <div class="col-sm-12">
                    <?= $form->field($modelForm, 'full_name') ?>
                </div>

                <div class="col-sm-6">
                    <?= $form->field($modelForm, 'phone')->textInput([
                            'class' => 'form-control mask-phone',
                            'placeholder' => Yii::t('policy', '+998XX-XXX-XX-XX'),
                    ]) ?>
                </div>
                <div class="col-sm-6">
                    <?= $form->field($modelForm, 'email') ?>
                </div>
                <div class="col-sm-6">
                    <?= $form->field($modelForm, 'policy_series')->textInput() ?>
                </div>
                <div class="col-sm-6">
                    <?= $form->field($modelForm, 'policy_number') ?>
                </div>

                <div class="col-sm-12">
                    <?= $form->field($modelForm, 'message')->textarea(['rows' => 6]) ?>
                </div>
                <div class="col-sm-12 captcha-block">
                    <?= $form->field($modelForm, 'verifyCode', ['options' =>['class' => 'form-group flex-wrap']])->widget(Captcha::className(), [
                        'template' => '<div class="row"><div class="col-md-6">{input}</div></div>{image}<button type="button" id="captcha_refresh" class="btn btn-default btn-primary">  <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor" class="bi bi-arrow-clockwise" viewBox="0 0 16 16">
<path fill-rule="evenodd" d="M8 3a5 5 0 1 0 4.546 2.914.5.5 0 0 1 .908-.417A6 6 0 1 1 8 2v1z"></path>
<path d="M8 4.466V.534a.25.25 0 0 1 .41-.192l2.36 1.966c.12.1.12.284 0 .384L8.41 4.658A.25.25 0 0 1 8 4.466z"></path>
</svg></button>',
                        'imageOptions' => ['id' => 'captcha_image']
                    ]) ?>
                    <?php $this->registerJs("jQuery('#captcha_refresh').on('click', function(e){
                    e.preventDefault(); jQuery('#captcha_image').yiiCaptcha('refresh'); })") ?>
                </div>
                <div class="form-group">
                    <?= Html::submitButton(Yii::t('frontend', 'Отправить сообщение'), ['class' => 'btn btn-primary-buy btn-primary mt-2', 'name' => 'contact-button']) ?>
                </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>
<?php
$js = <<<JS
 $(document).ready(function() {
     $('.mask-phone').mask('+000 (00) 000-00-00');
 })
JS;
$this->registerJs($js, View::POS_END);
?>
