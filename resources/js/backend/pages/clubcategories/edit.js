var categoryEdit = function () {

	var initFormValidations = function () {
		var userForm = $('.edit-category-form');

		userForm.validate({
			ignore: [],
			errorClass: 'invalid-feedback animated fadeInDown',
			errorElement: 'div',
			errorPlacement: function (error, e) {
				if ((e.attr("name") == "logo"))  {
					$(e).parents('.form-group .logo-input').append(error);
				} else {
					$(e).parents('.form-group').append(error);
				}
			},
			highlight: function (e) {
				$(e).closest('.form-group').removeClass('is-invalid').addClass('is-invalid');
			},
			unhighlight: function (e) {
				$(e).closest('.form-group').removeClass('is-invalid').removeClass('is-invalid');
			},
			success: function (e) {
				$(e).closest('.form-group').removeClass('is-invalid');
				$(e).remove();
			},
			rules: {
				'name': {
					required: true,
				},
				'logo': {
					required: function(text) {
						return $('#logo_file_name').val() ? false : true;
					},
					accept: "image/png",
					extension: "png",
					icondimension: [150, 150, 'logo'],
				}
			},
			messages: {
				'logo': {
					accept: 'Please upload the correct file format.',
					icondimension: 'Please upload the correct file size.'
				}
			}

		});
	};

	return {
		init: function () {
			initFormValidations();
		}
	};
}();

// Initialize when page loads
jQuery(function () {
	categoryEdit.init();
});

function readLogoURL(input) {
	if (input.files && input.files[0]) {
		var reader = new FileReader();
		reader.onload = function (e) {
			$('.js-manage-logo-width').removeClass('col-12').addClass('col-9');
			if ($('#category-logo').hasClass('d-md-none')) {
				$('#category-logo').removeClass('d-md-none');
			}
			$('#' + input.id + '_preview_container').html('<div class="logo_preview_container ml-3"><img id="logo_preview" class="" src="' + e.target.result + '" /></div>');
			$('#' + input.id + '_preview_container').addClass('new_upload');
		}
		reader.readAsDataURL(input.files[0]);
	}
}

$("#logo").change(function () {
	readLogoURL(this);
});
