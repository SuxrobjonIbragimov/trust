let url_post_price_calc = jQuery('#url_post_price_calc').val();
let url_post_tin = jQuery('#url_post_tin').val();
let url_post_okonx = jQuery('#url_post_okonx').val();

$(function() {

    var currentDate = new Date();
    var driver_limit_id_cur = $('input[name="PolicyOsgo[driver_limit_id]"]:checked').val();
    eighteenYearsAgo = currentDate.setFullYear(currentDate.getFullYear()-18);
    hundredYearsAgo = currentDate.setFullYear(currentDate.getFullYear()-100);


    // if ($('body').find('#policyosgo-start_date')) {
    //     var startDate = $('#policyosgo-start_date');
    //     var endDate = $('#policyosgo-end_date');
    //
    //     startDate.datepicker({
    //         autoHide: true,
    //         startDate: new Date((new Date()).valueOf() + 1000*3600*24*0),
    //         minDate: new Date((new Date()).valueOf() + 1000*3600*24*0),
    //         endDate: new Date(new Date().setDate(new Date().getDate() + 30)),
    //         maxDate: new Date(new Date().setDate(new Date().getDate() + 30)),
    //         format: 'dd.mm.yyyy'
    //     });
    //     endDate.datepicker({
    //         autoHide: true,
    //         format: 'dd.mm.yyyy',
    //         date: startDate.datepicker('getDate'),
    //         startDate: startDate.datepicker('getDate')
    //     });
    //
    //     startDate.on('changeDate', function () {
    //         endDate.datepicker('setDate', startDate.datepicker('getDate'));
    //         endDate.datepicker('setStartDate', startDate.datepicker('getDate'));
    //         endDate.datepicker('setMinDate', startDate.datepicker('getDate'));
    //         endDate.show();
    //         startDate.hide();
    //     });
    //
    //     var from;
    //     var days;
    //     var to;
    //
    //     function addDays(date, days) {
    //         days--;
    //         // console.log("date1");
    //         // console.log(date);
    //         var date_split = date.split(".");
    //         // console.log("date_split");
    //         // console.log(date_split);
    //         var new_date = date_split[2]+"-"+date_split[1]+"-"+date_split[0];
    //         // console.log("new_date");
    //         // console.log(new_date);
    //         var result = new Date(new_date);
    //         // console.log("result1");
    //         // console.log(result);
    //         result.setDate(result.getDate() + Number(days));
    //         // console.log("result2");
    //         // console.log(result);
    //         var year_curr = result.getFullYear();
    //         var month_curr = result.getMonth()<9?'0'+(result.getMonth()+1):(result.getMonth()+1);
    //         var date_cur = result.getDate()<10?'0'+result.getDate():result.getDate();
    //         var full_date = date_cur+'.'+month_curr+'.'+year_curr;
    //         // console.log("Full_date");
    //         // console.log(full_date);
    //         return full_date;
    //     }
    //
    //     function subtractDays(strdate1, strdate2){
    //         var date_split = strdate1.split(".");
    //         var new_strdate1 = date_split[2]+"-"+date_split[1]+"-"+date_split[0];
    //         const date1 = new Date(new_strdate1);
    //         date_split = strdate2.split(".");
    //         var new_strdate2 = date_split[2]+"-"+date_split[1]+"-"+date_split[0];
    //         const date2 = new Date(new_strdate2);
    //         const diffTime = Math.abs(date2 - date1);
    //         const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
    //         return diffDays+1;
    //     }
    //
    //     $(document).on('change', "#policyosgo-start_date", function (e) {
    //         from = e.target.value;
    //         aggregated('FROM');
    //     })
    //     $(document).on('change', "#policyosgo-days", function (e) {
    //         days = e.target.value;
    //         aggregated('DAYS');
    //     })
    //     $(document).on('change', "#policyosgo-end_date", function (e) {
    //         to = e.target.value;
    //         aggregated('TO');
    //     })
    //
    //     function aggregated(TARGET){
    //         switch(TARGET){
    //             case 'FROM':
    //                 if(!days){
    //                     days=365;
    //                 }
    //                 $('#policyosgo-days').val(days);
    //                 $('#policyosgo-end_date').attr('min',from)
    //                 $('#policyosgo-end_date').val(addDays(from, days))
    //                 break;
    //             case 'TO':
    //                 if(!from)
    //                     break;
    //                 days = subtractDays(from, to);
    //                 $('#policyosgo-end_date').attr('min',from)
    //                 $("#policyosgo-days").val(days);
    //                 break;
    //             case 'DAYS':
    //                 if(!from)
    //                     break;
    //                 // console.log(from,days)
    //                 $('#policyosgo-end_date').attr('min',from)
    //                 $('#policyosgo-end_date').val(addDays(from, days))
    //                 break;
    //         }
    //     }
    //
    // }

    function _getCalc() {

        var form = $('#policy-osgo-form');
        // return false if form still have some validation errors

        let annual_salary = $('#policyosgo-org_annual_salary').val();
        let org_okonx = $('#policyosgo-org_okonx').val();
        if ((annual_salary && org_okonx))
        {
            overlay_right.show();
            // submit form
            $.ajax({
                url    : url_post_price_calc,
                type   : 'post',
                data   : form.serialize(),
                success: function (response)
                {
                    // console.log(response);
                    overlay_right.hide();
                    let amount_uzs = response.prem;
                    let end_date = response.end_date;
                    $('#policy_price').html(amount_uzs);
                    $('#policyosgo-end_date').val(end_date);
                    $('.policy_price-block').removeClass('d-none');
                    $("#submit-button").removeClass('d-none');
                    return true;
                },
                error  : function ()
                {
                    overlay_right.hide();
                    alert('internal server error');
                }
            });
        } else {
            let amount_uzs = 0;
            let amount_usd = 0;
            let price = 0;
            $('#policy_price').html(amount_uzs.toLocaleString('fr'));
            return false;
        }

    }

    $(document).on('change', '.get-calc-ajax', function(e) {
        _getCalc();
        return true;
    })

    function _getTinData(tin) {
        // return false if form still have some validation errors
        if ((tin))
        {
            overlay.show();
            // submit form
            $.ajax({
                url    : url_post_tin,
                type   : 'post',
                data   : {csrfParam: csrfToken, tin: tin},
                success: function (response)
                {
                    overlay.hide();
                    console.log(response);
                    if (response.ERROR > 0) {
                        $.notify({
                            // options
                            // title: '<strong>Warning</strong>',
                            // icon: 'bx bxs-info-circle',
                            message: response.ERROR_MESSAGE,
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
                        if (data.ORGNAME) {
                            $('#policyosgo-owner_inn').attr('readonly', true);
                            $('#policyosgo-owner_orgname').val(data.ORGNAME);
                            $("#check-vehicle").removeClass('check').addClass('clear').addClass('bg-danger').removeClass('btn-primary');
                        }
                        if (data.ADDRESS) {
                            $('#policyosgo-owner_address').val(data.ADDRESS);
                        }
                        if (data.OKED) {
                            $('#policyosgo-owner_oked').val(data.OKED).attr('readonly', true).trigger('change');
                        } else {
                            $('#policyosgo-owner_oked').val(null).removeAttr('readonly');
                        }
                        if (data.ORGNAME) {
                            $('#policyosgo-owner_orgname').val(data.ORGNAME);
                        }

                        $("#vehicle-info").removeClass('d-none');
                        $("#owner-info").removeClass('d-none');
                        $("#submit-button").removeClass('d-none');
                        if (!($('#policyosgo-owner_is_applicant').is(':checked'))) {
                            $("#applicant-info").removeClass('d-none');
                        } else {
                            $("#driver-info").removeClass('d-none');
                        }

                        if (data.okonx_list) {
                            var output = [];
                            $.each(data.okonx_list, function(key, value)
                            {
                                output.push('<option value="'+ key +'">'+ value +'</option>');
                            });
                            $('#policyosgo-org_okonx').html(output.join(''));
                        }
                        if (data.ORG_OKONX) {
                            $('#policyosgo-org_okonx').val(data.ORG_OKONX).change();;
                            $('#policyosgo-app_phone').focus();
                        } else {
                            $('#policyosgo-org_okonx').focus();
                        }

                        _getCalc();

                    }
                },
                error  : function ()
                {
                    overlay.hide();
                    console.log('internal server error');
                }
            });
        } else {
            return false;
        }
    }

    function _getOkonxData(oked) {

        // return false if form still have some validation errors
        if ((oked))
        {
            overlay.show();
            // submit form
            $.ajax({
                url    : url_post_okonx,
                type   : 'post',
                data   : {csrfParam: csrfToken, oked: oked},
                success: function (response)
                {
                    overlay.hide();
                    console.log(response);
                    if (response.ERROR > 0) {
                        $.notify({
                            // options
                            // title: '<strong>Warning</strong>',
                            // icon: 'bx bxs-info-circle',
                            message: response.ERROR_MESSAGE,
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

                        if (data.okonx_list) {
                            var output = [];
                            $.each(data.okonx_list, function(key, value)
                            {
                                output.push('<option value="'+ key +'">'+ value +'</option>');
                            });
                            $('#policyosgo-org_okonx').html(output.join(''));
                        }
                        if (data.ORG_OKONX) {
                            $('#policyosgo-org_okonx').val(data.ORG_OKONX).change();;
                            $('#policyosgo-app_phone').focus();
                        } else {
                            $('#policyosgo-org_okonx').focus();
                        }

                        _getCalc();
                    }
                },
                error  : function ()
                {
                    overlay.hide();
                    console.log('internal server error');
                }
            });
        } else {
            return false;
        }

    }

    function hideInfoBlock(block = 'all'){
        if (block == 'all') {
        } else {
            $(block).addClass('d-none');
        }
    }

    // VEHICLE
    $(document).on('click', '#check-vehicle', function(e) {
        if ($(this).hasClass('clear')) {
            $('#policyosgo-owner_inn').val('').removeAttr('readonly').focus();
            $(this).addClass('check').removeClass('clear');
        } else if ($(this).hasClass('check')) {
            $('#policyosgo-owner_inn').trigger('keyup');
        }
    })


    $(document).on('keyup', '.on-change-inn', function(e) {
        let inn = $('#policyosgo-owner_inn');
        if (inn.val().length >= 7) {
            $(this).trigger('change');
            _getTinData(inn.val());
        }
    })
    $(document).on('change', '.on-change-oked', function(e) {
        let item = $('#policyosgo-owner_oked');
        if (item.val()) {
            _getOkonxData(item.val());
        }
    })

    function checkAgree() {
        if ($('#policyosgo-offer').prop('checked')) {
            $('.submitFormOsgo').removeAttr('disabled')
            return true;
        } else {
            $('.submitFormOsgo').attr('disabled',true)
            return false;
        }
    }

    checkAgree();

    jQuery(document).on('change', '#policyosgo-offer', function (event) {
        if ($(this).prop('checked')) {
            $('.submitFormOsgo').removeAttr('disabled')
        } else {
            $('.submitFormOsgo').attr('disabled',true)
        }
    });

    let isLoading = false;

})