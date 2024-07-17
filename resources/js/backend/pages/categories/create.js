var CategoryCreate = function () {

	var initFormValidations = function () {
		var categoryForm = $('.create-category-form');

		categoryForm.validate({
			ignore: [],
			errorClass: 'invalid-feedback animated fadeInDown',
			errorElement: 'div',
			errorPlacement: function (error, e) {
				if (e.attr("name") == "logo") {
					$(e).parents('.form-group .logo-input').append(error);
				} else {
					$(e).parents('.form-group').append(error);
				}
			},
			highlight: function (e) {
				$(e).closest('.form-group').removeClass('is-invalid').addClass('is-invalid');
			},
			unhighlight: function (e) {
				$(e).closest('.form-group').removeClass('is-invalid').removeClass('is-invalid');
			},
			success: function (e) {
				$(e).closest('.form-group').removeClass('is-invalid');
				$(e).remove();
			},
			rules: {
				'title': {
					required: true,
				},
				'rewards_percentage_override': {
					number: true
				},
				'logo': {
					required: true,
					accept: "image/png",
					extension: "png",
					icondimension: [840, 630,'logo'],
				}
			},
			messages: {
				'logo': {
					accept: 'Please upload the correct file format.',
					icondimension: 'Please upload the correct file size.'
				}
			}
		});
		$('.create-category-form').data('validator').settings.ignore = ".note-editor *";
	};

	return {
		init: function () {
			initFormValidations();
		}
	};

}();

// Initialize when page loads
jQuery(function () {
	CategoryCreate.init();
});

function readLogoURL(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
          $('.js-manage-logo-width').removeClass('col-12').addClass('col-9');
          $('#logo_preview').attr('src', e.target.result);
          $('#logo_preview_container').removeClass('d-md-none');
        }
        reader.readAsDataURL(input.files[0]);
    }
}

$("#logo").change(function() {
    var ext = $(this).val().split('.').pop().toLowerCase();
    if($.inArray(ext, ['png','jpg','jpeg']) != -1) {
        readLogoURL(this);
    }
});
