var PricingBandEdit = function() {

	var initFormValidations = function () {
		var PricingBandForm = $('.edit-pricing-band-form');

		PricingBandForm.validate({
			ignore: [],
			errorClass: 'invalid-feedback animated fadeInDown',
			errorElement: 'div',
			errorPlacement: function(error, e)
			{
				if (e.attr("name") == "seat") {
                    $(e).parents('.form-group .custom-file').append(error);
                } else {
                    $(e).parents('.form-group').append(error);
                }
			},
			highlight: function(e) {
				$(e).closest('.form-group').removeClass('is-invalid').addClass('is-invalid');
			},
			unhighlight: function (e) {
				$(e).closest('.form-group').removeClass('is-invalid').removeClass('is-invalid');
			},
			success: function(e) {
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
                'seat_file_name' : {
					required : {
						depends: function(element) {
                            return ($('#seatValidation').val() == 1) ? true : false;
                        }
					},
					extension: "csv|xlsx|xls|xlsm"
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
                    url: $('#seat').data('url'),
                    data: fd,
                    success: function(response){
                       // console.log('try',response.status);return false;
                        var $html ='';
                        if(response.status == 'error')
                        {
                            $.each( response.block, function( key, value ) {
                               $html += '<br/>'+value;
                            });
                            $('#seat').val('');
                            $('.js-label-change').html($('#seat_file_name').val());
                            swal({
                                title: "Pricing band upload error",
                                html: "The following blocks were not uploaded as they do not currently exist."+$html,
                                type: "error"});
                        }
                    }
                });
            } else {
                $('#seat').val('');
                $('.js-label-change').html($('#seat_file_name').val());
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
	PricingBandEdit.init();
});

function readLogoURL(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            $('.js-manage-file-width').removeClass('col-12').addClass('col-9');
            if ($('#seat_preview').hasClass('d-md-none')) {
                $('#seat_preview').removeClass('d-md-none');
            }
            $('#seat_preview').html('<a download href="' + e.target.result + '">Download</a>');
        }
        reader.readAsDataURL(input.files[0]);
    }
}
 $(".uploadPricingBandSeatFile").change(function() {
    readLogoURL(this);
});
