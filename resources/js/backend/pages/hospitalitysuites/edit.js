var HospitalitySuiteCreate = function() {

    var initFormValidations = function () {
        var userForm = $('.edit-hospitalitysuite-form');

        userForm.validate({
            ignore: [],
            errorClass: 'invalid-feedback animated fadeInDown',
            errorElement: 'div',
            errorPlacement: function(error, e)
            {
				if (e.attr("name") == "image") {
					$(e).parents('.form-group .logo-input').append(error);
				} else if (e.attr("name") == "long_description") {
                    $(e).parent().find('.cke_editor_js-ckeditor').addClass('is-invalid');
                    $(e).parents('.form-group').append(error);
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
            	'name' : {
                    required : true,
                },
                'price' : {
                    required : true,
                    number: true,
                },
				'long_description' : {
					required: function(textarea){
						CKEDITOR.instances[textarea.id].updateElement();
						var editorcontent = textarea.value.replace(/<[^>]*>/gi, '');
                        if(editorcontent.length > 0) {
                            $("#"+textarea.id).parent().find('.cke_editor_js-ckeditor').removeClass('is-invalid');
                        }
						return editorcontent.length === 0;
					}
				},
				'short_description' : {
					required : true,
				},
                'image' : {
					required: function(text) {
						return $('#image_file_name').val() ? false : true;
					},
                    accept: "image/png",
                    extension: "png",
                    icondimension: [840, 630, 'image'],
                },
                'number_of_seat' : {
                    required : true,
                },
                'vat_rate': {
                    required: true,
                    number: true,
                    min: 0
                },
            },
            messages: {
                'image': {
                    accept: 'Please upload the correct file format.',
                    icondimension: 'Please upload the correct file size.'
                }
            }
        });
    };

     var uiHelperCkeditor = function(){
        if (jQuery('#js-ckeditor:not(.js-ckeditor-enabled)').length) {
            CKEDITOR.replace('js-ckeditor').on( 'change', function(e) {
                var editorcontent = e.editor.getData().replace(/<[^>]*>/gi, '');
                if(editorcontent.length > 0) {
                    $("."+e.editor.id).removeClass('is-invalid');
                    $("."+e.editor.id).parent().find('.invalid-feedback').remove();
                } else {
                    $('.js-hospitalitysuite-update').trigger('click');
                }
            });
            jQuery('#js-ckeditor').addClass('js-ckeditor-enabled');
        }
    };
    var initFormRepeaters = function() {
    	$('.repeater').repeater({
    		show: function () {
    			$(this).slideDown();
    		},
    		isFirstItemUndeletable: false,
    	});
    };

    return {
        init: function() {
        	initFormValidations();
            initFormRepeaters();
            uiHelperCkeditor();

        }
    };
}();

// Initialize when page loads
jQuery(function() {
	HospitalitySuiteCreate.init();
});

function readLogoURL(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            $('.js-manage-logo-width').removeClass('col-12').addClass('col-9');
            if(input.id=='seating_plan'){
				$('#'+input.id+'_preview_container').html('<a download href="' + e.target.result + '">Download</a>');
            } else{
				$('#'+input.id+'_preview_div').removeClass('d-md-none');
				$('#'+input.id+'_preview').attr('src', e.target.result);
            }
			$('#'+input.id+'_preview_container').removeClass('d-md-none');
			$('#' + input.id + '_preview_container').addClass('new_upload');
        }
        reader.readAsDataURL(input.files[0]);
    }
}

$(".uploadimage").change(function() {
    readLogoURL(this);
});
