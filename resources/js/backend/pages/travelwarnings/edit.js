var travelWarningEdit = function() {

    var initFormValidations = function () {

    	var userForm = $('.edit-travelwarnings-form');

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
                'text' : {
                    required : true,
                },
                'publication_date_time' : {
                    required : true,
                },
                'show_until' : {
                    required : true,
                    greaterThanDateTime: "#publication_datetime"
                },
                'color' : {
                    required: true
                },
                'status' : {
                    required : true,
                },
            },
            messages: {
                'show_until': {
                    greaterThanDateTime: "Show until date must be greater than publication date."
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
	var characterCountLimit =function () {
		var maxLength = 100;
		var length = $('#travel_warning_text').val().length;
		var length = maxLength-length;
		$('#travel_warning_chars_count').text(length);

		$('#travel_warning_text').keyup(function() {
			var length = $(this).val().length;
			var length = maxLength-length;
			$('#travel_warning_chars_count').text(length);
		});
	};

	return {
        init: function() {
            initFormValidations();
            uiHelperDateTimePicker();
			characterCountLimit();

		}
    };
}();

// Initialize when page loads
jQuery(function() {
    travelWarningEdit.init();
});
