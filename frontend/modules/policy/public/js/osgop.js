let url_post_price_calc = jQuery('#url_post_price_calc').val();
let url_post_tech_pass_data = jQuery('#url_post_tech_pass_data').val();
let url_post_pass_birthday = jQuery('#url_post_pass_birthday').val();
let url_post_pass_pinfl = jQuery('#url_post_pass_pinfl').val();
let label_driver = jQuery('#label_driver').val();

$(function() {

    var currentDate = new Date();
    var driver_limit_id_cur = $('input[name="PolicyOsgo[driver_limit_id]"]:checked').val();
    eighteenYearsAgo = currentDate.setFullYear(currentDate.getFullYear()-18);
    hundredYearsAgo = currentDate.setFullYear(currentDate.getFullYear()-100);


    if ($('body').find('#policyosgo-start_date')) {
        var startDate = $('#policyosgo-start_date');
        var endDate = $('#policyosgo-end_date');

        startDate.datepicker({
            autoHide: true,
            startDate: new Date((new Date()).valueOf() + 1000*3600*24*0),
            minDate: new Date((new Date()).valueOf() + 1000*3600*24*0),
            endDate: new Date(new Date().setDate(new Date().getDate() + 30)),
            maxDate: new Date(new Date().setDate(new Date().getDate() + 30)),
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

        var from;
        var days;
        var to;

        function addDays(date, days) {
            days--;
            // console.log("date1");
            // console.log(date);
            var date_split = date.split(".");
            // console.log("date_split");
            // console.log(date_split);
            var new_date = date_split[2]+"-"+date_split[1]+"-"+date_split[0];
            // console.log("new_date");
            // console.log(new_date);
            var result = new Date(new_date);
            // console.log("result1");
            // console.log(result);
            result.setDate(result.getDate() + Number(days));
            // console.log("result2");
            // console.log(result);
            var year_curr = result.getFullYear();
            var month_curr = result.getMonth()<9?'0'+(result.getMonth()+1):(result.getMonth()+1);
            var date_cur = result.getDate()<10?'0'+result.getDate():result.getDate();
            var full_date = date_cur+'.'+month_curr+'.'+year_curr;
            // console.log("Full_date");
            // console.log(full_date);
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

        $(document).on('change', "#policyosgo-start_date", function (e) {
            from = e.target.value;
            aggregated('FROM');
        })
        $(document).on('change', "#policyosgo-days", function (e) {
            days = e.target.value;
            aggregated('DAYS');
        })
        $(document).on('change', "#policyosgo-end_date", function (e) {
            to = e.target.value;
            aggregated('TO');
        })

        function aggregated(TARGET){
            switch(TARGET){
                case 'FROM':
                    if(!days){
                        days=365;
                    }
                    $('#policyosgo-days').val(days);
                    $('#policyosgo-end_date').attr('min',from)
                    $('#policyosgo-end_date').val(addDays(from, days))
                    break;
                case 'TO':
                    if(!from)
                        break;
                    days = subtractDays(from, to);
                    $('#policyosgo-end_date').attr('min',from)
                    $("#policyosgo-days").val(days);
                    break;
                case 'DAYS':
                    if(!from)
                        break;
                    // console.log(from,days)
                    $('#policyosgo-end_date').attr('min',from)
                    $('#policyosgo-end_date').val(addDays(from, days))
                    break;
            }
        }

    }

    function _getCalc() {

        var form = $('#policy-osgo-form');
        // return false if form still have some validation errors
        if ((1))
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
                    let amount_uzs = response.prem;
                    let end_date = response.end_date;
                    let period_name = response.period_name;
                    $('#policy_price').html(amount_uzs);
                    $('#policyosgo-end_date').val(end_date);
                    $('#calc_period_name').html(period_name);
                    $('.policy_price-block').removeClass('d-none');
                    overlay_right.hide();
                    return true;
                },
                error  : function ()
                {
                    overlay_right.hide();
                    console.log('internal server error');
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

    if ($('body').find('#policyosgo-app_birthday')) {

        var _insurer_birthday = $('#policyosgo-app_birthday');
        var _driver_birthday = $('.driver_birthday');
        var _license_issue_date = $('.license_issue_date');

        _insurer_birthday.datepicker({
            autoHide: true,
            endDate: eighteenYearsAgo,
            maxDate: eighteenYearsAgo,
            format: 'dd.mm.yyyy'
        });

        _driver_birthday.datepicker({
            autoHide: true,
            endDate: eighteenYearsAgo,
            maxDate: eighteenYearsAgo,
            format: 'dd.mm.yyyy'
        });

        $('.license_issue_date').datepicker({
            autoHide: true,
            endDate: new Date(new Date().setDate(new Date().getDate())),
            maxDate: new Date(new Date().setDate(new Date().getDate())),
            format: 'dd.mm.yyyy'
        });
    }

    // Dynamicform
    jQuery(".dynamicform_wrapper_driver").on("afterInsert", function(e, item) {
        $('.item-driver').attr('class', function(i, c){
            return c.replace(/(^|\s)driver-index-\S+/g, '');
        });

        jQuery(".dynamicform_wrapper_driver .item-driver").each(function(index) {
            jQuery(this).addClass("driver-index-" + (index))
            jQuery(this).find('button.check-driver').addClass("check-driver-index-" + (index)).data('index',index);

            $('#policyosgodriver-'+index+'-driver_limit').val(driver_limit_id_cur).attr('readonly', true).parent('div').removeClass('has-error').find('.help-block').html('');
            if ($('#policyosgodriver-'+index+'-birthday').val().length<1) {
                $('#policyosgodriver-'+index+'-birthday').removeAttr('readonly');
                $('#policyosgodriver-'+index+'-pass_sery').removeAttr('readonly');
                $('#policyosgodriver-'+index+'-pass_num').removeAttr('readonly');
            }
            $('#policyosgodriver-'+index+'-relationship_id').attr('readonly', false).parent('div').removeClass('has-error').find('.help-block').html('');
        });

        let last_index = 0;
        jQuery(".dynamicform_wrapper_driver .panel-title-driver").each(function(index) {
            jQuery(this).html(label_driver+": " + (index + 1))
            last_index = index;
        });
        if (last_index >= 4) {
            jQuery('.add-item-driver').addClass("d-none");
        } else {
            jQuery('.add-item-driver').removeClass("d-none");
        }

        $('.driver_birthday').datepicker({
            autoHide: true,
            endDate: eighteenYearsAgo,
            maxDate: eighteenYearsAgo,
            format: 'dd.mm.yyyy'
        });

        $('.license_issue_date').datepicker({
            autoHide: true,
            endDate: new Date(new Date().setDate(new Date().getDate())),
            maxDate: new Date(new Date().setDate(new Date().getDate())),
            format: 'dd.mm.yyyy'
        });

        $('.mask-birthday').mask('00.00.0000');
        $('.mask-date').mask('00.00.0000');

    });

    jQuery(".dynamicform_wrapper_driver").on("beforeDelete", function(e, item) {
        let last_index = 0;
        jQuery(".dynamicform_wrapper_driver .panel-title-driver").each(function(index) {
            jQuery(this).html(label_driver+": " + (index + 1));
            last_index = index;
        });
        if (driver_limit_id_cur == 1 && last_index < 1) {
            return false
        } else {
            return true;
        }
    });

    jQuery(".dynamicform_wrapper_driver").on("afterDelete", function(e) {

        $('.item-driver').attr('class', function(i, c){
            return c.replace(/(^|\s)driver-index-\S+/g, '');
        });

        jQuery(".dynamicform_wrapper_driver .item-driver").each(function(index) {
            console.log("afterDelete",index);
            jQuery(this).addClass("driver-index-" + (index))
            jQuery(this).find('button.check-driver').addClass("check-driver-index-" + (index))
        });

        let last_index = 0;
        jQuery(".dynamicform_wrapper_driver .panel-title-driver").each(function(index) {
            jQuery(this).html(label_driver+": " + (index + 1));
            last_index = index;
        });

        if (last_index >= 4) {
            jQuery('.add-item-driver').addClass("d-none");
        } else {
            jQuery('.add-item-driver').removeClass("d-none");
        }

        $('.driver_birthday').datepicker({
            autoHide: true,
            endDate: eighteenYearsAgo,
            maxDate: eighteenYearsAgo,
            format: 'dd.mm.yyyy'
        });

        $('.license_issue_date').datepicker({
            autoHide: true,
            endDate: new Date(new Date().setDate(new Date().getDate())),
            maxDate: new Date(new Date().setDate(new Date().getDate())),
            format: 'dd.mm.yyyy'
        });

        $('.mask-birthday').mask('00.00.0000');
        $('.mask-date').mask('00.00.0000');
    });


    $(document).on('change', '#policyosgo-owner_is_applicant', function(event) {
        let owner_fy = parseInt($('#owner_fy').val());
        $('#policyosgo-owner_is_pensioner').val('1').trigger('change');
        $('#policyosgo-owner_is_pensioner').attr('readonly', true);
        if (owner_fy == 0) {
            if ($(this).is(':checked')) {
                $('#policyosgo-owner_is_pensioner').removeAttr('readonly');
                $("#applicant-info").addClass('d-none');

                let owner_pinfl = $('#owner_pinfl').val();
                let owner_inn = $('#owner_inn').val();
                let owner_birthday = $('#owner_birthday').val();
                let owner_pass_sery = $('#owner_pass_sery').val();
                let owner_pass_num = $('#owner_pass_num').val();
                let owner_last_name = $('#owner_last_name').val();
                let owner_first_name = $('#owner_first_name').val();
                let owner_middle_name = $('#owner_middle_name').val();
                let owner_address = $('#owner_address').val();

                if (owner_fy == 0 && owner_birthday) {
                    $('#policyosgo-app_birthday').val(owner_birthday).attr('readonly', true).parent('div').removeClass('has-error').find('.help-block').html('');
                    $('#policyosgo-app_pass_sery').val(owner_pass_sery).attr('readonly', true).parent('div').removeClass('has-error').find('.help-block').html('');
                    $('#policyosgo-app_pass_num').val(owner_pass_num).attr('readonly', true).parent('div').removeClass('has-error').find('.help-block').html('');
                    $('#policyosgo-app_last_name').val(owner_last_name).attr('readonly', true).parent('div').removeClass('has-error').find('.help-block').html('');
                    $('#policyosgo-app_first_name').val(owner_first_name).attr('readonly', true).parent('div').removeClass('has-error').find('.help-block').html('');
                    $('#policyosgo-app_middle_name').val(owner_middle_name).attr('readonly', true).parent('div').removeClass('has-error').find('.help-block').html('');
                    $('#policyosgo-app_address').val(owner_address).attr('readonly', true).parent('div').removeClass('has-error').find('.help-block').html('');
                    $('#policyosgo-owner_is_driver').trigger('change');
                }
            } else {
                $("#applicant-info").removeClass('d-none');
                $('#policyosgo-app_birthday').val('').removeAttr('readonly');
                $('#policyosgo-app_pass_sery').val('').removeAttr('readonly');
                $('#policyosgo-app_pass_num').val('').removeAttr('readonly');
                $('#policyosgo-app_last_name').val('').removeAttr('readonly');
                $('#policyosgo-app_first_name').val('').removeAttr('readonly');
                $('#policyosgo-app_middle_name').val('').removeAttr('readonly');
                $('#policyosgo-app_address').val('').removeAttr('readonly');
            }
        } else {
            $("#applicant-info").removeClass('d-none');
            $("#driver-info").addClass('d-none');
            $(this).prop('checked', false);
        }
    })

    $(document).on('change', '.change-driver_limit', function(event) {
        let add_driver_label = $(this).data('add_driver_label');
        let driver_label = $(this).data('driver_label');
        let driver_limit_val = $(this).val();
        if (add_driver_label && add_driver_label != undefined) {
            $(".add-item-driver").html(add_driver_label);
            driver_limit_id_cur = driver_limit_val;
            label_driver = driver_label;

            jQuery(".dynamicform_wrapper_driver .item-driver").each(function(index) {
                console.log("afterCHange Limit",index);
                $('#policyosgodriver-'+index+'-driver_limit').val(driver_limit_val).attr('readonly', true).parent('div').removeClass('has-error').find('.help-block').html('');
                $('#policyosgodriver-'+index+'-relationship_id').attr('readonly', false).parent('div').removeClass('has-error').find('.help-block').html('');

            });

            jQuery(".dynamicform_wrapper_driver .panel-title-driver").each(function(index) {
                jQuery(this).html(label_driver+": " + (index + 1))
            });

            jQuery(".dynamicform_wrapper_driver .add-item-driver").each(function(index) {
                jQuery(this).html(add_driver_label)
            });
        }

        let owner_fy = parseInt($('#owner_fy').val());
        if (driver_limit_val == 1 && owner_fy == 0) {
            jQuery('.owner_is_driver-block').removeClass('d-none');

            let last_index = 0;
            jQuery(".dynamicform_wrapper_driver .item-driver").each(function(index) {
                last_index++;
            });
            if (last_index == 0) {
                $('.add-item-driver').trigger('click');
            }

            jQuery("#policyosgo-owner_is_driver").prop("checked", true);
            if ($('#policyosgo-owner_is_driver').is(':checked')) {
                jQuery("#policyosgo-owner_is_driver").prop("checked", true);
                jQuery("#policyosgo-owner_is_driver").trigger("change");
            }
        } else if (driver_limit_val == 1 && owner_fy == 1) {

            let last_index = 0;
            jQuery(".dynamicform_wrapper_driver .item-driver").each(function(index) {
                last_index++;
            });
            if (last_index == 0) {
                $('.add-item-driver').trigger('click');
            }

            jQuery("#policyosgo-owner_is_driver").prop("checked", false);
            if (!$('#policyosgo-owner_is_driver').is(':checked')) {
                jQuery("#policyosgo-owner_is_driver").prop("checked", false);
                jQuery("#policyosgo-owner_is_driver").trigger("change");
            }
            jQuery('.owner_is_driver-block').addClass('d-none');
        } else {
            jQuery("#policyosgo-owner_is_driver").prop("checked", false);
            if (!$('#policyosgo-owner_is_driver').is(':checked')) {
                jQuery("#policyosgo-owner_is_driver").prop("checked", false);
                jQuery("#policyosgo-owner_is_driver").trigger("change");
            }
            jQuery('.owner_is_driver-block').addClass('d-none');
        }
    })

    function _getTechPassData(tech_pass_series, tech_pass_number, gov_number) {

        // return false if form still have some validation errors
        if ((tech_pass_series && tech_pass_number && gov_number))
        {
            overlay.show();
            // submit form
            $.ajax({
                url    : url_post_tech_pass_data,
                type   : 'post',
                data   : {csrfParam: csrfToken, tech_pass_series: tech_pass_series, tech_pass_number: tech_pass_number, vehicle_gov_number: gov_number},
                success: function (response)
                {
                    overlay.hide();
                    console.log(response);
                    if (response.ERROR > 0) {
                        swal({
                            title: "Error",
                            text: response.ERROR_MESSAGE,
                            icon: "error",
                        });
                    } else if ( (response.ERROR == 0) && response) {
                        data = response;
                        if (data.MODEL_NAME) {

                            $('#policyosgo-vehicle_gov_number').attr('readonly', true);
                            $('#policyosgo-tech_pass_series').attr('readonly', true);
                            $('#policyosgo-tech_pass_number').attr('readonly', true);

                            $('#policyosgo-vehicle_model_name').val(data.MODEL_NAME);
                            $("#check-vehicle").removeClass('check').removeClass('btn-success').addClass('clear').addClass('bg-danger');

                        }
                        if (data.VEHICLE_TYPE_NAME) {
                            $('#policyosgo-vehicle_type_id').val(data.VEHICLE_TYPE_NAME);
                            $('#calc_vehicle_type_name').html(data.VEHICLE_TYPE_NAME);
                        }
                        if (data.VEHICLE_TYPE_ID) {
                            $('#vehicle_type_id').val(data.VEHICLE_TYPE_ID);
                        }
                        if (data.REGION_NAME) {
                            $('#policyosgo-region_id').val(data.REGION_NAME);
                            $('#calc_region_name').html(data.REGION_NAME);
                        }

                        if (data.SEATS) {
                            $('#policyosgo-vehicle_seats_count').val(data.SEATS);
                            // $('#vehicle_seats_count').val(data.SEATS);
                        }

                        if (data.ISSUE_YEAR) {
                            $('#policyosgo-vehicle_issue_year').val(data.ISSUE_YEAR);
                        } else {
                            $('#policyosgo-vehicle_issue_year').focus();
                            $('#policyosgo-vehicle_issue_year').removeAttr('readonly');
                        }
                        if (data.BODY_NUMBER) {
                            $('#policyosgo-vehicle_body_number').val(data.BODY_NUMBER).attr('readonly', true);
                        } else {
                            $('#policyosgo-vehicle_body_number').focus();
                            $('#policyosgo-vehicle_body_number').removeAttr('readonly');
                        }
                        if (data.ENGINE_NUMBER) {
                            $('#policyosgo-vehicle_engine_number').val(data.ENGINE_NUMBER).attr('readonly', true);
                        } else {
                            $('#policyosgo-vehicle_engine_number').focus();
                            $('#policyosgo-vehicle_engine_number').removeAttr('readonly');
                        }
                        if (data.ORGNAME) {
                            $('#policyosgo-owner_orgname').val(data.ORGNAME);
                        }
                        if (data.VEHICLE_TERRITORY_ID) {
                            $('#region_id').val(data.VEHICLE_TERRITORY_ID).attr('readonly', true).parent('div').removeClass('has-error').find('.help-block').html('');
                        }

                        if (data.REGION_ID) {
                            $('#policyosgo-owner_region').val(data.REGION_ID).attr('readonly', true).parent('div').removeClass('has-error').find('.help-block').html('');
                        } else {
                            // $(".owner-region-info").removeClass('d-none');
                            // $('#policyosgo-owner_region').removeAttr('readonly').parent('div');
                        }

                        if (data.DISTRICT_ID) {
                            $('#policyosgo-owner_district').val(data.DISTRICT_ID).change().attr('readonly', true).parent('div').removeClass('has-error').find('.help-block').html('');
                        } else {
                            // $(".owner-region-info").removeClass('d-none');
                            // $('#policyosgo-owner_district').removeAttr('readonly').parent('div');
                        }


                        $('#owner_inn').val(data.INN);
                        $('#owner_pinfl').val(data.PINFL);
                        $('#owner_pass_sery').val(data.PASSPORT_SERIES);
                        $('#owner_pass_num').val(data.PASSPORT_NUMBER);
                        $('#owner_last_name').val(data.LAST_NAME);
                        $('#owner_first_name').val(data.FIRST_NAME);
                        $('#owner_middle_name').val(data.MIDDLE_NAME);
                        $('#owner_address').val(data.ADDRESS);
                        $('#policyosgo-owner_address').val(data.ADDRESS);
                        $('#owner_birthday').val(data.BIRTHDAY);
                        $('#owner_fy').val(data.FY);

                        if (data.FY == 1) {
                            $("#policyosgo-owner_is_applicant").prop('checked', false);
                            $(".owner_is_driver-block").addClass('d-none');
                            $(".inn-block").removeClass('d-none');
                            $(".pinfl-block").addClass('d-none');
                            $(".owner-is-app").addClass('d-none');
                            if (data.INN) {
                                $('#policyosgo-owner_inn').val(data.INN).attr('readonly', true);
                            } else {
                                $('#policyosgo-owner_inn').removeAttr('readonly');
                            }
                        } else {
                            $(".owner-is-app").removeClass('d-none');
                            $(".pinfl-block").removeClass('d-none');
                            $(".inn-block").addClass('d-none');
                            if (data.PINFL) {
                                $('#policyosgo-owner_pinfl').val(data.PINFL).attr('readonly', true);
                            } else {
                                $('#policyosgo-owner_pinfl').val(data.PINFL).removeAttr('readonly');
                            }
                            if (data.PASSPORT_SERIES) {
                                $('#policyosgo-owner_pass_sery').val(data.PASSPORT_SERIES).attr('readonly', true);
                            } else {
                                $('#policyosgo-owner_pass_sery').val(data.PASSPORT_SERIES).removeAttr('readonly');
                            }
                            if (data.PASSPORT_NUMBER) {
                                $('#policyosgo-owner_pass_num').val(data.PASSPORT_NUMBER).attr('readonly', true);
                            } else {
                                $('#policyosgo-owner_pass_num').val(data.PASSPORT_NUMBER).removeAttr('readonly');
                            }

                            if ($('#policyosgo-owner_is_applicant').is(':checked')) {
                                if (data.BIRTHDAY) {
                                    $('#policyosgo-app_birthday').val(data.BIRTHDAY).attr('readonly', true);
                                }
                                if (data.PASSPORT_SERIES) {
                                    $('#policyosgo-app_pass_sery').val(data.PASSPORT_SERIES).attr('readonly', true);
                                }
                                if (data.PASSPORT_NUMBER) {
                                    $('#policyosgo-app_pass_num').val(data.PASSPORT_NUMBER).attr('readonly', true);
                                }
                                if (data.ISPENSIONER && data.ISPENSIONER>1) {
                                    $('.owner-pensioner-info').removeClass('d-none');
                                } else {
                                    $('.owner-pensioner-info').addClass('d-none');
                                }

                                $('#policyosgo-app_last_name').val(data.LAST_NAME);
                                $('#policyosgo-app_first_name').val(data.FIRST_NAME);
                                $('#policyosgo-app_middle_name').val(data.MIDDLE_NAME);
                                $('#policyosgo-app_address').val(data.ADDRESS);

                                $(".app-name-address-info").addClass('d-none');

                                $("#driver-info").removeClass('d-none');

                                $("#submit-button").removeClass('d-none');

                                $('#policyosgo-owner_is_driver').trigger('change');
                            }

                        }

                        $("#vehicle-info").removeClass('d-none');
                        $("#owner-info").removeClass('d-none');
                        if (!($('#policyosgo-owner_is_applicant').is(':checked'))) {
                            $("#applicant-info").removeClass('d-none');
                        } else {
                            $("#driver-info").removeClass('d-none');
                        }

                        _getCalc();
                        if (data.FY == 1) {
                            if (data.INN) {
                                $('#policyosgo-app_phone').focus();
                            } else {
                                $('#policyosgo-owner_inn').focus();
                            }
                        } else {
                            if (data.PASSPORT_SERIES) {
                                $('#policyosgo-app_phone').focus();
                            } else {
                                $('#policyosgo-owner_pass_sery').focus();
                            }
                        }

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

    function _getPassBirthdayData(birthday, pass_series, pass_number, driver_id = null) {

        // return false if form still have some validation errors
        if ((pass_series && pass_number && birthday))
        {
            overlay.show();
            // submit form
            $.ajax({
                url    : url_post_pass_birthday,
                type   : 'post',
                data   : {csrfParam: csrfToken, pass_series: pass_series, pass_number: pass_number, birthday: birthday, driver_id: driver_id},
                success: function (response)
                {
                    overlay.hide();
                    console.log(response);
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

                            $('#policyosgo-app_birthday').attr('readonly', true);
                            $('#policyosgo-app_pass_sery').attr('readonly', true);
                            $('#policyosgo-app_pass_num').attr('readonly', true);

                            $("#check-applicant").removeClass('check').addClass('clear').removeClass('bg-primary').addClass('bg-danger');

                            $('#policyosgo-app_last_name').val(data.LAST_NAME).attr('readonly', true).parent('div').removeClass('has-error').find('.help-block').html('');
                            $('#policyosgo-app_first_name').val(data.FIRST_NAME).attr('readonly', true).parent('div').removeClass('has-error').find('.help-block').html('');
                            $('#policyosgo-app_pinfl').val(data.PINFL).attr('readonly', true).parent('div').removeClass('has-error').find('.help-block').html('');
                            $('#policyosgo-app_region').val(data.REGION_ID).attr('readonly', true).parent('div').removeClass('has-error').find('.help-block').html('');
                            $('#policyosgo-app_district').val(data.DISTRICT_ID).attr('readonly', true).parent('div').removeClass('has-error').find('.help-block').html('');
                            if (data.MIDDLE_NAME) {
                                $('#policyosgo-app_middle_name').val(data.MIDDLE_NAME).attr('readonly', true).parent('div').removeClass('has-error').find('.help-block').html('');
                            } else {
                                $('#policyosgo-app_middle_name').removeAttr('readonly').parent('div').removeClass('has-error').find('.help-block').html('');
                            }
                            if (data.ADDRESS) {
                                $('#policyosgo-app_address').val(data.ADDRESS).attr('readonly', true).parent('div').removeClass('has-error').find('.help-block').html('');
                            } else {
                                $('#policyosgo-app_address').removeAttr('readonly').parent('div').removeClass('has-error').find('.help-block').html('');
                            }

                            $(".app-name-address-info").removeClass('d-none');
                            $("#driver-info").removeClass('d-none');
                            $("#submit-button").removeClass('d-none');
                        } else if (driver_id) {

                            $("button.check-driver.check-driver-index-"+driver_id).removeClass('check').addClass('clear');
                            $('#policyosgodriver-'+driver_id+'-_full_name').val(data.FULL_NAME_TMP).attr('readonly', true).parent('div').removeClass('has-error').find('.help-block').html('');

                            if (data.LICENSE_SERIA) {
                                $('#policyosgodriver-'+driver_id+'-license_series').val(data.LICENSE_SERIA).attr('readonly', true).parent('div').removeClass('has-error').find('.help-block').html('');
                            } else {
                                $('#policyosgodriver-'+driver_id+'-license_series').removeAttr('readonly').parent('div').removeClass('has-error').find('.help-block').html('');
                            }

                            if (data.LICENSE_NUMBER) {
                                $('#policyosgodriver-'+driver_id+'-license_number').val(data.LICENSE_NUMBER).attr('readonly', true).parent('div').removeClass('has-error').find('.help-block').html('');
                            } else {
                                $('#policyosgodriver-'+driver_id+'-license_number').removeAttr('readonly').parent('div').removeClass('has-error').find('.help-block').html('');
                            }

                            if (data.ISSUE_DATE) {
                                $('#policyosgodriver-'+driver_id+'-license_issue_date').val(data.ISSUE_DATE).attr('readonly', true).parent('div').removeClass('has-error').find('.help-block').html('');
                            } else {
                                $('#policyosgodriver-'+driver_id+'-license_issue_date').removeAttr('readonly').parent('div').removeClass('has-error').find('.help-block').html('');
                            }

                            if (data.PINFL) {
                                $('#policyosgodriver-'+driver_id+'-pinfl').val(data.PINFL).attr('readonly', true).parent('div').removeClass('has-error').find('.help-block').html('');
                            } else {
                                $('#policyosgodriver-'+driver_id+'-pinfl').removeAttr('readonly').parent('div').removeClass('has-error').find('.help-block').html('');
                            }

                            $('#policyosgodriver-'+driver_id+'-relationship_id').removeAttr('readonly').parent('div').removeClass('has-error').find('.help-block').html('');
                            $('.driver-index-'+driver_id+' .driver-license-info').removeClass('d-none');

                            if ($('#policyosgo-owner_is_driver').is(':checked')) {
                                $('.driver-index-0 .relationship-block').addClass('d-none');
                            } else {
                                $('.relationship-block').removeClass('d-none');
                                $('#policyosgodriver-'+driver_id+'-relationship_id').removeAttr('readonly').parent('div').removeClass('has-error').find('.help-block').html('');
                                $('#policyosgodriver-'+driver_id+'-relationship_id').focus();
                            }
                        }

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

    function _getPasspinflData(pinfl, pass_series, pass_number, driver_id = null) {

        let vehicle_gov_number = $('#policyosgo-vehicle_gov_number').val();
        // return false if form still have some validation errors
        if ((pass_series && pass_number && pinfl && vehicle_gov_number))
        {
            overlay.show();
            // submit form
            $.ajax({
                url    : url_post_pass_pinfl,
                type   : 'post',
                data   : {csrfParam: csrfToken, pass_series: pass_series, pass_number: pass_number, pinfl: pinfl, driver_id: driver_id, vehicle_gov_number: vehicle_gov_number},
                success: function (response)
                {
                    overlay.hide();
                    if (response.ERROR > 0) {
                        hideSubmitButton()
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
                    } else if ( (response.ERROR == 0) && response) {
                        data = response;
                        showSubmitButton()
                        if (driver_id == null) {
                            $(".app-name-address-info").removeClass('d-none');
                            $("#driver-info").removeClass('d-none');
                            $("#submit-button").removeClass('d-none');

                            if ($('#policyosgo-owner_is_applicant').is(':checked')) {

                                if (data.ISPENSIONER && data.ISPENSIONER>1) {
                                    $('.owner-pensioner-info').removeClass('d-none');
                                } else {
                                    $('.owner-pensioner-info').addClass('d-none');
                                }
                            }
                        } else if (driver_id) {

                            $("button.check-driver.check-driver-index-"+driver_id).removeClass('check').addClass('clear');
                            $('#policyosgodriver-'+driver_id+'-_full_name').val(data.FULL_NAME_TMP).attr('readonly', true).parent('div').removeClass('has-error').find('.help-block').html('');

                            if (data.LICENSE_SERIA) {
                                $('#policyosgodriver-'+driver_id+'-license_series').val(data.LICENSE_SERIA).attr('readonly', true).parent('div').removeClass('has-error').find('.help-block').html('');
                            } else {
                                $('#policyosgodriver-'+driver_id+'-license_series').removeAttr('readonly').parent('div').removeClass('has-error').find('.help-block').html('');
                            }

                            if (data.LICENSE_NUMBER) {
                                $('#policyosgodriver-'+driver_id+'-license_number').val(data.LICENSE_NUMBER).attr('readonly', true).parent('div').removeClass('has-error').find('.help-block').html('');
                            } else {
                                $('#policyosgodriver-'+driver_id+'-license_number').removeAttr('readonly').parent('div').removeClass('has-error').find('.help-block').html('');
                            }

                            if (data.ISSUE_DATE) {
                                $('#policyosgodriver-'+driver_id+'-license_issue_date').val(data.ISSUE_DATE).attr('readonly', true).parent('div').removeClass('has-error').find('.help-block').html('');
                            } else {
                                $('#policyosgodriver-'+driver_id+'-license_issue_date').removeAttr('readonly').parent('div').removeClass('has-error').find('.help-block').html('');
                            }

                            if (data.PINFL) {
                                $('#policyosgodriver-'+driver_id+'-pinfl').val(data.PINFL).attr('readonly', true).parent('div').removeClass('has-error').find('.help-block').html('');
                            } else {
                                $('#policyosgodriver-'+driver_id+'-pinfl').removeAttr('readonly').parent('div').removeClass('has-error').find('.help-block').html('');
                            }

                            $('#policyosgodriver-'+driver_id+'-relationship_id').removeAttr('readonly').parent('div').removeClass('has-error').find('.help-block').html('');
                            $('.driver-index-'+driver_id+' .driver-license-info').removeClass('d-none');

                            if ($('#policyosgo-owner_is_driver').is(':checked')) {
                                $('.driver-index-0 .relationship-block').addClass('d-none');
                            } else {
                                $('.relationship-block').removeClass('d-none');
                                $('#policyosgodriver-'+driver_id+'-relationship_id').removeAttr('readonly').parent('div').removeClass('has-error').find('.help-block').html('');
                                $('#policyosgodriver-'+driver_id+'-relationship_id').focus();
                            }
                        }

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
            $(".pinfl-block").addClass('d-none');
            $(".inn-block").addClass('d-none');
            $(".app-name-address-info").addClass('d-none');
            $("#vehicle-info").addClass('d-none');
            $("#owner-info").addClass('d-none');
            $("#applicant-info").addClass('d-none');
            $("#driver-info").addClass('d-none');
            $("#submit-button").addClass('d-none');
        } else {
            $(block).addClass('d-none');
        }
    }

    // VEHICLE
    $(document).on('click', '#check-vehicle', function(e) {
        if ($(this).hasClass('clear')) {
            $('#policyosgo-vehicle_gov_number').val('').removeAttr('readonly').focus();
            $('#policyosgo-tech_pass_series').val('').removeAttr('readonly');
            $('#policyosgo-tech_pass_number').val('').removeAttr('readonly').trigger('keyup');
            $(this).addClass('check').removeClass('clear').removeClass('bg-danger').addClass('btn-success');
        } else if ($(this).hasClass('check')) {
            $('#policyosgo-tech_pass_number').trigger('keyup');
        }
    })

    $(document).on('keyup', '#policyosgo-vehicle_gov_number', function(e) {
        let maxLength = $(this).attr('maxlength');
        if ($(this).val().length >= maxLength) {
            $('#policyosgo-tech_pass_series').focus();
        }
    })

    $(document).on('keyup', '#policyosgo-tech_pass_series', function(e) {
        let maxLength = $(this).attr('maxlength');
        if ($(this).val().length >= maxLength) {
            $('#policyosgo-tech_pass_number').focus();
        }
    })

    $(document).on('keyup', '.on-change-vehicle', function(e) {
        let vehicle_gov_number = $('#policyosgo-vehicle_gov_number');
        let tech_pass_series = $('#policyosgo-tech_pass_series');
        let tech_pass_number = $('#policyosgo-tech_pass_number');
        if (vehicle_gov_number.val().length >= 8 && tech_pass_series.val().length >= 3 && tech_pass_number.val().length >= 7) {
            $(this).trigger('change');
            _getTechPassData(tech_pass_series.val(), tech_pass_number.val(), vehicle_gov_number.val());
        } else {
            hideInfoBlock();
        }
    })

    $(document).on('keyup', '#policyosgo-owner_pinfl', function(e) {
        let maxLength = $(this).attr('maxlength');
        if ($(this).val().length >= maxLength) {
            $('#policyosgo-owner_pass_sery').focus();
        }
    })

    $(document).on('keyup', '#policyosgo-owner_pass_sery', function(e) {
        let maxLength = $(this).attr('maxlength');
        if ($(this).val().length >= maxLength) {
            $('#policyosgo-owner_pass_num').focus();
        }
    })

    $(document).on('keyup', '#policyosgo-owner_pass_num', function(e) {
        let maxLength = $(this).attr('maxlength');
        if ($(this).val().length >= maxLength) {
            $('#policyosgo-app_phone').focus();
        }
    })

    // ON change owner info
    $(document).on('keyup', '.on-change-owner-fy-info', function(e) {
        let owner_pinfl = $('#policyosgo-owner_pinfl');
        let owner_pass_sery = $('#policyosgo-owner_pass_sery');
        let owner_pass_num = $('#policyosgo-owner_pass_num');

        let owner_pinfl_maxLength = owner_pinfl.attr('maxlength');
        let owner_pass_sery_maxLength = owner_pass_sery.attr('maxlength');
        let owner_pass_num_maxLength = owner_pass_num.attr('maxlength');
        if (owner_pinfl.val().length >= owner_pinfl_maxLength && owner_pass_sery.val().length >= owner_pass_sery_maxLength && owner_pass_num.val().length >= owner_pass_num_maxLength) {
            $(this).trigger('change');
            _getPasspinflData(owner_pinfl.val(), owner_pass_sery.val(), owner_pass_num.val(), null);
        }
    })

    // APPLICANT
    $(document).on('click', '#check-applicant', function(e) {
        if ($(this).hasClass('clear')) {
            $('#policyosgo-app_birthday').val('').removeAttr('readonly').focus();
            $('#policyosgo-app_pass_sery').val('').removeAttr('readonly');
            $('#policyosgo-app_pass_num').val('').removeAttr('readonly').trigger('keyup');
            $(this).addClass('check').removeClass('clear').removeClass('bg-danger').addClass('bg-primary');
        } else if ($(this).hasClass('check')) {
            $('#policyosgo-app_birthday').datepicker('hide');
            $('#policyosgo-app_pass_num').trigger('keyup');
        }
    })

    $(document).on('keyup', '#policyosgo-app_birthday', function(e) {
        let maxLength = $(this).attr('maxlength');
        if ($(this).val().length >= maxLength) {
            $(this).trigger('change');
            $(this).datepicker('hide');
            $('#policyosgo-app_pass_sery').focus();
        }
    })

    $(document).on('keyup', '#policyosgo-app_pass_sery', function(e) {
        let maxLength = $(this).attr('maxlength');
        if ($(this).val().length >= maxLength) {
            $('#policyosgo-app_pass_num').focus();
        }
    })

    $(document).on('keyup', '.on-change-app-info', function(e) {
        let app_birthday = $('#policyosgo-app_birthday');
        let app_pass_sery = $('#policyosgo-app_pass_sery');
        let app_pass_num = $('#policyosgo-app_pass_num');

        let app_birthday_maxLength = app_birthday.attr('maxlength');
        let app_pass_sery_maxLength = app_pass_sery.attr('maxlength');
        let app_pass_num_maxLength = app_pass_num.attr('maxlength');
        if (app_birthday.val().length >= app_birthday_maxLength && app_pass_sery.val().length >= app_pass_sery_maxLength && app_pass_num.val().length >= app_pass_num_maxLength) {
            $(this).trigger('change');
            _getPassBirthdayData(app_birthday.val(), app_pass_sery.val(), app_pass_num.val(), null);
        } else {
            $('#policyosgo-app_last_name').val('').removeAttr('readonly');
            $('#policyosgo-app_first_name').val('').removeAttr('readonly');
            $('#policyosgo-app_middle_name').val('').removeAttr('readonly');
            $('#policyosgo-app_address').val('').removeAttr('readonly');
        }
    })

    // DRIVER
    $(document).on('click', 'button.check-driver', function(e) {
        let driver_id = $(this).data('index') ? $(this).data('index') : 0;
        if ($(this).hasClass('clear')) {
            $('#policyosgodriver-'+driver_id+'-birthday').val('').removeAttr('readonly').focus();
            $('#policyosgodriver-'+driver_id+'-pass_sery').val('').removeAttr('readonly');
            $('#policyosgodriver-'+driver_id+'-pass_num').val('').removeAttr('readonly');
            $('#policyosgodriver-'+driver_id+'-_full_name').val('').removeAttr('readonly');
            $('#policyosgodriver-'+driver_id+'-pinfl').val('').removeAttr('readonly');
            $('#policyosgodriver-'+driver_id+'-license_series').val('').removeAttr('readonly');
            $('#policyosgodriver-'+driver_id+'-license_number').val('').removeAttr('readonly');
            $('#policyosgodriver-'+driver_id+'-license_issue_date').val('').removeAttr('readonly');
            $('#policyosgodriver-'+driver_id+'-relationship_id').val('').removeAttr('readonly');
            $('.driver-index-'+driver_id+' .driver-license-info').addClass('d-none');
            $(this).addClass('check').removeClass('clear');
        } else if ($(this).hasClass('check')) {
            $('.driver_birthday').datepicker('hide');
            $('#policyosgodriver-'+driver_id+'-pass_num').trigger('keyup');
        }
    })

    $(document).on('change', '#policyosgo-owner_is_driver', function(event) {
        if ($(this).is(':checked')) {
            let owner_birthday = $('#owner_birthday').val();
            let owner_pass_sery = $('#policyosgo-owner_pass_sery').val();
            let owner_pass_num = $('#policyosgo-owner_pass_num').val();

            if (owner_birthday) {
                $('#policyosgodriver-0-birthday').val(owner_birthday).attr('readonly', true).parent('div').removeClass('has-error').find('.help-block').html('');
            }
            if (owner_birthday && owner_pass_sery && owner_pass_num) {
                $('#policyosgodriver-0-birthday').val(owner_birthday).attr('readonly', true).parent('div').removeClass('has-error').find('.help-block').html('');
                $('#policyosgodriver-0-pass_sery').val(owner_pass_sery).attr('readonly', true).parent('div').removeClass('has-error').find('.help-block').html('');
                $('#policyosgodriver-0-pass_num').val(owner_pass_num).attr('readonly', true).parent('div').removeClass('has-error').find('.help-block').html('');
                $('#policyosgodriver-0-pass_num').trigger('keyup');
            }
        } else {
            $('#policyosgodriver-0-birthday').val('').removeAttr('readonly').focus();
            $('#policyosgodriver-0-pass_sery').val('').removeAttr('readonly');
            $('#policyosgodriver-0-pass_num').val('').removeAttr('readonly');
            $('#policyosgodriver-0-_full_name').val('').removeAttr('readonly');
            $('#policyosgodriver-0-pinfl').val('').removeAttr('readonly');
            $('#policyosgodriver-0-license_series').val('').removeAttr('readonly');
            $('#policyosgodriver-0-license_number').val('').removeAttr('readonly');
            $('#policyosgodriver-0-license_issue_date').val('').removeAttr('readonly');
            $('#policyosgodriver-0-relationship_id').val('').removeAttr('readonly');
        }
    })

    $(document).on('change', '.change-resident_id', function(e) {
        let attr_id = $(this).attr('id');
        const attr_id_ar = attr_id.split("-");
        let driver_id = (attr_id_ar[1]) ? attr_id_ar[1] : null;
        if ($(this).val() == $(this).data('id-uz')) {
            $('#policyosgo-owner_is_driver').prop('checked', false).removeAttr('readonly');
            $('#policyosgodriver-'+driver_id+'-birthday').datepicker('hide').trigger('change');
            $('.driver-index-'+driver_id+' .driver-name-info').addClass('d-none');
        } else {
            $("button.check-driver.check-driver-index-"+driver_id).addClass('check').removeClass('clear');
            $('#policyosgo-owner_is_driver').prop('checked', false).attr('readonly', true);
            $('.driver-index-'+driver_id+' .driver-license-info.pinfl-block').addClass('d-none');
            $('.driver-index-'+driver_id+' .driver-name-info').removeClass('d-none');
            $('.driver-index-'+driver_id+' .license-block').removeClass('d-none');
            $('.driver-index-'+driver_id+' .relationship-block').removeClass('d-none');
            if ($('#policyosgodriver-'+driver_id+'-birthday').val() && $('#policyosgodriver-'+driver_id+'-pass_sery').val() && $('#policyosgodriver-'+driver_id+'-pass_num').val()) {
                $('#policyosgodriver-'+driver_id+'-first_name').focus();
            } else if ($('#policyosgodriver-'+driver_id+'-birthday').val() && $('#policyosgodriver-'+driver_id+'-pass_sery').val()) {
                $('#policyosgodriver-'+driver_id+'-pass_num').focus();
            } else if ($('#policyosgodriver-'+driver_id+'-birthday').val()) {
                $('#policyosgodriver-'+driver_id+'-pass_sery').focus();
            } else {
                $('#policyosgodriver-'+driver_id+'-birthday').focus();
            }
            $('#policyosgodriver-'+driver_id+'-resident_id').removeAttr('readonly');
            $('#policyosgodriver-'+driver_id+'-birthday').removeAttr('readonly');
            $('#policyosgodriver-'+driver_id+'-pass_sery').removeAttr('readonly');
            $('#policyosgodriver-'+driver_id+'-pass_num').removeAttr('readonly');
            $('#policyosgodriver-'+driver_id+'-first_name').val('').removeAttr('readonly');
            $('#policyosgodriver-'+driver_id+'-last_name').val('').removeAttr('readonly');
            $('#policyosgodriver-'+driver_id+'-middle_name').val('').removeAttr('readonly');
            $('#policyosgodriver-'+driver_id+'-license_series').val('').removeAttr('readonly');
            $('#policyosgodriver-'+driver_id+'-license_number').val('').removeAttr('readonly');
            $('#policyosgodriver-'+driver_id+'-license_issue_date').val('').removeAttr('readonly');
            $('#policyosgodriver-'+driver_id+'-relationship_id').val('').removeAttr('readonly');
        }
    })

    $(document).on('keyup', '.on-change-driver-info', function(e) {
        let attr_id = $(this).attr('id');
        const attr_id_ar = attr_id.split("-");
        let driver_id = (attr_id_ar[1]) ? attr_id_ar[1] : null;
        let param_name = (attr_id_ar[2]) ? attr_id_ar[2] : null;
        let maxLength = 0;
        if (param_name && driver_id) {
            switch (param_name) {
                case 'birthday' :
                    maxLength = $('#policyosgodriver-'+driver_id+'-'+param_name).attr('maxlength');
                    if ($('#policyosgodriver-'+driver_id+'-'+param_name).val().length >= maxLength) {
                        $(this).trigger('change');
                        $(this).datepicker('hide');
                        $('#policyosgodriver-'+driver_id+'-pass_sery').focus();
                    }
                    break;
                case 'pass_sery' :
                    maxLength = $('#policyosgodriver-'+driver_id+'-'+param_name).attr('maxlength');
                    if ($('#policyosgodriver-'+driver_id+'-'+param_name).val().length >= maxLength) {
                        $('#policyosgodriver-'+driver_id+'-pass_num').focus();
                    }
                    break;
            }
        }
        if (driver_id) {
            let birthday = $('#policyosgodriver-'+driver_id+'-birthday');
            let pass_sery = $('#policyosgodriver-'+driver_id+'-pass_sery');
            let pass_num = $('#policyosgodriver-'+driver_id+'-pass_num');

            let birthday_maxLength = birthday.attr('maxlength');
            let pass_sery_maxLength = pass_sery.attr('maxlength');
            let pass_num_maxLength = pass_num.attr('maxlength');
            if (birthday.val().length >= birthday_maxLength && pass_sery.val().length >= pass_sery_maxLength && pass_num.val().length >= pass_num_maxLength) {
                $(this).trigger('change');
                $('#policyosgodriver-'+driver_id+'-birthday').datepicker('hide');
                $('#policyosgodriver-'+driver_id+'-birthday').trigger('change');
                _getPassBirthdayData(birthday.val(), pass_sery.val(), pass_num.val(), driver_id);
            } else {
                $('#policyosgodriver-'+driver_id+'-_full_name').val('').removeAttr('readonly');
                $('#policyosgodriver-'+driver_id+'-pinfl').val('').removeAttr('readonly');
                $('#policyosgodriver-'+driver_id+'-license_series').val('').removeAttr('readonly');
                $('#policyosgodriver-'+driver_id+'-license_number').val('').removeAttr('readonly');
                $('#policyosgodriver-'+driver_id+'-license_issue_date').val('').removeAttr('readonly');
                $('#policyosgodriver-'+driver_id+'-relationship_id').val('').removeAttr('readonly');
                $('.driver-index-'+driver_id+' .driver-license-info').addClass('d-none');
            }
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

    function hideSubmitButton()
    {
        $("#submit-button").addClass('d-none');
    }

    function showSubmitButton()
    {
        $("#submit-button").removeClass('d-none');
    }
})