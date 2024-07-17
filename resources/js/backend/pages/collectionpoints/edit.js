var CollectionPointUpdate = function () {

	var initFormValidations = function () {
		var collectionPointForm = $('.edit-collection-point-form');

		collectionPointForm.validate({
			ignore: [],
			errorClass: 'invalid-feedback animated fadeInDown',
			errorElement: 'div',
			errorPlacement: function (error, e) {
				$(e).parents('.form-group').append(error);
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
				'blocks[]': {
					required: {
						depends: function(element) {
							return Site.is_using_allocated_seating == 1 ? true : false
						}
					}
				}
			}
		});
		$('.edit-collection-point-form').data('validator').settings.ignore = ".note-editor *";
	};

	return {
		init: function () {
			initFormValidations();
		}
	};

}();

// Initialize when page loads
jQuery(function () {
	CollectionPointUpdate.init();
});

$("#blocks").select2();
