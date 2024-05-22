<?php

use common\widgets\CustomAlert;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;
use yii\bootstrap\Alert;
use yii2mod\notify\BootstrapNotify;

/* @var $this yii\web\View */
/* @var $type yii\web\View */
/* @var $message yii\web\View */
/* @var $model \backend\models\review\Subscribe */
/* @var $form yii\widgets\ActiveForm */
?>

    <!--========== SECTION SUBSCRIBE ==========-->
<?php Pjax::begin(['id' => 'subscribe_pjax', 'enablePushState' => false]); ?>
    <div class="" id="mc_embed_signup">
        <?php $form = ActiveForm::begin([
            'action' => ['/site/subscribe'],

            'options' => [
                'data' => ['pjax' => true],

                'class' => [' '],
            ],
        ]); ?>
        <div class="input-group">
            <?= Html::activeInput('email', $model, 'email', [
                'class' => 'form-control',
                'placeholder' => Yii::t('frontend', 'Введите свою электронную почту'),
                'enableClientValidation' => false,
            ]) ?>
            <div class="input-group-btn">
                <?= Html::submitButton('<i class="bx bx-right-arrow-alt bx-sm"></i>', ['class' => 'btn btn-default btn-primary rounded-0 rounded-end d-flex align-items-center justify-content-center']) ?>
            </div>
            <div class="info"></div>
            <?php if ($message): ?>
                <?= CustomAlert::widget() ?>
            <?php endif; ?>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
<?php Pjax::end(); ?>