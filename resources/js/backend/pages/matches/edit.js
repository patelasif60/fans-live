var MatchEdit = function () {

	var initFormValidations = function () {
		var MatchForm = $('.edit-match-form');

		MatchForm.validate({
			ignore: [],
			errorClass: 'invalid-feedback animated fadeInDown',
			errorElement: 'div',
			errorPlacement: function (error, e) {
				if (e.hasClass("uploadimage")) {
					$(e).parents('.form-group .js-manage-sponsor-logo-width').append(error);
				} else if (e.hasClass("uploadhospitality")) {
					$(e).parents('.form-group .logo-fields-wrapper').append(error);
				} else {
					$(e).parents('.form-group').append(error);
				}
				showTabError();
			},
			highlight: function (e) {
				$(e).closest('.form-group').removeClass('is-invalid').addClass('is-invalid');
			},
			unhighlight: function (e) {
				$(e).closest('.form-group').removeClass('is-invalid');
			},
			success: function (e) {
				$(e).closest('.form-group').removeClass('is-invalid');
				$(e).remove();
			},
			rules: {
				'kickoff_time': {
					required: true,
				},
				'home': {
					required: true,
				},
				'away': {
					required: true,
				},
				'maximum_number_of_ticket_per_user': {
					required: {
						depends: function (element) {
							return $(element).closest('form').find('#is_enable_ticket').is(':checked');
						}
					}
				},
				'available_blocks[]': {
					required: {
						depends: function (element) {
							return $('#seatValidaton').val() == 1 ? $(element).closest('form').find('#is_enable_ticket').is(':checked') : false;
						}
					}
				},
				'pricing_bands[]': {
					required: {
						depends: function (element) {
							return $(element).closest('form').find('#is_enable_ticket').is(':checked');
						}
					}
				},
				'rewards_percentage_override' : {
					number: true,
                    min: 0,
                    max: 100
				},
				'ticket_resale_fee_type': {
					required: {
						depends: function (element) {
							return $(element).closest('form').find('#allow_ticket_returns_resales').is(':checked');
						}
					}
				},
				'ticket_resale_fee_amount': {
					required: {
						depends: function (element) {
							return $(element).closest('form').find('#allow_ticket_returns_resales').is(':checked');
						}
					}
				},
				'hospitality_override_base_price': {
					number: true,
					min: 0,
				},
				'hospitality_rewards_percentage_override': {
					number: true,
					min: 0,
					max: 100
				},
				"hospitality_suites[]" : {
                    required: {
						depends: function (element) {
							return $(element).closest('form').find('#is_enable_hospitality').is(':checked');
						}
					},
                },
			},
		});
		$('.js-sponsors-fields-wrapper').each(function () {
			addValidationRules($(this));
		});
	};


	var uiHelperDateTimePicker = function () {
		$(".js-datetimepicker").datetimepicker({
			ignoreReadonly: true,
			format: Site.dateTimeCmsFormat,
			timeZone: Site.clubTimezone,
            buttons: {showClear: true},
		});
	};

	var uiHelperDatePicker = function () {
		$(".js-datepicker").datetimepicker({
			ignoreReadonly: true,
			format: Site.dateCmsFormat,
			timeZone: Site.clubTimezone,
            buttons: {showClear: true},
		});
	};
	var uiHelperHospitalityDatePicker = function () {
		$(".js-hospitality-datepicker").datetimepicker({
			ignoreReadonly: true,
			format: Site.dateTimeCmsFormat,
			timeZone: Site.clubTimezone,
            buttons: {showClear: true},
		});
	};
	var addValidationRules = function (formElement) {
		var $formElement = $(formElement);
		var $inputs = $formElement.find('input[name^="sponsors"]');

		var addRequiredValidation = function () {
//            $(this).rules('add', {
//                required:{
//                    depends: function(element) {
//                        return $(element).closest('form').find('#is_enable_ticket').is(':checked');
//                    }
//                }
//            });
		};

		$inputs.filter('input[name$="[sponsor]"]').each(addRequiredValidation);
	};
	var addDateValidationRules = function (formElement) {
		var $formElement = $(formElement);
		var $inputs = $formElement.find('input[name^="package"]');

		var addRequiredDateValidation = function () {
			$(this).rules('add', {
				required: {
					depends: function (element) {
						return $(element).closest('form').find('#is_enable_ticket').is(':checked');
					}
				}
			});
		};

		$inputs.filter('input[name^="package"]').each(addRequiredDateValidation);
	};
	var addDateHospitalityValidationRules = function (formElement) {
		var $formElement = $(formElement);
		var $inputs = $formElement.find('input[name^="hospitality_package"]');

		var addRequiredDateValidation = function () {
			$(this).rules('add', {
				required: {
					depends: function (element) {
						return $(element).closest('form').find('#is_enable_hospitality').is(':checked');
					}
				}
			});
		};

		$inputs.filter('input[name^="hospitality_package"]').each(addRequiredDateValidation);
	};

	var addTicketingPackageDatesValidationRules = function () {
		$.validator.addMethod('atLeastOneTicketingPackageDate', function(value, element, params) {
			if(!$('#is_enable_ticket').is(':checked')) {
				return true;
			}
		    var dates = $('input[name^="package"]').filter(function() {
		        return $(this).val() != '';
		    });
	    	return dates.length > 0;
		}, 'Please select atleast one on sale date.');

		$.validator.addClassRules('ticketing-package-date-required', {atLeastOneTicketingPackageDate: true});
	};

	var addHospitalityPackageDatesValidationRules = function () {
		$.validator.addMethod('atLeastOneHospitalityPackageDate', function(value, element, params) {
			if(!$('#is_enable_hospitality').is(':checked')) {
				return true;
			}
		    var dates = $('input[name^="hospitality_package"]').filter(function() {
		        return $(this).val() != '';
		    });
	    	return dates.length > 0;
		}, 'Please select atleast one on sale date.');

		$.validator.addClassRules('hospitality-package-date-required', {atLeastOneHospitalityPackageDate: true});
	};

	var initFormRepeaters = function () {
		$('.repeater').repeater({
			show: function () {
				$(this).slideDown();
				addValidationRules(this);
			},
			isFirstItemUndeletable: true,
		});
	};

	var manageOnLoad = function (id, name) {
		var playersList = '';
		$.each(Site.players, function (key, value) {
			playersList += '<option value=' + value.id + '>' + value.name + '</option>';
		});

		// Match home team player
		$(document).on('click', ".js-edit-home-team-player", function () {

			if ($('.edit-home-team').attr('id') >= 0) {
				var editHomeTeamId = parseInt($('#edit_home_team_player').children().last().attr('id')) + 1;
			} else {
				var editHomeTeamId = 0;
			}
			editHomeTeamPlayer(editHomeTeamId, playersList);
			uiHelperSelect2();
		});

		// Match away team player
		$(document).on('click', ".js-edit-away-team-player", function () {
			if ($('.edit-away-team').attr('id') >= 0) {
				var addAwayTeamId = parseInt($('#edit_away_team_player').children().last().attr('id')) + 1;
			} else {
				var addAwayTeamId = 0
			}

			editAwayTeamPlayer(addAwayTeamId, playersList);
			uiHelperSelect2();
		});

		$(document).on('click', ".js-add-player-save", function () {
			if (!addPlayerForm.valid()) {
				return false;
			}
			var name = $("#player_name").val();
			var data = "name=" + name;

			ajaxCall("../addPlayer", data, 'POST', 'json', addPlayerDataSuccess);
		});

		function addPlayerDataSuccess(addPlayerData) {
			$('#add_player').modal('hide');
			$('#player_name').val('');
			toastr.success('Player added successfully.', 'Success!');
			playersList += '<option value=' + addPlayerData['id'] + '>' + addPlayerData['name'] + '</option>';

			$('.edit-home-team .line-ups-home-name').each(function () {
				$(this).append('<option value=' + addPlayerData['id'] + '>' + addPlayerData['name'] + '</option>');
			})

			$('.edit-away-team .line-ups-away-name').each(function () {
				$(this).append('<option value=' + addPlayerData['id'] + '>' + addPlayerData['name'] + '</option>');
			})
		}
	};

	return {
		init: function () {
			initFormValidations();
			uiHelperDateTimePicker();
			uiHelperDatePicker();
			formLineUpsValidation();
			initFormRepeaters();
			uiHelperHospitalityDatePicker();
			addTicketingPackageDatesValidationRules();
			addHospitalityPackageDatesValidationRules();
			manageOnLoad();
		}
	};
}();

// Initialize when page loads
jQuery(function () {
	MatchEdit.init();
});

// variable declaration
var homePlayer = [];
var awayPlayer = [];
var benchHomePlayer = [];
var benchAwayPlayer = [];

$('.edit-match-form').submit(function (event) {
	if ($('.edit-match-form').valid()) {
		$('.match-submit-btn').attr('disabled', 'disabled');
		return true;
	}
	event.preventDefault();
});

function formLineUpsValidation() {

	$('.match-event-team, .line-ups-home-number, .line-ups-away-number, .match_events_time').each(function (index) {
		$(this).rules("add", {
			required: true,
			number: true,
			min: 0,
			messages: {
				required: "This field is required."
			}
		});
	});

	$('.line-ups-home-name, .line-ups-away-name, .match-type-of-event, .match-event-player, .select2-substitution-player').each(function () {
		$(this).rules('add', {
			required: true,
			messages: {
				required: "This field is required."
			}
		});
	});

};

var uiHelperSelect2 = function () {        // Init Select2 (with .js-select2-allow-clear class)
	jQuery('.js-select2-allow-clear:not(.js-select2-enabled), .js-select2:not(.js-select2-enabled)').each(function () {
		var el = jQuery(this);

		// Add .js-select2-enabled class to tag it as activated
		el.addClass('js-select2-enabled');

		// Init
		el.select2({
			allowClear: $(this).hasClass('js-select2-allow-clear') ? true : false,
			placeholder: "Please select"
		});
	});
};

// Bootstrap Custom File Input Filename
var uiHelperCoreCustomFileInput = function () {
	// Populate custom Bootstrap file inputs with selected filename
	jQuery('[data-toggle="custom-file-input"]:not(.js-custom-file-input-enabled)').each(function (index, element) {
		var el = jQuery(element);

		// Add .js-custom-file-input-enabled class to tag it as activated
		el.addClass('js-custom-file-input-enabled').on('change', function (e) {
			var fileName = (e.target.files.length > 1) ? e.target.files.length + ' ' + (el.data('lang-files') || 'Files') : e.target.files[0].name;

			el.next('.custom-file-label').css('overflow-x', 'hidden').html(fileName);
		});
	});
};

function editHomeTeamPlayer(editHomeTeamId, playersList) {
	var addLineUpsHomeTeamPlayer = '<div class="block block-bordered block-default block-rounded"> <div class="block-header block-header-default"> <div></div><div class="block-options"><button type="button" class="btn-block-option js-edit-home-team-delete text-danger" title="" data-original-title="Delete"><i class="fal fa-trash"></i></button></div></div><div class="block-content"> <div class="row"> <div class="col-xl-4"><div class="form-group"><label for="line_ups_home_number" class="required">No:</label><input type="number" min="0" class="form-control line-ups-home-number" id="line_ups_home_number' + editHomeTeamId + '" name="line_ups_home_number_new[' + editHomeTeamId + ']" value=""><input type="hidden" name="line_ups_home_number_edit[]" value="0"></div></div><div class="col-xl-4"><div class="form-group"><label for="line_ups_home_name" class="required">Name:</label><select class="js-select2 form-control line-ups-home-name" id="line_ups_home_name' + editHomeTeamId + '" name="line_ups_home_name_new[' + editHomeTeamId + ']"><option value="">Please select</option>' + playersList + '</select></div></div><div class="col-xl-4"><div class="form-group row"> <label class="col-12">Sub</label> <div class="col-12"> <div class="custom-control custom-checkbox mb-5"> <input class="custom-control-input sub-home" type="checkbox" name="sub_home_new[' + editHomeTeamId + ']" id="sub_home' + editHomeTeamId + '"><label class="custom-control-label" for="sub_home' + editHomeTeamId + '"></label> </div></div></div></div></div></div></div>';

	$('#edit_home_team_player').append('<div class="col-xl-12 edit-home-team" id=' + editHomeTeamId + '>' + addLineUpsHomeTeamPlayer + '</div>');
	formLineUpsValidation();
}

$(document).on('click', '.js-edit-home-team-delete', function () {
	$(this).closest('.edit-home-team').remove();
});

function editAwayTeamPlayer(addAwayTeamId, playersList) {
	var addLineUpsAwayTeamPlayer = '<div class="block block-bordered block-default block-rounded"><div class="block-header block-header-default"> <div></div><div class="block-options"><button type="button" class="btn-block-option js-edit-home-team-delete text-danger" title="" data-original-title="Delete"><i class="fal fa-trash"></i></button></div></div><div class="block-content"> <div class="row"> <div class="col-xl-4"><div class="form-group"><label for="line_ups_away_number" class="required">No:</label><input type="number" min="0" class="form-control line-ups-away-number" id="line_ups_away_number' + addAwayTeamId + '" name="line_ups_away_number_new[' + addAwayTeamId + ']" value=""><input type="hidden" name="line_ups_away_number_edit[]" value="0"></div></div><div class="col-xl-4"><div class="form-group"><label for="line_ups_away_name" class="required">Name:</label><select class="js-select2 form-control line-ups-away-name" id="line_ups_away_name' + addAwayTeamId + '" name="line_ups_away_name_new[' + addAwayTeamId + ']"><option value="">Please select</option>' + playersList + '</select></div></div><div class="col-xl-4"><div class="form-group row"> <label class="col-12">Sub</label> <div class="col-12"> <div class="custom-control custom-checkbox mb-5"> <input class="custom-control-input sub-away" type="checkbox" name="sub_away[' + addAwayTeamId + ']" id="sub_away' + addAwayTeamId + '"> <label class="custom-control-label" for="sub_away' + addAwayTeamId + '"></label> </div></div></div></div></div></div></div>';

	$('#edit_away_team_player').append('<div class="col-xl-12 edit-away-team" id=' + addAwayTeamId + '>' + addLineUpsAwayTeamPlayer + '</div>')
	formLineUpsValidation();
}

function getSelectValues(data) {
	var returnData = [];
	var options = {};
	options.id = '';
	options.text = 'Please select';
	returnData.push(options);

	$.each(data, function (key, value) {
		var options = {};
		options.id = value.player.id;
		options.text = value.player.name;
		returnData.push(options);
	});

	return returnData;
}


function getMatchEventValue() {
	var matchEventValue = [];
	var options = {};
	options.id = '';
	options.text = 'Please select';
	matchEventValue.push(options);

	$.each(Site.matchEventtype, function (value, key) {
		var options = {};
		options.id = value;
		options.text = key;
		matchEventValue.push(options);
	});
	return matchEventValue;
}

$(document).on('click', '.js-edit-home-team-delete', function () {
	$(this).closest('.edit-away-team').remove();
});

// Match event
$(document).on('click', ".js-edit-match-event", function () {

	if ($('.edit-match-event').attr('id') >= 0) {
		var editMatchEventId = parseInt($('#edit_match_event').children().last().attr('id')) + 1;
	} else {
		var editMatchEventId = 0;
	}

	editPlayerMatchEvent(editMatchEventId);
	uiHelperCoreCustomFileInput();
	uiHelperSelect2();

	$('.select2-match-event-type').select2({
		data: getMatchEventValue(),
		placeholder: "Please select",
		allowClear: true,
	});

	getSelectValues(Site.awayLineupPlayer); // assign value to variable

	$('.match-event-player:last').html('').select2({
		data: getSelectValues(Site.homeLineupPlayer),
		placeholder: "Please select",
		allowClear: true,
	});
});

function editPlayerMatchEvent(editMatchEventId) {
	var addMatchEvents = '<div class="block block-bordered block-default"><div class="block-header block-header-default"><div></div><div class="block-options"><button type="button" class="btn-block-option js-match-event-delete text-danger" title="" data-original-title="Delete"><i class="fal fa-trash"></i></button></div></div><div class="block-content block-content-full"><div class="row"><div class="col-xl-4"><div class="form-group"><label class="required" for="match_event' + editMatchEventId + '">Team:</label><select class="js-select2 form-control match-event-team" id="match_event' + editMatchEventId + '" name="match_event[' + editMatchEventId + ']" style="width:100%"><option value="">Please select</option><option value="' + Site.homeTeam.id + '" data-type="home">' + Site.homeTeam.name + '</option><option value="' + Site.awayTeam.id + '" data-type="away">' + Site.awayTeam.name + '</option></select></div></div><div class="col-xl-4"><div class="form-group"><label class="required" for="match_event_player' + editMatchEventId + '">Player:</label><select class="js-select2 form-control match-event-player" id="match_event_player' + editMatchEventId + '" name="match_event_player[' + editMatchEventId + ']" style="width:100%"><option value="1">Please select</option></select></div></div><div class="col-xl-4"><div class="form-group"><label class="required" for="match_events_time' + editMatchEventId + '">Time (mins):</label><input type="number" min="0" class="form-control match_events_time" id="match_events_time' + editMatchEventId + '" name="match_events_time[' + editMatchEventId + ']" value=""></div></div><div class="col-xl-4"><div class="form-group mb-0"><label>Action replay video:</label><div class="custom-file"><input type="file" class="custom-file-input action_replay_video" id="action_replay_video' + editMatchEventId + '" name="action_replay_video[' + editMatchEventId + ']" data-toggle="custom-file-input" accept="video/*"><label class="custom-file-label text-truncate pr-100" for="action_replay_video' + editMatchEventId + '">Choose file</label></div></div></div><div class="col-xl-8"><div class="row type-of-event-main-div"><div class="col-xl-6"><div class="form-group mb-0" data-select2-id="21"><label class="required" for="match_type_of_event' + editMatchEventId + '">Type of event:</label><select class="form-control select2-match-event-type match-type-of-event js-select2" id="match_type_of_event' + editMatchEventId + '" name="match_type_of_event[' + editMatchEventId + ']" style="width:100%"><option value="">Please select</option></select></div></div></div></div></div></div>';

	$('#edit_match_event').append('<div class="col-xl-12 edit-match-event" id=' + editMatchEventId + '>' + addMatchEvents + '</div>');
	formLineUpsValidation();
}

$(document).on('change', '.match-type-of-event', function () {
	manageSubstitution($(this));
});

function manageSubstitution(me) {

	var editMatchEventId = me.closest('.edit-match-event').attr('id');
	var type = (me.val()).trim();

	me.closest('.edit-match-event').find('.substitution-player').remove();
	if (type && type == 'substitution') {
		me.closest('.edit-match-event').find('.type-of-event-main-div').append('<div class="col-xl-6 substitution-player"><div class="form-group mb-0" data-select2-id="21"><label for="match_type_of_event' + editMatchEventId + '">Subbed for:</label><select class="form-control js-select2 select2-substitution-player" id="substitution-player' + editMatchEventId + '" name="substitution_player[' + editMatchEventId + ']" style="width:100%"><option value="">Please select</option></select></div>');

		var benchPlayerOption = me.closest('.edit-match-event').find('.match-event-team').children("option:selected").attr('data-type');

		if (benchPlayerOption == 'home') {
			me.closest('.row').find('.select2-substitution-player').select2({
				data: getSelectValues(Site.homeBenchPlayer),
				placeholder: "Please select",
				allowClear: true,
			});
		} else if (benchPlayerOption == 'away') {
			me.closest('.row').find('.select2-substitution-player').select2({
				data: getSelectValues(Site.awayBenchPlayer),
				placeholder: "Please select",
				allowClear: true,
			});
		}

		// $('.select2-substitution-player').each(function () {
		me.closest('.edit-match-event').find('.select2-substitution-player').rules('add', {
			required: true,
			messages: {
				required: "This field is required."
			}
		});
		// });

	}
}

$(document).on('change', '.match-event-team', function () {
	var editMatchEventId = $(this).closest('.edit-match-event').attr('id');
	if ($(this).children("option:selected").attr('data-type') == 'home') { // option home selected
		$(this).closest('.edit-match-event').find('.match-event-player').html('').select2({
			data: getSelectValues(Site.homeLineupPlayer),
			placeholder: "Please select",
			allowClear: true,
		});

		$('#substitution-player' + editMatchEventId).html('').select2({
			data: getSelectValues(Site.homeBenchPlayer),
			placeholder: "Please select",
			allowClear: true,
		});
	} else { // option away selected
		$(this).closest('.edit-match-event').find('.match-event-player').html('').select2({
			data: getSelectValues(Site.awayLineupPlayer),
			placeholder: "Please select",
			allowClear: true,
		});
		$('#substitution-player' + editMatchEventId).html('').select2({
			data: getSelectValues(Site.awayBenchPlayer),
			placeholder: "Please select",
			allowClear: true,
		});
	}
});

$(document).on('click', '.js-match-event-delete', function () {
	$(this).closest('.edit-match-event').remove();
});

function readLogoURL(input) {
	if (input.files && input.files[0]) {
		var reader = new FileReader();
		reader.onload = function (e) {
			$('.js-manage-unavailable-seats-width').removeClass('col-12').addClass('col-9');
			if ($('#unavailable_seats_image').hasClass('d-md-none')) {
				$('#unavailable_seats_image').removeClass('d-md-none');
			}
			$('#unavailable_seats_preview_remove').removeClass('d-md-none');
			$('#unavailable_seats_preview_container').removeClass('d-md-none');
			$('#unavailable_seats_preview_container').html('<a download href="' + e.target.result + '">Download</a>');
		}
		reader.readAsDataURL(input.files[0]);
	}
}

$("#unavailable_seats_preview_remove").on('click', function () {
	$('#unavailable_seats').val('');
	let lbl = document.getElementById('lbl_unavailable_seats');
	lbl.innerText = "Choose File";
	$('#unavailable_seats_preview_container').addClass('d-md-none');
	$('.js-manage-unavailable-seats-width').removeClass('col-9').addClass('col-12');
	//$('#logo_preview').attr('src', '');
	$('#unavailable_seats_preview_remove').addClass('d-md-none');
});


$(document).on('change', '#unavailable_seats', function (e) {
	readLogoURL(this);
	//$(this).next('.custom-file-label').css('overflow-x', 'hidden').html(e.target.files[0].name);
});

function readHospitalityImageURL(input) {
	if (input.files && input.files[0]) {
		var reader = new FileReader();
		reader.onload = function (e) {
			$('.js-manage-hospitality-unavailable-seats-width').removeClass('col-12').addClass('col-9');
			if ($('#hospitality_unavailable_seats_image').hasClass('d-md-none')) {
				$('#hospitality_unavailable_seats_image').removeClass('d-md-none');
			}
			$('#hospitality_unavailable_seats_preview_remove').removeClass('d-md-none');
			$('#unavailable_hospitality_seats_preview_container').removeClass('d-md-none');
			$('#unavailable_hospitality_seats_preview_container').html('<a download href="' + e.target.result + '">Download</a>');
		}
		reader.readAsDataURL(input.files[0]);
	}
}

$("#hospitality_unavailable_seats_preview_remove").on('click', function () {
	$('#hospitality_unavailable_seats').val('');
	let lbl = document.getElementById('lbl_hospitality_unavailable_seats');
	lbl.innerText = "Choose File";
	$('#unavailable_hospitality_seats_preview_container').addClass('d-md-none');
	$('.js-manage-hospitality-unavailable-seats-width').removeClass('col-9').addClass('col-12');
	//$('#logo_preview').attr('src', '');
	$('#hospitality_unavailable_seats_preview_remove').addClass('d-md-none');
});


$(document).on('change', '#hospitality_unavailable_seats', function (e) {
	readHospitalityImageURL(this);
});

$(document).on('click', '.manage-hide-show', function () {
	if (this.checked) {
		$(this).closest('.tab-pane').find('.manage-hide-show-div').removeClass('d-none');
		$(this).closest('.tab-pane').find('.manage-hide-show-div').addClass('d-block');
	} else {
		$(this).closest('.tab-pane').find('.manage-hide-show-div').removeClass('d-block');
		$(this).closest('.tab-pane').find('.manage-hide-show-div').addClass('d-none');
	}
});

$(document).on('click', '.manage-ticket-type-amount', function () {
	if (this.checked) {
		$(this).closest('.tab-pane').find('.manage-ticket-type-amount-container').removeClass('d-none');
		$(this).closest('.tab-pane').find('.manage-ticket-type-amount-container').addClass('d-block');
	} else {
		$(this).closest('.tab-pane').find('.manage-ticket-type-amount-container').removeClass('d-block');
		$(this).closest('.tab-pane').find('.manage-ticket-type-amount-container').addClass('d-none');
	}
});
$(document).on('click', '.js-remove-img', function (e) {
	$('.js-remove-thumb:last').addClass('d-md-none');
});
$(document).on('click', ".logo-delete", function() {
	$(this).closest('.js-manage-sponsor-logo-width').remove();
});
$(document).on('change', '.uploadimage', function () {
	var fileName = this.files[0].name;
    $(this).next('.custom-file-label').css('overflow-x', 'hidden').html(fileName);
	var ext = $(this).val().split('.').pop().toLowerCase();
	if ($.inArray(ext, ['png', 'jpg', 'jpeg']) != -1) {
		readSponsereURL(this);
	}
});

function readSponsereURL(input) {
	var data = input.name
	if (input.files && input.files[0]) {
		var reader = new FileReader();
		reader.onload = function (e) {
			$('img[name="' + data.replace("[sponsor]", "[preview]") + '"]').attr('src', e.target.result);
			$('div[name="' + data.replace("[sponsor]", "[preview_container]") + '"]').removeClass('d-md-none');
		}
		reader.readAsDataURL(input.files[0]);
	}
}

$(document).on('change', '#home', function () {
	var optionText = $('#home option[value="' + Site.clubId + '"]').text();
	if ($('#home').select2('val') == Site.clubId) {
		$('#is_enable_ticket').removeAttr('disabled');
        $('#is_enable_hospitality').removeAttr('disabled');
		$('#away').select2('val', '');
		$('#away option[value="' + Site.clubId + '"]').detach();
	} else {
		$('#is_enable_ticket').attr('disabled','disabled');
        $('#is_enable_hospitality').attr('disabled','disabled');
		if ($('#away').find("option[value='" + Site.clubId + "']").length) {
			$('#away').val(Site.clubId).trigger('change.select2');
		} else {
			var data = {
				id: Site.clubId,
				text: optionText
			};
			var newOption = new Option(data.text, data.id, true, true);
			$('#away').append(newOption).trigger('change');
		}
	}
});

$(document).on('change', '#away', function () {
	var optionText = $('#away option[value="' + Site.clubId + '"]').text();
	if ($('#away').select2('val') == Site.clubId) {
		$('#is_enable_ticket').attr('disabled','disabled');
        $('#is_enable_hospitality').attr('disabled','disabled');
		$('#home').select2('val', '');
		$('#home option[value="' + Site.clubId + '"]').detach();
	} else {
		$('#is_enable_ticket').removeAttr('disabled');
        $('#is_enable_hospitality').removeAttr('disabled');
		if ($('#home').find("option[value='" + Site.clubId + "']").length) {
			$('#home').val(Site.clubId).trigger('change.select2');
		} else {
			var data = {
				id: Site.clubId,
				text: optionText
			};
			var newOption = new Option(data.text, data.id, true, true);
			$('#home').append(newOption).trigger('change');
		}
	}
});

$(window).on('load', function () {
	if ($('#home').select2('val') == Site.clubId) {
		$('#away').select2('val', '');
		$('#away option[value="' + Site.clubId + '"]').detach();
	}
	if ($('#away').select2('val') == Site.clubId) {
		$('#home').select2('val', '');
		$('#home option[value="' + Site.clubId + '"]').detach();
	}
});

var addPlayerForm = $('#add_player_form');

addPlayerForm.validate({
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
		'player_name': {
			required: true
		}
	},
});

$("#available_blocks").select2();
$("#pricing_bands").select2();
$("#hospitality_suites").select2();
