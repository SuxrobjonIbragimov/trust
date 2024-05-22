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
    <div class="section-subscribe--content br-30 my-3">
        <h5 class="footer-info--title d-none"><?= Yii::t('subscribe', 'Подписаться на рассылку') ?></h5>
        <div class="input-group">
            <?php $form = ActiveForm::begin([
                'action' => ['/site/subscribe'],

                'options' => [
                    'data' => ['pjax' => true],

                    'class' => ['section-subscribe--form d-flex', 'result-' . $type],
                ],
            ]); ?>

            <?= Html::activeInput('email', $model, 'email', [
                'class' => 'form-control section-subscribe--input result-' . $type,
                'placeholder' => Yii::t('frontend', 'Ваша почта'),
                'enableClientValidation' => false,
            ]) ?>

            <?= Html::submitButton(Yii::t('frontend', 'Подписаться'), ['class' => 'btn btn-secondary section-subscribe--button']) ?>
        </div>
        <?php if ($message): ?>
            <?= CustomAlert::widget() ?>
        <?php endif; ?>

        <?php ActiveForm::end(); ?>
    </div>
<?php Pjax::end(); ?>