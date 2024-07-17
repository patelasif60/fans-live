var StadiumBlockEdit = function() {

	var initFormValidations = function () {
		var StadiumBlockForm = $('.edit-stadium-block-form');

		StadiumBlockForm.validate({
			ignore: [],
			errorClass: 'invalid-feedback animated fadeInDown',
			errorElement: 'div',
			errorPlacement: function(error, e)
			{
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
                'name' : {
                    required : true
                },
                'pos_data' : {
                    required : true
                },
                'seating_plan' : {
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
	StadiumBlockEdit.init();
	//clearCanvas(true);
});

function readLogoURL(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            $('.js-manage-file-width').removeClass('col-12').addClass('col-9');
            if ($('#seating_plan').hasClass('d-md-none')) {
                $('#seating_plan').removeClass('d-md-none');
            }
            $('#seating_plan').html('<a download href="' + e.target.result + '">Download</a>');
        }
        reader.readAsDataURL(input.files[0]);
    }
}
 $(".uploadStadiumBlockSeatingFile").change(function() {
    readLogoURL(this);
});

$(document).on('click','.updateBlock',function(){
	$( "#stadiumblocksForm" ).submit();
}); 
