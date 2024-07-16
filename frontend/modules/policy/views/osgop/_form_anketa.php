<?php

use backend\modules\handbook\models\HandbookFondRegion;
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
    <div class="row custom-row--lg <?= is_mobile_app() ? 'mb-0' : 'mb-3' ?>">
        <div class="col-lg-8 col-12">

            <div class="calculator-group">
                <!--                <h3 class="main-title-sm text-uppercase">-->
                <!--                    --><?php //= Yii::t('policy', 'Fill in personal information'); ?>
                <!--                </h3>-->
                <div class="row">
                    <div class="col-12">
                        <h5 class="form-section-title mb-0 fw-bold"><?= Yii::t('policy', 'Данные свидетельства о регистрации (техпаспорта) транспортного средства') ?></h5>
                    </div>
                    <div class="col-12">
                        <div class="row">
                            <div class="col-md-6 col-sm-5 col-12">
                                <?php
                                // necessary for update action.
                                if (!$model->isNewRecord) {
                                    echo Html::activeHiddenInput($model, "id");
                                }
                                ?>
                                <?php $label = $model->getAttributeLabel('vehicle_gov_number'); ?>
                                <?= $form->field($model, 'vehicle_gov_number')/*, ['options' => ['class' => 'form-group w-100', 'style' => 'padding:8px;']]*/
                                ->textInput(['type' => 'text',
                                    'maxlength' => 8,
                                    'readonly' => !empty($model->vehicle_gov_number) ? $model->vehicle_gov_number : false,
                                    'autocomplete' => 'off',
                                    'autofocus' => empty($model->vehicle_gov_number),
                                    'class' => 'field__input form-control on-change-vehicle form__anketa latin_letters_and_number',
                                    'oninput' => 'this.value = this.value.toUpperCase()',
                                    'placeholder' => Yii::t('policy', '01A001AA'),
                                ])->label($label, ['class' => 'control-label main-form-label d-block my-2']) ?>
                            </div>
                            <div class="col-md-6 col-sm-6 col-12">
                                <div class="row series-number-row align-items-start justify-content-between">
                                    <div class="col-xl-4 col-md-4 col-4 series-three-things">
                                        <?php $label = Yii::t('policy', 'Texnik pasport seriyasi va raqami'); ?>
                                        <?= $form->field($model, 'tech_pass_series', ['options' => ['class' => 'form-group']])->textInput([
                                            'maxlength' => 3,
                                            'autocomplete' => 'off',
                                            'readonly' => !empty($model->tech_pass_series),
                                            'class' => 'field__input form-control latin_letters_no_number on-change-vehicle form__anketa',
                                            'oninput' => 'this.value = this.value.toUpperCase()',
                                            'placeholder' => Yii::t('policy', 'AAF'),
                                        ])->label($label, ['class' => 'control-label main-form-label d-block w-100 overflow-visible visible ws-nowrap my-2']) ?>
                                    </div>
                                    <div class="col-xl-5 col-md-5 col-5 "> <!--series-seven-numbers-->
                                        <?php $label = $model->getAttributeLabel('tech_pass_number'); ?>
                                        <?= $form->field($model, 'tech_pass_number', ['options' => ['class' => 'form-group']])->textInput([
                                            'maxlength' => 7,
                                            'autocomplete' => 'off',
                                            'readonly' => !empty($model->tech_pass_number),
                                            'class' => 'field__input form-control only_number on-change-vehicle form__anketa',
                                            'placeholder' => Yii::t('policy', '0000000'),
                                            'onkeypress' => 'return (event.charCode >= 48 && event.charCode <= 57)',
                                        ])->label($label, ['class' => 'control-label main-form-label d-block ws-nowrap invisible my-2']) ?>
                                    </div>
                                    <div class="col-xl-3 col-md-3 col-3 ms-auto me-0">
                                        <label class="control-label main-form-label invisible my-2">label</label>
                                        <button type="button" id="check-vehicle"
                                                class="check-button btn anketa__check btn d-flex align-items-center justify-content-center ms-auto fill-white <?= ($model->isNewRecord) ? 'check btn-success padding-for-button' : 'clear btn-danger padding-for-button' ?>">
                                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none"
                                                 xmlns="http://www.w3.org/2000/svg" class="check-icon">
                                                <path fill-rule="evenodd" clip-rule="evenodd"
                                                      d="M3.33366 9.16675C3.33366 5.94509 5.94533 3.33341 9.16699 3.33341C12.3887 3.33341 15.0003 5.94509 15.0003 9.16675C15.0003 10.7384 14.3788 12.1648 13.3682 13.2137C13.3396 13.2357 13.3122 13.2597 13.2861 13.2858C13.2599 13.312 13.2359 13.3394 13.214 13.3679C12.1651 14.3786 10.7386 15.0001 9.16699 15.0001C5.94533 15.0001 3.33366 12.3884 3.33366 9.16675ZM13.8484 15.0267C12.5653 16.053 10.9378 16.6667 9.16699 16.6667C5.02486 16.6667 1.66699 13.3089 1.66699 9.16675C1.66699 5.02461 5.02486 1.66675 9.16699 1.66675C13.3091 1.66675 16.667 5.02461 16.667 9.16675C16.667 10.9376 16.0533 12.5651 15.0269 13.8482L18.0896 16.9108C18.415 17.2363 18.415 17.7639 18.0896 18.0893C17.7641 18.4148 17.2365 18.4148 16.9111 18.0893L13.8484 15.0267Z"
                                                      fill="#1E2134"/>
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
                                                c6.074-6.075,6.074-15.909,0-21.986L285.08,230.397z"/>
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

                        <div id="vehicle-info"
                             class="row mt-2 <?= ($model->isNewRecord) ? 'd-none' : '' ?>">

                            <div class="col-12">
                                <div class="row">
                                    <div class="col-md-6 col-12 ">
                                        <?php $label = $model->getAttributeLabel('vehicle_model_name'); ?>
                                        <?= $form->field($model, 'vehicle_model_name', ['options' => ['class' => 'form-group']])->textInput([
                                            'maxlength' => true,
                                            'readonly' => true,
                                            'class' => 'field__input form-control latin_letters_no_number',
                                            'oninput' => 'this.value = this.value.toUpperCase()',
                                            'placeholder' => Yii::t('policy', '{label}ни киритинг',
                                                ['label' => $label]),
                                        ])->label($label, ['class' => 'control-label main-form-label my-2']) ?>

                                    </div>
                                    <div class="col-md-6 col-12 field modal__field modal__field-col">
                                        <?php $label = $model->getAttributeLabel('vehicle_type_id'); ?>
                                        <?= $form->field($model, 'vehicle_type_id', ['options' => ['class' => 'form-group']])->textInput([
                                            'maxlength' => true,
                                            'readonly' => true,
                                            'value' => $model->getVehicleTypesListOsgop($model->vehicle_type_id),
                                            'class' => 'field__input form-control latin_letters_no_number',
                                            'placeholder' => Yii::t('policy', '{label}ни киритинг',
                                                ['label' => $label]),
                                        ])->label($label, ['class' => 'control-label main-form-label my-2']) ?>

                                        <?php
                                        echo Html::hiddenInput('PolicyOsgo[vehicle_type_id]', $model->vehicle_type_id, ['id' => 'vehicle_type_id']);
                                        ?>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 owner-region-info d-none">
                                <div class="row">
                                    <div class="col-md-6 col-12 ">
                                        <?php $label = $model->getAttributeLabel('owner_region'); ?>
                                        <?= $form->field($model, "owner_region", ['options' => ['class' => 'form-group']])->dropDownList(HandbookFondRegion::_getItemsListByInsParam(), [
                                            'multiple' => false,
                                            'readonly' => true,
                                            'maximumSelectionLength' => 1,
                                            'class' => 'field__input form-control',
                                            'prompt' => Yii::t('policy', 'Выберите'),
                                        ])->label($label, ['class' => 'control-label main-form-label my-2']) ?>
                                    </div>
                                    <div class="col-md-6 col-12 field">
                                        <?php $label = $model->getAttributeLabel('owner_district'); ?>
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
                                        ]) ?>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="row">
                                    <div class="col-md-6 col-12 ">
                                        <?php $label = Yii::t('policy','Vehicle Seats Count'); ?>
                                        <?= $form->field($model, 'vehicle_seats_count', ['options' => ['class' => 'form-group']])->textInput([
                                            'maxlength' => true,
                                            'readonly' => false,
                                            'class' => 'field__input form-control only_number get-calc-ajax',
                                            'placeholder' => Yii::t('policy', '{label}ни киритинг',
                                                ['label' => $label]),
                                        ])->label($label, ['class' => 'control-label main-form-label my-2']) ?>
                                        <?php
                                        echo Html::hiddenInput('PolicyOsgo[region_id]', $model->region_id, ['id' => 'region_id']);
                                        //                                        echo Html::hiddenInput('PolicyOsgo[vehicle_seats_count]', $model->vehicle_seats_count, ['id' => 'vehicle_seats_count']);
                                        ?>
                                    </div>
                                    <div class="col-md-6 col-12 ">
                                        <?php $label = $model->getAttributeLabel('vehicle_issue_year'); ?>
                                        <?= $form->field($model, 'vehicle_issue_year', ['options' => ['class' => 'form-group']])->textInput([
                                            'maxlength' => 4,
                                            'readonly' => true,
                                            'class' => 'field__input form-control only_number',
                                            'placeholder' => Yii::t('policy', '{label}ни киритинг',
                                                ['label' => $label]),
                                        ])->label($label, ['class' => 'control-label main-form-label my-2']) ?>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="row">
                                    <div class="col-md-6 col-12 ">
                                        <?php $label = $model->getAttributeLabel('vehicle_body_number'); ?>
                                        <?= $form->field($model, 'vehicle_body_number', ['options' => ['class' => 'form-group']])->textInput([
                                            'maxlength' => 30,
                                            'readonly' => !empty($model->vehicle_body_number),
                                            'class' => 'field__input form-control latin_letters_and_number',
                                            'placeholder' => Yii::t('policy', '{label}ни киритинг',
                                                ['label' => $label]),
                                        ])->label($label, ['class' => 'control-label main-form-label my-2']) ?>
                                    </div>
                                    <div class="col-md-6 col-12 ">
                                        <?php $label = Yii::t('policy','Vehicle Engine Number'); ?>
                                        <?= $form->field($model, 'vehicle_engine_number', ['options' => ['class' => 'form-group']])->textInput([
                                            'maxlength' => 20,
                                            'readonly' => !empty($model->vehicle_engine_number),
                                            'class' => 'field__input form-control latin_letters_and_number',
                                            'placeholder' => Yii::t('policy', '{label}ни киритинг',
                                                ['label' => $label]),
                                        ])->label($label, ['class' => 'control-label main-form-label my-2']) ?>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="row mt-4">
                            <div id="owner-info"
                                 class="col-12 <?= ($model->isNewRecord) ? 'd-none' : '' ?>">
                                <div class="row">
                                    <p class="col-12 contact-us-page__label form-section-title mb-0 fw-bold">
                                        <?= Yii::t('policy', 'Данные собственника транспортного средства') ?>
                                    </p>
                                    <div class="col-md-12 col-12">
                                        <div class="row">
                                            <div class="col-md-12 col-12 modal__field-full-col">
                                                <?php $label = $model->getAttributeLabel('owner_orgname'); ?>
                                                <?= $form->field($model, 'owner_orgname', ['options' => ['class' => 'form-group']])->textInput([
                                                    'maxlength' => true,
                                                    'readonly' => true,
                                                    'class' => 'field__input form-control latin_letters_no_number',
                                                    'oninput' => 'this.value = this.value.toUpperCase()',
                                                    'placeholder' => Yii::t('policy', '{label}ни киритинг',
                                                        ['label' => $label]),
                                                ])->label($label, ['class' => 'control-label main-form-label my-2']) ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12 col-12 pinfl-block <?= (empty($model->owner_pinfl)) ? 'd-none' : '' ?>">
                                        <div class="row">
                                            <div class="col-md-6 col-12">
                                                <?php $label = $model->getAttributeLabel('owner_pinfl'); ?>
                                                <?= $form->field($model, 'owner_pinfl', ['options' => ['class' => 'form-group']])
                                                    ->textInput(['type' => 'text',
                                                        'maxlength' => 14,
                                                        'readonly' => true,
                                                        'autocomplete' => 'off',
                                                        'class' => 'field__input form-control only_number on-change-owner-fy-info',
                                                        'oninput' => 'this.value = this.value.toUpperCase()',
                                                        'placeholder' => Yii::t('policy', 'XXXXXXXXXXXXXX'),
                                                    ])->label($label, ['class' => 'control-label main-form-label d-block my-2']) ?>
                                            </div>
                                            <div class="col-md-6 col-12">
                                                <div class="row justify-content-between">
                                                    <div class="col-xl-4 col-md-4 col-4 "> <!--series-two-things-->
                                                        <?php $label = Yii::t('policy', 'Owner Passport/ID sery number'); ?>
                                                        <?= $form->field($model, 'owner_pass_sery', ['options' => ['class' => 'form-group']])->textInput([
                                                            'maxlength' => 2,
                                                            'readonly' => true,
                                                            'autocomplete' => 'off',
                                                            'class' => 'field__input form-control on-change-owner-fy-info',
                                                            'oninput' => 'this.value = this.value.toUpperCase()',
                                                            'placeholder' => Yii::t('policy', 'AA'),
                                                        ])->label($label, ['class' => 'control-label main-form-label d-block w-100 overflow-visible visible ws-nowrap my-2']) ?>
                                                    </div>
                                                    <div class="col-xl-8 col-md-8 col-8 "> <!--series-two-things-right-auto-->
                                                        <?php $label = $model->getAttributeLabel('owner_pass_num'); ?>
                                                        <?= $form->field($model, 'owner_pass_num', ['options' => ['class' => 'form-group']])->textInput([
                                                            'maxlength' => 7,
                                                            'readonly' => true,
                                                            'class' => 'field__input form-control only_number on-change-owner-fy-info',
                                                            'placeholder' => Yii::t('policy', '0000001'),
                                                        ])->label($label, ['class' => 'control-label main-form-label ws-nowrap d-block invisible my-2']) ?>

                                                        <?php
                                                        echo Html::hiddenInput('owner_birthday', $model->owner_birthday, ['id' => 'owner_birthday']);
                                                        echo Html::hiddenInput('owner_pinfl', $model->owner_pinfl, ['id' => 'owner_pinfl']);
                                                        echo Html::hiddenInput('owner_pass_sery', $model->owner_pass_sery, ['id' => 'owner_pass_sery']);
                                                        echo Html::hiddenInput('owner_pass_num', $model->owner_pass_num, ['id' => 'owner_pass_num']);
                                                        echo Html::hiddenInput('owner_last_name', $model->app_last_name, ['id' => 'owner_last_name']);
                                                        echo Html::hiddenInput('owner_first_name', $model->app_first_name, ['id' => 'owner_first_name']);
                                                        echo Html::hiddenInput('owner_middle_name', $model->app_middle_name, ['id' => 'owner_middle_name']);
                                                        echo Html::activeHiddenInput($model, 'owner_fy', ['id' => 'owner_fy', 'value' => $model->owner_fy ? $model->owner_fy : 0]);
                                                        ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12 col-12 inn-block  <?= (empty($model->owner_inn) || $model->owner_fy == PolicyOsgo::LEGAL_TYPE_FIZ) ? 'd-none' : '' ?>">
                                        <div class="row">
                                            <div class="col-md-6 col-12">
                                                <?php $label = $model->getAttributeLabel('owner_inn'); ?>
                                                <?= $form->field($model, 'owner_inn', ['options' => ['class' => 'form-group']])
                                                    ->textInput(['type' => 'text',
                                                        'maxlength' => 14,
                                                        'autocomplete' => 'off',
                                                        'class' => 'field__input form-control only_number',
                                                        'oninput' => 'this.value = this.value.toUpperCase()',
                                                        'placeholder' => Yii::t('policy', '000000001')
                                                    ])->label($label, ['class' => 'control-label main-form-label my-2']) ?>
                                            </div>
                                        </div>
                                    </div>
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
                                            <div class="col-md-6 col-12 owner-pensioner-info d-none">
                                                <?php $label = $model->getAttributeLabel('owner_is_pensioner'); ?>
                                                <?= $form->field($model, "owner_is_pensioner", ['options' => ['class' => 'form-group']])->dropDownList($model->_getDiscountList(), [
                                                    'multiple' => false,
                                                    'maximumSelectionLength' => 1,
                                                    'class' => 'field__input form-control on-change-owner-info get-calc-ajax',
                                                ])->label($label, ['class' => 'control-label main-form-label my-2']) ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12 col-12 owner-is-app <?= ($model->owner_fy == PolicyOsgo::LEGAL_TYPE_YUR) ? 'd-none' : '' ?>">
                                        <div class="row">
                                            <div class="col-md-6 col-12">
                                                <div class="page-footer-button mt-3">
                                                    <?php
                                                    $label = $model->getAttributeLabel('owner_is_applicant');
                                                    $template = '{input}{error}{hint}';
                                                    echo $form->field($model, 'owner_is_applicant', [
                                                        'template' => $template
                                                    ])->checkbox([
                                                        'class' => 'on-change-insurer primary-accent-color',
                                                    ])->label(false);
                                                    ?>
                                                    <?php
                                                    echo Html::activeHiddenInput($model, "app_pinfl");
                                                    echo Html::activeHiddenInput($model, "app_region");
                                                    echo Html::activeHiddenInput($model, "app_district");
                                                    ?>

                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>

                            <div id="applicant-info"
                                 class="<?= ($model->owner_is_applicant || $model->isNewRecord) ? 'd-none' : ''; ?>">

                                <p class="contact-us-page__label mb-0 fw-bold"><?= Yii::t('policy', 'Applicant information') ?></p>

                                <div class="">
                                    <div class="row align-start">
                                        <div class="col-md-6 col-12">
                                            <?php $label = $model->getAttributeLabel('app_birthday'); ?>
                                            <?= $form->field($model, 'app_birthday', ['options' => ['class' => 'form-group']])
                                                ->textInput(['type' => 'text',
                                                    'maxlength' => true,
                                                    'readonly' => !empty($model->app_birthday),
                                                    'autocomplete' => 'off',
                                                    'class' => 'field__input form-control mask-birthday app_birthday on-change-app-info',
                                                    'placeholder' => Yii::t('policy', 'dd.mm.yyyy')
                                                ])->label($label, ['class' => 'control-label main-form-label my-2']) ?>
                                        </div>
                                        <div class="col-md-6 col-12">
                                            <div class="row align-start justify-space-between">
                                                <div class=" col-4 series-two-things"> <!--col-xl-2 col-lg-2 col-md-2 col-sm-4-->
                                                    <?php $label = Yii::t('policy', 'Applicant passport/ID sery number'); ?>
                                                    <?= $form->field($model, 'app_pass_sery', ['options' => ['class' => 'form-group']])->textInput([
                                                        'type' => 'text',
                                                        'maxlength' => 2,
                                                        'autocomplete' => 'off',
                                                        'readonly' => !empty($model->app_pass_sery),
                                                        'class' => 'field__input form-control on-change-app-info',
                                                        'oninput' => 'this.value = this.value.toUpperCase()',
                                                        'placeholder' => Yii::t('policy', 'AA'),
                                                    ])->label($label, ['class' => 'control-label main-form-label d-block w-100 overflow-visible visible ws-nowrap my-2']) ?>
                                                </div>
                                                <div class="col-5"> <!--col-xl-5 col-lg-5 col-md-5 col-sm-8 col-8 series-two-things-right-middle-->
                                                    <!--series-two-things-right-auto"-->
                                                    <?php $label = $model->getAttributeLabel('app_pass_num'); ?>
                                                    <?= $form->field($model, 'app_pass_num', ['options' => ['class' => 'form-group']])->textInput([
                                                        'maxlength' => 7,
                                                        'readonly' => !empty($model->app_pass_num),
                                                        'class' => 'field__input form-control only_number on-change-app-info',
                                                        'placeholder' => Yii::t('policy', '0000001'),
                                                    ])->label($label, ['class' => 'control-label main-form-label d-block ws-nowrap invisible my-2']) ?>
                                                </div>
                                                <div class="col-3 "> <!--col-xl-2 col-lg-2 col-md-2 col-sm-2 col-2 series-two-things-right-last-btn-->
                                                    <label class="invisible main-form-label my-2">label</label>
                                                    <button type="button" id="check-applicant"
                                                            class="anketa__check s-custom-btn s-custom-primary ms-auto btn--md s-custom-btn--icon text-capitalize check-button btn btn d-flex align-items-center justify-content-center fill-white <?= ($model->isNewRecord) ? 'check btn-success padding-for-button' : 'clear btn-danger padding-for-button' ?>">
                                                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none"
                                                             xmlns="http://www.w3.org/2000/svg" class="check-icon">
                                                            <path fill-rule="evenodd" clip-rule="evenodd"
                                                                  d="M3.33366 9.16675C3.33366 5.94509 5.94533 3.33341 9.16699 3.33341C12.3887 3.33341 15.0003 5.94509 15.0003 9.16675C15.0003 10.7384 14.3788 12.1648 13.3682 13.2137C13.3396 13.2357 13.3122 13.2597 13.2861 13.2858C13.2599 13.312 13.2359 13.3394 13.214 13.3679C12.1651 14.3786 10.7386 15.0001 9.16699 15.0001C5.94533 15.0001 3.33366 12.3884 3.33366 9.16675ZM13.8484 15.0267C12.5653 16.053 10.9378 16.6667 9.16699 16.6667C5.02486 16.6667 1.66699 13.3089 1.66699 9.16675C1.66699 5.02461 5.02486 1.66675 9.16699 1.66675C13.3091 1.66675 16.667 5.02461 16.667 9.16675C16.667 10.9376 16.0533 12.5651 15.0269 13.8482L18.0896 16.9108C18.415 17.2363 18.415 17.7639 18.0896 18.0893C17.7641 18.4148 17.2365 18.4148 16.9111 18.0893L13.8484 15.0267Z"
                                                                  fill="#1E2134"/>
                                                        </svg>
                                                        <svg width="20" height="20" version="1.1" class="clear-icon"
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
        c6.074-6.075,6.074-15.909,0-21.986L285.08,230.397z"/>
                                                            <g>
                                                            </g>
                                                            <g>
                                                            </g>
                                                            <g>
                                                            </g>
                                                            <g>
                                                            </g>
                                                            <g>
                                                            </g>
                                                            <g>
                                                            </g>
                                                            <g>
                                                            </g>
                                                            <g>
                                                            </g>
                                                            <g>
                                                            </g>
                                                            <g>
                                                            </g>
                                                            <g>
                                                            </g>
                                                            <g>
                                                            </g>
                                                            <g>
                                                            </g>
                                                            <g>
                                                            </g>
                                                            <g>
                                                            </g>
    </svg>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="app-name-address-info <?= ($model->owner_is_applicant || $model->isNewRecord) ? 'd-none' : ''; ?>">
                                    <div class="row">
                                        <div class="col-md-4 col-12 field">
                                            <?php $label = $model->getAttributeLabel('app_last_name'); ?>
                                            <?= $form->field($model, 'app_last_name', ['options' => ['class' => 'form-group']])->textInput([
                                                'maxlength' => true,
                                                'readonly' => true,
                                                'class' => 'field__input form-control latin_letters_no_number',
                                                'oninput' => 'this.value = this.value.toUpperCase()',
                                                'placeholder' => Yii::t('policy', '{label}ни киритинг',
                                                    ['label' => $label]),
                                            ])->label($label, ['class' => 'control-label main-form-label my-2']) ?>
                                        </div>
                                        <div class="col-md-4 col-12 field">
                                            <?php $label = $model->getAttributeLabel('app_first_name'); ?>
                                            <?= $form->field($model, 'app_first_name', ['options' => ['class' => 'form-group']])->textInput([
                                                'maxlength' => true,
                                                'readonly' => true,
                                                'class' => 'field__input form-control latin_letters_no_number',
                                                'oninput' => 'this.value = this.value.toUpperCase()',
                                                'placeholder' => Yii::t('policy', '{label}ни киритинг',
                                                    ['label' => $label]),
                                            ])->label($label, ['class' => 'control-label main-form-label my-2']) ?>
                                        </div>
                                        <div class="col-md-4 col-12 field">
                                            <?php $label = $model->getAttributeLabel('app_middle_name'); ?>
                                            <?= $form->field($model, 'app_middle_name', ['options' => ['class' => 'form-group']])->textInput([
                                                'maxlength' => true,
                                                'readonly' => true,
                                                'class' => 'field__input form-control latin_letters_no_number',
                                                'oninput' => 'this.value = this.value.toUpperCase()',
                                                'placeholder' => Yii::t('policy', '{label}ни киритинг',
                                                    ['label' => $label]),
                                            ])->label($label, ['class' => 'control-label main-form-label my-2']) ?>
                                        </div>

                                        <div class="col-md-12 col-12">
                                            <?php $label = $model->getAttributeLabel('app_address'); ?>
                                            <?= $form->field($model, 'app_address', ['options' => ['class' => 'form-group']])
                                                ->textInput(['type' => 'text',
                                                    'autocomplete' => 'off',
                                                    'class' => 'field__input form-control',
                                                    'oninput' => 'this.value = this.value.toUpperCase()',
                                                    'placeholder' => Yii::t('policy', '')
                                                ])->label($label, ['class' => 'control-label main-form-label']) ?>
                                        </div>
                                    </div>
                                </div>

                            </div>

                            <div id="driver-info" class=" <?= ($model->isNewRecord) ? 'd-none' : ''; ?>">

                                <div class="row">
                                    <div class="col-md-4 col-4">
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
                                    <div class="col-md-4 col-4">
                                        <?php $label = $model->getAttributeLabel('period'); ?>
                                        <?= $form->field($model, "period", ['options' => ['class' => 'form-group']])->dropDownList($model->getPeriodArray(), [
                                            'multiple' => false,
                                            'maximumSelectionLength' => 1,
                                            'class' => 'field__input form-control get-calc-ajax',
                                        ])->label($label, ['class' => 'control-label main-form-label my-2']) ?>
                                    </div>
                                    <div class="col-md-4 col-4">
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
        <div class="col-lg-4 col-12">
            <div class="calculator-card bg-light rounded-3 border rounded-end rounded-bottom py-3 px-4 box-shadow-primary sticky-top z-index-8 position-sticky top-25">
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

                    <div class="calculator-card__item">
                        <div class="calculator-card__label small">
                            <?= Yii::t('policy', 'Транспортное средство') ?>
                        </div>
                        <div class="calculator-card__value h5 fw-bold">
                            <span id="calc_vehicle_type_name"><?= $model->getVehicleTypesListOsgop($model->vehicle_type_id) ?></span>
                        </div>
                    </div>

                    <div class="calculator-card__item d-none">
                        <div class="calculator-card__label small">
                            <?= Yii::t('policy', 'Регион регистрации ТС') ?>
                        </div>
                        <div class="calculator-card__value h5 fw-bold">
                            <span id="calc_region_name"><?= $model->_getUseTerritoryList($model->region_id) ?></span>
                        </div>
                    </div>

                    <?php if (count($model->getPeriodArray()) > 1): ?>
                        <div class="calculator-card__item">
                            <div class="calculator-card__label small">
                                <?= Yii::t('policy', 'Period'); ?>
                            </div>
                            <div class="calculator-card__value h5 fw-bold">
                                <span id="calc_period_name"><?= $model->getPeriodArray($model->period) ?></span>
                            </div>
                        </div>
                    <?php endif; ?>

                    <div class="calculator-card__item policy_price-block d-none">
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
                        $label = $model->getAttributeLabel('offer');
                        $lang = _lang();
                        $offer_link = Url::to(['/rule/osgo']);
                        $offer_label = Yii::t('policy', '«Пользовательского соглашения»');
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

            <input type="hidden" id="url_post_price_calc" value="<?= Url::to(['osgop/calculate-price']) ?>">
            <input type="hidden" id="url_post_tech_pass_data" value="<?= Url::to(['osgop/get-tech-pass-data']) ?>">
            <input type="hidden" id="url_post_pass_birthday" value="<?= Url::to(['osgop/get-pass-birthday-data']) ?>">
            <input type="hidden" id="url_post_pass_pinfl" value="<?= Url::to(['osgop/get-pass-pinfl-data']) ?>">
            <input type="hidden" id="label_driver"
                   value="<?= ($model->driver_limit_id == PolicyOsgo::DRIVER_LIMITED) ? Yii::t('policy', 'Водитель') : Yii::t('policy', 'Родственник'); ?>">
            <input type="hidden" id="label_add_driver_0" value="<?= Yii::t('policy', 'Добавить родственник'); ?>">
            <input type="hidden" id="label_add_driver_1" value="<?= Yii::t('policy', 'Добавить водитель'); ?>">
            <?php ActiveForm::end(); ?>

        </div>

    </div>

<?php
$labelDriver = ($model->driver_limit_id == PolicyOsgo::DRIVER_LIMITED) ? Yii::t('policy', 'Водитель') : Yii::t('policy', 'Родственник');
$jsPjax = <<<JS

JS;

$this->registerJs($jsPjax);
?>
<?php //Pjax::end(); ?>

<?php

$get_tech_pass_data = Url::to(['osgop/get-tech-pass-data']);
$get_driver_summary = Url::to(['osgop/get-driver-summary']);
$get_pass_birthday = Url::to(['osgop/get-pass-birthday-data']);
$get_pass_pinfl = Url::to(['osgop/get-pass-pinf-data']);
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
});
JS;
$this->registerJs($JS);
?>