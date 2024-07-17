var StadiumGeneralSetting = function () {

	var initFormValidations = function () {
		var stadiumGeneralSettingForm = $('.edit-stadium-settings-form');

		stadiumGeneralSettingForm.validate({
			ignore: [],
			errorClass: 'invalid-feedback animated fadeInDown',
			errorElement: 'div',
			errorPlacement: function (error, e) {
				if (e.attr("name") == "image" || e.attr("name") == "aerial_view_ticketing_graphic") {
					$(e).parents('.form-group .logo-input').append(error);
				} else {
					$(e).parents('.form-group').append(error);
				}
			},
			highlight: function (e) {
				if ($(e).attr("name") == "image" || $(e).attr("name") == "aerial_view_ticketing_graphic") {
                    $(e).removeClass('is-invalid').addClass('is-invalid');
                }
				$(e).closest('.form-group').removeClass('is-invalid').addClass('is-invalid');
			},
			unhighlight: function (e) {
				if ($(e).attr("name") == "image" || $(e).attr("name") == "aerial_view_ticketing_graphic") {
                    $(e).removeClass('is-invalid');
                }
				$(e).closest('.form-group').removeClass('is-invalid').removeClass('is-invalid');
			},
			success: function (e) {
				$(e).closest('.form-group').removeClass('is-invalid');
				$(e).remove();
			},
			rules: {
				'name': {
					required: true
				},
				'address': {
					required: true
				},
				'town': {
					required: true
				},
				'postcode': {
					required: true
				},
				'aerial_view_ticketing_graphic': {
					required: function(text) {
						return ($('#aerial_view_ticketing_graphic_file_name').val() || !$("#is_using_allocated_seating").is(':checked')) ? false : true;
					}
				},
				'image': {
					accept: "image/png",
					extension: "png",
					imagedimension: [840, 525, 1], // 3rd parameter is optional, for empty src
				},
				'number_of_seats': {
					required: function(text) {
						return $("#is_using_allocated_seating").is(':checked') ? false : true
					}
				},
			},
			messages: {
				'image': {
					accept: 'Please upload the correct file format.',
					imagedimension: 'Please upload the correct file size.'
				}
			},

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
	StadiumGeneralSetting.init();
});

// function readLogoURL(input) {
//     if (input.files && input.files[0]) {
//         var reader = new FileReader();
//         reader.onload = function(e) {
//             $('.js-manage-image-width').removeClass('col-12').addClass('col-9');
//             if ($('#logo_preview').hasClass('d-md-none')) {
//                 $('#logo_preview').removeClass('d-md-none');
//             }
//             $('#logo_preview_container').html('<img class="img-avatar img-avatar-square" src="' + e.target.result + '" />');
//         }
//         reader.readAsDataURL(input.files[0]);
//     }
// }
//
// $("#aerial_view_ticketing_graphic").change(function() {
//     readLogoURL(this);
// });


function readLogoURL(input) {
	if (input.files && input.files[0]) {
		var reader = new FileReader();
		reader.onload = function (e) {
			$('.js-manage-logo-width').removeClass('col-12').addClass('col-9');
			$('#' + input.id + '_preview').attr('src', e.target.result);
			$('#' + input.id + '_preview_container').removeClass('d-md-none');
			$('#' + input.id + '_preview_container').addClass('new_upload');
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

$("#is_using_allocated_seating").click(function () {
	$(".js-number-of-seats").removeClass('d-none');
	$(".js-aerial-view-ticketing-graphic").addClass('d-none');
	if($('#is_using_allocated_seating').is(':checked')) {
		$(".js-number-of-seats").addClass('d-none');
		$(".js-aerial-view-ticketing-graphic").removeClass('d-none');
	}
});

$("#aerial_view_ticketing_graphic").change(function () {
	var ext = $(this).val().split('.').pop().toLowerCase();
	if ($.inArray(ext, ['png', 'jpg', 'jpeg']) != -1) {
		readArialImageURL(this);
	}
});

function readArialImageURL(input) {
	if (input.files && input.files[0]) {
		var reader = new FileReader();
		reader.onload = function (e) {
			$('.js-manage-arial-logo-width').removeClass('col-12').addClass('col-9');
			if ($('#logo_preview_container').hasClass('d-md-none')) {
				$('#logo_preview_container').removeClass('d-md-none');
			}
			$('#remove').removeClass('d-md-none');
			$('#logo_preview_container').html('<div class="logo_preview_container ml-3"><img id="logo_preview" alt="Category logo" src="' + e.target.result + '" /></div>');
		}
		reader.readAsDataURL(input.files[0]);
	}
}

$(document).on('click', '#remove', function () {
	$('#logo').val('');
	let lbl = document.getElementById('lbl_aerial_view_ticketing_graphic');
	lbl.innerText = "Choose File";
	$('#logo_preview_container').addClass('d-md-none');
	$('#remove').addClass('d-md-none');
	$('.js-manage-arial-logo-width').removeClass('col-9').addClass('col-12');
});
