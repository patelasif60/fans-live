var PollCreate = function() {
    var initFormValidations = function () {
        var userForm = $('.create-poll-form');
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
                    required : true,
                },
                'question' : {
                    required : true,
                },
                'publication_date': {
                    required:true
                },
                'associated_match': {
                    required:true
                },
				'closing_date': {
					greaterThanDateTime: "#publication_datetime"
				},
				'display_results_date': {
                    required:true,
					greaterThanPollClosingDateTime: "#closing_datetime",
					greaterThanDateTime: "#publication_datetime"
                },
            },
            messages: {
                'closing_date': {
					greaterThanDateTime: "Closing date must be greater than publication date."
                },
                'display_results_date': {
                    greaterThanDateTime: "Display results date must be greater than publication date."
                },
            }
        });
        addValidationRules($('.js-polls-answer-fields-wrapper').first());
    };

    var uiHelperDateTimePicker = function(){
        $(".js-datetimepicker").datetimepicker({
            ignoreReadonly: true,
            allowInputToggle: true,
            format: Site.dateTimeCmsFormat,
            timeZone: Site.clubTimezone,
            buttons: {showClear: true},
        });
    };

    var addValidationRules = function(formElement) {
    	var $formElement = $(formElement);
    	var $inputs = $formElement.find('input[name^="answers"]');

    	var addRequiredValidation = function() {
            $(this).rules('add', {
                required: true
            });
        };

        $inputs.filter('input[name$="[answer]"]').each(addRequiredValidation);
    };

	var initFormRepeaters = function() {
		var $repeater = $('.repeater').repeater({
			show: function () {
				$(this).slideDown();
				addValidationRules(this);
				repeaterIncrementText();
			},
			hide: function (deleteElement) {
				$(this).slideUp(deleteElement);
				setTimeout(function() {
                    repeaterIncrementText();
                }, 1000);
			},
			isFirstItemUndeletable: true,
		});
	};

	var repeaterIncrementText = function() {
		var repeaterIncrement = 0;
		$(".js-polls-answer .form-group").each(function(index) {
			$(this).find('label').text('Option '+ repeaterIncrement+':');
			repeaterIncrement++;
		});
	}
    return {
        init: function() {
            initFormRepeaters();
            initFormValidations();
            uiHelperDateTimePicker();
        }
    };
}();

// Initialize when page loads
jQuery(function() {
    PollCreate.init();
});
