var Global = function() {
    var initAuthFormValidations = function () {
        var loginForm = $('.auth-login-form');
        loginForm.validate({
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
                },
                'password': {
                    required: true
                }
            },
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