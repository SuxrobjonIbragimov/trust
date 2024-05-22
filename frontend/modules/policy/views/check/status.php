<?php

use common\library\payment\models\PaymentTransaction;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model \backend\modules\policy\models\PolicyOsgo */
/* @var $form yii\widgets\ActiveForm */
/* @var $response array */
/* @var $h array */

$this->title = Yii::t('policy','Policy info');
$this->params['breadcrumbs'][] = $this->title;

?>

<main class="middle">
    <div class="check-policy-page">
                <div class="container">

            <div class="flex-center-block mb-3">

                <?php if (!empty($model->policyOrder) && $model->policyOrder->payment_status == PaymentTransaction::STATUS_PAYMENT_PAID):?>
                    <div class="small-block">
                        <table class="table">
                            <?php if (!empty($model->policy_series)):?>
                                <tr>
                                    <th class="text-left"><?= Yii::t('policy','Policy number')?></th>
                                    <td><?= $model->policy_series ?> <?= $model->policy_number ?></td>
                                </tr>
                            <?php endif;?>
                            <?php if (!empty($model->app_phone)):?>
                                <tr>
                                    <th class="text-left"><?= Yii::t('policy','Phone')?></th>
                                    <td>+<?= $model->app_phone ?></td>
                                </tr>
                            <?php endif;?>
                            <?php if (!empty($h)):?>
                                <tr>
                                    <th class="text-left"><?= Yii::t('policy','Download policy')?></th>
                                    <?php if ($model->formName() == 'PolicyOsgo') :?>
                                        <td>
                                            <a href="http://polis.e-osgo.uz/site/export-to-pdf?id=<?= $model->uuid_fond?>" target="_blank">
                                                <?= Yii::t('policy','Download'); ?>
                                            </a>
                                        </td>
                                    <?php else:?>
                                        <td>
                                            <a href="<?= Url::to(['/policy/default/download','h' => $h], 'https')?>">
                                                <?= Yii::t('policy','Download'); ?>
                                            </a>
                                        </td>
                                    <?php endif;?>
                                </tr>
                            <?php endif;?>

                        </table>
                    </div>
                <?php else:?>
                    <div class="">
                        <div class="alert alert-danger">
                            <?=Yii::t('policy','Policy not payed yet')?>
                        </div>
                    </div>
                <?php
                    $_id = $h;
                    $url = '/policy/check/status';
                    $urlAjax = '/policy/check/check-payment';
                    $_url = Url::to([$url, 'h' => $h,]);
                    $_urlAjax = Url::to([$urlAjax, 'h' => $h,]);
                    $js=<<<JS

$(function () {
    setInterval(function(){
        var response = myCheckTransAjaxCall('$_urlAjax','$h');
        if (response.status) {
            window.location.href = "$_url";
        } else {
            console.log(response);
        }
    }, 5000);
});
JS;
                    $this->registerJs($js);

                    ?>
                <?php endif;?>
            </div>
        </div>
    </div>
</main>
