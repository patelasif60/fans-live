var Global = function() {
    var initAuthFormValidations = function () {
        var resetPasswordForm = $('.auth-reset-password-form');
        var setPasswordForm = $('.auth-set-password-form');
        resetPasswordForm.validate({
            ignore: [],
            errorClass: 'invalid-feedback animated fadeInDown',
            errorElement: 'div',
            errorPlacement: function(error, e) {
                jQuery(e).parents('.form-group > div').append(error);
            },
            highlight: function(e) {                
                jQuery(e).closest('.form-group').removeClass('is-invalid').addClass('is-invalid');
            },
            success: function(e) {
                jQuery(e).closest('.form-group').removeClass('is-invalid');
                jQuery(e).remove();
            },
            rules: {
                'email': {
                    required: true,
                    email: true
                }
            },
        });

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
                'email': {
                    required: true,
                    email: true
                },
                'password': {
                    required: true
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