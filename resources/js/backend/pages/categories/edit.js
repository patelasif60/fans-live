var categorytEdit = function() {

    var initFormValidations = function () {
        var categoryForm = $('.edit-category-form');

        categoryForm.validate({
            ignore: [],
            errorClass: 'invalid-feedback animated fadeInDown',
            errorElement: 'div',
            errorPlacement: function(error, e)
            {
				if (e.attr("name") == "logo") {
					$(e).parents('.form-group .logo-input').append(error);
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
                'title' : {
                    required : true,
                },
				'rewards_percentage_override' : {
					number: true
                },
				'logo' : {
					required: function(text) {
						return $('#image_file_name').val() ? false : true;
					},
					accept: "image/png",
					extension: "png",
					icondimension: [840, 630,'logo'],
				}
            },
			messages: {
                'logo': {
                    accept: 'Please upload the correct file format.',
                    icondimension: 'Please upload the correct file size.'
                }
            }
        });
        $('.edit-category-form').data('validator').settings.ignore = ".note-editor *";
    };

	return {
        init: function() {
            initFormValidations();
        }
    };
}();

// Initialize when page loads
jQuery(function() {
    categorytEdit.init();
});

function readLogoURL(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            $('.js-manage-logo-width').removeClass('col-12').addClass('col-9');
            if ($('#logo_preview_div').hasClass('d-md-none')) {
                $('#logo_preview_div').removeClass('d-md-none');
            }
            $('#logo_preview_container').html('<div class="logo_preview_container"><img id="logo_preview" src="' + e.target.result + '" /></div>');
        }
        reader.readAsDataURL(input.files[0]);
    }
}

$("#logo").change(function() {
    readLogoURL(this);
});
