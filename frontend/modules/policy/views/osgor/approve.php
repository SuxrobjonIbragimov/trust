<?php

use backend\modules\policy\models\PolicyOsgo;
use frontend\modules\policy\assets\OsgoAsset;
use yii\helpers\Html;
use yii\widgets\Breadcrumbs;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\modules\policy\models\PolicyOsgo */
/* @var $modelPaymentForm \frontend\models\PaymentForm */

OsgoAsset::register($this);
$this->title = Yii::t('policy', 'Проверьте данные');
//$this->params['breadcrumbs'][] = ['label' => Yii::t('policy', 'Calculate Policy'), 'url' => ['calculate']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);

?>

<?php if (!empty($modelPage->body)):?>
    <?php $this->params['footer_text'] = $modelPage->body; ?>
<?php endif;?>

<?php if (!empty($modelPage->image)):?>
    <?php $this->params['header_bg_image'] = $modelPage->image; ?>
<?php endif;?>

<main class="middle">
    <div class="policy-page">
        <div class="container px-0">

            <div class="flex-center-block mb-3">

                <div class="policy-driver-view calculator-card mt-5 overflow-hidden">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered detail-view">
                            <tr>
                                <th colspan="2" class="text-center fs-md"><?=Yii::t('policy','Ваши данные')?></th>
                            </tr>
                            <tr>
                                <th>
                                    <?=Yii::t('policy','Полное имя')?>
                                </th>
                                <td>
                                    <?= $model->owner_orgname; ?>
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    <?=Yii::t('policy','Owner Inn')?>
                                </th>
                                <td>
                                    <?= $model->owner_inn; ?>
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    <?=Yii::t('policy','Номер телефона')?>
                                </th>
                                <td>
                                    <?= mask_to_phone_format($model->app_phone); ?>
                                </td>
                            </tr>

                            <tr>
                                <th>
                                    <?=Yii::t('policy','Period')?>
                                </th>
                                <td>
                                    <?= date('d.m.Y', strtotime($model->start_date))?> - <?= date('d.m.Y', strtotime($model->end_date))?>
                                </td>
                            </tr>


                        </table>

                    </div>

                    <div class="row align-items-center mx-0 modal__container mt-5 w-100"><!--modal__mid-->
                        <div class="col-xl-5 col-lg-5 col-md-5 col-12 me-auto mt-md-0 mt-2 mb-md-0 mb-3  p-0 field  modal__field d-flex justify-content-center me-auto order-md-0 order-1"><!--modal__field-col-->
                            <?= Html::a(Yii::t('policy', 'Изменить данные'), ['/policy/osgor/form', 'h' => _model_encrypt($model)], ['class' => 'btn  btn-light border-light-subtle  w-100']) ?>
                        </div>

                        <div class="col-xl-5 col-lg-5 col-md-5 field col-12 ms-auto mt-md-0 mt-2 mb-md-0 mb-3 p-0 modal__field ms-auto order-md-1 order-0"><!--modal__field-col-->
                            <?= Html::a(Yii::t('policy', 'Перейти к оплате'), ['/policy/osgor/confirm', 'h' => _model_encrypt($model)], ['class' => 'btn btn-success  w-100']) ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
</main>
