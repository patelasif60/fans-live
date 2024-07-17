var TransactionSettings = function() {

	var initFormValidations = function () {

		var updateForm = $('.update-transaction-settings-form');

		updateForm.validate({
			ignore: [],
			errorClass: 'invalid-feedback animated fadeInDown',
			errorElement: 'div',
			errorPlacement: function(error, e) {
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
				'minimum_card_fee_amount' : {
					required : true,
					number: true
				},
				'card_fee_percentage' : {
					required : true,
					number: true
				},
				'bank_fee': {
					required: true,
					number: true,
				},
				'footer_text_for_receipt' : {
					required : true
				},
				'max_transaction_amount': {
					required: true,
					number: true,
				},
				'threshold_transaction_minutes': {
					required: true,
					number: true,
				},
			}
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
	TransactionSettings.init();
});
