<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use mihaildev\elfinder\InputFile;

/* @var $this yii\web\View */
/* @var $model backend\models\sliders\SliderItems */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="slider-items-form box-body">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-lg-6 col-md-10">
            <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>


            <?= $form->field($model, 'image')->widget(InputFile::className(), [
                'controller' => 'elfinder',
                'path' => '/content/sliders',
                'filter' => 'image',
                'template' => '<div class="input-group">{input}<span class="input-group-btn">{button}</span></div>',
                'options' => ['class' => 'form-control'],
                'buttonOptions' => ['class' => 'btn btn-warning'],
                'buttonName' => '<i class="fas fa-camera"></i>',
            ]) ?>

            <?= $form->field($model, 'link')->textInput(['maxlength' => true]) ?>
            <?= $form->field($model, 'subtitle')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>

            <?php if (Yii::$app->user->can('administrator')):?>
                <?= $form->field($model, 'weight')->textInput(['type' => 'number']) ?>
            <?php endif;?>
        </div>
    </div>

    <?php if (Yii::$app->user->can('administrator')):?>

        <div class="row">
            <div class="col-md-2 col-sm-4  col-xs-6">
                <?= $form->field($model, 'status')->dropDownList($model->getStatusArray()) ?>
            </div>
        </div>
    <?php endif;?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('views', 'Create') : Yii::t('views', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
