var ConsumerUserCreate = function() {

    var initFormValidations = function () {
        var userForm = $('.create-consumer-user-form');

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
                    remote: {
                        url: "/admin/staffuser/checkEmail",
                        type: "post",
                        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
                    }
                },
                'dob': {
                    required:true
                },
                'club': {
                    required:true
                },
            },
            messages: {
                email:{
                    remote:'Email already exists.',
                }
            },
        });
    };

    return {
        init: function() {
            initFormValidations();
        }
    };
}();
var FeedItemIndex = function() {
    var uiHelperDatePicker = function(){
        if(Site.currentPanel=='clubadmin')
        {
            $(".js-datepicker").datetimepicker({
                ignoreReadonly: true,
                format: Site.dateCmsFormat,
                timeZone: Site.clubTimezone,
                maxDate: new Date().setHours(23,59,59,999)
            });

        }
        else
        {
            $(".js-datepicker").datetimepicker({
                ignoreReadonly: true,
                format: Site.dateCmsFormat,
                maxDate: new Date().setHours(23,59,59,999)
            });
        }
       
    };
    return {
        init: function() {
            uiHelperDatePicker();
        }
    };
}();

// Initialize when page loads
jQuery(function() {
    ConsumerUserCreate.init();
    FeedItemIndex.init();
});
