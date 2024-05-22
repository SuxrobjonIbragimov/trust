<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\modules\policy\models\search\PolicyTravelTravellerSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="policy-travel-traveller-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'policy_travel_id') ?>

    <?= $form->field($model, 'first_name') ?>

    <?= $form->field($model, 'surname') ?>

    <?= $form->field($model, 'birthday') ?>

    <?php // echo $form->field($model, 'pass_sery') ?>

    <?php // echo $form->field($model, 'pass_num') ?>

    <?php // echo $form->field($model, 'pinfl') ?>

    <?php // echo $form->field($model, 'phone') ?>

    <?php // echo $form->field($model, 'email') ?>

    <?php // echo $form->field($model, 'address') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('views', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('views', 'Reset'), ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
