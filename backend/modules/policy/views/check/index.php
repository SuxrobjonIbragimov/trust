<?php

use app\common\widgets\CustomAlert;
use app\widgets\ActionsWidget;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\Breadcrumbs;

/* @var $this yii\web\View */
/* @var $model \backend\modules\policy\models\CheckPolicy */
/* @var $form yii\widgets\ActiveForm */
/* @var $response array */

$this->title = Yii::t('policy','Check policy');
$this->params['breadcrumbs'][] = $this->title;

?>

<main class="middle">
    <div class="check-policy-page">
        
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
        <div class="container">
            <h1 class="title title--center news__title"><?= $this->title ?></h1>

            <div class="flex-center-block mb-3">

                
                <?= $this->render('_form', [
                    'model' => $model,
                ]) ?>

                <?php if (!empty($response['status']) && !empty(!empty($response['response']))):?>
                <div class="small-block">
                    <table class="table">
                        <?php if (!empty($response['response']['policy_number'])):?>
                            <tr>
                                <th class="text-left"><?= Yii::t('policy','Policy number')?></th>
                                <td><?= $response['response']['policy_number'] ?></td>
                            </tr>
                        <?php endif;?>
                        <?php if (!empty($response['response']['country'])):?>
                            <tr>
                                <th  class="text-left"><?= Yii::t('policy','Country')?></th>
                                <td><?= $response['response']['country'] ?></td>
                            </tr>
                        <?php endif;?>
                        <?php if (!empty($response['response']['begin_date'])):?>
                            <tr>
                                <th  class="text-left"><?= Yii::t('policy','Begin date')?></th>
                                <td><?= $response['response']['begin_date'] ?></td>
                            </tr>
                        <?php endif;?>
                        <?php if (!empty($response['response']['end_date'])):?>
                            <tr>
                                <th  class="text-left"><?= Yii::t('policy','End date')?></th>
                                <td><?= $response['response']['end_date'] ?></td>
                            </tr>
                        <?php endif;?>
                        <?php if (!empty($response['response']['tariff'])):?>
                            <tr>
                                <th  class="text-left"><?= Yii::t('policy','Tariff')?></th>
                                <td><?= $response['response']['tariff'] ?></td>
                            </tr>
                        <?php endif;?>
                        <?php if (!empty($response['response']['payment'])):?>
                            <tr>
                                <th  class="text-left"><?= Yii::t('policy','Payment')?></th>
                                <td><?= number_format($response['response']['payment'], '2', '.', ' ') ?></td>
                            </tr>
                        <?php endif;?>

                        <?php if (!empty($response['response']['price'])):?>
                            <tr>
                                <th  class="text-left"><?= Yii::t('policy','Страховой стоимость')?></th>
                                <td><?= number_format($response['response']['price'], '2', '.', ' ') ?></td>
                            </tr>
                        <?php endif;?>

                        <?php if (!empty($response['response']['insurer']) && !is_array($response['response']['insurer'])):?>
                            <tr>
                                <th  class="text-left"><?= Yii::t('policy','Insurer')?></th>
                                <td><?= $response['response']['insurer'] ?></td>
                            </tr>
                        <?php endif;?>

                        <?php if (!empty($response['response']['owner']) && !is_array($response['response']['owner'])):?>
                            <tr>
                                <th  class="text-left"><?= Yii::t('policy','Insurer')?></th>
                                <td><?= $response['response']['owner'] ?></td>
                            </tr>
                        <?php endif;?>

                        <?php if (Yii::$app->user->can('accessPolicyFullInfo')):?>
                            <!--POLICY FULL INFO-->
                                <?php if (!empty($response['response']['program'])):?>
                                    <tr>
                                        <th  class="text-left"><?= Yii::t('policy','Program')?></th>
                                        <td><?= $response['response']['program'] ?></td>
                                    </tr>
                                <?php endif;?>
                                <?php if (!empty($response['response']['address'])):?>
                                    <tr>
                                        <th  class="text-left"><?= Yii::t('policy','Address')?></th>
                                        <td><?= $response['response']['address'] ?></td>
                                    </tr>
                                <?php endif;?>
                                <?php if (!empty($response['response']['date_control'])):?>
                                    <tr>
                                        <th  class="text-left"><?= Yii::t('policy','Date control')?></th>
                                        <td><?= $response['response']['date_control'] ?></td>
                                    </tr>
                                <?php endif;?>
                                <?php if (!empty($response['response']['insured'])):?>
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
                                                <?php foreach ($response['response']['insured'] as $key => $item):?>
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
                <?/*= d($response)*/?>
                <?php endif;?>
            </div>
        </div>
    </div>
</main>
