var UserRoleEdit = function() {

    var initFormValidations = function () {
        var competitionForm = $('.edit-role-form');

        competitionForm.validate({
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
                'display_name' : {
                    required : true,
                },
                'permission':{
                     required : true,
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

// Initialize when page loads
jQuery(function() { 
    UserRoleEdit.init();
});

function readLogoURL(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            $('.js-manage-logo-width').removeClass('col-12').addClass('col-9');
            if ($('#competition-logo').hasClass('d-md-none')) {
                $('#competition-logo').removeClass('d-md-none');
            }
            $('#logo_preview_container').html('<img class="img-avatar img-avatar-square" src="' + e.target.result + '" />');
        }
        reader.readAsDataURL(input.files[0]);
    }
}

$("#logo").change(function() {
    readLogoURL(this);
});