<?php

use backend\modules\handbook\models\HandbookFondRegion;
use backend\modules\handbook\models\HandbookOked;
use backend\modules\policy\models\PolicyOsgo;
use kartik\depdrop\DepDrop;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $program array */
/* @var $model backend\modules\policy\models\PolicyOsgo */
/* @var $form yii\widgets\ActiveForm */

$insurance_sum_coverage_health = PolicyOsgo::INSURANCE_SUM_COVERAGE_HEALTH;
$insurance_sum_coverage_property = PolicyOsgo::INSURANCE_SUM_COVERAGE_PROPERTY;

$model->_ins_amount = PolicyOsgo::INSURANCE_SUM;
?>

<?php $use_territory_model = HandbookFondRegion::_getByTerritory($model->region_id) ?>
<?php $use_territory_name = !empty($use_territory_model->shortName) ? $use_territory_model->shortName : null ?>

<?php //Pjax::begin(['id' => 'pjax_policy_travel_calc']); ?>
<?php $form = ActiveForm::begin([
    'id' => 'policy-osgo-form',
    'options' => [
        'data-pjax' => false,
        'class' => 'v-form',
    ]
]); ?>
    <div class="row <?= is_mobile_app() ? 'mb-0' : 'mb-3' ?>">
        <div class="col-lg-8 col-12">

            <div class="calculator-group">
                <!--                <h3 class="main-title-sm text-uppercase">-->
                <?php //= Yii::t('policy', 'Fill in company information'); ?><!--</h3>-->

                <div class="row">
                    <div class="col-12">
                        <div class="row">
                            <div class="col-md-3 col-sm-3 col-12"> <!--col-md-3 col-sm-3 col-12-->
                                <div class="row align-items-start justify-content-between">
                                    <div class="col-12">
                                        <?php
                                        // necessary for update action.
                                        if (!$model->isNewRecord) {
                                            echo Html::activeHiddenInput($model, "id");
                                        }
                                        ?>
                                        <?php $label = $model->getAttributeLabel('owner_inn'); ?>
                                        <?= $form->field($model, 'owner_inn', ['options' => ['class' => 'form-group']])
                                            ->textInput(['type' => 'text',
                                                'maxlength' => 11,
                                                'readonly' => !empty($model->owner_inn) ? $model->owner_inn : false,
                                                'autocomplete' => 'off',
                                                'autofocus' => empty($model->owner_inn),
                                                'class' => 'field__input form-control on-change-inn form__anketa mask-tin',
                                                'oninput' => 'this.value = this.value.toUpperCase()',
                                                'placeholder' => Yii::t('policy', '000 000 001'),
                                            ])->label($label, ['class' => 'control-label main-form-label d-block my-2']) ?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-9 col-sm-9 col-12">
                                <div class="row align-items-start justify-content-between">
                                    <div class="col-xl-10 col-md-10 col-9">
                                        <?php $label = Yii::t('policy', 'Company name'); ?>
                                        <?= $form->field($model, 'owner_orgname', ['options' => ['class' => 'form-group']])->textInput([
                                            'maxlength' => true,
                                            'readonly' => true,
                                            'class' => 'field__input form-control latin_letters_and_number',
                                            'oninput' => 'this.value = this.value.toUpperCase()',
                                            'placeholder' => Yii::t('policy', '{label}ни киритинг',
                                                ['label' => $label]),
                                        ])->label($label, ['class' => 'control-label main-form-label d-block my-2']) ?>
                                    </div>
                                    <div class="col-xl-2 col-md-2 col-3 ms-auto me-0">
                                        <label class="control-label  main-form-label invisible d-block  my-2">label</label>
                                        <button type="button" id="check-vehicle"
                                                class="ms-auto mt-0 me-0 check-button btn anketa__check s-custom-btn s-custom-primary btn--md s-custom-btn--icon  btn  d-flex align-items-center justify-content-center ms-auto fill-white <?= ($model->isNewRecord) ? 'check btn-primary padding-for-button' : 'clear btn-danger padding-for-button' ?>">
                                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none"
                                                 xmlns="http://www.w3.org/2000/svg" class="check-icon">
                                                <path fill-rule="evenodd" clip-rule="evenodd"
                                                      d="M3.33366 9.16675C3.33366 5.94509 5.94533 3.33341 9.16699 3.33341C12.3887 3.33341 15.0003 5.94509 15.0003 9.16675C15.0003 10.7384 14.3788 12.1648 13.3682 13.2137C13.3396 13.2357 13.3122 13.2597 13.2861 13.2858C13.2599 13.312 13.2359 13.3394 13.214 13.3679C12.1651 14.3786 10.7386 15.0001 9.16699 15.0001C5.94533 15.0001 3.33366 12.3884 3.33366 9.16675ZM13.8484 15.0267C12.5653 16.053 10.9378 16.6667 9.16699 16.6667C5.02486 16.6667 1.66699 13.3089 1.66699 9.16675C1.66699 5.02461 5.02486 1.66675 9.16699 1.66675C13.3091 1.66675 16.667 5.02461 16.667 9.16675C16.667 10.9376 16.0533 12.5651 15.0269 13.8482L18.0896 16.9108C18.415 17.2363 18.415 17.7639 18.0896 18.0893C17.7641 18.4148 17.2365 18.4148 16.9111 18.0893L13.8484 15.0267Z"
                                                      fill="#fff"/>
                                            </svg>
                                            <svg width="20" height="20" version="1.1" class="clear-icon "
                                                 xmlns="http://www.w3.org/2000/svg"
                                                 xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                                                 viewBox="0 0 460.775 460.775"
                                                 style="enable-background:new 0 0 460.775 460.775;"
                                                 xml:space="preserve">
                                                <path d="M285.08,230.397L456.218,59.27c6.076-6.077,6.076-15.911,0-21.986L423.511,4.565c-2.913-2.911-6.866-4.55-10.992-4.55
                                                c-4.127,0-8.08,1.639-10.993,4.55l-171.138,171.14L59.25,4.565c-2.913-2.911-6.866-4.55-10.993-4.55
                                                c-4.126,0-8.08,1.639-10.992,4.55L4.558,37.284c-6.077,6.075-6.077,15.909,0,21.986l171.138,171.128L4.575,401.505
                                                c-6.074,6.077-6.074,15.911,0,21.986l32.709,32.719c2.911,2.911,6.865,4.55,10.992,4.55c4.127,0,8.08-1.639,10.994-4.55
                                                l171.117-171.12l171.118,171.12c2.913,2.911,6.866,4.55,10.993,4.55c4.128,0,8.081-1.639,10.992-4.55l32.709-32.719
                                                c6.074-6.075,6.074-15.909,0-21.986L285.08,230.397z" fill="#fff"/>
                                                <g></g>
                                                <g></g>
                                                <g></g>
                                                <g></g>
                                                <g></g>
                                                <g></g>
                                                <g></g>
                                                <g></g>
                                                <g></g>
                                                <g></g>
                                                <g></g>
                                                <g></g>
                                                <g></g>
                                                <g></g>
                                                <g></g>
                                            </svg>
                                        </button>

                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="vehicle-info" class="row mt-2">
                            <div class="col-12">
                                <div class="row">
                                    <div class="col-md-6 col-12 ">
                                        <?php $label = $model->getAttributeLabel('owner_oked'); ?>
                                        <?= $form->field($model, "owner_oked", ['options' => ['class' => 'form-group']])->dropDownList(HandbookOked::_getItemsListByInsParam(), [
                                            'multiple' => false,
                                            'readonly' => false,
                                            'maximumSelectionLength' => 1,
                                            'class' => 'field__input form-control on-change-oked',
                                            'prompt' => Yii::t('policy', 'Выберите'),
                                        ])->label($label, ['class' => 'control-label main-form-label my-2']) ?>
                                    </div>
                                    <div class="col-md-6 col-12 field modal__field modal__field-col">
                                        <?php $label = $model->getAttributeLabel('org_okonx'); ?>
                                        <?= $form->field($model, "org_okonx", ['options' => ['class' => 'form-group']])->dropDownList($model->okonx_list, [
                                            'multiple' => false,
                                            'readonly' => false,
                                            'maximumSelectionLength' => 1,
                                            'class' => 'field__input form-control get-calc-ajax',
                                            'prompt' => Yii::t('policy', 'Выберите'),
                                        ])->label($label, ['class' => 'control-label main-form-label my-2']) ?>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 owner-region-info">
                                <div class="row">
                                    <div class="col-md-6 col-12 ">
                                        <?php $label = Yii::t('policy', 'Вилоят'); ?>
                                        <?= $form->field($model, "owner_region", ['options' => ['class' => 'form-group']])->dropDownList(HandbookFondRegion::_getItemsListByInsParam(), [
                                            'multiple' => false,
                                            'maximumSelectionLength' => 1,
                                            'class' => 'field__input form-control',
                                            'prompt' => Yii::t('policy', 'Выберите'),
                                        ])->label($label, ['class' => 'control-label main-form-label my-2']) ?>
                                    </div>
                                    <div class="col-md-6 col-12 field">
                                        <?php $label = Yii::t('policy', 'Туман/шаҳар'); ?>
                                        <?= $form->field($model, 'owner_district')->widget(DepDrop::className(), [
                                            'data' => HandbookFondRegion::_getItemsListByInsParam('all'),
                                            'type' => DepDrop::TYPE_DEFAULT,
                                            'options' => [
                                                'multiple' => false,
                                                'id' => 'owner_district',
                                                'class' => 'field__input form-control',
                                            ],
                                            'select2Options' => ['hideSearch' => true],
                                            'pluginOptions' => [
                                                'depends' => ['policyosgo-owner_region'],
                                                'url' => Url::to(['osgo/get-handbook-district']),
                                                'placeholder' => Yii::t('policy', 'Выберите'),
                                            ]
                                        ])->label($label, ['class' => 'control-label main-form-label my-2']) ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div id="owner-info" class="col-12">
                                <div class="row">
                                    <div class="col-md-12 col-12">
                                        <div class="row">
                                            <div class="col-md-12 col-12">
                                                <?php $label = $model->getAttributeLabel('owner_address'); ?>
                                                <?= $form->field($model, 'owner_address', ['options' => ['class' => 'form-group']])
                                                    ->textInput(['type' => 'text',
                                                        'autocomplete' => 'off',
                                                        'class' => 'field__input form-control',
                                                        'oninput' => 'this.value = this.value.toUpperCase()',
                                                        'placeholder' => Yii::t('policy', '')
                                                    ])->label($label, ['class' => 'control-label main-form-label my-2']) ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12 col-12">
                                        <div class="row">
                                            <div class="col-md-6 col-12">
                                                <?php $label = $model->getAttributeLabel('app_phone'); ?>
                                                <?= $form->field($model, 'app_phone', ['options' => ['class' => 'form-group']])->textInput([
                                                    'type' => 'tel',
                                                    'maxlength' => true,
                                                    'autocomplete' => 'off',
                                                    'class' => 'field__input field--mask form-control mask-phone',
                                                    'placeholder' => Yii::t('policy', '+998XX-XXX-XX-XX'),
                                                ])->label($label, ['class' => 'control-label main-form-label my-2']) ?>
                                                <div class="help-block-hint small">
                                                    <small><i><?= Yii::t('policy', 'Iltimos mavjud telefon raqamni kiriting, chunki polis sotib olinganligi haqida sms shu raqamga jo`natiladi.') ?></i></small>
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-12 owner-pensioner-info">
                                                <?php $label = $model->getAttributeLabel('org_annual_salary'); ?>
                                                <?= $form->field($model, 'org_annual_salary', ['options' => ['class' => 'form-group']])->textInput([
                                                    'type' => 'text',
                                                    'maxlength' => true,
                                                    'readonly' => false,
                                                    'autocomplete' => 'off',
                                                    'class' => 'field__input field--mask form-control get-calc-ajax mask-money',
                                                    'placeholder' => Yii::t('policy', '1 000 000 000'),
                                                ])->label($label, ['class' => 'control-label main-form-label my-2']) ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div id="driver-info" class="col-12">
                                <div class="row">
                                    <div class="col-md-6 col-6">
                                        <?php $label = $model->getAttributeLabel('start_date'); ?>
                                        <?= $form->field($model, 'start_date', ['options' => ['class' => 'form-group']])
                                            ->textInput(['type' => 'text',
                                                'maxlength' => 10,
                                                'readonly' => true,
                                                'autocomplete' => 'off',
                                                'class' => 'field__input form-control mask-date get-calc-ajax',
                                                'min' => date('Y-m-d'),
                                                'placeholder' => Yii::t('policy', 'dd.mm.yyyy')
                                            ])->label($label, ['class' => 'control-label main-form-label my-2']) ?>
                                    </div>
                                    <div class="col-md-6 col-6">
                                        <?php $label = $model->getAttributeLabel('end_date'); ?>
                                        <?= $form->field($model, 'end_date', ['options' => ['class' => 'form-group']])
                                            ->textInput(['type' => 'text',
                                                'maxlength' => 10,
                                                'autocomplete' => 'off',
                                                'readonly' => true,
                                                'disabled' => true,
                                                'class' => 'field__input  form-control mask-date disabled readonly',
                                                'placeholder' => Yii::t('policy', 'dd.mm.yyyy')
                                            ])->label($label, ['class' => 'control-label main-form-label my-2']) ?>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-12" style="">
            <div class="calculator-card bg-light rounded-3 border rounded-end rounded-bottom py-3 px-4 box-shadow-primary position-sticky top-25 sticky-top z-index-8 sticky-top-100">

                <div class="overlay-right" style="display: none;">
                    <div class="spinner"></div>
                </div>
                <div class="main-title-sm h4"><?= Yii::t('policy', 'Ҳисоблаш натижалари'); ?></div>

                <div class="contact-us-page__info">

                    <?php Pjax::begin(['id' => 'pjax_policy_osgo_calc_result', 'enablePushState' => false, 'scrollTo' => false]); ?>

                    <?php if (!empty($model->_tmp_message)) : ?>
                        <div class="alert-alert-warning">
                            <?= $model->_tmp_message ?>
                        </div>
                    <?php endif; ?>

                    <div class="calculator-card__item policy_price-block <?php echo ($model->amount_uzs) ? '' : 'd-none'?>">
                        <div class="calculator-card__label small">
                            <?= Yii::t('policy', 'Полис нархи'); ?>
                        </div>
                        <div class="calculator-card__value h5 fw-bold">
                            <span id="policy_price"><?= number_format($model->amount_uzs, 2, '.', ' ') ?> </span>
                            <span class="currency"><?= Yii::t('policy', 'сўм'); ?></span>

                        </div>
                    </div>

                    <?php Pjax::end(); ?>
                </div>
            </div>
        </div>
        <div class="col-12">

            <?php $submit_enbled = true; ?>
            <?php if ($submit_enbled): ?>
                <div id="submit-button" class="flex-column <?= ($model->isNewRecord) ? 'd-none' : ''; ?> mt-3">

                    <div class="page-footer-button mt-2">
                        <?php
                        $label = Yii::t('policy', 'Согласен с Правилами');
                        $lang = _lang();
                        $offer_link = Url::to(['/rule/osgor']);
                        $offer_label = Yii::t('policy', 'обязательного страхования гражданской ответственности работодателя');
                        $offer_label = '<a href="' . $offer_link . '" data-pjax="0" class="offer primary-color-text" type="button" data-toggle="" data-target="#offerModal" target="_blank"><i>' . $offer_label . '</i></a>';
                        $label_complate = Yii::t('policy', '{label} {link}', ['label' => $label, 'link' => $offer_label]);
                        $template = '{input} {error}{hint}';
                        echo $form->field($model, 'offer', [
                            'template' => $template
                        ])->checkbox([
                            'label' => $label_complate,
                            'class' => 'on-change-insurer primary-accent-color fs-3',
                        ])->label($label_complate);
                        ?>

                    </div>

                    <div class="contact-us-page__left">

                        <div class="">
                            <div class="page-footer-button">
                                <div class="form-group">
                                    <?php $btn_class = is_mobile_app() ? 'mb-0' : 'mb-5' ?>
                                    <?= Html::submitButton(Yii::t('policy', 'Перейти к просмотру'), ['class' => 'btn btn-success btn-sm-fluid btn-xs s-custom-btn s-custom-primary s-custom-btn--icon mt-2 btn btn-success submitForm ' . $btn_class, 'data-pjax' => false, 'disabled' => true]) ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="contact-us-page__right right-calc-block ml-2">
                    </div>
                </div>
            <?php endif; ?>

            <input type="hidden" id="url_post_price_calc" value="<?= Url::to(['osgor/calculate-price']) ?>">
            <input type="hidden" id="url_post_tin" value="<?= Url::to(['osgor/get-tin-data']) ?>">
            <input type="hidden" id="url_post_okonx" value="<?= Url::to(['osgor/get-okonx-data']) ?>">
            <?php ActiveForm::end(); ?>

        </div>

    </div>

<?php
$jsPjax = <<<JS

JS;
$this->registerJs($jsPjax);
?>
<?php //Pjax::end(); ?>

<?php

$JS = <<<JS
$(function() {
        
        // $.pjax.defaults.scrollTo = false;
        function checkAgree() {
          if ($('#policyosgo-offer').prop('checked')) {
              $('.submitForm').removeAttr('disabled')
              return true;
          } else {
              $('.submitForm').attr('disabled',true)
              return false;
          }
        }
        
        checkAgree();
        
        jQuery(document).on('change', '#policyosgo-offer', function (event) {
           if ($(this).prop('checked')) {
              $('.submitForm').removeAttr('disabled')
          } else {
              $('.submitForm').attr('disabled',true)
          }
        });
        
        $('.mask-money').mask("# ##0", {reverse: true})
        
});
JS;
$this->registerJs($JS);
?>