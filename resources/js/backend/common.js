//to hide flash message
$("document").ready(function () {
	$(".alert[role='alert']").fadeTo(2000, 500).slideUp(500, function () {
		$(".alert[role='alert']").slideUp(5000);
	});
});

var PageLimit = 10;
window.Vue = require('vue');

// define
window.paginationComponent = Vue.extend({
	template: '<div class="row align-items-center"><div class="dataTables_length col-4 col-md-4 col-lg-4 col-xl-3 pagination-length-div"><select id="pagination_length" name="pagination_length" aria-controls="pagination_length" class="form-control select2-hide-search-box input-xsmall input-inline"><option value="5">5</option><option value="10">10</option><option value="15">15</option><option value="20">20</option><option value="-1">All</option></select></div><div class="dataTables_info col-8 col-md-8 col-lg-8 col-xl-9" id="pagination_record_msg"></div></div>'
});

Vue.filter("numberformat", number => Number.parseFloat(number).toFixed(2));

var Global = function () {
	var setGlobalPlugin = function () {
		$.ajaxSetup({
			headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}
		});

		swal.setDefaults({
			buttonsStyling: false,
			confirmButtonClass: 'btn btn-lg btn-noborder btn-hero btn-primary m-5',
			cancelButtonClass: 'btn btn-lg btn-noborder btn-hero btn-alt-secondary m-5',
			inputClass: 'form-control',
			focusConfirm: false
		});
	};

	var initConfirmationOnDelete = function () {
		$('body').on('click', '.delete-confirmation-button', function (event) {
			event.preventDefault();
			var deleteUrl = $(this).attr('href');
			/* Act on the event */
			swal({
				title: 'Are you sure?',
				text: 'This information will be permanently deleted!',
				type: 'warning',
				showCancelButton: true,
				confirmButtonText: 'Yes, delete it!',
				html: false,
			}).then(
				function (result) {
					if (result.value) {
						submitDeleteResourceForm(deleteUrl);
					}
				}, function (dismiss) {
				}
			);
		});
	};

	var submitDeleteResourceForm = function (deleteUrl) {
		$('<form>', {
			'method': 'POST',
			'action': deleteUrl,
			'target': '_top'
		})
			.append($('<input>', {
				'name': '_token',
				'value': $('meta[name="csrf-token"]').attr('content'),
				'type': 'hidden'
			}))
			.append($('<input>', {
				'name': '_method',
				'value': 'DELETE',
				'type': 'hidden'
			}))
			.hide().appendTo("body").submit();
	}

	var dateTimePickerIcons = function () {
		if (typeof $.fn.datetimepicker !== 'undefined') {
			$.extend($.fn.datetimepicker.Constructor.Default, {
				icons: {
					time: 'fal fa-clock',
					date: 'fal fa-calendar',
					up: 'fal fa-arrow-up',
					down: 'fal fa-arrow-down',
					previous: 'fal fa-chevron-left',
					next: 'fal fa-chevron-right',
					today: 'fal fa-calendar-check-o',
					clear: 'fal fa-trash',
					close: 'fal fa-times'
				},
			});
		}
	};

	var initializeCustomValidationMethod = function () {

		jQuery.validator.addMethod("greaterThanDate", function (value, element, param) {
			if (value != '' && $(param).val() != '') {
				let startDate = moment($(param).val(), 'DD-MM-YYYY');
				let endDate = moment(value, 'DD-MM-YYYY');
				return endDate.diff(startDate) >= 0;
			}
			return true;
		}, 'To date must be greater than from date.');

		jQuery.validator.addMethod("greaterThanDateTime", function (value, element, param) {
			if (value != '' && $(param).val() != '') {
				let startDate = moment($(param).val(), 'HH:mm:ss DD-MM-YYYY');
				let endDate = moment(value, 'HH:mm:ss DD-MM-YYYY');
				return endDate.diff(startDate) >= 0;
			}
			return true;
		}, 'To date must be greater than from date.');

		// custom validation for poll end date, if empty skip the validation
		jQuery.validator.addMethod("greaterThanPollClosingDateTime", function (value, element, param) {
				if ($(param).val() == '') {
					return true;
				} else {
					let startDate = moment($(param).val(), 'HH:mm:ss DD-MM-YYYY');
					let endDate = moment(value, 'HH:mm:ss DD-MM-YYYY');
					return endDate.diff(startDate) <= 0;
				}
				return true;
			}, 'To display results date must be less than closing date.'
		);


		// custom icon size validation method
		jQuery.validator.addMethod("icondimension", function (value, element, param) {
			//for create
			var imageData = $('#' + param[2] + '_preview').prop('src'); // Image reference
			//for edit mode
			if (imageData.includes('http') || imageData.includes('https')) {
				// imageData = base64_encode(imageData);
				// imageData = Buffer.from(imageData).toString('base64');
				return true;
			}
			$("body").append("<img id='hiddenImage' src='" + imageData + "' />");
			var width = $('#hiddenImage').width();
			var height = $('#hiddenImage').height();
			$('#hiddenImage').remove();
			if (Math.ceil(width) == param[0] && Math.ceil(height) == param[1]) {
				return true;
			} /*else if(!$('.new_upload').length){ // in edit mode if no change found, skip the validation
				console.log('exit-2');
				return true;
			}*/ else {
				return false;
			}
		}, 'Please upload an image with desired dimension.');

		// custom icon size validation method
		jQuery.validator.addMethod("imagedimension", function (value, element, param) {
			//for create
			var imageData = $('#image_preview').attr('src'); // Image reference
			if (typeof param[2] != "undefined" && param[2] == 1) {
				if (imageData == "") {
					return false;
				}
			}
			//for edit mode
			$("body").append("<img id='hiddenImage' src='" + imageData + "' />");
			var width = $('#hiddenImage').width();
			var height = $('#hiddenImage').height();
			$('#hiddenImage').remove();
			if (Math.ceil(width) == param[0] && Math.ceil(height) == param[1]) {
				return true;
			} else if (!$('.new_upload').length) { // in edit mode if no change found, skip the validation
				return true;
			} else {
				return false;
			}
		}, 'Please upload an image with desired dimension.');
	}

	return {
		init: function () {
			setGlobalPlugin();
			initConfirmationOnDelete();
			dateTimePickerIcons();
			initializeCustomValidationMethod();
		}
	};
}
();

// Initialize when page loads
jQuery(function () {
	Global.init();

	$(document).on('change', '.js-context-switch', function () {
		window.location.href = $(this).val();
	});
});

window.ajaxCall = function (url, data, method, dataType, successHandlerFunction, processDataFlag, contentTypeFlag) {
	if (typeof (processDataFlag) == 'undefined') {
		processDataFlag = true;
	}

	if (typeof (contentTypeFlag) == 'undefined') {
		contentTypeFlag = 'application/x-www-form-urlencoded';
	}

	$(".js-data-table .overlay").show();

	geturl = $.ajax({
		url: url,
		data: data,
		processData: processDataFlag,
		contentType: contentTypeFlag,
		type: method,
		dataType: dataType,
		cache: false,
		success: successHandlerFunction,
		headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
		complete: function () {
			$(".js-data-table .overlay").hide();
		}
	});
}

window.setPaginationAmount = function () {
	var set_pagination = '';

	if (typeof ($.cookie('pagination_length')) == "undefined") {
		set_pagination += "&pagination_length=10";
	} else {
		if ($.cookie('pagination_length') == -1) {
			set_pagination += "&pagination=false";
		} else {
			set_pagination += "&pagination_length=" + $.cookie('pagination_length');
		}
	}

	return set_pagination;
}

window.setPaginationRecords = function (start, records, totalcount) {
	if (records > totalcount) {
		$("#pagination_record_msg").html("Showing " + start + " to " + totalcount + " of " + totalcount + " entries");
	} else {
		$("#pagination_record_msg").html("Showing " + start + " to " + records + " of " + totalcount + " entries");
	}
}

window.initPaginationRecord = function () {
	if (typeof ($.cookie('pagination_length')) != "undefined") {
		$("#pagination_length").val($.cookie('pagination_length'));
	} else {
		$.cookie('pagination_length', PageLimit);
	}
}

window.clearFormData = function (formId) {
	$("#" + formId).find("input").val('');
	$("#" + formId).find("textarea").val('');
	$("#" + formId).find("select").val('');
	$("#" + formId).find(".form-group.is-invalid").removeClass('is-invalid');

	if ($("#" + formId).find(".js-select2").length) {
		$("#" + formId + " .js-select2").each(function () {
			$(this).val('').trigger('change.select2');
		});
	}
}

window.showTabError = function () {
	var errorFlag = false;
	$('.nav-item .nav-link').each(function (k, v) {
		$id = $(v).attr("href");
		if ($($id).find('.form-group.is-invalid').length == 0) {
			$(v).removeClass('text-danger');
		} else {
			$(v).addClass('text-danger');
			errorFlag = true;
		}
	});

	if (errorFlag && $('.js-tab-error-message').length == 0) {
		var errorMsg = "<span class='text-danger d-flex mb-15 js-tab-error-message'>Please confirm mandatory information on all tabs.</span>";
		$('.tab-pane').parent().prepend(errorMsg);
	} else if (!errorFlag && $('.js-tab-error-message').length > 0) {
		$('.js-tab-error-message').remove();
	}
}
