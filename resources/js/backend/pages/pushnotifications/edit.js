var PushNotificationEdit = function() {
	var initFormValidations = function () {
        var userForm = $('.edit-pushnotification-form');
        userForm.validate({
            ignore: [],
            errorClass: 'invalid-feedback animated fadeInDown',
            errorElement: 'div',
            errorPlacement: function(error, e)
            {
                $(e).parents('.form-group').append(error);
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
                    required: true,
                },
                'message' : {
                    required: true,
                },
                'publication_date': {
                    required:true
                },
                'swipe_action_category' : {
                    required: true,
                },
                'send_to_user_attending_this_match': {
                    required:true,
                },
                'swipe_action_item' : {
                    required: {
                        depends: function(element) {
                               return $(element).closest('form').find('#swipe_action_category').val() == "merchandise_category" || $(element).closest('form').find('#swipe_action_category').val() == "food_and_drink_category" || $(element).closest('form').find('#swipe_action_category').val() == "travel_offer";
                        }
                    }
                },
            }
        });
    };
    var uiHelperDateTimePicker = function(){
        $(".js-datetimepicker").datetimepicker({
            ignoreReadonly: true,
            format: Site.dateTimeCmsFormat,
            timeZone: Site.clubTimezone
        });
    };
    var manageSwipeActionItem = function(){
		$(document).on('change', '#swipe_action_category', function(){
			if ($(this).val() == 'merchandise_category' || $(this).val() == 'food_and_drink_category' || $(this).val() == 'travel_offer') {
				getSwipeActionItemsOnChange($(this).val());
				$('#swipe_action_item_container').removeClass('d-none');
			} else {
				$('#swipe_action_item_container').addClass('d-none');
			}
		});
    };
    var characterCountLimit =function () {
		var maxLength = 250;
		var length = $('#message').val().length;
		var length = maxLength-length;
		$('#push_notification_chars_count').text(length);

		$('#message').keyup(function() {
			var length = $(this).val().length;
			var length = maxLength-length;
			$('#push_notification_chars_count').text(length);
		});
	};
    var getSwipeActionItemsOnChange = function(selectedOption){
        $.ajax({
            type: "POST",
            url: "getSwipeActionItems",
            data: { 'swipe_action_category': selectedOption },
            success: function(response){
                $('#swipe_action_item option').detach();
                $.each( response, function( key, value ) {
                    var data = {
                        id: value.id,
                        text: value.title
                    };
                    var newOption = new Option(data.text, data.id, false, false);
                    $('#swipe_action_item').append(newOption);
                });
                setTimeout(function() {
                    $('#swipe_action_item').val(Site.pushnotificationSwipeActionItem).trigger('change.select2');
                }, 100);
            }
        });
    };
    var getSwipeActionItemOnLoad = function(){
        $( document ).ready(function() {
            if ($('#swipe_action_category').val() == 'merchandise_category' || $('#swipe_action_category').val() == 'food_and_drink_category' || $('#swipe_action_category').val() == 'travel_offer') {
                    getSwipeActionItemsOnChange($('#swipe_action_category').val());
                }
        });
    };
    return {
        init: function() {
            initFormValidations();
            uiHelperDateTimePicker();
            characterCountLimit();
            manageSwipeActionItem();
            getSwipeActionItemOnLoad();
            formAvailOption()
        }
    };
}();

// Initialize when page loads
jQuery(function() {
    PushNotificationEdit.init();
});

function formAvailOption() {
	$('.custom-users-with-membership').each(function () {
		$(this).rules('add', {
			required: true,
			messages: {
				required: "This field is required."
			}
		});
	});
};

$(".default-package").click(function() {
    if(this.checked) {
        $(".premium-package").prop('checked', true);
        $(".premium-package").prop('disabled', true);
    } else {
        $(".premium-package").prop('checked', false);
        $(".premium-package").prop('disabled', false);
    }
});