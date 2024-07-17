var StadiumBlockCreate = function() {

    var initFormValidations = function () {
        var StadiumBlockForm = $('.create-stadium-block-form');

        StadiumBlockForm.validate({
            ignore: [],
            errorClass: 'invalid-feedback animated fadeInDown',
            errorElement: 'div',
            errorPlacement: function(error, e)
            {
                //$('.js-position-error').hide();
                if (e.attr("name") == "seating_plan") {
                    $(e).parents('.form-group .logo-fields-wrapper').append(error);
                } else if(e.attr("name") == "pos_data") {
                    $('.js-position-error').html(error).show();
                } else {
                    $(e).parents('.form-group').append(error);
                }
                setTimeout(function(){ window.app.recalcOffsetValues(); }, 500);
            },
            highlight: function(e) {
                if ($(e).attr("name") == "seating_plan") {
                    $(e).removeClass('is-invalid').addClass('is-invalid');
                }
                $(e).closest('.form-group').removeClass('is-invalid').addClass('is-invalid');
            },
            unhighlight: function (e) {
                if ($(e).attr("name") == "seating_plan") {
                    $(e).removeClass('is-invalid');
                }
                $(e).closest('.form-group').removeClass('is-invalid');
            },
            success: function(e) {
                $(e).closest('.form-group').removeClass('is-invalid');
                $(e).remove();
            },
            rules: {
                'name' : {
                    required : true,
                },
                'pos_data' : {
                    required : true,
                },
                'seating_plan' : {
					required : true,
                    extension: "csv|xlsx|xls|xlsm"
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
    StadiumBlockCreate.init();
});

function readLogoURL(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            $('.js-manage-file-width').removeClass('col-12').addClass('col-9');
            if(input.id=='seating_plan'){
                $('#seating_plan_preview_container').html('<a download href="' + e.target.result + '">Download</a>');
            }
            $('#seating_plan_preview_container').removeClass('d-md-none');
            $('#seating_plan_preview_container').addClass('d-flex');
        }
        reader.readAsDataURL(input.files[0]);
    }
}
$(".uploadStadiumBlockSeatingFile").change(function() {
    readLogoURL(this);
});
$(document).on('click','.createBlock',function(){
    $( "#stadiumblocksForm" ).submit();
}); 