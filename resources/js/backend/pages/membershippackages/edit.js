var MembershipPackageEdit = function () {

	var initFormValidations = function () {
		var MembershipPackageForm = $('.edit-membership-package-form');

		MembershipPackageForm.validate({
			ignore: [],
			errorClass: 'invalid-feedback animated fadeInDown',
			errorElement: 'div',
			errorPlacement: function (error, e) {
				if (e.attr("name") == "icon") {
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
				'title': {
					required: true
				},
				'membership_duration': {
					required: true,
					number: true,
				},
				'rewards_percentage_override': {
					number: true,
					min: 0,
					max: 100
				},
				'price': {
					required: true,
					number: true,
					min: 0
				},
				'vat_rate': {
					required: true,
					number: true,
					min: 0
				},
				'icon': {
					accept: "image/png",
					extension: "png",
					icondimension: [150, 150, 'icon'],
				},
			},
			messages: {
				'icon': {
					accept: 'Please upload the correct file format.',
					icondimension: 'Please upload the correct file size.'
				}
			}
		});
		$('.edit-membership-package-form').data('validator').settings.ignore = ".note-editor *";
	};

	var uiHelperCkeditor = function () {
		if (jQuery('#js-ckeditor:not(.js-ckeditor-enabled)').length) {
			CKEDITOR.replace('js-ckeditor', {
				toolbar: [
					['Bold', 'Link', 'Maximize', 'Source']
				],
			},);
			jQuery('#js-ckeditor').addClass('js-ckeditor-enabled');
		}
	};

	return {
		init: function () {
			initFormValidations();
			uiHelperCkeditor();
		}
	};
}();

// Initialize when page loads
jQuery(function () {
	MembershipPackageEdit.init();
});

function readLogoURL(input) {
	if (input.files && input.files[0]) {
		var reader = new FileReader();
		reader.onload = function (e) {
			$('.js-manage-logo-width').removeClass('col-12').addClass('col-9');
			if ($('#' + input.id + '_image').hasClass('d-md-none')) {
				$('#' + input.id + '_image').removeClass('d-md-none');
			}
			$('#' + input.id + '_preview_container').html('<img class="img-avatar img-avatar-square" id="icon_preview" name="icon_preview" src="' + e.target.result + '" />');
		}
		reader.readAsDataURL(input.files[0]);
	}
}

$(".uploadimage").change(function () {
	readLogoURL(this);
});
