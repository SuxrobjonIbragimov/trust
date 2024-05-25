<?php

use backend\modules\handbook\models\HandbookFondRegion;
use backend\modules\policy\models\PolicyOsgo;
use backend\modules\policy\models\PolicyOsgoDriver;
use kartik\depdrop\DepDrop;
use wbraganca\dynamicform\DynamicFormWidget;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $program array */
/* @var $model backend\modules\policy\models\PolicyOsgo */
/* @var $modelDrivers backend\modules\policy\models\PolicyOsgoDriver */
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
    <div class="row custom-row--lg mb-3">
        <div class="col-lg-8 col-12">
            <div class="calculator-group">

                <div class="row">
                    <div class="col-12">
                        <h5 class="form-section-title mb-0 fw-bold"><?= Yii::t('policy', 'Данные свидетельства о регистрации (техпаспорта) транспортного средства') ?></h5>
                    </div>
                    <div class="col-12">
                        <div class="row">
                            <div class="col-xl-4 col-lg-6 col-md-3 col-sm-5 col-12">
                                <?php
                                // necessary for update action.
                                if (!$model->isNewRecord) {
                                    echo Html::activeHiddenInput($model, "id");
                                }
                                ?>
                                <?php $label = $model->getAttributeLabel('vehicle_gov_number'); ?>
                                <?= $form->field($model, 'vehicle_gov_number')
                                    ->textInput(['type' => 'text',
                                        'maxlength' => 8,
                                        'readonly' => $model->isReadOnly(),
                                        'autocomplete' => 'off',
                                        'autofocus' => empty($model->vehicle_gov_number),
                                        'class' => 'field__input form-control on-change-vehicle form__anketa latin_letters_and_number',
                                        'oninput' => 'this.value = this.value.toUpperCase()',
                                        'placeholder' => Yii::t('policy', '01A001AA'),
                                    ])->label($label, ['class' => 'control-label d-block  my-2']) ?>
                            </div>
                            <div class="col-xl-4 col-lg-6 col-md-5 col-sm-7 col-12">
                                <?php $label = Yii::t('policy', 'Texnik pasport seriyasi va raqami'); ?>
                                <?= $form->field($model, 'tech_pass_series', ['options' => ['class' => 'form-group']])->textInput([
                                    'maxlength' => 3,
                                    'autocomplete' => 'off',
                                    'readonly' => $model->isReadOnly(),
                                    'class' => 'field__input form-control latin_letters_no_number on-change-vehicle form__anketa',
                                    'oninput' => 'this.value = this.value.toUpperCase()',
                                    'placeholder' => Yii::t('policy', 'AAF'),
                                ])->label($label, ['class' => 'control-label w-100 d-block overflow-visible visible ws-nowrap my-2']) ?>
                            </div>
                            <div class="col-xl-2 col-lg-10 col-md-2 col-sm-10 col-12">
                                <?php $label = $model->getAttributeLabel('tech_pass_number'); ?>
                                <?= $form->field($model, 'tech_pass_number', ['options' => ['class' => 'form-group']])->textInput([
                                    'maxlength' => 7,
                                    'autocomplete' => 'off',
                                    'readonly' => $model->isReadOnly(),
                                    'class' => 'field__input form-control only_number on-change-vehicle form__anketa',
                                    'placeholder' => Yii::t('policy', '0000000'),
                                    'onkeypress' => 'return (event.charCode >= 48 && event.charCode <= 57)',
                                ])->label('label', ['class' => 'control-label d-block  invisible  my-2']) ?>
                            </div>
                            <div class="col-xl-2 col-lg-2 col-md-2 col-sm-2 col-12">
                                <label class="control-label invisible  my-2">label</label>
                                <button type="button" id="check-vehicle"
                                        class="check-button btn anketa__check btn d-flex align-items-center mt-0 ms-auto justify-content-center fill-white px-3 py-2 <?= ($model->isReadOnly()) ? 'clear bg-danger' : 'check btn-primary' ?> padding-for-button">
                                    <svg width="20" height="20" viewBox="0 0 20 20" fill="none"
                                         xmlns="http://www.w3.org/2000/svg" class="check-icon">
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                              d="M3.33366 9.16675C3.33366 5.94509 5.94533 3.33341 9.16699 3.33341C12.3887 3.33341 15.0003 5.94509 15.0003 9.16675C15.0003 10.7384 14.3788 12.1648 13.3682 13.2137C13.3396 13.2357 13.3122 13.2597 13.2861 13.2858C13.2599 13.312 13.2359 13.3394 13.214 13.3679C12.1651 14.3786 10.7386 15.0001 9.16699 15.0001C5.94533 15.0001 3.33366 12.3884 3.33366 9.16675ZM13.8484 15.0267C12.5653 16.053 10.9378 16.6667 9.16699 16.6667C5.02486 16.6667 1.66699 13.3089 1.66699 9.16675C1.66699 5.02461 5.02486 1.66675 9.16699 1.66675C13.3091 1.66675 16.667 5.02461 16.667 9.16675C16.667 10.9376 16.0533 12.5651 15.0269 13.8482L18.0896 16.9108C18.415 17.2363 18.415 17.7639 18.0896 18.0893C17.7641 18.4148 17.2365 18.4148 16.9111 18.0893L13.8484 15.0267Z"
                                              fill="var(--bs-white)"/>
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

                        <div id="vehicle-info"
                             class="row <?= ($model->isNewRecord) ? 'd-none' : '' ?>">

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
                                        ])->label($label, ['class' => 'control-label my-2']) ?>
                                    </div>
                                    <div class="col-md-6 col-12 field modal__field modal__field-col">
                                        <?php $label = $model->getAttributeLabel('vehicle_type_id'); ?>
                                        <?= $form->field($model, 'vehicle_type_id', ['options' => ['class' => 'form-group']])->textInput([
                                            'maxlength' => true,
                                            'readonly' => true,
                                            'value' => $model->getVehicleTypesList($model->vehicle_type_id),
                                            'class' => 'field__input form-control latin_letters_no_number',
                                            'placeholder' => Yii::t('policy', '{label}ни киритинг',
                                                ['label' => $label]),
                                        ])->label($label, ['class' => 'control-label my-2']) ?>

                                        <?php
                                        echo Html::hiddenInput('PolicyOsgo[vehicle_type_id]', $model->vehicle_type_id, ['id' => 'vehicle_type_id']);
                                        ?>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12  owner-region-info d-none">
                                <div class="row">
                                    <div class="col-md-6 col-12 ">
                                        <?php $label = $model->getAttributeLabel('owner_region'); ?>
                                        <?= $form->field($model, "owner_region", ['options' => ['class' => 'form-group']])->dropDownList(HandbookFondRegion::_getItemsListByInsParam(), [
                                            'multiple' => false,
                                            'readonly' => true,
                                            'maximumSelectionLength' => 1,
                                            'class' => 'field__input form-control',
                                            'prompt' => Yii::t('policy', 'Выберите'),
                                        ])->label($label, ['class' => 'control-label my-2']) ?>
                                    </div>
                                    <div class="col-md-6 col-12 field">
                                        <?php $label = $model->getAttributeLabel('owner_district'); ?>
                                        <?= $form->field($model, 'owner_district')->hiddenInput()->label(false) ?>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="row">
                                    <div class="col-md-6 col-sm-6 col-12">
                                        <?php $label = $model->getAttributeLabel('region_id'); ?>
                                        <?= $form->field($model, 'region_id', ['options' => ['class' => 'form-group']])->textInput([
                                            'maxlength' => true,
                                            'readonly' => true,
                                            'value' => $use_territory_name,
                                            'class' => 'field__input form-control latin_letters_no_number',
                                            'placeholder' => Yii::t('policy', '{label}ни киритинг',
                                                ['label' => $label]),
                                        ])->label($label, ['class' => 'control-label my-2']) ?>
                                        <?php
                                        echo Html::hiddenInput('PolicyOsgo[region_id]', $model->region_id, ['id' => 'region_id']);
                                        ?>
                                    </div>
                                    <div class="col-md-6 col-sm-6 col-12">
                                        <?php $label = $model->getAttributeLabel('vehicle_issue_year'); ?>
                                        <?= $form->field($model, 'vehicle_issue_year', ['options' => ['class' => 'form-group']])->textInput([
                                            'maxlength' => 4,
                                            'readonly' => true,
                                            'class' => 'field__input form-control only_number',
                                            'placeholder' => Yii::t('policy', '{label}ни киритинг',
                                                ['label' => $label]),
                                        ])->label($label, ['class' => 'control-label my-2']) ?>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="row mt-4">
                            <div id="owner-info" class="col-12 <?= ($model->isNewRecord) ? 'd-none' : '' ?>">
                                <div class="row">
                                    <h5 class="col-12 contact-us-page__label form-section-title mb-0 fw-bold">
                                        <?= Yii::t('policy', 'Данные собственника транспортного средства') ?>
                                    </h5>
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
                                                ])->label($label, ['class' => 'control-label my-2']) ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12 col-12 pinfl-block <?= (empty($model->owner_pinfl)) ? 'd-none' : '' ?>">
                                        <div class="row">
                                            <div class="col-xl-4 col-lg-4 col-md-4 col-12">
                                                <?php $label = $model->getAttributeLabel('owner_pinfl'); ?>
                                                <?= $form->field($model, 'owner_pinfl', ['options' => ['class' => 'form-group']])
                                                    ->textInput(['type' => 'text',
                                                        'maxlength' => 14,
                                                        'readonly' => true,
                                                        'autocomplete' => 'off',
                                                        'class' => 'field__input form-control only_number on-change-owner-fy-info',
                                                        'oninput' => 'this.value = this.value.toUpperCase()',
                                                        'placeholder' => Yii::t('policy', 'XXXXXXXXXXXXXX'),
                                                    ])->label($label, ['class' => 'control-label d-block my-2']) ?>
                                            </div>
                                            <div class="col-xl-4 col-lg-5 col-md-5 col-sm-7 col-12">
                                                <!--series-two-things-->
                                                <?php $label = Yii::t('policy', 'Owner Passport/ID sery number'); ?>
                                                <?= $form->field($model, 'owner_pass_sery', ['options' => ['class' => 'form-group']])->textInput([
                                                    'maxlength' => 2,
                                                    'readonly' => true,
                                                    'autocomplete' => 'off',
                                                    'class' => 'field__input form-control latin_letters_no_number on-change-owner-fy-info',
                                                    'oninput' => 'this.value = this.value.toUpperCase()',
                                                    'placeholder' => Yii::t('policy', 'AA'),
                                                ])->label($label, ['class' => 'control-label d-block w-100 overflow-visible visible ws-nowrap text-nowrap my-2']) ?>
                                            </div>
                                            <div class="col-xl-4 col-lg-3 col-md-3 col-sm-5 col-12">
                                                <?php $label = $model->getAttributeLabel('owner_pass_num'); ?>
                                                <?= $form->field($model, 'owner_pass_num', ['options' => ['class' => 'form-group']])->textInput([
                                                    'maxlength' => 7,
                                                    'readonly' => true,
                                                    'class' => 'field__input form-control only_number on-change-owner-fy-info',
                                                    'placeholder' => Yii::t('policy', '0000001'),
                                                ])->label('label', ['class' => 'control-label invisible d-block my-2']) ?>

                                                <?php
                                                echo Html::hiddenInput('owner_birthday', $model->owner_birthday, ['id' => 'owner_birthday']);
                                                echo Html::hiddenInput('owner_pinfl', $model->owner_pinfl, ['id' => 'owner_pinfl']);
                                                echo Html::hiddenInput('owner_pass_sery', $model->owner_pass_sery, ['id' => 'owner_pass_sery']);
                                                echo Html::hiddenInput('owner_pass_num', $model->owner_pass_num, ['id' => 'owner_pass_num']);
                                                echo Html::hiddenInput('owner_last_name', $model->owner_last_name, ['id' => 'owner_last_name']);
                                                echo Html::hiddenInput('owner_first_name', $model->owner_first_name, ['id' => 'owner_first_name']);
                                                echo Html::hiddenInput('owner_middle_name', $model->owner_middle_name, ['id' => 'owner_middle_name']);
                                                echo Html::hiddenInput('owner_address', $model->owner_address, ['id' => 'owner_address']);
                                                echo Html::hiddenInput('owner_inn', $model->owner_inn, ['id' => 'owner_inn']);
                                                echo Html::hiddenInput('owner_fy', 0, ['id' => 'owner_fy']);
                                                ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12 col-12 inn-block  <?= (empty($model->owner_inn) || $model->owner_fy == PolicyOsgo::LEGAL_TYPE_FIZ) ? 'd-none' : '' ?>">
                                        <div class="row">
                                            <div class="col-xl-6 col-lg-12 col-md-6 col-12">
                                                <?php $label = $model->getAttributeLabel('owner_inn'); ?>
                                                <?= $form->field($model, 'owner_inn', ['options' => ['class' => 'form-group']])
                                                    ->textInput(['type' => 'text',
                                                        'maxlength' => 14,
                                                        'readonly' => true,
                                                        'autocomplete' => 'off',
                                                        'class' => 'field__input form-control latin_letters_no_number',
                                                        'oninput' => 'this.value = this.value.toUpperCase()',
                                                        'placeholder' => Yii::t('policy', '01A001AA')
                                                    ])->label($label, ['class' => 'control-label my-2']) ?>
                                                <?= $form->field($model, 'owner_fy')->hiddenInput()->label(false);?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12 col-12">
                                        <div class="row">
                                            <div class="col-xl-6 col-lg-12 col-md-6 col-12">
                                                <?php $label = $model->getAttributeLabel('app_phone'); ?>
                                                <?= $form->field($model, 'app_phone', ['options' => ['class' => 'form-group']])->textInput([
                                                    'type' => 'tel',
                                                    'maxlength' => true,
                                                    'class' => 'field__input field--mask form-control mask-phone',
                                                    'placeholder' => Yii::t('policy', '+998XX-XXX-XX-XX'),
                                                ])->label($label, ['class' => 'control-label my-2']) ?>
                                            </div>
                                            <div class="col-md-6 col-12 owner-pensioner-info d-none">
                                                <?php $label = $model->getAttributeLabel('owner_is_pensioner'); ?>
                                                <?= $form->field($model, "owner_is_pensioner", ['options' => ['class' => 'form-group']])->dropDownList($model->_getDiscountList(), [
                                                    'multiple' => false,
                                                    'maximumSelectionLength' => 1,
                                                    'class' => 'field__input form-control on-change-owner-info get-calc-ajax',
                                                ])->label($label, ['class' => 'control-label my-2']) ?>
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
                                                        'class' => 'on-change-insurer',
                                                    ])->label(false);
                                                    ?>
                                                    <?php
                                                    echo Html::activeHiddenInput($model, "app_region");
                                                    echo Html::activeHiddenInput($model, "app_district");
                                                    ?>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12 col-12 bg-white rounded-5 py-1 mt-1 px-3">
                                        <div class="row custom-row--sm">
                                            <div class="col-12">
                                                <?php $label = $model->getAttributeLabel('driver_limit_id'); ?>
                                                <?php $add_driver_label = $model->_getAddDriverLabelList(); ?>
                                                <?php $driver_label_order = $model->_getCountDriverLabelList(); ?>
                                                <?= $form->field($model, 'driver_limit_id')->radioList($model->_getDriverLimitList(), [
                                                    'class' => 'row mx-0 field__input form-control  get-calc-ajax',
                                                    'item' => function ($index, $label, $name, $checked, $value) use ($add_driver_label, $driver_label_order) {
                                                        $checked = $checked ? 'checked' : '';
                                                        $add_dr_label = !empty($add_driver_label[$value]) ? $add_driver_label[$value] : $add_driver_label[PolicyOsgo::DRIVER_UNLIMITED];
                                                        $dr_label = !empty($driver_label_order[$value]) ? $driver_label_order[$value] : $driver_label_order[PolicyOsgo::DRIVER_UNLIMITED];
                                                        return "<label class='col-lg-6 col-md-6 col-sm-12 col-12 fs-14px checkbox px-0'><input type='radio' {$checked} name='{$name}' value='{$value}' data-add_driver_label='{$add_dr_label}' data-driver_label='{$dr_label}' class='change-driver_limit accent-color me-1'><i class='ml-05'></i>{$label}</label>";
                                                    }
                                                ])->label($label, ['class' => 'control-label main-form-label']) ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div id="applicant-info"
                                 class="<?= ($model->owner_is_applicant || $model->isNewRecord) ? 'd-none' : ''; ?> mt-4">

                                <h5 class="contact-us-page__label mb-0 fw-bold"><?= Yii::t('policy', 'Applicant information') ?></h5>

                                <div>
                                    <div class="row">
                                        <div class="col-xl-3 col-lg-6 col-md-3 col-sm-5">
                                            <?php $label = $model->getAttributeLabel('app_birthday'); ?>
                                            <?= $form->field($model, 'app_birthday', ['options' => ['class' => 'form-group']])
                                                ->textInput(['type' => 'text',
                                                    'maxlength' => true,
                                                    'readonly' => !empty($model->app_birthday),
                                                    'autocomplete' => 'off',
                                                    'class' => 'field__input form-control mask-birthday app_birthday on-change-app-info',
                                                    'placeholder' => Yii::t('policy', 'dd.mm.yyyy')
                                                ])->label($label, ['class' => 'control-label d-block my-2']) ?>
                                        </div>
                                        <div class="col-xl-5 col-lg-6 col-md-5 col-sm-7 series-two-things">
                                            <?php $label = Yii::t('policy', 'Applicant passport/ID sery number'); ?>
                                            <?= $form->field($model, 'app_pass_sery', ['options' => ['class' => 'form-group']])->textInput([
                                                'type' => 'text',
                                                'maxlength' => 2,
                                                'autocomplete' => 'off',
                                                'readonly' => !empty($model->app_pass_sery),
                                                'class' => 'field__input form-control latin_letters_no_number on-change-app-info',
                                                'oninput' => 'this.value = this.value.toUpperCase()',
                                                'placeholder' => Yii::t('policy', 'AA'),
                                            ])->label($label, ['class' => 'control-label d-block w-100 overflow-visible visible ws-nowrap my-2']) ?>
                                        </div>
                                        <div class="col-xl-2 col-lg-10 col-md-2 col-sm-10">
                                            <?php $label = $model->getAttributeLabel('app_pass_num'); ?>
                                            <?= $form->field($model, 'app_pass_num', ['options' => ['class' => 'form-group']])->textInput([
                                                'maxlength' => 7,
                                                'readonly' => !empty($model->app_pass_num),
                                                'class' => 'field__input form-control only_number on-change-app-info',
                                                'placeholder' => Yii::t('policy', '0000001'),
                                            ])->label('label', ['class' => 'control-label invisible d-block my-2']) ?>
                                        </div>
                                        <div class="col-xl-2 col-lg-2 col-md-2 col-sm-2">
                                            <label class="control-label invisible  my-2">label</label>
                                            <button type="button" id="check-applicant"
                                                    class="anketa__check s-custom-btn s-custom-primary btn--md py-2 px-3 mt-0 s-custom-btn--icon ms-auto text-capitalize check-button btn d-flex align-items-center justify-content-center fill-white <?= ($model->isNewRecord) ? 'check btn-primary padding-for-button' : 'clear padding-for-button' ?>">
                                                <svg width="20" height="20" viewBox="0 0 20 20" fill="none"
                                                     xmlns="http://www.w3.org/2000/svg" class="check-icon">
                                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                                          d="M3.33366 9.16675C3.33366 5.94509 5.94533 3.33341 9.16699 3.33341C12.3887 3.33341 15.0003 5.94509 15.0003 9.16675C15.0003 10.7384 14.3788 12.1648 13.3682 13.2137C13.3396 13.2357 13.3122 13.2597 13.2861 13.2858C13.2599 13.312 13.2359 13.3394 13.214 13.3679C12.1651 14.3786 10.7386 15.0001 9.16699 15.0001C5.94533 15.0001 3.33366 12.3884 3.33366 9.16675ZM13.8484 15.0267C12.5653 16.053 10.9378 16.6667 9.16699 16.6667C5.02486 16.6667 1.66699 13.3089 1.66699 9.16675C1.66699 5.02461 5.02486 1.66675 9.16699 1.66675C13.3091 1.66675 16.667 5.02461 16.667 9.16675C16.667 10.9376 16.0533 12.5651 15.0269 13.8482L18.0896 16.9108C18.415 17.2363 18.415 17.7639 18.0896 18.0893C17.7641 18.4148 17.2365 18.4148 16.9111 18.0893L13.8484 15.0267Z"
                                                          fill="var(--bs-light)"/>
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
                                        <div class="col-lg-12 col-md-6">
                                            <?php $label = $model->getAttributeLabel('app_pinfl'); ?>
                                            <?= $form->field($model, 'app_pinfl', ['options' => ['class' => 'form-group']])
                                                ->textInput(['type' => 'text',
                                                    'maxlength' => 14,
                                                    'readonly' => false,
                                                    'autocomplete' => 'off',
                                                    'class' => 'field__input form-control only_number on-change-app-info',
                                                    'oninput' => 'this.value = this.value.toUpperCase()',
                                                    'placeholder' => Yii::t('policy', 'XXXXXXXXXXXXXX'),
                                                ])->label($label, ['class' => 'control-label d-block my-2']) ?>
                                        </div>
                                    </div>
                                </div>

                                <div class="app-name-address-info <?= ($model->owner_is_applicant || $model->isNewRecord) ? 'd-none' : ''; ?>">
                                    <div class="row">
                                        <div class="col-sm-4 col-12 field">
                                            <?php $label = $model->getAttributeLabel('app_last_name'); ?>
                                            <?= $form->field($model, 'app_last_name', ['options' => ['class' => 'form-group']])->textInput([
                                                'maxlength' => true,
                                                'readonly' => true,
                                                'class' => 'field__input form-control latin_letters_no_number',
                                                'oninput' => 'this.value = this.value.toUpperCase()',
                                                'placeholder' => Yii::t('policy', '{label}ни киритинг',
                                                    ['label' => $label]),
                                            ])->label($label, ['class' => 'control-label my-2']) ?>
                                        </div>
                                        <div class="col-sm-4 col-12 field">
                                            <?php $label = $model->getAttributeLabel('app_first_name'); ?>
                                            <?= $form->field($model, 'app_first_name', ['options' => ['class' => 'form-group']])->textInput([
                                                'maxlength' => true,
                                                'readonly' => true,
                                                'class' => 'field__input form-control latin_letters_no_number',
                                                'oninput' => 'this.value = this.value.toUpperCase()',
                                                'placeholder' => Yii::t('policy', '{label}ни киритинг',
                                                    ['label' => $label]),
                                            ])->label($label, ['class' => 'control-label my-2']) ?>
                                        </div>
                                        <div class="col-sm-4 col-12 field">
                                            <?php $label = $model->getAttributeLabel('app_middle_name'); ?>
                                            <?= $form->field($model, 'app_middle_name', ['options' => ['class' => 'form-group']])->textInput([
                                                'maxlength' => true,
                                                'readonly' => true,
                                                'class' => 'field__input form-control latin_letters_no_number',
                                                'oninput' => 'this.value = this.value.toUpperCase()',
                                                'placeholder' => Yii::t('policy', '{label}ни киритинг',
                                                    ['label' => $label]),
                                            ])->label($label, ['class' => 'control-label my-2']) ?>
                                        </div>
                                    </div>
                                </div>

                            </div>

                            <div id="driver-info"
                                 class="<?= (((empty($model->policyOsgoDrivers)) && $model->isNewRecord)) ? 'd-none' : ''; ?>">

                                <div class="row owner_is_driver-block <?php echo ($model->driver_limit_id == PolicyOsgo::DRIVER_LIMITED) ? '' : 'd-none' ?>">
                                    <div class=" mt-1 mb-0">
                                        <div class="page-footer-button mt-3">
                                            <?php
                                            $label = $model->getAttributeLabel('owner_is_driver');
                                            $template = '{input}{error}{hint}';
                                            echo $form->field($model, 'owner_is_driver', [
                                                'template' => $template
                                            ])->checkbox([
                                                'class' => 'on-change-owner_is_driver',
                                            ])->label(false);
                                            ?>
                                        </div>
                                    </div>
                                </div>


                                <?php DynamicFormWidget::begin([
                                    'widgetContainer' => 'dynamicform_wrapper_driver', // required: only alphanumeric characters plus "_" [A-Za-z0-9_]
                                    'widgetBody' => '.container-items-driver', // required: css class selector
                                    'widgetItem' => '.item-driver', // required: css class
                                    'limit' => \backend\modules\policy\models\PolicyOsgo::MAX_DRIVER_LIMIT, // the maximum times, an element can be cloned (default 999)
                                    'min' => $model->minDriverLimit, // 0 or 1 (default 1)
                                    'insertButton' => '.add-item-driver', // css class
                                    'deleteButton' => '.remove-item-driver', // css class
                                    'model' => $modelDrivers[0],
                                    'formId' => 'policy-osgo-form',
                                    'formFields' => [
                                        'resident_id',
                                        'birthday',
                                        'pass_sery',
                                        'pass_num',
                                        'pinfl',
                                        '_full_name',
                                        'first_name',
                                        'last_name',
                                        'middle_name',
                                        'license_series',
                                        'license_number',
                                        'license_number',
                                        'license_issue_date',
                                        'relationship_id',
                                        'driver_limit',
                                    ],
                                ]); ?>

                                <div class="container-items-driver">
                                    <!-- widgetContainer -->
                                    <?php /** @var \backend\modules\policy\models\PolicyOsgoDriver $modelItem */
                                    foreach ($modelDrivers

                                             as $i => $modelItem): ?>
                                        <div class="item-driver panel panel-default driver-index-<?= $i ?>"
                                             data-index="<?= $i ?>"><!-- widgetBody -->
                                            <div class="panel-heading p-relative my-3 position-relative">
                                                <h5 class="panel-title pull-left panel-title-driver mb-0 fw-bold"><?= ($model->driver_limit_id == PolicyOsgo::DRIVER_LIMITED) ? Yii::t('policy', 'Водитель: <span class="index">{0}</span>', [$i + 1]) : Yii::t('policy', 'Родственник: <span class="index">{0}</span>', [$i + 1]) ?></h5>
                                                <div class="pull-right page-footer-button">
                                                    <button type="button"
                                                            class="btn d-flex remove-item-driver align-items-center justify-content-center bg-red px-3 py-2"
                                                            title="<?= Yii::t('policy', 'Delete') ?>"><i
                                                                class='bx bx-minus text-white fs-5'></i>
                                                    </button>
                                                </div>
                                                <div class="clearfix"></div>
                                            </div>
                                            <div class="panel-body">
                                                <?php
                                                // necessary for update action.
                                                if (!$modelItem->isNewRecord) {
                                                    echo Html::activeHiddenInput($modelItem, "[{$i}]id");
                                                }
                                                echo Html::activeHiddenInput($modelItem, "[{$i}]driver_limit");
                                                ?>
                                                <div class="row">
                                                    <div class="col-md-12 col-12 ">
                                                        <div class="row">
                                                            <div class="col-12 field">
                                                                <?php $label = $modelItem->getAttributeLabel('resident_id'); ?>
                                                                <?= $form->field($modelItem, "[{$i}]resident_id", ['options' => ['class' => 'form-group']])->dropDownList(PolicyOsgoDriver::_getResidentList(), [
                                                                    'multiple' => false,
                                                                    'maximumSelectionLength' => 1,
                                                                    'data-id-uz' => PolicyOsgoDriver::RESIDENT_UZB,
                                                                    'class' => 'field__input form-control on-change-driver-info change-resident_id',
                                                                ])->label($label, ['class' => 'control-label my-2']) ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12 col-12">
                                                        <div class="row">
                                                            <div class="col-xl-4 col-lg-6 col-md-3 col-sm-5 field">
                                                                <?php $label = $modelItem->getAttributeLabel('birthday'); ?>
                                                                <?= $form->field($modelItem, "[{$i}]birthday", ['options' => ['class' => 'form-group']])
                                                                    ->textInput(['type' => 'text',
                                                                        'maxlength' => true,
                                                                        'readonly' => !empty($modelItem->birthday),
                                                                        'autocomplete' => 'off',
                                                                        'class' => 'field__input form-control mask-birthday driver_birthday on-change-driver-info',
                                                                        'placeholder' => Yii::t('policy', 'dd.mm.yyyy')
                                                                    ])->label($label, ['class' => 'control-label d-block my-2']) ?>
                                                            </div>
                                                            <div class="col-xl-4 col-lg-6 col-md-5 col-sm-7 series-two-things">
                                                                <?php $label = Yii::t('policy', 'Passport/ID sery number'); ?>
                                                                <?= $form->field($modelItem, "[{$i}]pass_sery", ['options' => ['class' => 'form-group']])->textInput([
                                                                    'type' => 'text',
                                                                    'maxlength' => 2,
                                                                    'readonly' => !empty($modelItem->pass_sery),
                                                                    'autocomplete' => 'off',
                                                                    'class' => 'field__input form-control latin_letters_no_number on-change-driver-info',
                                                                    'oninput' => 'this.value = this.value.toUpperCase()',
                                                                    'placeholder' => Yii::t('policy', 'AA'),
                                                                ])->label($label, ['class' => 'control-label d-block w-100 overflow-visible visible ws-nowrap my-2']) ?>
                                                            </div>
                                                            <div class="col-xl-2 col-lg-10 col-md-2 col-sm-10">
                                                                <?php $label = $modelItem->getAttributeLabel('pass_num'); ?>
                                                                <?= $form->field($modelItem, "[{$i}]pass_num", ['options' => ['class' => 'form-group']])->textInput([
                                                                    'type' => 'text',
                                                                    'maxlength' => 7,
                                                                    'readonly' => !empty($modelItem->pass_num),
                                                                    'autocomplete' => 'off',
                                                                    'class' => 'field__input form-control only_number on-change-driver-info',
                                                                    'oninput' => 'this.value = this.value.toUpperCase()',
                                                                    'placeholder' => Yii::t('policy', '0000001'),
                                                                ])->label('label', ['class' => 'control-label invisible d-block my-2']) ?>
                                                            </div>
                                                            <div class="col-xl-2 col-lg-2 col-md-2 col-sm-2">
                                                                <label class="control-label invisible  my-2">label</label>
                                                                <button type="button"
                                                                        class="check-driver check-button btn anketa__check s-custom-btn py-2 px-3 s-custom-primary ms-auto mt-0 btn--md s-custom-btn--icon text-capitalize d-flex align-items-center justify-content-center fill-white check-driver-index-<?= $i ?> btn <?= ($modelItem->isNewRecord) ? 'check btn-primary padding-for-button' : 'clear padding-for-button' ?>"
                                                                        data-index="<?= $i ?>">
                                                                    <svg width="20" height="20" viewBox="0 0 20 20"
                                                                         fill="none" xmlns="http://www.w3.org/2000/svg"
                                                                         class="check-icon">
                                                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                                                              d="M3.33366 9.16675C3.33366 5.94509 5.94533 3.33341 9.16699 3.33341C12.3887 3.33341 15.0003 5.94509 15.0003 9.16675C15.0003 10.7384 14.3788 12.1648 13.3682 13.2137C13.3396 13.2357 13.3122 13.2597 13.2861 13.2858C13.2599 13.312 13.2359 13.3394 13.214 13.3679C12.1651 14.3786 10.7386 15.0001 9.16699 15.0001C5.94533 15.0001 3.33366 12.3884 3.33366 9.16675ZM13.8484 15.0267C12.5653 16.053 10.9378 16.6667 9.16699 16.6667C5.02486 16.6667 1.66699 13.3089 1.66699 9.16675C1.66699 5.02461 5.02486 1.66675 9.16699 1.66675C13.3091 1.66675 16.667 5.02461 16.667 9.16675C16.667 10.9376 16.0533 12.5651 15.0269 13.8482L18.0896 16.9108C18.415 17.2363 18.415 17.7639 18.0896 18.0893C17.7641 18.4148 17.2365 18.4148 16.9111 18.0893L13.8484 15.0267Z"
                                                                              fill="var(--bs-light)"/>
                                                                    </svg>
                                                                    <svg width="20" height="20" version="1.1"
                                                                         class="clear-icon "
                                                                         xmlns="http://www.w3.org/2000/svg"
                                                                         xmlns:xlink="http://www.w3.org/1999/xlink"
                                                                         x="0px" y="0px"
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

                                                <div class="row driver-license-info pinfl-block <?= ($modelItem->isNewRecord) ? 'd-none' : ''; ?>">
                                                    <div class="col-md-6 col-sm-7 col-12 field">
                                                        <?php $label = $modelItem->getAttributeLabel('_full_name'); ?>
                                                        <?= $form->field($modelItem, "[{$i}]_full_name", ['options' => ['class' => 'form-group']])->textInput([
                                                            'maxlength' => true,
                                                            'readonly' => true,
                                                            'autocomplete' => 'off',
                                                            'class' => 'field__input form-control latin_letters_no_number',
                                                            'oninput' => 'this.value = this.value.toUpperCase()',
                                                            'placeholder' => Yii::t('policy', '{label}ни киритинг',
                                                                ['label' => $label]),
                                                        ])->label($label, ['class' => 'control-label my-2']) ?>
                                                    </div>
                                                    <div class="col-md-6 col-sm-5 col-12 field">
                                                        <?php $label = $modelItem->getAttributeLabel('pinfl'); ?>
                                                        <?= $form->field($modelItem, "[{$i}]pinfl", ['options' => ['class' => 'form-group']])
                                                            ->textInput(['type' => 'text',
                                                                'maxlength' => 14,
                                                                'readonly' => !empty($modelItem->pinfl),
                                                                'autocomplete' => 'off',
                                                                'class' => 'field__input form-control latin_letters_no_number',
                                                                'oninput' => 'this.value = this.value.toUpperCase()',
                                                                'placeholder' => Yii::t('policy', 'XXXXXXXXXXXXXX'),
                                                            ])->label($label, ['class' => 'control-label my-2']) ?>
                                                    </div>
                                                </div>

                                                <div class="row driver-name-info <?= ($modelItem->isNewRecord || !empty($modelItem->_full_name)) ? 'd-none' : ''; ?>">
                                                    <div class="col">
                                                        <?php $label = $modelItem->getAttributeLabel('first_name'); ?>
                                                        <?= $form->field($modelItem, "[{$i}]first_name", ['options' => ['class' => 'form-group']])->textInput([
                                                            'maxlength' => true,
                                                            'readonly' => true,
                                                            'autocomplete' => 'off',
                                                            'class' => 'field__input form-control latin_letters_no_number',
                                                            'oninput' => 'this.value = this.value.toUpperCase()',
                                                            'placeholder' => Yii::t('policy', '{label}ни киритинг',
                                                                ['label' => $label]),
                                                        ])->label($label, ['class' => 'control-label my-2']) ?>
                                                    </div>
                                                    <div class="col">
                                                        <?php $label = $modelItem->getAttributeLabel('last_name'); ?>
                                                        <?= $form->field($modelItem, "[{$i}]last_name", ['options' => ['class' => 'form-group']])->textInput([
                                                            'maxlength' => true,
                                                            'readonly' => true,
                                                            'autocomplete' => 'off',
                                                            'class' => 'field__input form-control latin_letters_no_number',
                                                            'oninput' => 'this.value = this.value.toUpperCase()',
                                                            'placeholder' => Yii::t('policy', '{label}ни киритинг',
                                                                ['label' => $label]),
                                                        ])->label($label, ['class' => 'control-label my-2']) ?>
                                                    </div>
                                                    <div class="col">
                                                        <?php $label = $modelItem->getAttributeLabel('middle_name'); ?>
                                                        <?= $form->field($modelItem, "[{$i}]middle_name", ['options' => ['class' => 'form-group']])->textInput([
                                                            'maxlength' => true,
                                                            'readonly' => true,
                                                            'autocomplete' => 'off',
                                                            'class' => 'field__input form-control latin_letters_no_number',
                                                            'oninput' => 'this.value = this.value.toUpperCase()',
                                                            'placeholder' => Yii::t('policy', '{label}ни киритинг',
                                                                ['label' => $label]),
                                                        ])->label($label, ['class' => 'control-label my-2']) ?>
                                                    </div>
                                                </div>

                                                <div class="row driver-license-info license-block <?= ($modelItem->isNewRecord) ? 'd-none' : ''; ?>">
                                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-4 field">
                                                        <?php $label = $modelItem->getAttributeLabel('license_issue_date'); ?>
                                                        <?= $form->field($modelItem, "[{$i}]license_issue_date", ['options' => ['class' => 'form-group']])
                                                            ->textInput(['type' => 'text',
                                                                'maxlength' => true,
                                                                'readonly' => !empty($modelItem->license_issue_date),
                                                                'autocomplete' => 'off',
                                                                'class' => 'field__input form-control mask-birthday license_issue_date',
                                                                'placeholder' => Yii::t('policy', 'dd.mm.yyyy')
                                                            ])->label($label, ['class' => 'control-label my-2']) ?>
                                                    </div>
                                                    <div class="col-xl-3 col-lg-3 col-md-3 col-sm-5 series-two-things">
                                                        <?php $label = $modelItem->getAttributeLabel('license_series'); ?>
                                                        <?= $form->field($modelItem, "[{$i}]license_series", ['options' => ['class' => 'form-group']])->textInput([
                                                            'type' => 'text',
                                                            'maxlength' => 3,
                                                            'readonly' => !empty($modelItem->license_series),
                                                            'autocomplete' => 'off',
                                                            'class' => 'field__input form-control latin_letters_no_number ',
                                                            'oninput' => 'this.value = this.value.toUpperCase()',
                                                            'placeholder' => Yii::t('policy', 'AF'),
                                                        ])->label($label, ['class' => 'control-label d-block w-100 overflow-visible visible ws-nowrap my-2 text-nowrap']) ?>
                                                    </div>
                                                    <div class="col-xl-3 col-lg-3 col-md-3 col-sm-3 field">
                                                        <?php $label = $modelItem->getAttributeLabel('license_number'); ?>
                                                        <?= $form->field($modelItem, "[{$i}]license_number", ['options' => ['class' => 'form-group']])->textInput([
                                                            'maxlength' => 7,
                                                            'readonly' => !empty($modelItem->license_number),
                                                            'autocomplete' => 'off',
                                                            'class' => 'field__input form-control only_number',
                                                            'placeholder' => Yii::t('policy', '0000001'),
                                                        ])->label('label', ['class' => 'control-label invisible d-block my-2']) ?>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row driver-license-info relationship-block  <?= ($modelItem->isNewRecord) ? 'd-none' : ''; ?>">
                                                <div class="field">
                                                    <?php $label = $modelItem->getAttributeLabel('relationship_id'); ?>
                                                    <?= $form->field($modelItem, "[{$i}]relationship_id", ['options' => ['class' => 'form-group']])->dropDownList(PolicyOsgoDriver::_getRelationList(), [
                                                        'multiple' => false,
                                                        'maximumSelectionLength' => 1,
                                                        'class' => 'field__input form-control',
                                                        'prompt' => Yii::t('policy', 'Выберите',
                                                            ['label' => $label]),
                                                    ])->label($label, ['class' => 'control-label my-2']) ?>
                                                </div>
                                            </div>

                                        </div>
                                    <?php endforeach; ?>
                                </div>

                                <div class="pull-right- page-footer-button">
                                    <button type="button" class="add-item-driver btn btn-primary btn-xs my-3">
                                        <?= ($model->driver_limit_id == PolicyOsgo::DRIVER_LIMITED) ? Yii::t('policy', 'Добавить водителя') : Yii::t('policy', 'Добавить родственник') ?>
                                    </button>
                                </div>
                                <?php DynamicFormWidget::end(); ?>
                            </div>
                        </div>
                    </div>
                </div>


            </div>
        </div>
        <div class="col-lg-4 col-12 sticky-top z-index-8">
            <div class="bg-light rounded-3 border rounded-end rounded-bottom py-3 px-4 box-shadow-primary position-sticky top-25">
                <!--p-relative-->

                <div class="overlay-right" style="display: none;">
                    <div class="spinner"></div>
                </div>
                <div class="main-title-sm h4 mb-3 fw-bold"><?= Yii::t('policy', 'Ҳисоблаш натижалари'); ?></div>

                <div class="contact-us-page__info">

                    <?php Pjax::begin(['id' => 'pjax_policy_osgo_calc_result', 'enablePushState' => false, 'scrollTo' => false]); ?>

                    <?php if (!empty($model->_tmp_message)) : ?>
                        <div class="alert-alert-warning">
                            <?= $model->_tmp_message ?>
                        </div>
                    <?php endif; ?>

                    <div class="calculator-card__item mb-2">
                        <div class="calculator-card__label small ">
                            <?= Yii::t('policy', 'Транспортное средство') ?>
                        </div>
                        <div class="calculator-card__value h5 fw-bold">
                            <span id="calc_vehicle_type_name"><?= $model->getVehicleTypesList($model->vehicle_type_id) ?></span>
                        </div>
                    </div>

                    <div class="calculator-card__item mb-2 d-none">
                        <div class="calculator-card__label small">
                            <?= Yii::t('policy', 'Регион регистрации ТС') ?>
                        </div>
                        <div class="calculator-card__value h5 fw-bold">
                            <span id="calc_region_name"><?= $model->_getUseTerritoryList($model->region_id) ?></span>
                        </div>
                    </div>

                    <div class="calculator-card__item mb-2 d-none">
                        <div class="calculator-card__label small">
                            <?php echo Yii::t('policy', 'Количество водителей') ?>
                        </div>
                        <div class="calculator-card__value h5 fw-bold">
                            <span><?php echo $model->_getDriverLimitList($model->driver_limit_id) ?></span>
                        </div>
                    </div>

                    <?php if (count($model->_getPeriodList()) > 1): ?>
                        <div class="calculator-card__item mb-2 d-none">
                            <div class="calculator-card__label small">
                                <?= Yii::t('policy', 'Period'); ?>
                            </div>
                            <div class="calculator-card__value h5 fw-bold">
                                <span><?= $model->_getPeriodList($model->period_id) ?></span>
                            </div>
                        </div>
                    <?php endif; ?>

                    <div class="calculator-card__item mb-2">
                        <div class="calculator-card__label small ">
                            <?= Yii::t('policy', 'Полис нархи'); ?>
                        </div>
                        <div class="calculator-card__value h5 fw-bold">
                            <span id="policy_price"><?= number_format($model->amount_uzs, 2, '.', ' ') ?></span>
                            <span class="currency"><?= Yii::t('policy', 'сўм'); ?></span>

                        </div>
                    </div>

                    <div class="calculator-card__item mb-2">
                        <div class="calculator-card__label small ">
                            <?php echo Yii::t('policy', 'Суғурта суммаси'); ?>
                        </div>
                        <div class="calculator-card__value h5 fw-bold">
                            <span id="policy_gift_price"><?php echo number_format($model->_ins_amount, 2, '.', ' ') ?></span>
                            <span class="currency"><?php echo Yii::t('policy', 'сўм'); ?></span>
                        </div>
                    </div>

                    <?php Pjax::end(); ?>
                </div>
            </div>
        </div>
        <div class="col-12">

            <?php $submit_enbled = true; ?>
            <?php if ($submit_enbled): ?>
                <div id="submit-button" class="flex-row <?= ($model->isNewRecord) ? 'd-none' : ''; ?> mt-3">
                    <div class="contact-us-page__left">

                        <div class="">
                            <div class="page-footer-button">
                                <div class="form-group">
                                    <?php if ($model->isNewRecord): ?>
                                        <?= Html::submitButton(Yii::t('policy', 'Перейти к просмотру'), ['class' => 'btn btn-primary btn-xs d-none', 'id' => 'osgo-submit-btn']) ?>
                                    <?php else: ?>
                                        <?= Html::submitButton(Yii::t('policy', 'Перейти к просмотру'), ['class' => 'btn btn-primary btn-xs']) ?>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="contact-us-page__right right-calc-block ml-2">
                    </div>
                </div>
            <?php endif; ?>

            <input type="hidden" id="url_post_price_calc" value="<?= Url::to(['osgo/calculate-price']) ?>">
            <input type="hidden" id="url_post_tech_pass_data" value="<?= Url::to(['osgo/get-tech-pass-data']) ?>">
            <input type="hidden" id="url_post_pass_birthday" value="<?= Url::to(['osgo/get-pass-birthday-data']) ?>">
            <input type="hidden" id="url_post_pass_pinfl" value="<?= Url::to(['osgo/get-pass-pinfl-data']) ?>">
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

$get_tech_pass_data = Url::to(['osgo/get-tech-pass-data']);
$get_driver_summary = Url::to(['osgo/get-driver-summary']);
$get_pass_birthday = Url::to(['osgo/get-pass-birthday-data']);
$get_pass_pinfl = Url::to(['osgo/get-pass-pinf-data']);
$JS = <<<JS
JS;
$this->registerJs($JS);
?>