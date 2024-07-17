var categoryEdit = function () {

	var initFormValidations = function () {
		var userForm = $('.edit-traveloffers-form');

		userForm.validate({
			ignore: [],
			errorClass: 'invalid-feedback animated fadeInDown',
			errorElement: 'div',
			errorPlacement: function (error, e) {
				if (e.attr("name") == "logo" || e.attr("name") == "icon" || e.attr("name") == "thumbnail") {
					$(e).parents('.form-group .logo-input').append(error);
				} else if (e.attr("name") == 'content_description') {
					$(e).parent().find('.cke_editor_js-ckeditor').addClass('is-invalid');
                    $(e).parents('.form-group').append(error);
				} else {
					$(e).parents('.form-group').append(error);
				}
				showTabError();
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
				'showuntil': {
					required: true,
					greaterThanDateTime: "#publication_datetime"
				},
				'button_colour': {
					required: true,
				},
				'button_text_colour': {
					required: true,
				},
				'button_text': {
					required: true,
				},
				'button_url': {
					required: true,
				},
				'banner': {
					extension: "jpg|jpeg|png"
				},
				'thumbnail': {
					extension: "jpg|jpeg|png"
				},
				'icon': {
					required: function(text) {
						return $('#icon_file_name').val() ? false : true;
					},
					accept: "image/png",
					extension: "png",
					icondimension: [150, 150, 'icon'],
				},
				'logo': {
					accept: "image/png",
					extension: "png",
					icondimension: [840, 280, 'banner'],
				},
				'content_description': {
					required: function(textarea) {
                        CKEDITOR.instances[textarea.id].updateElement();
						var editorcontent = textarea.value.replace(/<[^>]*>/gi, '');
						if(editorcontent.length > 0) {
							$("#"+textarea.id).parent().find('.cke_editor_js-ckeditor').removeClass('is-invalid');
						}
						return editorcontent.length === 0;
                    }
				}
			},
			messages: {
				'logo': {
					accept: 'Please upload the correct file format.',
					icondimension: 'Please upload the correct file size.'
				},
				'thumbnail': {
					accept: 'Please upload image file.'
				},
				'icon': {
					accept: 'Please upload the correct file format.',
					icondimension: 'Please upload the correct file size.'
				},
				'showuntil': {
					greaterThanDateTime: "Show until must be greater than publication date."
				},
			}
		});
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
			CKEDITOR.replace('js-ckeditor').on( 'change', function(e) {
				var editorcontent = e.editor.getData().replace(/<[^>]*>/gi, '');
				if(editorcontent.length > 0) {
					$("."+e.editor.id).removeClass('is-invalid');
					$("."+e.editor.id).parent().find('.invalid-feedback').remove();
					$("#cke_js-ckeditor").parent('.form-group').removeClass('is-invalid');
				} else {
					$(".js-loyaltyrewards-save").trigger("click");
				}
			});
			jQuery('#js-ckeditor').addClass('js-ckeditor-enabled');
		}
	};
	var uiHelperColorpicker = function () {
		jQuery('.js-colorpicker:not(.js-colorpicker-enabled)').each(function () {
			var el = jQuery(this);
			el.addClass('js-colorpicker-enabled');
			el.colorpicker();
		});
	};
	return {
		init: function () {
			initFormValidations();
			uiHelperDateTimePicker();
			uiHelperCkeditor();
			uiHelperColorpicker();
		}
	};
}();

// Initialize when page loads
jQuery(function () {
	categoryEdit.init();
});

// icon upload
$(".uploadimage").change(function () {
	readIconURL(this);
});

function readIconURL(input) {
	if (input.files && input.files[0]) {
		var reader = new FileReader();
		reader.onload = function (e) {
			$('.js-manage-icon-width').removeClass('col-12').addClass('col-9');
			$('#' + input.id + '_preview').attr('src', e.target.result);
			$('#' + input.id + '_preview_container').removeClass('d-md-none');
			/*if ($('#icon_image').hasClass('d-md-none')) {
				$('#icon_image').removeClass('d-md-none');
			}
			$('#icon_preview_container').html('<img id="icon_preview" class="img-avatar img-avatar-square" src="' + e.target.result + '" id="icon_preview" name="icon_preview" />');
			$('#icon_preview_container').addClass('new_upload');*/
		}
		reader.readAsDataURL(input.files[0]);
	}
}

// thumbnail upload
$(".uploadThumbnail").change(function () {
	readThumbnailURL(this);
});

function readThumbnailURL(input) {
	if (input.files && input.files[0]) {
		var reader = new FileReader();
		reader.onload = function (e) {
			$('.js-manage-thumbnail-width').removeClass('col-12').addClass('col-9');
			if ($('#thumbnail_preview_container').hasClass('d-md-none')) {
				$('#thumbnail_preview_container').removeClass('d-md-none');
			}
			$('#remove_thumbnail').removeClass('d-md-none');
			$('#thumbnail_preview_container').html('<div class="logo_preview_container ml-3"><img id="thumbnail_preview" alt="Thumbnail logo" src="' + e.target.result + '" /><a href="#" id="remove_thumbnail" name="remove_thumbnail" class="close-preview" data-toggle="tooltip" title="Delete"><i class="far fa-trash-alt text-muted"></i></a></div>');
		}
		reader.readAsDataURL(input.files[0]);
	}
}

$(document).on('click', '#remove_thumbnail', function () {
	$('#thumbnail').val('');
	$('#thumbnail_edit').val('');
	let lbl = document.getElementById('lbl_thumbnail');
	lbl.innerText = "Choose File";
	$('#thumbnail_preview_container').addClass('d-md-none');
	$('.js-manage-thumbnail-width').removeClass('col-9').addClass('col-12');
});

// banner upload and remove
$(".uploadBanner").change(function () {
	readBannerURL(this);
});

function readBannerURL(input) {
	if (input.files && input.files[0]) {
		var reader = new FileReader();
		reader.onload = function (e) {
			$('.js-manage-banner-width').removeClass('col-12').addClass('col-9');
			if ($('#banner_preview_container').hasClass('d-md-none')) {
				$('#banner_preview_container').removeClass('d-md-none');
			}
			$('#remove_banner').removeClass('d-md-none');
			$('#banner_preview_container').html('<div class="logo_preview_container ml-3"><img id="banner_preview" alt="Banner logo" src="' + e.target.result + '" /></div>');
		}
		reader.readAsDataURL(input.files[0]);
	}
}

$(document).on('click', '#remove_banner', function () {
	$('#logo').val('');
	$('#banner_edit').val('');
	let lbl = document.getElementById('lbl_banner');
	lbl.innerText = "Choose File";
	$('.js-edit-banner-hide-show').addClass('d-md-none');
	$('.js-manage-banner-width').removeClass('col-12').addClass('col-9');
});
