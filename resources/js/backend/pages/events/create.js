var EventCreate = function () {

	var initFormValidations = function () {
		var eventForm = $('.create-event-form');

		eventForm.validate({
			ignore: [],
			errorClass: 'invalid-feedback animated fadeInDown',
			errorElement: 'div',
			errorPlacement: function (error, e) {
				if (e.attr("name") == "logo") {
					$(e).parents('.form-group .logo-input').append(error);
				} else if (e.attr("name") == "description") {
                    $(e).parent().find('.cke_editor_js-ckeditor').addClass('is-invalid');
                    $(e).parents('.form-group').append(error);
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
					required: true,
				},
				'location': {
					required: true,
				},
				'packageList[]':
				{
					required: true,
				},
				'dateandtime': {
					required: true,
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
				'description': {
					required: function (textarea) {
						CKEDITOR.instances[textarea.id].updateElement();
						var editorcontent = textarea.value.replace(/<[^>]*>/gi, '');
						if(editorcontent.length > 0) {
                            $("#"+textarea.id).parent().find('.cke_editor_js-ckeditor').removeClass('is-invalid');
                        }
						return editorcontent.length === 0;
					}
				},
				'rewards_percentage_override': {
					number: true,
                    min: 0,
                    max: 100
				},
				'number_of_tickets': {
					number: true,
					required: true,
				},
				'logo': {
					required: true,
					extension: "png",
					icondimension: [840, 525, 'logo'],

				}
			},
			messages: {
				'logo': {
					accept: 'Please upload the correct file format.',
					icondimension: 'Please upload the correct file size.'
				}
			}
		});
		$('.create-event-form').data('validator').settings.ignore = ".note-editor *";
	};

	var uiHelperDateTimePicker = function () {
		$(".js-datetimepicker").datetimepicker({
			ignoreReadonly: true,
			format: Site.dateTimeCmsFormat,
			timeZone: Site.clubTimezone
		});
	};

	var uiHelperCkeditor = function () {
		if (jQuery('#js-ckeditor:not(.js-ckeditor-enabled)').length) {
			CKEDITOR.replace('js-ckeditor', {
				toolbar: [
					['Bold', 'Link', 'Maximize', 'Source']
				],
			},).on( 'change', function(e) {
                var editorcontent = e.editor.getData().replace(/<[^>]*>/gi, '');
                if(editorcontent.length > 0) {
					$("."+e.editor.id).removeClass('is-invalid');
                    $("."+e.editor.id).closest('.form-group').find('.invalid-feedback').remove();
                } else {
                    $('.js-event-save').trigger('click');
                }
            });
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
	EventCreate.init();
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

$(".default-package").click(function() {
    if(this.checked) {
        $(".premium-package").prop('checked', true);
        $(".premium-package").prop('disabled', true);
    } else {
        $(".premium-package").prop('checked', false);
        $(".premium-package").prop('disabled', false);
    }
});