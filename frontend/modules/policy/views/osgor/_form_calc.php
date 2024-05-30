<?php

use backend\modules\policy\models\PolicyOsgo;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $model backend\modules\policy\models\PolicyOsgo */
/* @var $form yii\widgets\ActiveForm */

$insurance_sum_coverage_health = PolicyOsgo::INSURANCE_SUM_COVERAGE_HEALTH;
$insurance_sum_coverage_property = PolicyOsgo::INSURANCE_SUM_COVERAGE_PROPERTY;

$model->_ins_amount = PolicyOsgo::INSURANCE_SUM;
?>


    <?php $form = ActiveForm::begin([
            'id' => 'policy-osgo-form',
            'options' => [
                    'data-pjax' => false
                ]
    ]); ?>

    <div class="flex-row">
        <div class="contact-us-page__left">

            <div class="branch__title"><?= Yii::t('policy', 'Нархни ҳисоблаш'); ?></div>

            <div class="contact-us-page__info mr-2">

                <div class="field modal__field">
                    <?php $label = $model->getAttributeLabel('vehicle_type_id');?>
                    <?= $form->field($model, 'vehicle_type_id')->radioList($model->getVehicleTypesList(), [
                        'class' => 'field__input form-control  get-calc-ajax',
                        'item' => function ($index, $label, $name, $checked, $value) {
                            $checked = $checked ? 'checked' : '';
                            return "<label class='checkbox ml-1'><input type='radio' {$checked} name='{$name}' value='{$value}'><i class='ml-05'></i>{$label}</label>";
                        }
                    ]) ?>
                </div>

                <div class="field modal__field">
                    <?php $label = $model->getAttributeLabel('region_id');?>
                    <?= $form->field($model, 'region_id')->radioList($model->_getUseTerritoryList(), [
                        'class' => 'field__input form-control  get-calc-ajax',
                        'item' => function ($index, $label, $name, $checked, $value) {
                            $checked = $checked ? 'checked' : '';
                            return "<label class='checkbox ml-1'><input type='radio' {$checked} name='{$name}' value='{$value}'><i class='ml-05'></i>{$label}</label>";
                        }
                    ]) ?>
                </div>

                <?php if (count($model->_getPeriodList())>1):?>
                    <div class="field modal__field">
                        <?php $label = $model->getAttributeLabel('period_id');?>
                        <?= $form->field($model, 'period_id')->radioList($model->_getPeriodList(), [
                            'class' => 'field__input form-control  get-calc-ajax',
                            'item' => function ($index, $label, $name, $checked, $value) {
                                $checked = $checked ? 'checked' : '';
                                return "<label class='checkbox ml-1'><input type='radio' {$checked} name='{$name}' value='{$value}'><i class='ml-05'></i>{$label}</label>";
                            }
                        ]) ?>
                    </div>
                <?php endif;?>

                <div class="field modal__field">
                    <?php $label = $model->getAttributeLabel('driver_limit_id');?>
                    <?= $form->field($model, 'driver_limit_id')->radioList($model->_getDriverLimitList(), [
                        'class' => 'field__input form-control  get-calc-ajax',
                        'item' => function ($index, $label, $name, $checked, $value) {
                            $checked = $checked ? 'checked' : '';
                            return "<label class='checkbox ml-1'><input type='radio' {$checked} name='{$name}' value='{$value}'><i class='ml-05'></i>{$label}</label>";
                        }
                    ]) ?>
                </div>

                <div class="row">
                    <div class="col-sm-2">
                        <?php $label = $model->getAttributeLabel('start_date');?>
                        <?= $form->field($model, 'start_date',['options' => ['class' => 'form-group']])
                            ->textInput(['type' => 'text',
                                'maxlength' => 10,
                                'autocomplete' => 'off',
                                'class' => 'field__input form-control mask-date',
                                'min' => date('Y-m-d'),
                                'placeholder' => Yii::t('policy','{label}ни киритинг',
                                    ['label' => $label])
                            ])->label() ?>
                    </div>
                    <div class="col-sm-2">
                        <?php $label = $model->getAttributeLabel('end_date');?>
                        <?= $form->field($model, 'end_date',['options' => ['class' => 'form-group']])
                            ->textInput(['type' => 'text',
                                'maxlength' => 10,
                                'autocomplete' => 'off',
                                'readonly' => true,
                                'disabled' => true,
                                'class' => 'field__input  form-control mask-date disabled readonly',
                                'placeholder' => Yii::t('policy','{label}ни киритинг',
                                    ['label' => $label])
                            ])->label() ?>
                    </div>
                </div>

            </div>


        </div>
        <div class="contact-us-page__right right-calc-block ml-2">
            <div class="overlay-right" style="display: none;">
                <div class="spinner"></div>
            </div>
            <div class="branch__title"><?= Yii::t('policy', 'Ҳисоблаш натижалари'); ?></div>

            <div class="contact-us-page__info">

                <?php Pjax::begin(['id' => 'pjax_policy_osgo_calc_result', 'enablePushState' => false, ]); ?>

                <?php if (!empty($model->_tmp_message)) :?>
                    <div class="alert-alert-warning">
                        <?=$model->_tmp_message?>
                    </div>
                <?php endif;?>

                <div class="field modal__field">
                    <div class="contact-us-page__label">
                        <?= Yii::t('policy', 'Полис нархи');?>
                    </div>
                    <div class="contact-us-page__text">
                        <span id="policy_price"><?= number_format($model->_policy_price_uzs, 2, '.', ' ')?></span>
                        <span class="currency"><?= Yii::t('policy', 'сўм');?></span>

                    </div>
                </div>
                <div class="field modal__field">
                    <div class="contact-us-page__label">
                        <?= Yii::t('policy', 'Суғурта суммаси');?>
                    </div>
                    <div class="contact-us-page__text">
                        <span id="policy_gift_price"><?= number_format($model->_ins_amount, 2, '.', ' ')?></span>
                        <span class="currency"><?= Yii::t('policy', 'сўм');?></span>
                    </div>
                </div>
                <div class="field modal__field">
                    <div class="contact-us-page__label">
                        <?= Yii::t('policy', 'Покрытие ущерба, причиненного жизни и здоровью потерпевшего');?>
                    </div>
                    <div class="contact-us-page__text">
                        <span id="policy_gift_price"><?= number_format($insurance_sum_coverage_health, 0, '.', ' ')?></span>
                        <span class="currency"><?= Yii::t('policy', '%');?></span>
                    </div>
                </div>
                <div class="field modal__field">
                    <div class="contact-us-page__label">
                        <?= Yii::t('policy', 'Покрытие ущерба, причиненного имуществу потерпевшего');?>
                    </div>
                    <div class="contact-us-page__text">
                        <span id="policy_gift_price"><?= number_format($insurance_sum_coverage_property, 0, '.', ' ')?></span>
                        <span class="currency"><?= Yii::t('policy', '%');?></span>
                    </div>
                </div>

                <?php Pjax::end(); ?>
            </div>
        </div>
    </div>


    <div class="flex-row">
        <div class="contact-us-page__left">

            <div class="field modal__field">
                <div class="page-footer-button mt-2">
                    <?php
                    $label = $model->getAttributeLabel('offer');
                    $lang = _lang();
                    $offer_link = Url::to(['/rule/osgo']);
                    $offer_label = Yii::t('policy', '«Пользовательского соглашения»');
                    $offer_label = '<a href="'.$offer_link.'" data-pjax="0" class="offer" type="button" data-toggle="" data-target="#offerModal" target="_blank"><i>'.$offer_label.'</i></a>';
                    $label_complate = Yii::t('policy', '{label} {link}',['label' => $label, 'link' => $offer_label]);
                    $template = '{input} {error}{hint}';
                    echo $form->field($model, 'offer', [
                        'template' => $template
                    ])->checkbox([
                        'label' => $label_complate,
                    ])->label($label_complate);
                    ?>

                </div>
            </div>

            <div class="field modal__field">
                <div class="page-footer-button">
                    <div class="form-group">
                        <?= Html::submitButton(Yii::t('policy', 'Apply'), ['class' => 's-custom-btn s-custom-primary btn--sm s-custom-btn--icon text-capitalize submitFormOsgo', 'disabled' => false]) ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="contact-us-page__right right-calc-block ml-2 fixed-sidebar">
        </div>
    </div>

    <input type="hidden" id="url_post_price_calc" value="<?= Url::to(['osgo/calculate-price'])?>">

    <?php ActiveForm::end(); ?>
<?php

$get_price_calc = Url::to(['osgo/calculate-price']);
$jsPjax =<<<JS

JS;

$this->registerJs($jsPjax);
?>

<?php
$JS =<<<JS
    $(function() {
        

    });
JS;
$this->registerJs($JS);
?>