var StaffUserEdit = function() {

    var initFormValidations = function () {
        var userForm = $('.edit-staff-form');

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
                'first_name' : {
                    required : true,
                },
                'last_name' : {
                    required : true,
                },
                'email' : {
                    required : true,
                    email : true,
                },
                'club': {
                    required:true    
                },
            },
        });
    };

    return {
        init: function() {
            initFormValidations();
        }
    };
}();

// Initialize when page loads
jQuery(function() { 
    StaffUserEdit.init();
});