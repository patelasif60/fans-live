var CmsUserCreate = function() {

    var initFormValidations = function () {
        var userForm = $('.create-cms-form');

        userForm.validate({
            ignore: [],
            errorClass: 'invalid-feedback animated fadeInDown',
            errorElement: 'div',
            errorPlacement: function(error, e)
            {
                if (e.attr("name") == "club_admin_roles[]") {
                    $('.error-display').append(error);
                } else {
                	$(e).parents('.form-group').append(error);
            	}
                 //$(e).parents('.form-group').append(error);
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
                        url: "/admin/cmsuser/checkEmail",
                        type: "post",
                        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
                    }
                },
                'company': {
                    required:true
                },
                'status': {
                    required:true
                },
                'role': {
                    required:true
                },
                'club': {
                    required: function(element) {
                        return $("input:radio[name='role']:checked").val() == 'clubadmin';
                    }
                },
                'club_admin_roles[]': {
                    required: function(element) {
                        return $("input:radio[name='role']:checked").val() == 'clubadmin';
                    }
                }
            },
            messages: {
                email:{
                    remote:'Email already exists.',
                }
            },
        });
    };

    var uiHelperSelect2 = function(){
        // Init Select2 (with .js-select2-allow-clear class)
        jQuery('.js-select2-allow-clear:not(.js-select2-enabled)').each(function(){
            var el = jQuery(this);

            // Add .js-select2-enabled class to tag it as activated
            el.addClass('js-select2-enabled');

            // Init
            el.select2({
                allowClear: true,
                placeholder: "Please select"
            });
        });
    };
     var showModelPopup = function(){

        $('.js-showmodelpopup').click(function() {
            var id = $(this).attr('ref-id');
            $.ajax({
                url: "viewrole",
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
            uiHelperSelect2();
            showModelPopup();
        }
    };
}();

// Initialize when page loads
jQuery(function() {
    CmsUserCreate.init();
    //$(':radio:not(:checked)').attr('disabled', true);
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
