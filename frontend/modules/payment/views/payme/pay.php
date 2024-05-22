<?php

use app\common\widgets\CustomAlert;
use app\widgets\ActionsWidget;
use yii\helpers\Html;
use yii\widgets\Breadcrumbs;

/* @var $this yii\web\View */
/* @var $h string */
/* @var $model frontend\modules\policy\models\PolicyTravel */
/* @var $paymentModel \app\common\library\paycom\Paycom\PaycomSubscribeForm */

$this->title = Yii::t('policy','Pay with payme');
$this->params['breadcrumbs'][] = ['label' => Yii::t('policy','Calculate Policy'), 'url' => ['policy/travel/calculate']];
$this->params['breadcrumbs'][] = $this->title;
?>

<main class="middle">
    <div class="policy-travel-create">
        <?php if (!is_mobile_app()) :?>
            
            <div class="container">
                <?= Breadcrumbs::widget([
                    'homeLink' => [
                        'label' => Yii::t('policy','Home page'),
                        'url' => Yii::$app->homeUrl,
                    ],
                    'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
                    'itemTemplate' => "<li  class='breadcrumbs__item'>{link}</li>\n",
                    'activeItemTemplate' => "<li  class='breadcrumbs__item breadcrumbs__item--active'>{link}</li>\n",
                ]) ?>
            </div>
        <?php endif;?>

        <div class="container">
            <div class="flex-center-block contact-us-page__container mb-3">
                
            </div>
        </div>
        <div class="container">

            <div class="flex-center-block contact-us-page__container mb-3">
                <?= $this->render('_form', [
                    'model' => $model,
                    'paymentModel' => $paymentModel,
                    'h' => $h,
                ]) ?>
            </div>
        </div>
</main>

<?php
$JS = <<<JS
JS;
$this->registerJs($JS);
?>