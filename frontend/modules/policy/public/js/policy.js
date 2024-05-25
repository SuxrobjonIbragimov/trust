let csrfParam = $('meta[name="csrf-param"]').attr("content");
let csrfToken = $('meta[name="csrf-token"]').attr("content");
let lang = $('html').attr('lang');

let overlay = jQuery('.overlay');
let overlay_right = jQuery('.overlay-right');

$(function () {
    $('.mask-card-number').mask('0000 0000 0000 0000');
    $('.mask-card-expire').mask('00/00');
    $('.mask-phone').mask('+000 (00) 000-00-00');
    $('.mask-birthday').mask('00.00.0000');
    $('.mask-date').mask('00.00.0000');
    $(document).on('change', 'body', function () {
        $('.mask-phone').mask('+000 (00) 000-00-00');
        $('.mask-birthday').mask('00.00.0000');
        $('.mask-date').mask('00.00.0000');
    })

    jQuery(document).on('pjax:complete', '#pjax_policy_travel_calc', function () {
        $('.mask-birthday').mask('00.00.0000');
        $('.mask-date').mask('00.00.0000');
    });

    jQuery(document).on('click', '.modal__exit', function (event) {
        $('.modal').modal('hide');
    });

    jQuery(document).on('click', '.hamburger', function (event) {
        $('.wrapper').toggleClass('wrapper--menu-open')
    });

    jQuery(document).on('focus', 'input', function (event) {
        var val = $(this).val();
        $(this).val('');
        $(this).val(val);
    });

});


var response = false;

function myCheckTransAjaxCall(url, hash) {
    $.ajax({
        url: url,
        type: 'post',
        data: {
            h: hash,
            csrfParam: csrfToken
        }
    }).done(function (data) {
        response = data;
    });
    console.log(response);
    return response;
}
