var CmsUserEdit = function() {

    var initFormValidations = function () {
        var userForm = $('.edit-cms-form');

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
                'company': {
                    required:true
                },
                'club_admin_roles[]': {
                    required: function(element) {
                        return $("input:radio[name='role']:checked").val() == 'clubadmin';
                    }
                },
                'status': {
                    required:true
                }
            },
        });
    };
    var showModelPopup = function(){
        
        $('.js-showmodelpopup').click(function() {
            var id = $(this).attr('ref-id');
            var url = $(this).attr('ref-url');
            $.ajax({
                url: url,
                data: {'roleID':id},
                wqdataType: "html",
                type: "POST",
                success: function(response){
                    $("#permissionData").html( response );
                }
            });
        });
        
     };
    return {
        init: function() {
            initFormValidations();
            showModelPopup();
        }
    };
}();

// Initialize when page loads
jQuery(function() { 
    CmsUserEdit.init();
    if(Site.clubdata)
    {
        $('#club_user').removeClass('d-none');
    }
    $('input[type=radio][name=role]').change(function() {
        if (this.value == 'clubadmin') {
            $('#club_user').removeClass('d-none');
        } else {
            $('#club_user').addClass('d-none');
        }
    });
});

