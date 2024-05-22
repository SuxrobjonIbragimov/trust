<?php

use backend\models\review\Contact;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model \backend\models\page\Pages */
/* @var $modelForm \backend\models\review\Contact */
/* @var $form yii\widgets\ActiveForm */
/* @var $response array */
/* @var $statistics array */

$this->title = $model->meta_title;
$this->params['breadcrumbs'][] = $this->title;

$this->params['meta_type'] = 'page';
$this->params['meta_url'] = Yii::$app->request->hostInfo . '/page/check-status';
if ($model->meta_keywords)
    $this->params['meta_keywords'] = $model->meta_keywords;
if ($model->meta_description)
    $this->params['meta_description'] = $model->meta_description;

?>

<main class="middle pt-3">
    <div class="check-policy-page check__policy-page pt-3">
        <div class="container">
            <div class="row check__policy-column">
                <div class="mb-3 mx-auto w-100">
                    <div class="flex-center-block mb-3 mb-3 mx-auto">
                        <?= $this->render('_form', [
                                'model' => $modelForm,
                            ]) ?>

                        <?php if (!empty($response['status']) && !empty(!empty($response['response']))): ?>
                                <div class="clearfix"></div>
                                <div class="small-block mt-5">
                                    <table class="table table table-striped table-bordered detail-view">
                                        <?php if (!empty($response['response']['id'])): ?>
                                            <tr>
                                                <th class="text-left"><?= Yii::t('frontend', '№') ?></th>
                                                <td class="text-right"><?= $response['response']['id'] ?></td>
                                            </tr>
                                        <?php endif; ?>
                                        <?php if (!empty($response['response']['full_name'])): ?>
                                            <tr>
                                                <?php $label = $modelForm->getAttributeLabel('full_name');?>
                                                <th class="text-left"><?= $label ?></th>
                                                <td class="text-right"><?= $response['response']['full_name'] ?></td>
                                            </tr>
                                        <?php endif; ?>
                                        <?php if (!empty($response['response']['message'])): ?>
                                            <tr>
                                                <?php $label = $modelForm->getAttributeLabel('message');?>
                                                <th class="text-left"><?= $label ?></th>
                                                <td class="text-right"><?= $response['response']['message'] ?></td>
                                            </tr>
                                        <?php endif; ?>
                                        <?php if (!empty($response['response']['status'])): ?>
                                            <tr>
                                                <?php $label = $modelForm->getAttributeLabel('status');?>
                                                <th class="text-left"><?= $label ?></th>
                                                <td class="text-right"><?= $response['response']['status'] ?></td>
                                            </tr>
                                        <?php endif; ?>

                                        <?php if (!empty($response['response']['created_at'])): ?>
                                            <tr>
                                                <?php $label = $modelForm->getAttributeLabel('created_at');?>
                                                <th class="text-left"><?= $label ?></th>
                                                <td class="text-right"><?= $response['response']['created_at'] ?></td>
                                            </tr>
                                        <?php endif; ?>

                                        <?php if (!empty($response['response']['updated_at'])): ?>
                                            <tr>
                                                <?php $label = $modelForm->getAttributeLabel('updated_at');?>
                                                <th class="text-left"><?= $label ?></th>
                                                <td class="text-right"><?= $response['response']['updated_at'] ?></td>
                                            </tr>
                                        <?php endif; ?>

                                    </table>
                                </div>
                            <?php endif; ?>
                    </div>
                </div>

                <h3 class="title title--center news__title fs-2 mt-5 mb-1 text-center w-100"><?= Yii::t('frontend', 'Murojaatlar statistikasi') ?></h3>
                <div class="mb-3 mx-auto w-100">
                    <div class="flex-center-block mb-3 mb-3 mx-auto">
                        <?php if (!empty($statistics)): ?>
                            <div class="small-block mt-3">
                                <table class="table table table-striped table-bordered detail-view">
                                    <tr>
                                        <th class="text-left"><?= Yii::t('frontend', 'Jami') ?></th>
                                        <td class="text-right"><?= $statistics['all'] ?></td>
                                    </tr>
                                    <tr>
                                        <th class="text-left"><?= Yii::t('frontend', 'Yangilar') ?></th>
                                        <td class="text-right"><?= $statistics[Contact::STATUS_NEW] ?></td>
                                    </tr>
                                    <tr>
                                        <th class="text-left"><?= Yii::t('frontend', 'Ko`rib chiqilmoqda') ?></th>
                                        <td class="text-right"><?= $statistics[Contact::STATUS_IN_PROGRESS] ?></td>
                                    </tr>
                                    <tr>
                                        <th class="text-left"><?= Yii::t('frontend', 'Yakunlandi') ?></th>
                                        <td class="text-right"><?= $statistics[Contact::STATUS_DONE] ?></td>
                                    </tr>

                                </table>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
