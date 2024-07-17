var PricingBandCreate = function() {

    var initFormValidations = function () {
        var PricingBandForm = $('.create-pricing-band-form');

        PricingBandForm.validate({
            ignore: [],
            errorClass: 'invalid-feedback animated fadeInDown',
            errorElement: 'div',
            errorPlacement: function(error, e)
            {
                if (e.attr("name") == "seat") {
                    $(e).parents('.form-group .logo-fields-wrapper').append(error);
                } else {
                    $(e).parents('.form-group').append(error);
                }
            },
            highlight: function(e) {
                if ($(e).attr("name") == "seat") {
                    $(e).removeClass('is-invalid').addClass('is-invalid');
                }
                $(e).closest('.form-group').removeClass('is-invalid').addClass('is-invalid');
            },
            unhighlight: function (e) {
                if ($(e).attr("name") == "seat") {
                    $(e).removeClass('is-invalid');
                }
                $(e).closest('.form-group').removeClass('is-invalid').removeClass('is-invalid');
            },
            success: function(e) {
                if ($(e).attr("name") == "seat") {
                    $(e).removeClass('is-invalid');
                }
                $(e).closest('.form-group').removeClass('is-invalid');
                $(e).remove();
            },
            rules: {
                'display_name' : {
                    required : true
                },
                'internal_name' : {
                    required : true
                },
                'vat_Rate' : {
                    required : true,
                    number: true,
                    min: 0
                },
                'price' : {
                    required : true,
                    number: true,
                },
                'seat' : {
                    extension: "csv|xlsx|xls|xlsm",
                    required: {
                        depends: function(element) {
                            return $('#seatValidation').val() == 1 ? true : false;
                        }
                    }
                },
            },
        });
    };

    var validateSeatsFile = function () {
        $("#seat").change(function() {
            var fd = new FormData();
            var files = $('#seat')[0].files[0];
            var filename = files.name;
            var splitFilename = filename.split('.');
            var fileExtension = splitFilename[splitFilename.length-1];
            if (fileExtension == 'csv' || fileExtension == 'xlsx' || fileExtension == 'xls' || fileExtension == 'xlsm') {
                fd.append('file',files);
                $.ajax({
                    type: "POST",
                    processData: false,
                    contentType: false,
                    url: "validateSeatData",
                    data: fd,
                    success: function(response){
                        var $html ='';
                        if(response.status == 'error')
                        {
                            $.each( response.block, function( key, value ) {
                                $html += '<br/>'+value;
                            });
                            $('#seat').val('');
                            $('.js-label-change').html('Choose file');
                            swal({
                                title: "Pricing band upload error",
                                html: "The following blocks were not uploaded as they do not currently exist."+$html,
                                type: "error"});
                        }
                    }
                });
            } else {
                $('#seat').val('');
                $('.js-label-change').html('Choose file');
                swal({
                    title: "Pricing band upload error",
                    html: "File is not valid. Valid extensions are csv, xlsx, xls and xlsm.",
                    type: "error"});
            }
        });
    };
    return {
        init: function() {
            initFormValidations();
            validateSeatsFile();
        }
    };
}();

// Initialize when page loads
jQuery(function() {
    PricingBandCreate.init();
});

function readLogoURL(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            $('.js-manage-file-width').removeClass('col-12').addClass('col-9');
            if(input.id=='seat'){
                $('#seat_preview_container').html('<a download href="' + e.target.result + '">Download</a>');
            }
            $('#seat_preview_container').removeClass('d-md-none');
        }
        reader.readAsDataURL(input.files[0]);
    }
}
$(".uploadPricingBandSeatFile").change(function() {
    readLogoURL(this);
});