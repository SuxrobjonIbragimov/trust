<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model \backend\modules\policy\models\CheckPolicy */
/* @var $form yii\widgets\ActiveForm */
/* @var $response array */

$this->title = Yii::t('policy', 'Проверка полиса');
$this->params['breadcrumbs'][] = $this->title;

?>

<main class="middle">
    <div class="check-policy-page">
        <div class="container">
            <div class="row">
                <div class="col-xl-8 col-lg-8 col-md-8 col-sm-12 col-12 mb-3 mx-auto">
                    <div class="flex-center-block mb-3 mb-3 mx-auto">
                        <?= $this->render('_form', [
                                'model' => $model,
                            ]) ?>

                        <?php if (!empty($response['INS_DATE'])):?>
                            <div class="small-block my-5">
                                <table class="table calculator-card mx-auto">
                                    <?php if (!empty($response['P_NUMBER'])):?>
                                        <tr>
                                            <th class="text-left pe-3"><?= Yii::t('policy','Policy number')?></th>
                                            <td><?= $response['P_SERY'] ?> <?= $response['P_NUMBER'] ?></td>
                                        </tr>
                                    <?php endif;?>
                                    <?php if (!empty($response['country'])):?>
                                        <tr>
                                            <th  class="text-left pe-3"><?= Yii::t('policy','Country')?></th>
                                            <td><?= $response['country'] ?></td>
                                        </tr>
                                    <?php endif;?>
                                    <?php if (!empty($response['BEGIN_DATE'])):?>
                                        <tr>
                                            <th  class="text-left pe-3"><?= Yii::t('policy','Begin date')?></th>
                                            <td><?= $response['BEGIN_DATE'] ?></td>
                                        </tr>
                                    <?php endif;?>
                                    <?php if (!empty($response['END_DATE'])):?>
                                        <tr>
                                            <th  class="text-left pe-3"><?= Yii::t('policy','End date')?></th>
                                            <td><?= $response['END_DATE'] ?></td>
                                        </tr>
                                    <?php endif;?>
                                    <?php if (!empty($response['tariff'])):?>
                                        <tr>
                                            <th  class="text-left pe-3"><?= Yii::t('policy','Tariff')?></th>
                                            <td><?= $response['tariff'] ?></td>
                                        </tr>
                                    <?php endif;?>
                                    <?php if (!empty($response['OPLATA'])):?>
                                        <tr>
                                            <th  class="text-left pe-3"><?= Yii::t('policy','Выплаченная сумма')?></th>
                                            <td><?= $response['OPLATA'] ?></td>
                                        </tr>
                                    <?php endif;?>

                                    <?php if (!empty($response['PREM'])):?>
                                        <tr>
                                            <th  class="text-left pe-3"><?= Yii::t('policy','Страховой премия')?></th>
                                            <td><?= $response['PREM'] ?></td>
                                        </tr>
                                    <?php endif;?>

                                    <?php if (!empty($response['INS_OTV'])):?>
                                        <tr>
                                            <th  class="text-left pe-3"><?= Yii::t('policy','Страховая сумма')?></th>
                                            <td><?= $response['INS_OTV'] ?></td>
                                        </tr>
                                    <?php endif;?>

                                    <?php if (!empty($response['insurer']) && !is_array($response['insurer'])):?>
                                        <tr>
                                            <th  class="text-left pe-3"><?= Yii::t('policy','Insurer')?></th>
                                            <td><?= $response['insurer'] ?></td>
                                        </tr>
                                    <?php endif;?>

                                    <?php if (!empty($response['owner']) && !is_array($response['owner'])):?>
                                        <tr>
                                            <th  class="text-left pe-3"><?= Yii::t('policy','Insurer')?></th>
                                            <td><?= $response['owner'] ?></td>
                                        </tr>
                                    <?php endif;?>

                                    <?php if (Yii::$app->user->can('accessPolicyFullInfo')):?>
                                        <!--POLICY FULL INFO-->
                                        <?php if (!empty($response['program'])):?>
                                            <tr>
                                                <th  class="text-left pe-3"><?= Yii::t('policy','Program')?></th>
                                                <td><?= $response['program'] ?></td>
                                            </tr>
                                        <?php endif;?>
                                        <?php if (!empty($response['address'])):?>
                                            <tr>
                                                <th  class="text-left pe-3"><?= Yii::t('policy','Address')?></th>
                                                <td><?= $response['address'] ?></td>
                                            </tr>
                                        <?php endif;?>
                                        <?php if (!empty($response['date_control'])):?>
                                            <tr>
                                                <th  class="text-left pe-3"><?= Yii::t('policy','Date control')?></th>
                                                <td><?= $response['date_control'] ?></td>
                                            </tr>
                                        <?php endif;?>
                                        <?php if (!empty($response['insured'])):?>
                                            <tr class="border-bottom-none">
                                                <td colspan="2">
                                                    <h3 class="text-center"><?= Yii::t('policy','Travellers')?></h3>
                                                    <table class="table">
                                                        <thead>
                                                        <tr>
                                                            <th><?= Yii::t('policy','№')?></th>
                                                            <th><?= Yii::t('policy','First name')?></th>
                                                            <th><?= Yii::t('policy','Surname')?></th>
                                                            <th><?= Yii::t('policy','Patronym')?></th>
                                                            <th><?= Yii::t('policy','Date_birth')?></th>
                                                            <th><?= Yii::t('policy','Passport')?></th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        <?php foreach ($response['insured'] as $key => $item):?>
                                                            <tr>
                                                                <td><?= ($key+1) ?></td>
                                                                <td><?= isset($item['first_name']) ? $item['first_name'] :'' ?></td>
                                                                <td><?= isset($item['surname']) ? $item['surname'] : '' ?></td>
                                                                <td><?= isset($item['patronym']) ? $item['patronym'] : '' ?></td>
                                                                <td><?= isset($item['date_birth']) ? $item['date_birth'] : '' ?></td>
                                                                <td><?= isset($item['passport']) ? $item['passport'] : '' ?></td>
                                                            </tr>
                                                        <?php endforeach;?>
                                                        </tbody>
                                                    </table>
                                                </td>
                                            </tr>
                                        <?php endif;?>

                                    <?php endif;?>
                                </table>
                            </div>
                        <?php endif;?>

                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
