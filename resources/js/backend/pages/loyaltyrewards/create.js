var LoyaltyRewardCreate = function () {

	var initFormValidations = function () {
		var userForm = $('.create-loyaltyreward-form');

		userForm.validate({
			ignore: [],
			errorClass: 'invalid-feedback animated fadeInDown',
			errorElement: 'div',
			errorPlacement: function (error, e) {
				if (e.attr("name") == "image") {
					$(e).parents('.form-group .logo-input').append(error);
				} else if (e.attr("name") == "description") {
					$(e).parent().find('.cke_editor_js-ckeditor').addClass('is-invalid');
					$(e).parents('.form-group').append(error);
				} else {
					$(e).parents('.form-group').append(error);
				}
			},
			highlight: function (e) {
				if ($(e).attr("name") == "image") {
                    $(e).removeClass('is-invalid').addClass('is-invalid');
                }
				$(e).closest('.form-group').removeClass('is-invalid').addClass('is-invalid');
			},
			unhighlight: function (e) {
                if ($(e).attr("name") == "image") {
                    $(e).removeClass('is-invalid');
                }
				$(e).closest('.form-group').removeClass('is-invalid').removeClass('is-invalid');
			},
			success: function (e) {
                if ($(e).attr("name") == "image") {
                    $(e).removeClass('is-invalid');
                }
				$(e).closest('.form-group').removeClass('is-invalid');
				$(e).remove();
			},
			rules: {
				'title': {
					required: true,
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
				'price_in_points': {
					required: true,
					number: true,
					min: 0
				},
				'image': {
					required: true,
					extension: "png",
					icondimension: [840, 630,'image'],
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

	var manageOnLoad = function () {
		$(document).on('click', ".js-custom-option-btn", function () {
			if ($('.js-reward-option').attr('id') >= 0) {
				var addCustomOptionId = parseInt($('#add_custom_option').children().last().attr('id')) + 1;
			} else {
				var addCustomOptionId = 0;
			}
			addCustomOption(addCustomOptionId);
		});

		$(document).on('click', '.js-custom-option-delete', function () {
			$(this).closest('.js-reward-option').remove();
		});

		$(document).on('click', '#btn_product_submit', function () {
			checkTabsvalidation();
		});
	};

	/*
	* CKEditor init, for more examples you can check out http://ckeditor.com/
	*
	* Codebase.helper('ckeditor')
	*
	*/
	var uiHelperCkeditor = function () {
		if (jQuery('#js-ckeditor:not(.js-ckeditor-enabled)').length) {
			CKEDITOR.replace('js-ckeditor').on( 'change', function(e) {
				var editorcontent = e.editor.getData().replace(/<[^>]*>/gi, '');
				if(editorcontent.length > 0) {
					$("."+e.editor.id).removeClass('is-invalid');
					$("."+e.editor.id).parent().find('.invalid-feedback').remove();
				} else {
					$(".js-loyaltyrewards-save").trigger("click");
				}
			});
			jQuery('#js-ckeditor').addClass('js-ckeditor-enabled');
		}
	};
	var initFormRepeaters = function () {
		$('.repeater').repeater({
			show: function () {
				$(this).slideDown();
			},
			isFirstItemUndeletable: true,
		});
	};

	return {
		init: function () {
			initFormRepeaters();
			manageOnLoad();
			initFormValidations();
			uiHelperCkeditor();
		}
	};
}();

// Initialize when page loads
jQuery(function () {
	LoyaltyRewardCreate.init();
});

function readLogoURL(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
        $('.js-manage-file-width').removeClass('col-12').addClass('col-9');
        if(input.id=='seating_plan'){
        	$('#'+input.id+'_preview_container').html('<a download href="' + e.target.result + '">Download</a>');
        }
        else{
        	  $('#'+input.id+'_preview').attr('src', e.target.result);
        }
          $('#'+input.id+'_preview_container').removeClass('d-md-none');
        }
        reader.readAsDataURL(input.files[0]);
    }
}
$(".uploadimage").change(function() {
	readLogoURL(this);
});

function formLineUpsValidation() {
	$('.custom-option-name-cls').each(function () {
		$(this).rules('add', {
			required: true,
			messages: {
				required: "This field is required."
			}
		});
	});
	$('.custom-option-number-cls').each(function () {
		$(this).rules('add', {
			required: true,
			min:1,
			max:9999,
			messages: {
				required: "This field is required."
			}
		});
	});
};


function addCustomOption(addCustomOptionId) {
	var addCustomOptionHtml = '<div class="block block-bordered block-default block-rounded js-home-main-div"><div class="block-header block-header-default"><div></div><div class="block-options"><button type="button" class="btn-block-option js-custom-option-delete text-danger" title="" data-original-title="Delete"><i class="fal fa-trash"></i></button>           </div></div><div class="block-content"><div class="row"><div class="col-xl-4"><div class="form-group"><label for="additional_cost" class="required">Additional points:</label><input type="number" min="1" max="9999" class="form-control custom-option-number-cls" id="additional_cost' + addCustomOptionId + '" name="additional_cost[' + addCustomOptionId + ']" value=""></div></div><div class="col-xl-4"><div class="form-group"><label for="name" class="required">Name:</label><input type="text" class="form-control custom-option-name-cls" id="name' + addCustomOptionId + '" name="name[' + addCustomOptionId + ']" value=""></div></div></div></div></div>';
	$('#add_custom_option').append('<div class="col-xl-12 js-reward-option" id=' + addCustomOptionId + '>' + addCustomOptionHtml + '</div>');
	formLineUpsValidation();
}
