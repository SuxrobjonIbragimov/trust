<?php

use backend\modules\policy\models\PolicyTravel;
use backend\modules\policy\models\PolicyTravelParentTraveller;
use backend\modules\policy\models\PolicyTravelPurpose;
use wbraganca\dynamicform\DynamicFormWidget;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $model \backend\modules\policy\models\PolicyTravel */
/* @var $modelItem \backend\modules\policy\models\PolicyTravelTraveller */
/* @var $modelTravellers \backend\modules\policy\models\PolicyTravelTraveller */
/* @var $modelParentTravellers \backend\modules\policy\models\PolicyTravelParentTraveller */
/* @var $form yii\widgets\ActiveForm */

$addon = <<< HTML
<span class="input-group-text">
    <i class="fas fa-calendar-alt"></i>
</span>
HTML;

?>


<?php Pjax::begin(['id' => 'pjax_policy_travel_calc']); ?>

<?php $form = ActiveForm::begin([
    'id' => 'policy-travel-form',
    'options' => [
        'data-pjax' => true
    ]
]); ?>

    <div class="row mb-3 form__calc">
        <div class="col-lg-8 col-12">
            <div class="calculator-group">
                <div class="row">
                    <div class="col-12">
                        <h4 class="contact-us-page__label mb-4 text-bold"><?= Yii::t('policy', 'Детали путешествия') ?></h4>
                    </div>
                    <div class="col-12">
                        <div class="row row-gap-3">
                            <div class="col-lg-6 col-md-5 col-12">
                                <?php
                                // necessary for update action.
                                if (!$model->isNewRecord) {
                                    echo Html::activeHiddenInput($model, "id");
                                }
                                ?>

                                <?php $label = $model->getAttributeLabel('_travelCountries'); ?>
                                <?= $form->field($model, '_travelCountries', ['options' => ['class' => 'form-group']])->dropDownList($model->_travelCountriesList, [
                                    'multiple' => true,
                                    'maximumSelectionLength' => 6,
                                    'size' => 1,
                                    'class' => 'field__input form-control select2-travel on-change',
                                ])->label($label, ['class' => 'control-label main-form-label']) ?>
                            </div>
                            <div class="col-lg-6 col-md-7 col-12">
                                <div class="row">
                                    <div class="<?= ($model->isMulti()) ? 'col-sm-6' : ''; ?> col-12">
                                        <?php $label = $model->getAttributeLabel('abroad_type_id'); ?>
                                        <?= $form->field($model, 'abroad_type_id', ['options' => ['class' => 'form-group']])->dropDownList($model->getAbroadTypeList(), [
                                            'multiple' => false,
                                            'maximumSelectionLength' => 1,
                                            'size' => 1,
                                            'class' => 'field__input form-control on-change',
                                            'prompt' => Yii::t('policy', 'Выберите',
                                                ['label' => $label]),
                                        ])->label($label, ['class' => 'control-label main-form-label']) ?>
                                    </div>
                                    <?php if ($model->isMulti()): ?>
                                        <div class="col-sm-6 col-12">
                                            <?php $label = $model->getAttributeLabel('multi_days_id'); ?>
                                            <?= $form->field($model, 'multi_days_id', ['options' => ['class' => 'form-group']])->dropDownList($model->getMultiDaysList(), [
                                                'multiple' => false,
                                                'maximumSelectionLength' => 1,
                                                'size' => 1,
                                                'class' => 'field__input form-control on-change',
                                                'prompt' => Yii::t('policy', 'Выберите',
                                                    ['label' => $label]),
                                            ])->label($label, ['class' => 'control-label main-form-label']) ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-2">
                            <div class="col-12">
                                <?php if (!empty($model->_travelProgramsList)): ?>
                                    <div class="form-group my-2">
                                        <?php $program_id = intval($model->program_id); ?>
                                        <?php $program_info = !empty($model->programs_info[$program_id]) ? $model->programs_info[$program_id] : null; ?>
                                        <?php $label = $model->getAttributeLabel('program_id'); ?>
                                        <?php $info_svg = '<svg width="24" height="24" xmlns="http://www.w3.org/2000/svg" fill-rule="evenodd" clip-rule="evenodd"><path d="M12 0c6.623 0 12 5.377 12 12s-5.377 12-12 12-12-5.377-12-12 5.377-12 12-12zm0 1c6.071 0 11 4.929 11 11s-4.929 11-11 11-11-4.929-11-11 4.929-11 11-11zm.5 17h-1v-9h1v9zm-.5-12c.466 0 .845.378.845.845 0 .466-.379.844-.845.844-.466 0-.845-.378-.845-.844 0-.467.379-.845.845-.845z"/></svg>'; ?>
                                        <?= $form->field($model, 'program_id')->radioList($model->_travelProgramsList, [
                                            'class' => 'form-control d-flex column-gap-2 on-change id-custom p-2',
                                            'item' => function ($index, $label, $name, $checked, $value) use ($info_svg) {
                                                $checked = $checked ? 'checked' : '';
                                                return "<div class='pretty p-default p-round p-smooth d-flex align-items-center'><input class='me-1 primary-accent-color' type='radio' {$checked} name='{$name}' value='{$value}'><i class='ml-05'></i><div class='state p-warning-o'><label class='checkbox checkbox-custom mr-4 position-relative'><span>{$label} </span> <span role='button' data-toggle='modal' data-target='#modalProgramInfo' class='show-modal-program ml-1 d-none' data-title='{$label}' data-key='{$value}'> {$info_svg} </span></label></div></div>";
                                            }
                                        ]) ?>
                                        <?php if (!empty($model->programs_info)) : ?>
                                            <?php foreach ($model->programs_info as $key_pr => $program_info): ?>
                                                <div class="modal-program-info-<?= $key_pr ?>" style="display: none">
                                                    <?php if (!empty($program_info)) : ?>
                                                        <ul class="">
                                                            <?php $program_labels = PolicyTravel::_getProgramInfoLabels() ?>
                                                            <?php foreach ($program_info as $key => $value): ?>
                                                                <?php if (!empty($program_labels[$key])) : ?>
                                                                    <li class="">
                                                                        <p class="flex flex-row flex-aligin-center flex-space-between fs-4"
                                                                           style="    justify-content: space-between;">
                                                                            <span><?= !empty($program_labels[$key]) ? $program_labels[$key] : $key; ?></span>
                                                                            <span><?= number_format(floatval($value), 0, '.', ' '); ?>
                                                        <span class="currency"><?= Yii::t('policy', 'EUR'); ?></span>
                                                        </span>
                                                                        </p>
                                                                    </li>
                                                                <?php endif; ?>
                                                            <?php endforeach; ?>
                                                        </ul>
                                                    <?php endif; ?>

                                                </div>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-sm-4 col-12">
                                <?php $label = $model->getAttributeLabel('start_date'); ?>
                                <?= $form->field($model, 'start_date', ['options' => ['class' => 'form-group']])
                                    ->textInput(['type' => 'text',
                                        'maxlength' => 10,
                                        'autocomplete' => 'off',
                                        'class' => 'form-control on-change mask-date',
                                        'min' => date('Y-m-d'),
                                        'placeholder' => Yii::t('policy', 'dd.mm.yyyy',
                                            ['label' => $label])
                                    ])->label($label, ['class' => 'control-label main-form-label']) ?>
                            </div>
                            <div class="col-sm-4 col-12">
                                <?php if (!empty($model->end_date)): ?>
                                    <?php $model->end_date = date('d.m.Y', strtotime($model->end_date)); ?>
                                <?php endif; ?>
                                <?php $label = $model->getAttributeLabel('end_date'); ?>
                                <?= $form->field($model, 'end_date', ['options' => ['class' => 'form-group']])
                                    ->textInput(['type' => 'text',
                                        'maxlength' => 10,
                                        'readonly' => $model->isMulti(),
                                        'autocomplete' => 'off',
                                        'class' => 'form-control on-change mask-date',
                                        'placeholder' => Yii::t('policy', 'dd.mm.yyyy',
                                            ['label' => $label])
                                    ])->label($label, ['class' => 'control-label main-form-label']) ?>
                            </div>
                            <div class="col-sm-4 col-12">
                                <?php $label = $model->getAttributeLabel('days'); ?>
                                <?= $form->field($model, 'days', ['options' => ['class' => 'form-group']])
                                    ->textInput(['maxlength' => 3,
                                        'autocomplete' => 'off',
                                        'readonly' => $model->isMulti(),
                                        'class' => 'form-control on-change',
                                        'placeholder' => Yii::t('policy', '{label}ни киритинг',
                                            ['label' => $label])
                                    ])->label($label, ['class' => 'control-label main-form-label']) ?>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-12">
                                <?php $label = $model->getAttributeLabel('purpose_id'); ?>
                                <?= $form->field($model, 'purpose_id')->radioList($model->_travelPurposesList, [
                                    'class' => 'form-control on-change form-custom d-flex flex-wrap p-3 row-gap-3 column-gap-2',
                                    'item' => function ($index, $label, $name, $checked, $value) {
                                        $checked = $checked ? 'checked' : '';
                                        return "<div class='pretty p-default p-round p-smooth d-flex align-items-center'><input class='me-1 primary-accent-color' type='radio' {$checked} name='{$name}' value='{$value}'><i class='ml-05'></i><div class='state p-warning-o'><label class='checkbox mr-4 position-relative'>{$label}</label></div></div>";
                                    }
                                ])->label($label, ['class' => 'control-label main-form-label']) ?>
                            </div>
                        </div>
                        <div class="programs-block">
                            <div class="field modal__field">
                            </div>


                            <?php if ($model->purpose_id == PolicyTravelPurpose::PURPOSE_TRAVEL): ?>
                                <div class="field mt-4">
                                    <div class="page-footer-button">
                                        <?php
                                        $label = $model->getAttributeLabel('is_family');
                                        $template = '{input}{error}{hint}';
                                        echo $form->field($model, 'is_family', [
                                            'template' => $template
                                        ])->checkbox([
                                            'class' => 'on-change primary-accent-color form__check-',
                                        ])->label(false);
                                        ?>

                                    </div>
                                </div>
                            <?php else: ?>
                                <?php $model->is_family = null ?>
                                <?php echo Html::activeHiddenInput($model, "is_family"); ?>
                            <?php endif; ?>

                            <hr class="text-primary border-1 border border-primary bg-primary my-3">

                            <h3 class="contact-us-page__label my-2 text-bold"><?= Yii::t('policy', 'Insurer information') ?></h3>

                            <div class="row mb-2">
                                <div class="col-xl-3 col-lg-6 col-md-3 col-sm-5 col-12">
                                    <?php $label = $model->getAttributeLabel('app_birthday'); ?>
                                    <?= $form->field($model, 'app_birthday', ['options' => ['class' => 'form-group']])
                                        ->textInput(['type' => 'text',
                                            'maxlength' => true,
                                            'readonly' => $model->isReadOnly(),
                                            'autocomplete' => 'off',
                                            'class' => 'field__input form-control mask-birthday app_birthday on-change-app-info',
                                            'placeholder' => Yii::t('policy', 'dd.mm.yyyy')
                                        ])->label($label, ['class' => 'control-label main-form-label']) ?>
                                </div>
                                <div class="col-xl-5 col-lg-6 col-md-5 col-sm-7 col-14 series-two-things">
                                    <?php $label = Yii::t('policy', 'Applicant passport/ID sery number'); ?>
                                    <?= $form->field($model, 'app_pass_sery', ['options' => ['class' => 'form-group']])->textInput([
                                        'type' => 'text',
                                        'maxlength' => 2,
                                        'readonly' => $model->isReadOnly(),
                                        'autocomplete' => 'off',
                                        'class' => 'field__input form-control on-change-app-info',
                                        'oninput' => 'this.value = this.value.toUpperCase()',
                                        'placeholder' => Yii::t('policy', 'AA'),
                                    ])->label($label, ['class' => 'control-label d-block w-100 overflow-visible visible ws-nowrap main-form-label']) ?>
                                </div>
                                <div class="col-xl-3 col-lg-10 col-md-3 col-sm-10 col-12 series-num-middle">
                                    <!--series-two-things-right-auto"-->
                                    <?php $label = $model->getAttributeLabel('app_pass_num'); ?>
                                    <?= $form->field($model, 'app_pass_num', ['options' => ['class' => 'form-group']])->textInput([
                                        'maxlength' => 7,
                                        'readonly' => $model->isReadOnly(),
                                        'class' => 'field__input form-control only_number on-change-app-info',
                                        'placeholder' => Yii::t('policy', '0000001'),
                                    ])->label($label, ['class' => 'control-label invisible main-form-label']) ?>
                                </div>
                                <div class="col-xl-1 col-lg-2 col-md-1 col-sm-2 col-12 btn-series">
                                    <label class="invisible main-form-label">hide</label>
                                    <button type="button" id="check-applicant"
                                            class="anketa__check mt-0  d-flex ms-auto justify-content-center align-items-center s-custom-btn--icon text-capitalize check-button btn <?= (!$model->isReadOnly()) ? 'check bg-primary' : 'clear bg-danger' ?>">
                                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none"
                                             xmlns="http://www.w3.org/2000/svg" class="check-icon">
                                            <path fill-rule="evenodd" clip-rule="evenodd"
                                                  d="M3.33366 9.16675C3.33366 5.94509 5.94533 3.33341 9.16699 3.33341C12.3887 3.33341 15.0003 5.94509 15.0003 9.16675C15.0003 10.7384 14.3788 12.1648 13.3682 13.2137C13.3396 13.2357 13.3122 13.2597 13.2861 13.2858C13.2599 13.312 13.2359 13.3394 13.214 13.3679C12.1651 14.3786 10.7386 15.0001 9.16699 15.0001C5.94533 15.0001 3.33366 12.3884 3.33366 9.16675ZM13.8484 15.0267C12.5653 16.053 10.9378 16.6667 9.16699 16.6667C5.02486 16.6667 1.66699 13.3089 1.66699 9.16675C1.66699 5.02461 5.02486 1.66675 9.16699 1.66675C13.3091 1.66675 16.667 5.02461 16.667 9.16675C16.667 10.9376 16.0533 12.5651 15.0269 13.8482L18.0896 16.9108C18.415 17.2363 18.415 17.7639 18.0896 18.0893C17.7641 18.4148 17.2365 18.4148 16.9111 18.0893L13.8484 15.0267Z"
                                                  fill="white"/>
                                        </svg>
                                        <svg width="20" height="20" version="1.1" class="clear-icon"
                                             xmlns="http://www.w3.org/2000/svg"
                                             xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                                             viewBox="0 0 460.775 460.775"
                                             style="enable-background:new 0 0 460.775 460.775;"
                                             xml:space="preserve">
                                                        <path fill="white" d="M285.08,230.397L456.218,59.27c6.076-6.077,6.076-15.911,0-21.986L423.511,4.565c-2.913-2.911-6.866-4.55-10.992-4.55
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

                            <div class="row mb-2">
                                <div class="col-sm-6 col-12 field">
                                    <?php $label = $model->getAttributeLabel('app_name'); ?>
                                    <?= $form->field($model, 'app_name', ['options' => ['class' => 'form-group']])->textInput([
                                        'maxlength' => true,
                                        'readonly' => false,
                                        'class' => 'field__input form-control latin_letters_no_number on-change-insurer',
                                        'oninput' => 'this.value = this.value.toUpperCase()',
                                        'placeholder' => Yii::t('policy', '{label}ни киритинг',
                                            ['label' => $label]),
                                    ])->label($label, ['class' => 'control-label main-form-label']) ?>
                                </div>
                                <div class="col-sm-6 col-12 field">
                                    <?php $label = $model->getAttributeLabel('app_surname'); ?>
                                    <?= $form->field($model, 'app_surname', ['options' => ['class' => 'form-group']])->textInput([
                                        'maxlength' => true,
                                        'readonly' => false,
                                        'class' => 'field__input form-control latin_letters_no_number on-change-insurer',
                                        'oninput' => 'this.value = this.value.toUpperCase()',
                                        'placeholder' => Yii::t('policy', '{label}ни киритинг',
                                            ['label' => $label]),
                                    ])->label($label, ['class' => 'control-label main-form-label']) ?>
                                    <?php
                                    echo Html::activeHiddenInput($model, "app_pinfl");
                                    ?>
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-sm-6 col-12 field">
                                    <?php $label = $model->getAttributeLabel('app_address'); ?>
                                    <?= $form->field($model, 'app_address', ['options' => ['class' => 'form-group']])
                                        ->textInput(['type' => 'text',
                                            'maxlength' => true,
                                            'class' => 'form-control',
                                            'oninput' => 'this.value = this.value.toUpperCase()',
                                            'placeholder' => Yii::t('policy', '{label}ни киритинг', ['label' => $label])
                                        ])->label($label, ['class' => 'control-label main-form-label']) ?>
                                </div>
                                <div class="col-sm-6 col-12 field">
                                    <?php $label = $model->getAttributeLabel('app_phone'); ?>
                                    <?= $form->field($model, 'app_phone', ['options' => ['class' => 'form-group']])->textInput([
                                        'type' => 'tel',
                                        'maxlength' => true,
                                        'class' => 'field__input field--mask form-control mask-phone',
                                        'placeholder' => Yii::t('policy', '+998XX-XXX-XX-XX',
                                            ['label' => $label]),
                                    ])->label($label, ['class' => 'control-label main-form-label']) ?>
                                </div>
                            </div>

                            <?php if ($model->is_family): ?>

                                <?php DynamicFormWidget::begin([
                                    'widgetContainer' => 'dynamicform_wrapper_parent', // required: only alphanumeric characters plus "_" [A-Za-z0-9_]
                                    'widgetBody' => '.container-items-parent', // required: css class selector
                                    'widgetItem' => '.item-parent', // required: css class
                                    'limit' => PolicyTravel::TRAVELLER_PARENT_LIMIT, // the maximum times, an element can be cloned (default 999)
                                    'min' => 1, // 0 or 1 (default 1)
                                    'insertButton' => '.add-item-parent', // css class
                                    'deleteButton' => '.remove-item-parent', // css class
                                    'model' => $modelParentTravellers[0],
                                    'formId' => 'policy-travel-form',
                                    'formFields' => [
                                        'birthday',
                                        'pass_sery',
                                        'pass_num',
                                        'first_name',
                                        'surname',
                                        'pinfl',
                                    ],
                                ]); ?>

                                <div class="container-items-parent"><!-- widgetContainer -->
                                    <?php foreach ($modelParentTravellers as $i => $modelItem): ?>
                                        <div class="item-parent panel panel-default mt-2"><!-- widgetBody -->
                                            <div class="panel-heading p-relative">
                                                <h3 class="panel-title pull-left panel-title-birthday contact-us-page__label fw-bold fs-4"><?= Yii::t('policy', 'Parent <span class="index">{0}</span>', [$i + 1]) ?></h3>
                                                <div class="pull-right page-footer-button">
                                                    <button type="button"
                                                            class="remove-item-parent btn btn-danger btn-xs w-42">-
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
                                                echo Html::activeHiddenInput($modelItem, "[{$i}]pinfl");
                                                ?>
                                                <div class="row mb-2">
                                                    <div class="col-xl-3 col-lg-6 col-md-3 col-sm-5 col-12">
                                                        <?php $label = $modelItem->getAttributeLabel('birthday'); ?>
                                                        <?= $form->field($modelItem, "[{$i}]birthday", ['options' => ['class' => 'form-group']])
                                                            ->textInput(['type' => 'text',
                                                                'maxlength' => 10,
                                                                'autocomplete' => 'off',
                                                                'data-param' => 'travelparent',
                                                                'class' => 'field__input form-control mask-birthday traveller-birthday on-change-parent on-change-traveller-info dd-with',
                                                                'placeholder' => Yii::t('policy', 'dd.mm.yyyy')
                                                            ])->label($label, ['class' => 'control-label main-form-label']) ?>
                                                    </div>
                                                    <div class="col-xl-5 col-lg-6 col-md-5 col-sm-7 col-12 series-two-things">
                                                        <?php $label = Yii::t('policy', 'Red passport sery number'); ?>
                                                        <?= $form->field($modelItem, "[{$i}]pass_sery", ['options' => ['class' => 'form-group']])->textInput([
                                                            'type' => 'text',
                                                            'maxlength' => 2,
                                                            'autocomplete' => 'off',
                                                            'data-param' => 'travelparent',
                                                            'class' => 'field__input form-control on-change-traveller-info',
                                                            'oninput' => 'this.value = this.value.toUpperCase()',
                                                            'placeholder' => Yii::t('policy', 'FA'),
                                                        ])->label($label, ['class' => 'control-label d-block w-100 overflow-visible visible ws-nowrap main-form-label']) ?>
                                                    </div>
                                                    <div class="col-xl-3 col-lg-10 col-md-3 col-sm-10 col-12 series-num-middle">
                                                        <!--series-two-things-right-auto"-->
                                                        <?php $label = $modelItem->getAttributeLabel('pass_num'); ?>
                                                        <?= $form->field($modelItem, "[{$i}]pass_num", ['options' => ['class' => 'form-group']])->textInput([
                                                            'maxlength' => 7,
                                                            'data-param' => 'travelparent',
                                                            'class' => 'field__input form-control only_number on-change-traveller-info',
                                                            'placeholder' => Yii::t('policy', '0000001'),
                                                        ])->label($label, ['class' => 'control-label invisible main-form-label']) ?>
                                                    </div>
                                                    <div class="col-xl-1 col-lg-2 col-md-1 col-sm-2 col-12 btn-series">
                                                        <label class="invisible main-form-label">hide</label>
                                                        <button type="button"
                                                                class="check-traveller check-button btn anketa__check d-flex ms-auto mt-0 justify-content-center align-items-center bg-primary text-capitalize <?= ($i == 0 || !$model->isNewRecord) ? 'check-travelparent-index-' . $i : '' ?> btn <?= ($modelItem->isNewRecord) ? 'check' : 'clear' ?>"
                                                                data-index="<?= $i ?>"
                                                                data-param="travelparent">
                                                            <svg width="20" height="20" viewBox="0 0 20 20"
                                                                 fill="none" xmlns="http://www.w3.org/2000/svg"
                                                                 class="check-icon">
                                                                <path fill-rule="evenodd" clip-rule="evenodd"
                                                                      d="M3.33366 9.16675C3.33366 5.94509 5.94533 3.33341 9.16699 3.33341C12.3887 3.33341 15.0003 5.94509 15.0003 9.16675C15.0003 10.7384 14.3788 12.1648 13.3682 13.2137C13.3396 13.2357 13.3122 13.2597 13.2861 13.2858C13.2599 13.312 13.2359 13.3394 13.214 13.3679C12.1651 14.3786 10.7386 15.0001 9.16699 15.0001C5.94533 15.0001 3.33366 12.3884 3.33366 9.16675ZM13.8484 15.0267C12.5653 16.053 10.9378 16.6667 9.16699 16.6667C5.02486 16.6667 1.66699 13.3089 1.66699 9.16675C1.66699 5.02461 5.02486 1.66675 9.16699 1.66675C13.3091 1.66675 16.667 5.02461 16.667 9.16675C16.667 10.9376 16.0533 12.5651 15.0269 13.8482L18.0896 16.9108C18.415 17.2363 18.415 17.7639 18.0896 18.0893C17.7641 18.4148 17.2365 18.4148 16.9111 18.0893L13.8484 15.0267Z"
                                                                      fill="white"/>
                                                            </svg>
                                                            <svg width="20" height="20" version="1.1"
                                                                 class="clear-icon "
                                                                 xmlns="http://www.w3.org/2000/svg"
                                                                 xmlns:xlink="http://www.w3.org/1999/xlink"
                                                                 x="0px"
                                                                 y="0px"
                                                                 viewBox="0 0 460.775 460.775"
                                                                 style="enable-background:new 0 0 460.775 460.775;"
                                                                 xml:space="preserve">
                                                                        <path fill="white" d="M285.08,230.397L456.218,59.27c6.076-6.077,6.076-15.911,0-21.986L423.511,4.565c-2.913-2.911-6.866-4.55-10.992-4.55
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
                                                <div class="row custom-row--sm">
                                                    <div class="col-sm-6 col-12 field">
                                                        <?php $label = $modelItem->getAttributeLabel('first_name'); ?>
                                                        <?= $form->field($modelItem, "[{$i}]first_name", ['options' => ['class' => 'form-group']])->textInput([
                                                            'maxlength' => true,
                                                            'readonly' => false,
                                                            'class' => 'field__input form-control latin_letters_no_number',
                                                            'oninput' => 'this.value = this.value.toUpperCase()',
                                                            'placeholder' => Yii::t('policy', '{label}ни киритинг',
                                                                ['label' => $label]),
                                                        ])->label($label, ['class' => 'control-label main-form-label']) ?>
                                                    </div>
                                                    <div class="col-sm-6 col-12 field">
                                                        <?php $label = $modelItem->getAttributeLabel('surname'); ?>
                                                        <?= $form->field($modelItem, "[{$i}]surname", ['options' => ['class' => 'form-group']])->textInput([
                                                            'maxlength' => true,
                                                            'readonly' => false,
                                                            'class' => 'field__input form-control latin_letters_no_number',
                                                            'oninput' => 'this.value = this.value.toUpperCase()',
                                                            'placeholder' => Yii::t('policy', '{label}ни киритинг',
                                                                ['label' => $label]),
                                                        ])->label($label, ['class' => 'control-label main-form-label']) ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                                <div class="pull-right-page-footer-button">
                                    <button type="button"
                                            class="add-item-parent <?= (count($modelParentTravellers) < 2) ? '' : 'd-none' ?> btn btn-primary btn-xs s-custom-btn s-custom-primary s-custom-btn--icon my-4"><?= Yii::t('policy', 'Add parent') ?></button>
                                </div>
                                <?php DynamicFormWidget::end(); ?>

                                <?php DynamicFormWidget::begin([
                                    'widgetContainer' => 'dynamicform_wrapper_child', // required: only alphanumeric characters plus "_" [A-Za-z0-9_]
                                    'widgetBody' => '.container-items-child', // required: css class selector
                                    'widgetItem' => '.item-child', // required: css class
                                    'limit' => (PolicyTravel::TRAVELLER_LIMIT - count($modelParentTravellers)), // the maximum times, an element can be cloned (default 999)
                                    'min' => 1, // 0 or 1 (default 1)
                                    'insertButton' => '.add-item-child', // css class
                                    'deleteButton' => '.remove-item-child', // css class
                                    'model' => $modelTravellers[0],
                                    'formId' => 'policy-travel-form',
                                    'formFields' => [
                                        'birthday',
                                        'pass_sery',
                                        'pass_num',
                                        'first_name',
                                        'surname',
                                        'pinfl',
                                    ],
                                ]); ?>

                                <div class="container-items-child"><!-- widgetContainer -->
                                    <?php foreach ($modelTravellers as $i => $modelItem): ?>
                                        <div class="item-child panel panel-default mt-2"><!-- widgetBody -->
                                            <div class="panel-heading p-relative">
                                                <h3 class="panel-title pull-left panel-title-birthday contact-us-page__label fw-bold fs-4"><?= Yii::t('policy', 'Child <span class="index">{0}</span>', [$i + 1]) ?></h3>
                                                <div class="pull-right page-footer-button">
                                                    <button type="button"
                                                            class="remove-item-child btn btn-danger btn-xs w-42">-
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
                                                echo Html::activeHiddenInput($modelItem, "[{$i}]pinfl");
                                                ?>
                                                <div class="row mb-2">
                                                    <div class="col-xl-3 col-lg-6 col-md-3 col-sm-5 col-12">
                                                        <?php $label = $modelItem->getAttributeLabel('birthday'); ?>
                                                        <?= $form->field($modelItem, "[{$i}]birthday", ['options' => ['class' => 'form-group']])
                                                            ->textInput(['type' => 'text',
                                                                'maxlength' => 10,
                                                                'autocomplete' => 'off',
                                                                'data-param' => 'travel',
                                                                'class' => 'field__input form-control mask-birthday traveller-birthday on-change-traveller-info dd-with',
                                                                'placeholder' => Yii::t('policy', 'dd.mm.yyyy')
                                                            ])->label($label, ['class' => 'control-label main-form-label']) ?>
                                                    </div>
                                                    <div class="col-xl-5 col-lg-6 col-md-5 col-sm-7 col-12 series-two-things">
                                                        <?php $label = Yii::t('policy', 'Red passport sery number'); ?>
                                                        <?= $form->field($modelItem, "[{$i}]pass_sery", ['options' => ['class' => 'form-group']])->textInput([
                                                            'type' => 'text',
                                                            'maxlength' => 2,
                                                            'autocomplete' => 'off',
                                                            'data-param' => 'travel',
                                                            'class' => 'field__input form-control on-change-traveller-info',
                                                            'oninput' => 'this.value = this.value.toUpperCase()',
                                                            'placeholder' => Yii::t('policy', 'FA'),
                                                        ])->label($label, ['class' => 'control-label d-block w-100 overflow-visible visible ws-nowrap main-form-label']) ?>
                                                    </div>
                                                    <div class="col-xl-3 col-lg-10 col-md-3 col-sm-10 col-12 series-num-middle">
                                                        <!--series-two-things-right-auto"-->
                                                        <?php $label = $modelItem->getAttributeLabel('pass_num'); ?>
                                                        <?= $form->field($modelItem, "[{$i}]pass_num", ['options' => ['class' => 'form-group']])->textInput([
                                                            'maxlength' => 7,
                                                            'data-param' => 'travel',
                                                            'class' => 'field__input form-control only_number on-change-traveller-info',
                                                            'placeholder' => Yii::t('policy', '0000001'),
                                                        ])->label($label, ['class' => 'control-label invisible main-form-label']) ?>
                                                    </div>
                                                    <div class="col-xl-1 col-lg-2 col-md-1 col-sm-2 col-12 btn-series">
                                                        <label class="invisible main-form-label">hide</label>
                                                        <button type="button"
                                                                class="check-traveller check-button btn anketa__check d-flex ms-auto mt-0 bg-primary justify-content-center align-items-center text-capitalize <?= ($i == 0 || !$model->isNewRecord) ? 'check-travel-index-' . $i : '' ?>  btn <?= ($modelItem->isNewRecord) ? 'check' : 'clear' ?>"
                                                                data-index="<?= $i ?>" data-param="travel">
                                                            <svg width="20" height="20" viewBox="0 0 20 20"
                                                                 fill="none" xmlns="http://www.w3.org/2000/svg"
                                                                 class="check-icon">
                                                                <path fill-rule="evenodd" clip-rule="evenodd"
                                                                      d="M3.33366 9.16675C3.33366 5.94509 5.94533 3.33341 9.16699 3.33341C12.3887 3.33341 15.0003 5.94509 15.0003 9.16675C15.0003 10.7384 14.3788 12.1648 13.3682 13.2137C13.3396 13.2357 13.3122 13.2597 13.2861 13.2858C13.2599 13.312 13.2359 13.3394 13.214 13.3679C12.1651 14.3786 10.7386 15.0001 9.16699 15.0001C5.94533 15.0001 3.33366 12.3884 3.33366 9.16675ZM13.8484 15.0267C12.5653 16.053 10.9378 16.6667 9.16699 16.6667C5.02486 16.6667 1.66699 13.3089 1.66699 9.16675C1.66699 5.02461 5.02486 1.66675 9.16699 1.66675C13.3091 1.66675 16.667 5.02461 16.667 9.16675C16.667 10.9376 16.0533 12.5651 15.0269 13.8482L18.0896 16.9108C18.415 17.2363 18.415 17.7639 18.0896 18.0893C17.7641 18.4148 17.2365 18.4148 16.9111 18.0893L13.8484 15.0267Z"
                                                                      fill="white"/>
                                                            </svg>
                                                            <svg width="20" height="20" version="1.1"
                                                                 class="clear-icon "
                                                                 xmlns="http://www.w3.org/2000/svg"
                                                                 xmlns:xlink="http://www.w3.org/1999/xlink"
                                                                 x="0px"
                                                                 y="0px"
                                                                 viewBox="0 0 460.775 460.775"
                                                                 style="enable-background:new 0 0 460.775 460.775;"
                                                                 xml:space="preserve">
                                                                        <path fill="white" d="M285.08,230.397L456.218,59.27c6.076-6.077,6.076-15.911,0-21.986L423.511,4.565c-2.913-2.911-6.866-4.55-10.992-4.55
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
                                                <div class="row custom-row--sm">
                                                    <div class="col-sm-6 col-12 field">
                                                        <?php $label = $modelItem->getAttributeLabel('first_name'); ?>
                                                        <?= $form->field($modelItem, "[{$i}]first_name", ['options' => ['class' => 'form-group']])->textInput([
                                                            'maxlength' => true,
                                                            'readonly' => false,
                                                            'class' => 'field__input form-control latin_letters_no_number',
                                                            'oninput' => 'this.value = this.value.toUpperCase()',
                                                            'placeholder' => Yii::t('policy', '{label}ни киритинг',
                                                                ['label' => $label]),
                                                        ])->label($label, ['class' => 'control-label main-form-label']) ?>
                                                    </div>
                                                    <div class="col-sm-6 col-12 field">
                                                        <?php $label = $modelItem->getAttributeLabel('surname'); ?>
                                                        <?= $form->field($modelItem, "[{$i}]surname", ['options' => ['class' => 'form-group']])->textInput([
                                                            'maxlength' => true,
                                                            'readonly' => false,
                                                            'class' => 'field__input form-control latin_letters_no_number',
                                                            'oninput' => 'this.value = this.value.toUpperCase()',
                                                            'placeholder' => Yii::t('policy', '{label}ни киритинг',
                                                                ['label' => $label]),
                                                        ])->label($label, ['class' => 'control-label main-form-label']) ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                                <div class="pull-right-page-footer-button">
                                    <button type="button"
                                            class="add-item-child <?= (count($modelParentTravellers) + count($modelTravellers) >= 6) ? 'd-none' : '' ?> btn btn-primary btn-xs s-custom-btn s-custom-primary s-custom-btn--icon my-4"><?= Yii::t('policy', 'Add child') ?></button>
                                </div>
                                <?php DynamicFormWidget::end(); ?>
                            <?php else: ?>

                                <?php if (!$model->is_family): ?>
                                    <div class="row custom-row--sm">
                                        <div class="col-12 field">
                                            <?php
                                            $label = $model->getAttributeLabel('also_traveller');
                                            $template = '{input}{error}{hint}';
                                            echo $form->field($model, 'also_traveller', [
                                                'template' => $template
                                            ])->checkbox([
                                                'class' => 'on-change-insurer fs-3 primary-accent-color',
                                            ])->label(false);
                                            ?>
                                        </div>
                                    </div>
                                <?php endif; ?>

                                <hr class="hr-primary my-3">

                                <?php DynamicFormWidget::begin([
                                    'widgetContainer' => 'dynamicform_wrapper', // required: only alphanumeric characters plus "_" [A-Za-z0-9_]
                                    'widgetBody' => '.container-items', // required: css class selector
                                    'widgetItem' => '.item', // required: css class
                                    'limit' => PolicyTravel::TRAVELLER_LIMIT, // the maximum times, an element can be cloned (default 999)
                                    'min' => 1, // 0 or 1 (default 1)
                                    'insertButton' => '.add-item', // css class
                                    'deleteButton' => '.remove-item', // css class
                                    'model' => $modelTravellers[0],
                                    'formId' => 'policy-travel-form',
                                    'formFields' => [
                                        'birthday',
                                        'pass_sery',
                                        'pass_num',
                                        'first_name',
                                        'surname',
                                        'pinfl',
                                    ],
                                ]); ?>

                                <div class="container-items d-flex flex-column row-gap-3"><!-- widgetContainer -->
                                    <?php foreach ($modelTravellers as $i => $modelItem): ?>
                                        <div class="item panel panel-default"><!-- widgetBody -->
                                            <div class="panel-heading p-relative my-2">
                                                <h3 class="panel-title pull-left panel-title-birthday contact-us-page__label fw-bold fs-4"><?= Yii::t('policy', 'Traveller <span class="index">{0}</span>', [$i + 1]) ?></h3>
                                                <div class="pull-right page-footer-button">
                                                    <button type="button" class="remove-item btn btn-danger btn-xs">
                                                        <i class='bx bxs-user-x bx-custom-sm'></i>
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
                                                echo Html::activeHiddenInput($modelItem, "[{$i}]pinfl");
                                                ?>
                                                <div class="row mb-2">
                                                    <div class="col-xl-3 col-lg-6 col-md-3 col-sm-5 col-12">
                                                        <?php $label = $modelItem->getAttributeLabel('birthday'); ?>
                                                        <?= $form->field($modelItem, "[{$i}]birthday", ['options' => ['class' => 'form-group']])
                                                            ->textInput(['type' => 'text',
                                                                'maxlength' => 10,
                                                                'autocomplete' => 'off',
                                                                'data-param' => 'travel',
                                                                'class' => 'field__input form-control mask-birthday traveller-birthday on-change-traveller-info dd-with',
                                                                'placeholder' => Yii::t('policy', 'dd.mm.yyyy')
                                                            ])->label($label, ['class' => 'control-label main-form-label']) ?>
                                                    </div>
                                                    <div class="col-xl-5 col-lg-6 col-md-5 col-sm-7 col-12 series-two-things">
                                                        <?php $label = Yii::t('policy', 'Red passport sery number'); ?>
                                                        <?= $form->field($modelItem, "[{$i}]pass_sery", ['options' => ['class' => 'form-group']])->textInput([
                                                            'type' => 'text',
                                                            'maxlength' => 2,
                                                            'autocomplete' => 'off',
                                                            'data-param' => 'travel',
                                                            'class' => 'field__input form-control on-change-traveller-info',
                                                            'oninput' => 'this.value = this.value.toUpperCase()',
                                                            'placeholder' => Yii::t('policy', 'FA'),
                                                        ])->label($label, ['class' => 'control-label d-block w-100 overflow-visible visible ws-nowrap main-form-label']) ?>
                                                    </div>
                                                    <div class="col-xl-3 col-lg-10 col-md-3 col-sm-10 col-12 series-num-middle">
                                                        <!--series-two-things-right-auto"-->
                                                        <?php $label = $modelItem->getAttributeLabel('pass_num'); ?>
                                                        <?= $form->field($modelItem, "[{$i}]pass_num", ['options' => ['class' => 'form-group']])->textInput([
                                                            'maxlength' => 7,
                                                            'data-param' => 'travel',
                                                            'class' => 'field__input form-control only_number on-change-traveller-info',
                                                            'placeholder' => Yii::t('policy', '0000001'),
                                                        ])->label($label, ['class' => 'control-label invisible main-form-label']) ?>
                                                    </div>
                                                    <div class="col-xl-1 col-lg-2 col-md-1 col-sm-2 col-12 btn-series">
                                                        <label class="invisible main-form-label">hide</label>
                                                        <button type="button"
                                                                class="check-traveller check-button btn anketa__check d-flex ms-auto justify-content-center align-items-center bg-primary mt-0 s-custom-btn--icon text-capitalize <?= ($i == 0 || !$model->isNewRecord) ? 'check-travel-index-' . $i : '' ?>  btn <?= ($modelItem->isNewRecord) ? 'check' : 'clear' ?>"
                                                                data-index="<?= $i ?>" data-param="travel">
                                                            <svg width="20" height="20" viewBox="0 0 20 20"
                                                                 fill="none" xmlns="http://www.w3.org/2000/svg"
                                                                 class="check-icon">
                                                                <path fill-rule="evenodd" clip-rule="evenodd"
                                                                      d="M3.33366 9.16675C3.33366 5.94509 5.94533 3.33341 9.16699 3.33341C12.3887 3.33341 15.0003 5.94509 15.0003 9.16675C15.0003 10.7384 14.3788 12.1648 13.3682 13.2137C13.3396 13.2357 13.3122 13.2597 13.2861 13.2858C13.2599 13.312 13.2359 13.3394 13.214 13.3679C12.1651 14.3786 10.7386 15.0001 9.16699 15.0001C5.94533 15.0001 3.33366 12.3884 3.33366 9.16675ZM13.8484 15.0267C12.5653 16.053 10.9378 16.6667 9.16699 16.6667C5.02486 16.6667 1.66699 13.3089 1.66699 9.16675C1.66699 5.02461 5.02486 1.66675 9.16699 1.66675C13.3091 1.66675 16.667 5.02461 16.667 9.16675C16.667 10.9376 16.0533 12.5651 15.0269 13.8482L18.0896 16.9108C18.415 17.2363 18.415 17.7639 18.0896 18.0893C17.7641 18.4148 17.2365 18.4148 16.9111 18.0893L13.8484 15.0267Z"
                                                                      fill="white"/>
                                                            </svg>
                                                            <svg width="20" height="20" version="1.1"
                                                                 class="clear-icon "
                                                                 xmlns="http://www.w3.org/2000/svg"
                                                                 xmlns:xlink="http://www.w3.org/1999/xlink"
                                                                 x="0px"
                                                                 y="0px"
                                                                 viewBox="0 0 460.775 460.775"
                                                                 style="enable-background:new 0 0 460.775 460.775;"
                                                                 xml:space="preserve">
                                                                        <path fill="white" d="M285.08,230.397L456.218,59.27c6.076-6.077,6.076-15.911,0-21.986L423.511,4.565c-2.913-2.911-6.866-4.55-10.992-4.55
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
                                                <div class="row">
                                                    <div class="col-sm-6 col-12 field">
                                                        <?php $label = $modelItem->getAttributeLabel('first_name'); ?>
                                                        <?= $form->field($modelItem, "[{$i}]first_name", ['options' => ['class' => 'form-group']])->textInput([
                                                            'maxlength' => true,
                                                            'class' => 'field__input form-control latin_letters_no_number',
                                                            'oninput' => 'this.value = this.value.toUpperCase()',
                                                            'placeholder' => Yii::t('policy', '{label}ни киритинг',
                                                                ['label' => $label]),
                                                        ])->label($label, ['class' => 'control-label main-form-label']) ?>
                                                    </div>
                                                    <div class="col-sm-6 col-12 field">
                                                        <?php $label = $modelItem->getAttributeLabel('surname'); ?>
                                                        <?= $form->field($modelItem, "[{$i}]surname", ['options' => ['class' => 'form-group']])->textInput([
                                                            'maxlength' => true,
                                                            'class' => 'field__input form-control latin_letters_no_number',
                                                            'oninput' => 'this.value = this.value.toUpperCase()',
                                                            'placeholder' => Yii::t('policy', '{label}ни киритинг',
                                                                ['label' => $label]),
                                                        ])->label($label, ['class' => 'control-label main-form-label']) ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                                <div class="pull-right-page-footer-button">
                                    <button type="button"
                                            class="add-item <?= (count($modelTravellers) >= 6) ? 'd-none' : '' ?> btn btn-primary btn-xs s-custom-btn s-custom-primary s-custom-btn--icon my-3"><?= Yii::t('policy', 'Add traveller') ?></button>
                                </div>
                                <?php DynamicFormWidget::end(); ?>

                            <?php endif; ?>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <div class="col-lg-4 col-12">
            <div class="calculator-card bg-light rounded-3 border rounded-end rounded-bottom py-3 px-4 box-shadow-primary position-sticky">
                <div class="overlay-right" style="display: none;">
                    <div class="spinner"></div>
                </div>
                <div class="main-title-sm text-uppercase fw-bold text-black"><?= Yii::t('policy', 'Ҳисоблаш натижалари'); ?></div>

                <div class="contact-us-page__info">

                    <?php Pjax::begin(['id' => 'pjax_policy_travel_calc_result', 'enablePushState' => false,]); ?>

                    <?php if (!empty($model->_tmp_message)) : ?>
                        <div class="alert-alert-warning my-2">
                            <?= $model->_tmp_message ?>
                        </div>
                    <?php endif; ?>
                    <div class="calculator-card__item policy_price-block">
                        <div class="calculator-card__label">
                            <?= Yii::t('policy', 'Полис нархи'); ?>
                        </div>
                        <div class="calculator-card__value">
                            <span id="policy_price"
                                  class="text-bold"><?= number_format($model->_policy_price_uzs, 2, '.', ' ') ?></span>
                            <span class="currency text-bold"><?= Yii::t('policy', 'UZS'); ?></span>
                        </div>
                        <div class="calculator-card__value small">
                            <span id="policy_price"
                                  class="text-bold"><small><?= number_format($model->_policy_price_usd, 2, '.', ' ') ?></small></span>
                            <span class="currency text-bold"><small><?= Yii::t('policy', 'USD'); ?></small></span>

                        </div>
                    </div>

                    <div class="calculator-card__item policy_price-block">
                        <div class="calculator-card__label">
                            <?= Yii::t('policy', 'Суғурта суммаси'); ?>
                        </div>
                        <div class="calculator-card__value">
                            <span id="policy_gift_price"
                                  class="text-bold"><?= number_format($model->_ins_amount, 2, '.', ' ') ?></span>
                            <span class="currency text-bold"><?= Yii::t('policy', 'EUR'); ?></span>

                        </div>
                    </div>

                    <?php Pjax::end(); ?>
                </div>
            </div>
        </div>
    </div>

    <div class="flex-row">
        <div class="contact-us-page__left fs-4">

            <div class="field modal__field">
                <div class="page-footer-button fs-5">
                    <?php
                    $label = $model->getAttributeLabel('offer');
                    $lang = _lang();
                    $offer_link = Url::to(['/rule/tr']);
                    $offer_label = Yii::t('policy', '«Пользовательского соглашения»');
                    $offer_label = '<a href="' . $offer_link . '" data-pjax="0" class="offer primary-color-text" type="button" data-toggle="" data-target="#offerModal" target="_blank"><i>' . $offer_label . '</i></a>';
                    $label_complate = Yii::t('policy', '{label} {link}', ['label' => $label, 'link' => $offer_label]);
                    $template = '{input} {error}{hint}';
                    echo $form->field($model, 'offer', [
                        'template' => $template
                    ])->checkbox([
                        'label' => $label_complate,
                        'class' => 'primary-accent-color offer-check'
                    ])->label($label_complate);
                    ?>

                </div>
            </div>

            <div class="field modal__field">
                <div class="page-footer-button">
                    <div class="form-group">
                        <input type="hidden" id="submitInput" name="submit" value="0">
                        <?= Html::submitButton(Yii::t('policy', 'Apply'), ['class' => 'btn btn-primary btn-xs s-custom-btn s-custom-primary s-custom-btn--icon my-4 btn btn-primary submitForm', 'data-pjax' => false, 'disabled' => true]) ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="contact-us-page__right right-calc-block ml-2 fixed-sidebar">
        </div>
        <input type="hidden" id="url_post_pass_birthday"
               value="<?= Url::to(['/policy/travel/get-pass-birthday-data']) ?>">
        <input type="hidden" id="url_post_pass_pinfl" value="<?= Url::to(['/policy/travel/get-pass-pinfl-data']) ?>">
    </div>


<?php ActiveForm::end(); ?>
<?php
$labelBirthdayParent = Yii::t('policy', 'Parent');
$labelBirthdayChild = Yii::t('policy', 'Child');
$labelBirthday = Yii::t('policy', 'Traveller');
$jsPjax = <<<JS

    $(function() {
        
    let isLoading = false;

    let url_post_pass_birthday = jQuery('#url_post_pass_birthday').val();

        // $.pjax.defaults.scrollTo = false;
        
        function refreshCalc() {
            
            let validation_field_1 = $('#policytravel-_travelcountries')
            let validation_field_2 = $('#policytravel-start_date')
        
            if (!isLoading) {
                if (validation_field_1.val() || validation_field_2.val()) {
                    overlay.show();
                    isLoading = true;
                    $.pjax.reload({
                        container: '#pjax_policy_travel_calc',
                        type: 'POST',
                        data: $('form[data-pjax]').serialize() + '&button=1'
                    });
                    
                }
            }
            if ($('#policy-travel-form').yiiActiveForm('validate',true)) {
                $('.submitForm').removeAttr('disabled');
            } else {
                $('.submitForm').attr('disabled','disabled');
            }
        }
        
        jQuery(document).on('pjax:complete', '#pjax_policy_travel_calc', function () {
            overlay.hide();
            $('#submitInput').val(0);
            isLoading = false
        });
        
      var _traveller_birthday = $('.traveller-birthday');
      var _traveller_birthday_parent = $('.traveller-birthday-parent');
      var _insurer_birthday = $('#policytravel-app_birthday');
      
      var parent_count = 0;
      
        var currentDate = new Date();
        eighteenYearsAgo = currentDate.setFullYear(currentDate.getFullYear()-18);
        hundredYearsAgo = currentDate.setFullYear(currentDate.getFullYear()-100);
      
          _insurer_birthday.datepicker({
            autoHide: true,
            endDate: eighteenYearsAgo,
            maxDate: eighteenYearsAgo,
            format: 'dd.mm.yyyy'
          });
      
        // Parent
        jQuery(".dynamicform_wrapper_parent").on("afterInsert", function(e, item) {
            jQuery(".dynamicform_wrapper_parent .panel-title-birthday").each(function(index) {
                jQuery(this).html("$labelBirthdayParent " + (index + 1))
            });
            
            jQuery(".dynamicform_wrapper_parent .item-parent").each(function(index) {
                jQuery(this).addClass("parent-index-" + (index))
                jQuery(this).find('button.check-traveller').addClass("check-travelparent-index-" + (index)).data('index',index);
                parent_count = index;
            });
            
            if ((parent_count) >= 1) {
                jQuery('.add-item-parent').addClass("d-none");
            } else {
                jQuery('.add-item-parent').removeClass("d-none");
            }

              $('.traveller-birthday').datepicker({
                autoHide: true,
                endDate: new Date(new Date().setDate(new Date().getDate())),
                maxDate: new Date(new Date().setDate(new Date().getDate())),
                format: 'dd.mm.yyyy'
              });
      
              $('.traveller-birthday-parent').datepicker({
                autoHide: true,
                endDate: eighteenYearsAgo,
                maxDate: eighteenYearsAgo,
                format: 'dd.mm.yyyy'
              });
              
            $('.mask-birthday').mask('00.00.0000');
            $('.mask-date').mask('00.00.0000');
      
        });
        
        jQuery(".dynamicform_wrapper_parent").on("afterDelete", function(e) {
            jQuery(".dynamicform_wrapper_parent .panel-title-birthday").each(function(index) {
                jQuery(this).html("$labelBirthdayParent " + (index + 1))
            });
            
            jQuery(".dynamicform_wrapper_parent .item-parent").each(function(index) {
                jQuery(this).addClass("parent-index-" + (index))
                jQuery(this).find('button.check-traveller').addClass("check-travelparent-index-" + (index)).data('index',index);
                parent_count = index;
            });
            
            if ((parent_count) >= 1) {
                jQuery('.add-item-parent').addClass("d-none");
            } else {
                jQuery('.add-item-parent').removeClass("d-none");
            }

            $('.traveller-birthday').datepicker({
                autoHide: true,
                endDate: new Date(new Date().setDate(new Date().getDate())),
                maxDate: new Date(new Date().setDate(new Date().getDate())),
                format: 'dd.mm.yyyy'
              });
              $('.traveller-birthday-parent').datepicker({
                autoHide: true,
                endDate: eighteenYearsAgo,
                maxDate: eighteenYearsAgo,
                format: 'dd.mm.yyyy'
              });
      
            $('.mask-birthday').mask('00.00.0000');
            $('.mask-date').mask('00.00.0000');
        });
        
        
        // Child
        jQuery(".dynamicform_wrapper_child").on("afterInsert", function(e, item) {
            jQuery(".dynamicform_wrapper_child .panel-title-birthday").each(function(index) {
                jQuery(this).html("$labelBirthdayChild " + (index + 1))
            });
            
            let last_index = 0;
            jQuery(".dynamicform_wrapper_child .item-child").each(function(index) {
                jQuery(this).addClass("child-index-" + (index))
                jQuery(this).find('button.check-traveller').addClass("check-travel-index-" + (index)).data('index',index);
                last_index = index;
            });
            
            if ((parent_count+last_index) >= 4) {
                jQuery('.add-item-child').addClass("d-none");
            } else {
                jQuery('.add-item-child').removeClass("d-none");
            }
            
              $('.traveller-birthday').datepicker({
                autoHide: true,
                endDate: new Date(new Date().setDate(new Date().getDate())),
                maxDate: new Date(new Date().setDate(new Date().getDate())),
                format: 'dd.mm.yyyy'
              });
              $('.traveller-birthday-parent').datepicker({
                autoHide: true,
                endDate: eighteenYearsAgo,
                maxDate: eighteenYearsAgo,
                format: 'dd.mm.yyyy'
              });
      
            $('.mask-birthday').mask('00.00.0000');
            $('.mask-date').mask('00.00.0000');
        });
        
        jQuery(".dynamicform_wrapper_child").on("afterDelete", function(e) {
            jQuery(".dynamicform_wrapper_child .panel-title-birthday").each(function(index) {
                jQuery(this).html("$labelBirthdayChild " + (index + 1))
            });
            
            let last_index = 0;
            jQuery(".dynamicform_wrapper_child .item-child").each(function(index) {
                jQuery(this).addClass("child-index-" + (index))
                jQuery(this).find('button.check-traveller').addClass("check-travel-index-" + (index)).data('index',index);
                last_index = index;
            });
            
            if ((parent_count+last_index) >= 4) {
                jQuery('.add-item-child').addClass("d-none");
            } else {
                jQuery('.add-item-child').removeClass("d-none");
            }
            
              $('.traveller-birthday').datepicker({
                autoHide: true,
                endDate: new Date(new Date().setDate(new Date().getDate())),
                maxDate: new Date(new Date().setDate(new Date().getDate())),
                format: 'dd.mm.yyyy'
              });
              $('.traveller-birthday-parent').datepicker({
                autoHide: true,
                endDate: eighteenYearsAgo,
                maxDate: eighteenYearsAgo,
                format: 'dd.mm.yyyy'
              });
      
            $('.mask-birthday').mask('00.00.0000');
            $('.mask-date').mask('00.00.0000');
        });
        
        
        // Traveller
        jQuery(".dynamicform_wrapper").on("afterInsert", function(e, item) {
            jQuery(".dynamicform_wrapper .panel-title-birthday").each(function(index) {
                jQuery(this).html("$labelBirthday " + (index + 1))
            });
            
            let last_index = 0;
            jQuery(".dynamicform_wrapper .item").each(function(index) {
                jQuery(this).addClass("traveller-index-" + (index))
                jQuery(this).find('button.check-traveller').addClass("check-travel-index-" + (index)).data('index',index);
                last_index = index;
            });
            
            if (last_index >= 6) {
                jQuery('.add-item').addClass("d-none");
            } else {
                jQuery('.add-item').removeClass("d-none");
            }
            
              $('.traveller-birthday').datepicker({
                autoHide: true,
                endDate: new Date(new Date().setDate(new Date().getDate())),
                maxDate: new Date(new Date().setDate(new Date().getDate())),
                format: 'dd.mm.yyyy'
              });
              $('.traveller-birthday-parent').datepicker({
                autoHide: true,
                endDate: eighteenYearsAgo,
                maxDate: eighteenYearsAgo,
                format: 'dd.mm.yyyy'
              });
      
            $('.mask-birthday').mask('00.00.0000');
            $('.mask-date').mask('00.00.0000');
        });
        
        jQuery(".dynamicform_wrapper").on("afterDelete", function(e) {
            jQuery(".dynamicform_wrapper .panel-title-birthday").each(function(index) {
                jQuery(this).html("$labelBirthday " + (index + 1))
            });
            
            let last_index = 0;
            jQuery(".dynamicform_wrapper .item").each(function(index) {
                jQuery(this).addClass("traveller-index-" + (index))
                jQuery(this).find('button.check-traveller').addClass("check-travel-index-" + (index)).data('index',index);
                last_index = index;
            });
            
            if (last_index >= 6) {
                jQuery('.add-item').addClass("d-none");
            } else {
                jQuery('.add-item').removeClass("d-none");
            }
            
              $('.traveller-birthday').datepicker({
                autoHide: true,
                endDate: new Date(new Date().setDate(new Date().getDate())),
                maxDate: new Date(new Date().setDate(new Date().getDate())),
                format: 'dd.mm.yyyy'
              });
              $('.traveller-birthday-parent').datepicker({
                autoHide: true,
                endDate: eighteenYearsAgo,
                maxDate: eighteenYearsAgo,
                format: 'dd.mm.yyyy'
              });
      
            $('.mask-birthday').mask('00.00.0000');
            $('.mask-date').mask('00.00.0000');
        });
        
        
        function _getPassBirthdayData(birthday, pass_series, pass_number, driver_id = null, param = 'travel') {
        
                // return false if form still have some validation errors
                if ((pass_series && pass_number && birthday) && !isLoading)
                {
                    overlay.show();
                    isLoading = true;
                    // submit form
                    $.ajax({
                        url    : url_post_pass_birthday,
                        type   : 'post',
                        data   : {csrfParam: csrfToken, pass_series: pass_series, pass_number: pass_number, birthday: birthday, driver_id: driver_id},
                        success: function (response)
                        {
                            overlay.hide();
                            if (response.ERROR > 0) {
                                $.notify({
                                    // options
                                    // icon: 'bx bxs-info-circle',
                                    message: "<br>" + response.ERROR_MESSAGE,
        
                                },{
                                    // settings
                                    element: 'body',
                                    position: null,
                                    type: "danger",
                                    allow_dismiss: true,
                                    newest_on_top: false,
                                    showProgressbar: false,
                                    placement: {
                                        from: "top",
                                        align: "center"
                                    },
                                    offset: 20,
                                    spacing: 10,
                                    z_index: 1031,
                                    delay: 5000,
                                    timer: 1000,
                                    url_target: '_blank',
                                    mouse_over: null,
                                    animate: {
                                        enter: 'animated fadeInDown',
                                        exit: 'animated fadeOutRight'
                                    },
                                    onShow: null,
                                    onShown: null,
                                    onClose: null,
                                    onClosed: null,
                                    icon_type: 'class',
                                });
                                /*alert(response.ERROR_MESSAGE);*/
                            } else if ( (response.ERROR == 0) && response) {
                                data = response;
                                if (driver_id == null) {
        
                                    hideSearchBtn()
                                    if (data.ADDRESS) {
                                        $('#policytravel-app_phone').focus();
                                        $('#policytravel-app_address').val(data.ADDRESS).attr('readonly', true).parent('div').removeClass('has-error').find('.help-block').html('');
                                    } else {
                                        $('#policytravel-app_address').focus();
                                        $('#policytravel-app_address').removeAttr('readonly').parent('div').removeClass('has-error').find('.help-block').html('');
                                    }
                                    if (data.LAST_NAME) {
                                        $('#policytravel-app_surname').val(data.LAST_NAME).attr('readonly', true).parent('div').removeClass('has-error').find('.help-block').html('');
                                        $('#policytravel-app_name').val(data.FIRST_NAME).attr('readonly', true).parent('div').removeClass('has-error').find('.help-block').html('');
                                        $('#policytravel-app_pinfl').val(data.PINFL).attr('readonly', true).parent('div').removeClass('has-error').find('.help-block').html('');
                                        
                                        $('#policytravel-app_surname').trigger('change');
                                        $('#policytravel-app_name').trigger('change');
                                    } else {
                                        
                                        $('#policytravel-app_birthday').removeAttr('readonly');
                                        $('#policytravel-app_pass_sery').removeAttr('readonly');
                                        $('#policytravel-app_pass_num').removeAttr('readonly');
        
                                        $('#policytravel-app_name').focus();
                                    }
        
                                } else if (driver_id) {

                                    $("button.check-traveller.check-"+param+"-index-"+driver_id).removeClass('check').addClass('clear').addClass('bg-danger');
        
                                    if (data.LAST_NAME) {
                                        $('#policy'+param+'traveller-'+driver_id+'-surname').val(data.LAST_NAME).attr('readonly', true).parent('div').removeClass('has-error').find('.help-block').html('');
                                    } else {
                                        $('#policy'+param+'traveller-'+driver_id+'-surname').removeAttr('readonly').parent('div').removeClass('has-error').find('.help-block').html('');
                                    }
                                    if (data.FIRST_NAME) {
                                        $('#policy'+param+'traveller-'+driver_id+'-first_name').val(data.FIRST_NAME).attr('readonly', true).parent('div').removeClass('has-error').find('.help-block').html('');
                                    } else {
                                        $('#policy'+param+'traveller-'+driver_id+'-first_name').focus();
                                        $('#policy'+param+'traveller-'+driver_id+'-first_name').removeAttr('readonly').parent('div').removeClass('has-error').find('.help-block').html('');
                                    }
                                    if (data.PINFL) {
                                        $('#policy'+param+'traveller-'+driver_id+'-pinfl').val(data.PINFL).attr('readonly', true).parent('div').removeClass('has-error').find('.help-block').html('');
                                    } else {
                                        $('#policy'+param+'traveller-'+driver_id+'-pinfl').removeAttr('readonly').parent('div').removeClass('has-error').find('.help-block').html('');
                                    }
        
                                }
                            }
                            isLoading = false;
                        },
                        error  : function ()
                        {
                            overlay.hide();
                            console.log('internal server error');
                            isLoading = false;
                        },
                        complete: function() {
                            $('#policytravel-also_traveller').trigger('change');
                        }
                    });
                } else {
                    return false;
                }
        
            }

    // APPLICANT
    $(document).on('click', '#check-applicant', function(e) {
        if ($(this).hasClass('clear')) {
            $('#policytravel-app_birthday').val('').removeAttr('readonly').focus();
            $('#policytravel-app_pass_sery').val('').removeAttr('readonly');
            $('#policytravel-app_pass_num').val('').removeAttr('readonly').trigger('keyup');
            showSearchBtn()
        } else if ($(this).hasClass('check')) {
            $('#policytravel-app_birthday').datepicker('hide');
            $('#policytravel-app_pass_num').trigger('keyup');
        }
    })

    $(document).on('keyup', '#policytravel-app_birthday', function(e) {
        let maxLength = $(this).attr('maxlength');
        if ($(this).val().length >= maxLength) {
            $(this).trigger('change');
            $(this).datepicker('hide');
            $('#policytravel-app_pass_sery').focus();
        }
    })

    $(document).on('keyup', '#policytravel-app_pass_sery', function(e) {
        let maxLength = $(this).attr('maxlength');
        if ($(this).val().length >= maxLength) {
            $('#policytravel-app_pass_num').focus();
        }
    })

    $(document).off('keyup', '.on-change-app-info').on('keyup', '.on-change-app-info', function(e) {
        let app_birthday = $('#policytravel-app_birthday');
        let app_pass_sery = $('#policytravel-app_pass_sery');
        let app_pass_num = $('#policytravel-app_pass_num');

        let app_birthday_maxLength = app_birthday.attr('maxlength');
        let app_pass_sery_maxLength = app_pass_sery.attr('maxlength');
        let app_pass_num_maxLength = app_pass_num.attr('maxlength');
        if (app_birthday.val().length >= app_birthday_maxLength && app_pass_sery.val().length >= app_pass_sery_maxLength && app_pass_num.val().length >= app_pass_num_maxLength) {
//            $(this).trigger('change');
            _getPassBirthdayData(app_birthday.val(), app_pass_sery.val(), app_pass_num.val(), null);
        } else {
            $('#policytravel-app_surname').val('').removeAttr('readonly');
            $('#policytravel-app_name').val('').removeAttr('readonly');
            $('#policytravel-app_address').val('').removeAttr('readonly');
        }
    })

        
    // TRAVELLER
    $(document).on('click', 'button.check-traveller', function(e) {
        let driver_id = $(this).data('index') ? $(this).data('index') : 0;
        let param = $(this).data('param');
        if ($(this).hasClass('clear')) {
            $('#policy'+param+'traveller-'+driver_id+'-birthday').val('').removeAttr('readonly').focus();
            $('#policy'+param+'traveller-'+driver_id+'-pass_sery').val('').removeAttr('readonly');
            $('#policy'+param+'traveller-'+driver_id+'-pass_num').val('').removeAttr('readonly');
            $('#policy'+param+'traveller-'+driver_id+'-pinfl').val('').removeAttr('readonly');
            $('#policy'+param+'traveller-'+driver_id+'-first_name').val('').removeAttr('readonly');
            $('#policy'+param+'traveller-'+driver_id+'-surname').val('').removeAttr('readonly');
            $(this).addClass('check').removeClass('clear');
        } else if ($(this).hasClass('check')) {
            $('#policy'+param+'traveller-'+driver_id+'-pass_num').trigger('keyup');
        }
    })
    $(document).off('keyup', '.on-change-traveller-info').on('keyup', '.on-change-traveller-info', function(e) {
        let attr_id = $(this).attr('id');
        let param = $(this).data('param');
        const attr_id_ar = attr_id.split("-");
        let driver_id = (attr_id_ar[1]) ? attr_id_ar[1] : null;
        let param_name = (attr_id_ar[2]) ? attr_id_ar[2] : null;
        let maxLength = 0;
        if (param_name && driver_id) {
            switch (param_name) {
                case 'birthday' :
                    maxLength = $('#policy'+param+'traveller-'+driver_id+'-'+param_name).attr('maxlength');
                    if ($('#policy'+param+'traveller-'+driver_id+'-'+param_name).val().length >= maxLength) {
                        $(this).trigger('change');
                        $(this).datepicker('hide');
                        $('#policy'+param+'traveller-'+driver_id+'-pass_sery').focus();
                    }
                    break;
                case 'pass_sery' :
                    maxLength = $('#policy'+param+'traveller-'+driver_id+'-'+param_name).attr('maxlength');
                    if ($('#policy'+param+'traveller-'+driver_id+'-'+param_name).val().length >= maxLength) {
                        $('#policy'+param+'traveller-'+driver_id+'-pass_num').focus();
                    }
                    break;
            }
        }
        if (driver_id) {
            let birthday = $('#policy'+param+'traveller-'+driver_id+'-birthday');
            let pass_sery = $('#policy'+param+'traveller-'+driver_id+'-pass_sery');
            let pass_num = $('#policy'+param+'traveller-'+driver_id+'-pass_num');

            let birthday_maxLength = birthday.attr('maxlength');
            let pass_sery_maxLength = pass_sery.attr('maxlength');
            let pass_num_maxLength = pass_num.attr('maxlength');
            if (birthday.val().length >= birthday_maxLength && pass_sery.val().length >= pass_sery_maxLength && pass_num.val().length >= pass_num_maxLength) {
                // $(this).trigger('change');
                $('#policy'+param+'traveller-'+driver_id+'-birthday').datepicker('hide');
                // $('#policy'+param+'traveller-'+driver_id+'-birthday').trigger('change');
                _getPassBirthdayData(birthday.val(), pass_sery.val(), pass_num.val(), driver_id, param);
            } else {
                $('#policy'+param+'traveller-'+driver_id+'-first_name').val('').removeAttr('readonly');
                $('#policy'+param+'traveller-'+driver_id+'-surname').val('').removeAttr('readonly');
                $('#policy'+param+'traveller-'+driver_id+'-pinfl').val('').removeAttr('readonly');
            }
        }
    })

            
            if ($('#policytravel-also_traveller').is(':checked')) {
                let first_name = $('#policytravel-app_name').val();
                let surname = $('#policytravel-app_surname').val();
                let birthday = $('#policytravel-app_birthday').val();
                let pass_sery = $('#policytravel-app_pass_sery').val();
                let pass_num = $('#policytravel-app_pass_num').val();
                
                $('#policytraveltraveller-0-first_name').val(first_name).attr('readonly', true);
                $('#policytraveltraveller-0-surname').val(surname).attr('readonly', true);
                $('#policytraveltraveller-0-birthday').val(birthday).attr('readonly', true);
                $('#policytraveltraveller-0-pass_sery').val(pass_sery).attr('readonly', true);
                $('#policytraveltraveller-0-pass_num').val(pass_num).attr('readonly', true);
            } else {
                $('#policytraveltraveller-0-first_name').removeAttr('readonly');
                $('#policytraveltraveller-0-surname').removeAttr('readonly');
                $('#policytraveltraveller-0-birthday').removeAttr('readonly');
                $('#policytraveltraveller-0-pass_sery').removeAttr('readonly');
                $('#policytraveltraveller-0-pass_num').removeAttr('readonly');
            }
            
        $(document).on('change', '.on-change-insurer', function(event) {
            if ($('#policytravel-also_traveller').is(':checked')) {
                let first_name = $('#policytravel-app_name').val();
                let surname = $('#policytravel-app_surname').val();
                let birthday = $('#policytravel-app_birthday').val();
                let pass_sery = $('#policytravel-app_pass_sery').val();
                let pass_num = $('#policytravel-app_pass_num').val();
                
                let birthday_maxLength = $('#policytravel-app_birthday').attr('maxlength');
                
                $('#policytraveltraveller-0-first_name').val(first_name).attr('readonly', true);
                $('#policytraveltraveller-0-surname').val(surname).attr('readonly', true);
                if (birthday.length >= birthday_maxLength && ($('#policytraveltraveller-0-birthday').val().length < birthday_maxLength)) {
                    $('#policytraveltraveller-0-birthday').val(birthday).attr('readonly', true);
                    $('#policytraveltraveller-0-birthday').trigger('change');
                } else if (birthday != $('#policytraveltraveller-0-birthday').val()) {
                    $('#policytraveltraveller-0-birthday').val(birthday).attr('readonly', true);
                }
                $('#policytraveltraveller-0-pass_sery').val(pass_sery).attr('readonly', true);
                $('#policytraveltraveller-0-pass_num').val(pass_num).attr('readonly', true);
            } else {
                $('#policytraveltraveller-0-first_name').removeAttr('readonly');
                $('#policytraveltraveller-0-surname').removeAttr('readonly');
                $('#policytraveltraveller-0-birthday').removeAttr('readonly');
                $('#policytraveltraveller-0-pass_sery').removeAttr('readonly');
                $('#policytraveltraveller-0-pass_num').removeAttr('readonly');
            }
        })


        $('.select2-travel').select2({
            maximumSelectionLength: 6,
            selectionCssClass:'form-control travel-multiple-check',
            containerCssClass :'travel-multiple-check-container',
            
        });
            
      var startDate = $('#policytravel-start_date');
      var endDate = $('#policytravel-end_date');

      startDate.datepicker({
        autoHide: true,
        startDate: new Date((new Date()).valueOf() + 1000*3600*24*0),
        minDate: new Date((new Date()).valueOf() + 1000*3600*24*0),
        endDate: new Date(new Date().setDate(new Date().getDate() + 365)),
        maxDate: new Date(new Date().setDate(new Date().getDate() + 365)),
        format: 'dd.mm.yyyy'
      });
      endDate.datepicker({
        autoHide: true,
        format: 'dd.mm.yyyy',
        date: startDate.datepicker('getDate'),
        startDate: startDate.datepicker('getDate')
      });

      startDate.on('changeDate', function () {
        endDate.datepicker('setDate', startDate.datepicker('getDate'));
        endDate.datepicker('setStartDate', startDate.datepicker('getDate'));
        endDate.datepicker('setMinDate', startDate.datepicker('getDate'));
        endDate.show();
        startDate.hide();
      });
      
      _traveller_birthday.datepicker({
        autoHide: true,
        endDate: new Date(new Date().setDate(new Date().getDate())),
        maxDate: new Date(new Date().setDate(new Date().getDate())),
        format: 'dd.mm.yyyy'
      });
      
      _traveller_birthday_parent.datepicker({
        autoHide: true,
        endDate: eighteenYearsAgo,
        maxDate: eighteenYearsAgo,
        format: 'dd.mm.yyyy'
      });
      
        var from;
        var days;
        var to;

        function addDays(date, days) {
            days--;
            var date_split = date.split(".");
            var new_date = date_split[2]+"-"+date_split[1]+"-"+date_split[0];
            var result = new Date(new_date);
            result.setDate(result.getDate() + Number(days));
            var year_curr = result.getFullYear();
            var month_curr = result.getMonth()<9?'0'+(result.getMonth()+1):(result.getMonth()+1);
            var date_cur = result.getDate()<10?'0'+result.getDate():result.getDate();
            var full_date = date_cur+'.'+month_curr+'.'+year_curr;
            return full_date;
        }

        function subtractDays(strdate1, strdate2){
            var date_split = strdate1.split(".");
            var new_strdate1 = date_split[2]+"-"+date_split[1]+"-"+date_split[0];
            const date1 = new Date(new_strdate1);
            date_split = strdate2.split(".");
            var new_strdate2 = date_split[2]+"-"+date_split[1]+"-"+date_split[0];
            const date2 = new Date(new_strdate2);
            const diffTime = Math.abs(date2 - date1);
            const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
            return diffDays+1;
        }
        
        $(document).on('change', "#policytravel-start_date", function (e) {
            from = e.target.value;
            aggregated('FROM');
        })
        $(document).on('change', "#policytravel-days", function (e) {
            days = e.target.value;
            aggregated('DAYS');
        })
        $(document).on('change', "#policytravel-end_date", function (e) {
            to = e.target.value;
            aggregated('TO');
        })

        function aggregated(TARGET){
            switch(TARGET){
                case 'FROM':
                    if(!days){
                        days=1;
                    }
                    if(days>365) {
                        days = 365
                    }
                    $('#policytravel-days').val(days);
                    $('#policytravel-end_date').attr('min',from)
                    $('#policytravel-end_date').val(addDays(from, days))
                    break;
                case 'TO':
                    if(!from)
                        break;
                    days = subtractDays(from, to);
                    if(days>365) {
                        days = 365
                    }
                    $('#policytravel-end_date').attr('min',from)
                    $("#policytravel-days").val(days);
                    break;
                case 'DAYS':
                    if(!from)
                        break;
                    if(days>365) {
                        days = 365
                    }
                    $('#policytravel-end_date').attr('min',from)
                    $('#policytravel-end_date').val(addDays(from, days))
                    break;
            }
        }
        
        function hideSearchBtn()
        {
            let btn = $("#check-applicant");
            btn.removeClass('check').addClass('clear')
            btn.removeClass('bg-primary').addClass('bg-danger')
        }        
        function showSearchBtn()
        {
            let btn = $("#check-applicant");
            btn.removeClass('clear').addClass('check')
            btn.removeClass('bg-danger').addClass('bg-primary')
        }
    })
JS;

$this->registerJs($jsPjax);
?>

<?php Pjax::end(); ?>

<?php
$JS = <<<JS
    $(function() {
        
    let isLoading = false;

        // $.pjax.defaults.scrollTo = false;
        function checkAgree() {
          if ($('#policytravel-offer').prop('checked')) {
              $('.submitForm').removeAttr('disabled')
              return true;
          } else {
              $('.submitForm').attr('disabled',true)
              return false;
          }
        }
        
        checkAgree();
        
        jQuery(document).on('change', '#policytravel-offer', function (event) {
           if ($(this).prop('checked')) {
              $('.submitForm').removeAttr('disabled')
          } else {
              $('.submitForm').attr('disabled',true)
          }
        });
        
        jQuery(document).on('click', '.submitForm', function (event) {
            event.preventDefault();
            if (!isLoading && checkAgree()) {
                $('#submitInput').val(1);
                $('#policy-travel-form').submit();
                isLoading = true
            }
        });
        jQuery(document).on('pjax:send', '#pjax_policy_travel_calc_result', function () {
            // overlay.show();
        });
    
        jQuery(document).on('pjax:complete', '#pjax_policy_travel_calc', function () {
            overlay.hide();
            $('#submitInput').val(0);
            isLoading = false
        });
        let validation_field_1 = $('#policytravel-_travelcountries')
        let validation_field_2 = $('#policytravel-start_date')

        $(document).on('change', '.on-change', function(event) {
            if (!isLoading) {
                if (validation_field_1.val() || validation_field_2.val()) {
                    overlay.show();
                    isLoading = true;
                    $.pjax.reload({
                        container: '#pjax_policy_travel_calc',
                        type: 'POST',
                        data: $('form[data-pjax]').serialize() + '&button=1'
                    });
                    
                    
                }
            }
            if ($('#policy-travel-form').yiiActiveForm('validate',true) && checkAgree()) {
                $('.submitForm').removeAttr('disabled');
            } else {
                $('.submitForm').attr('disabled','disabled');
            }
        })
       
        jQuery(document).on('click', '.show-modal-program', function (event) {
            let title = $(this).data('title');
            let key = $(this).data('key');
            let body = $('.modal-program-info-'+key).html();
            $('#modalProgramInfo').find('.modal-title').html(title);
            $('#modalProgramInfo').find('.modal-body').html(body);
            $('#modalProgramInfo').modal('show');
            event.preventDefault();
            return false;
        });
        
        

    });
JS;
$this->registerJs($JS);
?>