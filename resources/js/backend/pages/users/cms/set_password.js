var Global = function() {
    var initAuthFormValidations = function () {        
        var setPasswordForm = $('.admin-set-password-link-form');
        
        setPasswordForm.validate({
            ignore: [],
            errorClass: 'invalid-feedback animated fadeInDown',
            errorElement: 'div',
            errorPlacement: function(error, e) {
                $(e).parents('.form-group > div').append(error);
            },
            highlight: function(e) {
                $(e).closest('.form-group').removeClass('is-invalid').addClass('is-invalid');
            },
            success: function(e) {
                $(e).closest('.form-group').removeClass('is-invalid');
                $(e).remove();
            },
            rules: {
                'password': {
                    required: true,
                    minlength: 8
                },
                'password_confirmation': {
                    required: true,
                    equalTo: "#password"
                }
            },
            messages: {
                'password_confirmation': {
                    equalTo: "Passwords do not match",
                }
            }
        });
    };

    var jQueryValidationCustomeMessage = function () {
        jQuery.extend(jQuery.validator.messages, {
            required: "This field is required",
            email: "Please enter a valid email address"
        });
    }

    return {
        init: function () {
            initAuthFormValidations();
            jQueryValidationCustomeMessage();
        }
    };
}();

// Initialize when page loads
jQuery(function() { 
    Global.init();
});