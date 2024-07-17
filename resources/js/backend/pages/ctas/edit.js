var CTAEdit = function() {

	var initFormValidations = function () {
		var CTAForm = $('.edit-cta-form');

		CTAForm.validate({
			ignore: [],
			errorClass: 'invalid-feedback animated fadeInDown',
			errorElement: 'div',
			errorPlacement: function(error, e)
			{
				if (e.attr("name") == "image") {
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
					required : true
				},
				'first_button_text' : {
					required : true
				},
				'first_button_action' : {
					required : true
				},
				'publication_date' : {
					required : true
				},
				'image' : {
					required: function(text) {
						return $('#image_file_name').val() ? false : true;
					},
					accept: "image/png",
					extension: "png",
					icondimension: [840, 525,'image'],
				},
				'image_file_name' : {
					required : true
				}
			},
            messages: {
                'image': {
                    accept: 'Please upload the correct file format.',
                    icondimension: 'Please upload the correct file size.'
                }
            }
		});
	};

	 var uiHelperSelect2 = function(){
		// Init Select2 (with .js-select2-allow-clear class)
		jQuery('.js-select2-allow-clear:not(.js-select2-enabled), .js-select2:not(.js-select2-enabled)').each(function(){
			var el = jQuery(this);

			// Add .js-select2-enabled class to tag it as activated
			el.addClass('js-select2-enabled');

			// Init
			el.select2({
				allowClear: $(this).hasClass('js-select2-allow-clear') ? true : false,
				placeholder: "Please select"
			});
		});
	};

	var uiHelperDateTimePicker = function(){
		$(".js-datetimepicker").datetimepicker({
			ignoreReadonly: true,
			format: Site.dateTimeCmsFormat,
			timeZone: Site.clubTimezone
		});
	};
	var manageButtonOneActionItem = function(){
		$(document).on('change', '#first_button_action', function(){
			if ($(this).val() == 'merchandise_category' || $(this).val() == 'food_and_drink_category') {
				getSwipeActionItemsOnChange($(this).val(),'first_button_item');
				$('#first_button_item_container').removeClass('d-none');
			} else {
				$('#first_button_item_container').addClass('d-none');
			}
		});
    };
    var manageButtonTwoActionItem = function(){
		$(document).on('change', '#second_button_action', function(){
			if ($(this).val() == 'merchandise_category' || $(this).val() == 'food_and_drink_category') {
				getSwipeActionItemsOnChange($(this).val(),'second_button_item');
				$('#second_button_item_container').removeClass('d-none');
			} else {
				$('#second_button_item_container').addClass('d-none');
			}
		});
    };
	 var getSwipeActionItemsOnChange = function(selectedOption,flag){
	        $.ajax({
	            type: "POST",
	            async: false,
	            url: "getSwipeActionItems",
	            data: { 'swipe_action_category': selectedOption },
	            success: function(response){
	                $('#'+flag+' option').detach();
	                $.each(response, function( key, value){
	                    var data = {
	                        id: value.id,
	                        text: value.title
	                    };
	                    var newOption = new Option(data.text, data.id, false, false);
	                    $('#'+flag).append(newOption);
	                });
	            }
	        });
	    };
	    var buttonOneActionItemOnLoad = function(){
        $( document ).ready(function() {
            if ($('#first_button_action').val() == 'merchandise_category' || $('#first_button_action').val() == 'food_and_drink_category') {
                 getSwipeActionItemsOnChange($('#first_button_action').val(),'first_button_item');
                 $('#first_button_item').val(Site.buttonOneActionItem).trigger('change.select2');
			}
        });
    };
    var buttonTwoActionItemOnLoad = function(){
        $( document ).ready(function() {
            if ($('#second_button_action').val() == 'merchandise_category' || $('#second_button_action').val() == 'food_and_drink_category') {
              	getSwipeActionItemsOnChange($('#second_button_action').val(),'second_button_item');
				$('#second_button_item').val(Site.buttonTwoActionItem).trigger('change.select2');
            }
        });
    };
	return {
		init: function() {
			initFormValidations();
			uiHelperSelect2();
			uiHelperDateTimePicker();
			manageButtonOneActionItem();
            manageButtonTwoActionItem();
            buttonOneActionItemOnLoad();
            buttonTwoActionItemOnLoad();
		}
	};
}();

// Initialize when page loads
jQuery(function() {
	CTAEdit.init();
});

function readLogoURL(input) {
	if (input.files && input.files[0]) {
		var reader = new FileReader();
		reader.onload = function(e) {
			$('.js-manage-image-width').removeClass('col-12').addClass('col-9');
			if ($('#cta_image').hasClass('d-md-none')) {
				$('#cta_image').removeClass('d-md-none');
			}
			$('#image_preview_container').html('<img id="image_preview"  class="img-avatar img-avatar-square" src="' + e.target.result + '" />');
		}
		reader.readAsDataURL(input.files[0]);
	}
}

$("#image").change(function() {
	readLogoURL(this);
});
