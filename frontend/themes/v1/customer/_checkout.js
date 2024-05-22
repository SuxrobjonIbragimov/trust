jQuery(document).ready(function () {

    var checkoutWizard = jQuery('#checkout_wizard');
    var formStep = parseInt(jQuery('#form_step').val());
    var btnFinish = jQuery('<button></button>')
        .text(formStep === 3 ? 'Отправить' : 'Продолжить')
        .attr('type', 'submit')
        .addClass('btn btn-primary btn-finish');

    checkoutWizard.smartWizard({
        selected: formStep,
        showStepURLhash: false,
        keyNavigation: false,
        toolbarSettings: {
            showNextButton: false,
            showPreviousButton: false,
            toolbarExtraButtons: [btnFinish]
        },
        anchorSettings: {
            anchorClickable: false
        },
        lang: {
            next: 'следующий',
            previous: 'предыдущий'
        }
    });

    checkoutWizard.on("showStep", function (e, anchorObject, stepNumber, stepDirection, stepPosition) {
        if (stepNumber === formStep) {
            jQuery('.btn-finish').removeClass('hide');
        } else {
            jQuery('.btn-finish').addClass('hide');
        }
    });

    jQuery('#captcha_refresh').on('click', function (e) {
        e.preventDefault();
        jQuery('#captcha_image').yiiCaptcha('refresh');
    });

    var overlay = jQuery('.overlay');

    jQuery(document).on('pjax:send', '#checkout_pjax', function () {
        overlay.show();
    });

    jQuery(document).on('pjax:complete', '#checkout_pjax', function () {
        overlay.hide();
    });

});
