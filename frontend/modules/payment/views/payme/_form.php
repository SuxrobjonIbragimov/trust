<?php

use app\common\library\paycom\Paycom\PaycomSubscribeForm;
use app\widgets\BlocksWidget;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $h string */
/* @var $model frontend\modules\policy\models\PolicyTravel */
/* @var $paymentModel \app\common\library\paycom\Paycom\PaycomSubscribeForm */
/* @var $form yii\widgets\ActiveForm */
?>


<?php //Pjax::begin(['id' => 'pjax_policy_travel_calc']); ?>

    <?php $form = ActiveForm::begin([
            'id' => 'payme-subscribe-form',
            'options' => [
                    'data-pjax' => false
                ]
    ]); ?>

    <div class="flex-row">
        <div class="contact-us-page__left">
            <?php
                $title = Yii::t('policy','Please enter card information');;
                if ($paymentModel->step == PaycomSubscribeForm::STEP_VERIFICATION) {
                    $title = Yii::t('policy','Please enter verification code');
                }
                ?>
            <div class="branch__title"><?= $title ?></div>

            <div class="contact-us-page__info mr-2">
                <?php  if ($paymentModel->step == PaycomSubscribeForm::STEP_CARD_INFO) :?>
                    <div class="modal__container">
                        <div class="field  modal__field modal__field">
                            <?php $label = $paymentModel->getAttributeLabel('number');?>
                            <?= $form->field($paymentModel, 'number',['options' => ['class' => 'form-group']])->textInput([
                                'maxlength' => true,
                                'class' => 'field__input form-control mask-card-number',
                                'oninput' => 'this.value = this.value.toUpperCase()',
                                'placeholder' => Yii::t('frontend','____ ____ ____ ____',
                                    ['label' => $label]),
                            ])->label() ?>
                        </div>
                    </div>

                    <div class="modal__container">
                        <div class="field  modal__field modal__field text-right" >
                            <?php $label = $paymentModel->getAttributeLabel('expire');?>
                            <?= $form->field($paymentModel, 'expire',['options' => ['class' => 'form-group text-right']])->textInput([
                                'maxlength' => 2,
                                'class' => 'field__input form-control mask-card-expire',
                                'oninput' => 'this.value = this.value.toUpperCase()',
                                'placeholder' => Yii::t('policy','__/__'),
                            ])->label() ?>
                        </div>
                    </div>
                <?php  else: ?>                                                                   
                    <div class="modal__container">
                        <div class="field  modal__field modal__field" >
                            <?php $label = $paymentModel->getAttributeLabel('code');?>
                            <?= $form->field($paymentModel, 'code',['options' => ['class' => 'form-group']])->textInput([
                                'maxlength' => 2,
                                'class' => 'field__input form-control',
                                'oninput' => 'this.value = this.value.toUpperCase()',
                                'placeholder' => Yii::t('policy',''),
                            ])->label() ?>
                        </div>
                    </div>
                <?php  endif;?>
                <div class="text-right">
                    <p class="small"><?=Yii::t('policy','Provided by Payme')?></p>
                </div>
                <div class="field modal__field">
                    <div class="page-footer-button">
                        <div class="form-group">
                            <?= Html::activeHiddenInput($paymentModel,'step')?>
                            <?= Html::submitButton(Yii::t(['slug','Тўлаш']), ['class' => 'btn btn-success']) ?>
                        </div>
                    </div>
                </div>
            </div>


        </div>
    </div>

    <?php ActiveForm::end(); ?>
<?php
$jsPjax =<<<JS

    $(function() {
        $.pjax.defaults.scrollTo = false;
            
      var _traveller_birthday = $('.traveller_birthday');
      var _insurer_birthday = $('#policytravel-app_birthday');
      
      _insurer_birthday.datepicker({
        autoHide: true,
        minDate: new Date((new Date()).valueOf() - 1000*3600*24*365*100),
        endDate: new Date(new Date().setDate(new Date().getDate() - 1000*3600*24*365*18)),
        maxDate: new Date(new Date().setDate(new Date().getDate() - 1000*3600*24*365*18)),
        format: 'dd.mm.yyyy'
      });
      
      _traveller_birthday.datepicker({
        autoHide: true,
        endDate: new Date(new Date().setDate(new Date().getDate())),
        maxDate: new Date(new Date().setDate(new Date().getDate())),
        format: 'dd.mm.yyyy'
      });
      
    })
JS;

$this->registerJs($jsPjax);
?>
<?php //Pjax::end(); ?>

<?php
$JS =<<<JS
    $(function() {
        
        $(document).on('change', 'form[data-pjax]', function(event) {
          // $.pjax.submit(event, '#pjax_policy_travel_calc_result')
          //   var form = $(this);
          //   var formData = form.serialize();
          //  
          //   $.ajax({
          //
          //       url: form.attr("action"),
          //
          //       type: form.attr("method"),
          //
          //       data: formData,
          //
          //       success: function (data) {
          //           console.log(data);
          //       },
          //
          //       error: function () {
          //
          //           alert("Something went wrong");
          //
          //       }
          //
          //   });
        })

    });
JS;
$this->registerJs($JS);
?>