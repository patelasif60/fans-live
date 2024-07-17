var TravelOffersCreate = function () {

	var initFormValidations = function () {
		var userForm = $('.create-traveloffers-form');
		userForm.validate({
			ignore: [],
			errorClass: 'invalid-feedback animated fadeInDown',
			errorElement: 'div',
			errorPlacement: function (error, e) {
				if (e.attr("name") == "logo" || e.attr("name") == "icon" || e.attr("name") == "thumbnail") {
					$(e).parents('.form-group .logo-input').append(error);
				} else if (e.attr("name") == "button_text" || e.attr("name") == "button_url") {
					$(e).parents('.form-group').append(error);
				} else if (e.attr("name") == 'content_description'){
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
				'icon': {
					required: true,
					accept: "image/png",
					extension: "png",
					icondimension: [150, 150, 'icon'],
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
				'logo': {
					required: true,
					accept: "image/png",
					extension: "png",
					icondimension: [840, 280, 'logo'],
				},
				'thumbnail': {
					extension: "jpg|jpeg|png"
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
	/*
	* CKEditor init, for more examples you can check out http://ckeditor.com/
	*
	* Codebase.helper('ckeditor');
	*
	*/
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
			uiHelperCkeditor();
			uiHelperDateTimePicker();
			uiHelperColorpicker();
		}
	};
}();

//add custome rule for validating icon size
jQuery.validator.addMethod("customiconsize", function (value, element) {
		var imgWidth = $('#icon').width();
		var imgHeight = $('#icon').height();
		// console.log(imgWidth, imgHeight);  // gives some thing like this 621 34
		if (imgWidth >= 150 && imgHeight >= 150) {
			return true;
		} else {
			return false;
		}
		;
	}, "Minimum size should be 150px X 150px",
);

// Initialize when page loads
jQuery(function () {
	TravelOffersCreate.init();
});
//icon upload
function readLogoURL(input) {
	if (input.files && input.files[0]) {
		var reader = new FileReader();
		reader.onload = function (e) {
			$('.js-manage-logo-width').removeClass('col-12').addClass('col-9');
			$('#' + input.id + '_preview').attr('src', e.target.result);
			$('#' + input.id + '_preview_container').removeClass('d-md-none');
		}
		reader.readAsDataURL(input.files[0]);
	}
}

$(".uploadimage").change(function () {
	var ext = $(this).val().split('.').pop().toLowerCase();
	if ($.inArray(ext, ['png', 'jpg', 'jpeg']) != -1) {
		readLogoURL(this);
	}
});

//Banner upload and remove
$(".uploadbanner").change(function () {
	var ext = $(this).val().split('.').pop().toLowerCase();
	if ($.inArray(ext, ['png', 'jpg', 'jpeg']) != -1) {
		readBannerURL(this);
	}
});

function readBannerURL(input) {
	if (input.files && input.files[0]) {
		var reader = new FileReader();
		reader.onload = function (e) {
			$('.js-manage-banner-width').removeClass('col-12').addClass('col-9');
			$('#' + input.id + '_preview').attr('src', e.target.result);
			$('#' + input.id + '_preview_container').removeClass('d-md-none');
		}
		reader.readAsDataURL(input.files[0]);
	}
}

$("#remove_banner").on('click', function () {
	$('#logo').val('');
	let lbl = document.getElementById('lbl_logo');
	lbl.innerText = "Choose File";
	$('#logo_preview_container').addClass('d-md-none');
	$('.js-manage-banner-width').removeClass('col-9').addClass('col-12');
	$('#logo_preview').attr('src', '');
});

//Thumbnail upload and remove
$(".uploadthumbnail").change(function () {
	var ext = $(this).val().split('.').pop().toLowerCase();
	if ($.inArray(ext, ['png', 'jpg', 'jpeg']) != -1) {
		readThumbnailURL(this);
	}
});

function readThumbnailURL(input) {
	if (input.files && input.files[0]) {
		var reader = new FileReader();
		reader.onload = function (e) {
			$('.js-manage-thumbnail-width').removeClass('col-12').addClass('col-9');
			$('#' + input.id + '_preview').attr('src', e.target.result);
			$('#' + input.id + '_preview_container').removeClass('d-md-none');
		}
		reader.readAsDataURL(input.files[0]);
	}
}

$("#remove_thumbnail").on('click', function () {
	$('#thumbnail').val('');
	let lbl = document.getElementById('lbl_thumbnail');
	lbl.innerText = "Choose File";
	$('#thumbnail_preview_container').addClass('d-md-none');
	$('.js-manage-thumbnail-width').removeClass('col-9').addClass('col-12');
	$('#thumbnail_preview').attr('src', '');
});
