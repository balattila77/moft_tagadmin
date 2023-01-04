

jQuery(document).ready(function ($) {
    $(".datepicker").datepicker({
        'dateFormat': 'yy-mm-dd',
        'regional': 'hu'
    });

    function showError(key, val) {
        formElement = $("[name=" + key + "]");
        container = formElement.closest('.formfield');
        container.addClass('has-error');
        container.append('<span class="help-block">' + val + '</span>');
    }

    $(".selectMOFTtype").click(function () {
        //alert($(this).attr('data-display-value'));
        $("#totalbrutto>span").html($(this).attr('data-display-value'));
    });

    PDFObject.embed("https://fajdalom-tarsasag.hu/wp-content/uploads/2013/07/F%C3%A1jdalom_T%C3%A1rsas%C3%A1g_Alapszab%C3%A1ly_2015.06.05._tiszta.pdf", "#charter");
    PDFObject.embed("https://fajdalom-tarsasag.hu/wp-content/uploads/2020/09/privacy_policy_v1_1.pdf", "#privacy_policy");

    var subscriberForm = $('#tagadmin_register_form');
    var subscriberUrl = subscriberForm.attr('action');
    var profileForm = $('#tagadmin_profile_form');
    var profileUrl = profileForm.attr('action');
    //console.log(subscriberUrl);

    subscriberForm.bind('submit', function (e) {
        e.preventDefault();
        $form = $(this);
        $form.find(".has-error").removeClass("has-error");
        $form.find(".help-block").remove();
        var form_data = $form.serialize();
        $.ajax({
            'method': 'post',
            'url': subscriberUrl,
            'data': form_data,
            'dataType': 'json',
            'cache': false,
            'success': function (data, textStatus) {
                if (data.status === 1) {
                    $form[0].reset();
                    $("#retStatus").addClass('success');
                } else {
                    // a mentés hibába ütközött
                    $.each(data.errors, function (key, value) {
                        showError(key, value);
                    });
                    $("#retStatus").addClass('warning');
                }
                $("#retStatus").html(data.message + " " + data.error);
                $("#retStatus").show();
                $('html, body').stop().animate({
                    'scrollTop': $("#retStatus").offset().top - 40
                }, 900, 'swing', function () {
                    window.location.hash = $("#retStatus");
                });
            },
            'error': function (jqXHR, textStatus, errorThrown) {

            }
        });
    });
    
    profileForm.bind('submit', function (e) {
        e.preventDefault();
        $form = $(this);
        $form.find(".has-error").removeClass("has-error");
        $form.find(".help-block").remove();
        var form_data = $form.serialize();
        $.ajax({
            'method': 'post',
            'url': profileUrl,
            'data': form_data,
            'dataType': 'json',
            'cache': false,
            'success': function (data, textStatus) {
                if (data.status === 1) {
                    //$form[0].reset();
                    $("#retStatus").addClass('success');
                } else {
                    // a mentés hibába ütközött
                    $.each(data.errors, function (key, value) {
                        showError(key, value);
                    });
                    $("#retStatus").addClass('warning');
                }
                $("#retStatus").html(data.message + " " + data.error);
                $("#retStatus").show();
                $('html, body').stop().animate({
                    'scrollTop': $("#retStatus").offset().top - 40
                }, 900, 'swing', function () {
                    window.location.hash = $("#retStatus");
                });
            },
            'error': function (jqXHR, textStatus, errorThrown) {

            }
        });
    });

});