<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $program array */
/* @var $model \backend\modules\policy\models\PolicyTravel */
/* @var $modelTravellers \backend\modules\policy\models\PolicyTravelTraveller */
/* @var $form yii\widgets\ActiveForm */
?>


<?php //Pjax::begin(['id' => 'pjax_policy_travel_calc']); ?>

    <?php $form = ActiveForm::begin([
            'id' => 'policy-travel-form',
            'options' => [
                    'data-pjax' => true
                ]
    ]); ?>

    <div class="row row__def">
        <div class="contact-us-page__left col-xl-8">

            <div class="branch__title fs-3 mb-4"><?= Yii::t('policy','Fill in personal information'); ?></div>

            <div class="contact-us-page__info mr-2">
                <?php if ($model->is_family && $model->isNewRecord) :?>
                    <p class="contact-us-page__label fs-3"><?=Yii::t('policy','Traveller {0}',1)?> <?=Yii::t('policy','(insurer)')?></p>
                <?php else:?>
                    <p class="contact-us-page__label fs-3"><?=Yii::t('policy','Insurer information')?></p>
                <?php endif;?>
                <div class="modal__container row modal__grid-alt">
                    <div class="field  modal__field modal__field-col">
                        <?php
                        // necessary for update action.
                        if (!$model->isNewRecord) {
                            echo Html::activeHiddenInput($model, "id");
                        }
                        ?>
                        <?php $label = $model->getAttributeLabel('app_name');?>
                        <?= $form->field($model, 'app_name',['options' => ['class' => 'form-group']])->textInput([
                            'maxlength' => true,
                            'class' => 'w-100 border-primary p-2 br-4 my-2 fs-4 form-control alphaletters on-change-insurer',
                            'oninput' => 'this.value = this.value.toUpperCase()',
                            'placeholder' => Yii::t('policy','{label}ни киритинг',
                                ['label' => $label]),
                        ])->label() ?>
                    </div>
                    <div class="field  modal__field modal__field-col ml-1">
                        <?php $label = $model->getAttributeLabel('app_surname');?>
                        <?= $form->field($model, 'app_surname',['options' => ['class' => 'form-group']])->textInput([
                            'maxlength' => true,
                            'class' => 'w-100 border-primary p-2 br-4 my-2 fs-4 form-control alphaletters on-change-insurer',
                            'oninput' => 'this.value = this.value.toUpperCase()',
                            'placeholder' => Yii::t('policy','{label}ни киритинг',
                                ['label' => $label]),
                        ])->label() ?>
                    </div>
                </div>

                <div class="modal__container row modal__grid">
                    <div class="field  modal__field modal__field-col">
                        <?php $label = $model->getAttributeLabel('app_birthday');?>
                        <?= $form->field($model, 'app_birthday',['options' => ['class' => 'form-group']])
                            ->textInput(['type' => 'text',
                                'maxlength' => true,
                                'readonly' => !empty($model->is_family) ? $model->is_family : false,
                                'autocomplete' => 'off',
                                'class' => 'w-100 border-primary p-2 br-4 my-2 fs-4 mask-birthday form-control on-change-insurer',
                                'placeholder' => Yii::t('policy','dd.mm.yyyy',
                                    ['label' => $label])
                            ])->label() ?>
                    </div>
                    <div class="field  modal__field modal__field-col ml-1">
                        <?php $label = $model->getAttributeLabel('app_pass_sery');?>
                        <?= $form->field($model, 'app_pass_sery',['options' => ['class' => 'form-group']])->textInput([
                            'maxlength' => 2,
                            'class' => 'w-100 border-primary p-2 br-4 my-2 fs-4 form-control alphaletters on-change-insurer',
                            'oninput' => 'this.value = this.value.toUpperCase()',
                            'placeholder' => Yii::t('policy','FA'),
                        ])->label() ?>
                    </div>
                    <div class="field  modal__field modal__field-col ml-1">
                        <?php $label = $model->getAttributeLabel('app_pass_num');?>
                        <?= $form->field($model, 'app_pass_num',['options' => ['class' => 'form-group']])->textInput([
                            'maxlength' => 9,
                            'class' => 'w-100 border-primary p-2 br-4 my-2 fs-4 form-control only_number on-change-insurer',
                            'placeholder' => Yii::t('policy','0000001'),
                        ])->label() ?>
                    </div>
                </div>

                <div class="modal__container row modal__grid">
                    <div class="field  modal__field modal__field-col">
                        <?php $label = $model->getAttributeLabel('app_address');?>
                        <?= $form->field($model, 'app_address',['options' => ['class' => 'form-group']])
                            ->textInput(['type' => 'text',
                                'maxlength' => true,
                                'class' => 'w-100 border-primary p-2 br-4 my-2 fs-4 form-control',
                                'oninput' => 'this.value = this.value.toUpperCase()',
                                'placeholder' => Yii::t('policy','{label}ни киритинг',['label' => $label])
                            ])->label() ?>
                    </div>
                    <div class="field  modal__field modal__field-col ml-1">
                        <?php $label = $model->getAttributeLabel('app_phone');?>
                        <?= $form->field($model, 'app_phone',['options' => ['class' => 'form-group']])->textInput([
                            'type' => 'tel',
                            'maxlength' => true,
                            'class' => 'w-100 border-primary p-2 br-4 my-2 fs-4 field--mask form-control mask-phone',
                            'placeholder' => Yii::t('policy','+99897-000-00-01',
                                ['label' => $label]),
                        ])->label() ?>
                    </div>
                    <div class="field  modal__field modal__field-col ml-1">
                        <?php $label = $model->getAttributeLabel('app_email');?>
                        <?= $form->field($model, 'app_email',['options' => ['class' => 'form-group']])->textInput([
                            'type' => 'email',
                            'maxlength' => true,
                            'class' => 'w-100 border-primary p-2 br-4 my-2 fs-4 field--mask form-control',
                            'placeholder' => Yii::t('policy','{label}ни киритинг',['label' => $label])
                        ])->label() ?>
                    </div>
                </div>

                <?php if(!$model->is_family):?>
                    <div class="field modal__field">
                        <div class="page-footer-button">
                            <?php
                            $label = $model->getAttributeLabel('also_traveller');
                            $template = '{input}{error}{hint}';
                            echo $form->field($model, 'also_traveller', [
                                'template' => $template
                            ])->checkbox([
                                'class' => 'on-change-insurer fs-3',
                            ])->label(false);
                            ?>

                        </div>
                    </div>
                <?php endif;?>


                <hr>

                <?php foreach ($modelTravellers as $i => $modelTraveller):?>
                    <?php if(!$model->is_family || !$model->isNewRecord):?>
                        <?php $index = $i+1;?>
                    <?php elseif ((($model->is_family && $model->isNewRecord) && (count($modelTravellers)-1 > $i) )) :?>
                        <?php $index = $i+2;?>
                    <?php else :?>
                        <?php break;?>
                    <?php endif;?>
                    <?php if ($index<=6) :?>
                        <p class="contact-us-page__label fs-3 mt-4"><?=Yii::t('policy','Traveller {0}',[$index])?></p>
                        <div class="modal__container row modal__grid-alt">
                            <div class="field  modal__field modal__field-col ">

                                <?php
                                // necessary for update action.
                                if (!$modelTraveller->isNewRecord) {
                                    echo Html::activeHiddenInput($modelTraveller, "[{$i}]id");
                                }
                                ?>
                                <?php $label = $modelTraveller->getAttributeLabel('first_name');?>
                                <?= $form->field($modelTraveller, "[{$i}]first_name",['options' => ['class' => 'form-group']])->textInput([
                                    'maxlength' => true,
                                    'class' => 'w-100 border-primary p-2 br-4 my-2 fs-4 form-control alphaletters',
                                    'oninput' => 'this.value = this.value.toUpperCase()',
                                    'placeholder' => Yii::t('policy','{label}ни киритинг',
                                        ['label' => $label]),
                                ])->label() ?>
                            </div>
                            <div class="field  modal__field modal__field-col ml-1">
                                <?php $label = $modelTraveller->getAttributeLabel('surname');?>
                                <?= $form->field($modelTraveller, "[{$i}]surname",['options' => ['class' => 'form-group']])->textInput([
                                    'maxlength' => true,
                                    'class' => 'w-100 border-primary p-2 br-4 my-2 fs-4 form-control alphaletters',
                                    'oninput' => 'this.value = this.value.toUpperCase()',
                                    'placeholder' => Yii::t('policy','{label}ни киритинг',
                                        ['label' => $label]),
                                ])->label() ?>
                            </div>
                        </div>
                        <div class="modal__container row modal__grid">
                            <div class="field  modal__field modal__field-col">
                                <?php $label = $modelTraveller->getAttributeLabel('birthday');?>
                                <?= $form->field($modelTraveller, "[{$i}]birthday",['options' => ['class' => 'form-group']])
                                    ->textInput(['type' => 'text',
                                        'maxlength' => 10,
                                        'readonly' => true,
                                        'autocomplete' => 'off',
                                        'class' => 'w-100 border-primary p-2 br-4 my-2 fs-4  form-control mask-birthday traveller_birthday',
                                        'placeholder' => Yii::t('policy','dd.mm.yyyy',
                                            ['label' => $label])
                                    ])->label() ?>
                            </div>
                            <div class="field  modal__field modal__field-col  ml-1">
                                <?php $label = $modelTraveller->getAttributeLabel('pass_sery');?>
                                <?= $form->field($modelTraveller, "[{$i}]pass_sery",['options' => ['class' => 'form-group']])->textInput([
                                    'maxlength' => 2,
                                    'oninput' => 'this.value = this.value.toUpperCase()',
                                    'class' => 'w-100 border-primary p-2 br-4 my-2 fs-4 form-control alphaletters',
                                    'placeholder' => Yii::t('policy','FA'),
                                ])->label() ?>
                            </div>
                            <div class="field  modal__field modal__field-col ml-1">
                                <?php $label = $modelTraveller->getAttributeLabel('pass_num');?>
                                <?= $form->field($modelTraveller, "[{$i}]pass_num",['options' => ['class' => 'form-group']])->textInput([
                                    'maxlength' => 9,
                                    'class' => 'w-100 border-primary p-2 br-4 my-2 fs-4 form-control only_number',
                                    'placeholder' => Yii::t('policy','0000001'),
                                ])->label() ?>
                            </div>
                        </div>
                    <?php endif;?>
                <?php endforeach;?>

            </div>


        </div>
        <div class="contact-us-page__right ml-2 col-xl-4">

            <div class="branch__title fs-3 mb-4"><?= Yii::t('policy','Ҳисоблаш натижалари'); ?></div>

            <div class="contact-us-page__info">

                <?php Pjax::begin(['id' => 'pjax_policy_travel_calc_result', 'enablePushState' => false, 'scrollTo' => false]); ?>

                <?php if (!empty($model->_tmp_message)) :?>
                    <div class="alert-alert-warning">
                        <?=$model->_tmp_message?>
                    </div>
                <?php endif;?>
                <?php if (!empty($program['name'])):?>
                    <div class="field modal__field modal__right">
                        <div class="contact-us-page__label fs-3">
                            <?= Yii::t('policy','Program');?>
                        </div>
                        <div class="contact-us-page__text fs-4">
                            <span>
                                <?= ($program['name']) ?: 'Program not selected' ?>
                            </span>
                        </div>
                    </div>
                <?php endif;?>

                <div class="field modal__field modal__right">
                    <div class="contact-us-page__label fs-3">
                        <?= Yii::t('policy','Полис нархи');?>
                    </div>
                    <div class="contact-us-page__text fs-4">
                        <span id="policy_price"><?= number_format($model->amount_uzs, 2, '.', ' ')?></span>
                        <span class="currency"><?= Yii::t('policy','сўм');?></span>

                    </div>
                </div>
                <div class="field modal__field modal__right">
                    <div class="contact-us-page__label fs-3">
                        <?= Yii::t('policy','Period');?>
                    </div>
                    <div class="contact-us-page__text fs-4">
                        <span><?= date('d.m.Y', strtotime($model->start_date))?> - <?= date('d.m.Y', strtotime($model->end_date))?></span>
                    </div>
                </div>

                <?php Pjax::end(); ?>
            </div>
        </div>
    </div>

    <div class="flex-row">
        <div class="contact-us-page__left">

            <div class="field modal__field">
                <div class="page-footer-button">
                    <div class="form-group">
                        <?= Html::submitButton(Yii::t('policy','Перейти к просмотру'), ['class' => 'btn btn-success']) ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="contact-us-page__right right-calc-block ml-2">
        </div>
    </div>


    <?php ActiveForm::end(); ?>
<?php
$jsPjax =<<<JS

    $(function() {
        $.pjax.defaults.scrollTo = false;
            
      var _traveller_birthday = $('.traveller_birthday');
      var _insurer_birthday = $('#policytravel-app_birthday');
      
      
        var currentDate = new Date();
        eighteenYearsAgo = currentDate.setFullYear(currentDate.getFullYear()-18);
        hundredYearsAgo = currentDate.setFullYear(currentDate.getFullYear()-100);
      _insurer_birthday.datepicker({
        autoHide: true,
        endDate: eighteenYearsAgo,
        maxDate: eighteenYearsAgo,
        format: 'dd.mm.yyyy'
      });
      
      // _traveller_birthday.datepicker({
      //   autoHide: true,
      //   endDate: new Date(new Date().setDate(new Date().getDate())),
      //   maxDate: new Date(new Date().setDate(new Date().getDate())),
      //   format: 'dd.mm.yyyy'
      // });
      
    })
JS;

$this->registerJs($jsPjax);
?>
<?php //Pjax::end(); ?>

<?php
$JS =<<<JS
    $(function() {
        var current_birthday = $('#policytraveltraveller-0-birthday').val();
        if ($('#policytravel-also_traveller').is(':checked')) {
            let first_name = $('#policytravel-app_name').val();
            let surname = $('#policytravel-app_surname').val();
            let birthday = $('#policytravel-app_birthday').val();
            let pass_sery = $('#policytravel-app_pass_sery').val();
            let pass_num = $('#policytravel-app_pass_num').val();
            
            $('#policytraveltraveller-0-first_name').val(first_name);
            $('#policytraveltraveller-0-surname').val(surname);
            $('#policytraveltraveller-0-birthday').val(birthday);
            $('#policytraveltraveller-0-pass_sery').val(pass_sery);
            $('#policytraveltraveller-0-pass_num').val(pass_num);
        }
            
        $(document).on('change', '.on-change-insurer', function(event) {
            if ($('.on-change-insurer').is(':checked')) {
                let first_name = $('#policytravel-app_name').val();
                let surname = $('#policytravel-app_surname').val();
                let birthday = $('#policytravel-app_birthday').val();
                let pass_sery = $('#policytravel-app_pass_sery').val();
                let pass_num = $('#policytravel-app_pass_num').val();
                
                $('#policytraveltraveller-0-first_name').val(first_name);
                $('#policytraveltraveller-0-surname').val(surname);
                $('#policytraveltraveller-0-birthday').val(birthday);
                $('#policytraveltraveller-0-pass_sery').val(pass_sery);
                $('#policytraveltraveller-0-pass_num').val(pass_num);
            } else {
                $('#policytraveltraveller-0-birthday').val(current_birthday);
            }
        })

    });
JS;
$this->registerJs($JS);
?>