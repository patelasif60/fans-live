var CategoryCreate = function () {

	var initFormValidations = function () {
		var userForm = $('.create-news-form');

		userForm.validate({
			ignore: [],
			errorClass: 'invalid-feedback animated fadeInDown',
			errorElement: 'div',
			errorPlacement: function (error, e) {
				if (e.attr("name") == "logo") {
					$(e).parents('.form-group  .logo-input').append(error);
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
				'pubdate': {
					required: true,
				},
				'logo': {
					accept: "image/png",
					extension: "png",
					icondimension: [840, 525,'logo'],
				}
			},
			messages: {
				'logo': {
					accept: 'Please upload the correct file format.',
					icondimension: 'Please upload the correct file size.'
				}
			}
		});
		$('.create-news-form').data('validator').settings.ignore = ".note-editor *";
	};

	var uiHelperDateTimePicker = function () {
		$(".js-datetimepicker").datetimepicker({
			ignoreReadonly: true,
			format: Site.dateTimeCmsFormat,
			timeZone: Site.clubTimezone
		});
	};

	/*
	* Summernote, for more examples you can check out https://github.com/summernote/summernote/
	*
	* Codebase.helper('summernote');
	*
	*/

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
			uiHelperDateTimePicker();
			uiHelperCkeditor();
		}
	};
}();

// Initialize when page loads
jQuery(function () {
	CategoryCreate.init();
});

function readLogoURL(input) {
	if (input.files && input.files[0]) {
		var reader = new FileReader();
		reader.onload = function (e) {
			$('.js-manage-logo-width').removeClass('col-12').addClass('col-9');
			$('#logo_preview').attr('src', e.target.result);
			$('#logo_preview_container').removeClass('d-md-none');
			$('#remove').removeClass('d-md-none');
		}
		reader.readAsDataURL(input.files[0]);
	}
}

$("#logo").change(function () {
	var ext = $(this).val().split('.').pop().toLowerCase();
	if ($.inArray(ext, ['png', 'jpg', 'jpeg']) != -1) {
		readLogoURL(this);
	}
});

$("#remove").on('click', function (event) {
	event.preventDefault();
	$('#logo').val('');
	let lbl = document.getElementById('lbl_logo');
	lbl.innerText = "Choose File";
	$('#logo_preview_container').addClass('d-md-none');
	$('.js-manage-logo-width').removeClass('col-9').addClass('col-12');
	$('#logo_preview').attr('src', '');
});
