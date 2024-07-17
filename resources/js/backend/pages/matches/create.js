var MatchCreate = function() {
    var initFormValidations = function () {
        var MatchForm = $('.create-match-form');
        MatchForm.validate({
            ignore: [],
            errorClass: 'invalid-feedback animated fadeInDown',
            errorElement: 'div',
            errorPlacement: function(error, e)
            {

				if (e.hasClass("uploadimage")) {
                    $(e).parents('.form-group .logo-fields-wrapper').append(error);
                } else {
                    $(e).parents('.form-group').append(error);
                }
                showTabError();
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
                'kickoff_time' : {
                    required : true,
                },
                'home' : {
                    required : true,
                },
                'away' : {
                    required : true,
                },
                'maximum_number_of_ticket_per_user' : {
                    required: {
                        depends: function(element) {
                            return $(element).closest('form').find('#is_enable_ticket').is(':checked');
                        }
                    }
                },
                'available_blocks[]' : {
                    required: {
                        depends: function(element) {
                            return $('#seatValidaton').val() == 1 ? $(element).closest('form').find('#is_enable_ticket').is(':checked') : false;
                        }
                    }
                },
                'pricing_bands[]' : {
                    required: {
                        depends: function(element) {
                            return $(element).closest('form').find('#is_enable_ticket').is(':checked');
                        }
                    }
                },
                'unavailable_seats' : {
                    extension: "csv|xlsx|xls|xlsm"
                },
                'rewards_percentage_override' : {
                    number: true,
                    min: 0,
                    max: 100
                },
                'ticket_resale_fee_type' : {
                    required: {
                        depends: function(element) {
                            return $(element).closest('form').find('#allow_ticket_returns_resales').is(':checked');
                        }
                    }
                },
                'ticket_resale_fee_amount' : {
                    required: {
                        depends: function(element) {
                            return $(element).closest('form').find('#allow_ticket_returns_resales').is(':checked');
                        }
                    }
                },
                'hospitality_override_base_price' : {
                    number: true,
                    min: 0,
                },
                'hospitality_unavailable_seats' : {
                    extension: "csv|xlsx|xls|xlsm"
                },
                'hospitality_rewards_percentage_override' : {
                    number: true,
                    min: 0,
                    max: 100
                },
                "hospitality_suites[]" : {
                    required: {
                        depends: function(element) {
                            return $(element).closest('form').find('#is_enable_hospitality').is(':checked');
                        }
                    }
                },
            },
        });
       $('.js-sponsors-fields-wrapper').each(function () {
        	addValidationRules($(this));
        });
    };



    var uiHelperDateTimePicker = function(){
        $(".js-datetimepicker").datetimepicker({
            ignoreReadonly: true,
            format: Site.dateTimeCmsFormat,
            timeZone: Site.clubTimezone,
            buttons: {showClear: true},
        });
    };

    var uiHelperDatePicker = function(){
        $(".js-datepicker").datetimepicker({
            ignoreReadonly: true,
            format: Site.dateCmsFormat,
            timeZone: Site.clubTimezone,
            buttons: {showClear: true},
        });
    };
    var uiHelperHospitalityDatePicker = function(){
        $(".js-hospitality-datepicker").datetimepicker({
            ignoreReadonly: true,
            format: Site.dateTimeCmsFormat,
            timeZone: Site.clubTimezone,
            buttons: {showClear: true},
        });
    };
    var addValidationRules = function(formElement) {
        var $formElement = $(formElement);
        var $inputs = $formElement.find('input[name^="sponsors"]');

        var addRequiredValidation = function() {
//            $(this).rules('add', {
//                required: {
//                    depends: function(element) {
//                        return $(element).closest('form').find('#is_enable_ticket').is(':checked');
//                    }
//                }
//            });
        };

        $inputs.filter('input[name$="[sponsor]"]').each(addRequiredValidation);
    };

    var addDateValidationRules = function(formElement) {
        var $formElement = $(formElement);
        var $inputs = $formElement.find('input[name^="package"]');

        var addRequiredDateValidation = function() {
            $(this).rules('add', {
                required: {
                    depends: function(element) {
                        return $(element).closest('form').find('#is_enable_ticket').is(':checked');
                    }
                }
            });
        };

        $inputs.filter('input[name^="package"]').each(addRequiredDateValidation);
    };
    var addDateHospitalityValidationRules = function(formElement) {
        var $formElement = $(formElement);
        var $inputs = $formElement.find('input[name^="hospitality_package"]');

        var addRequiredDateValidation = function() {
            $(this).rules('add', {
                required: {
                    depends: function(element) {
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

    var initFormRepeaters = function() {
        $('.repeater').repeater({
            show: function () {
                $(this).slideDown();
                addValidationRules(this);
            },
            isFirstItemUndeletable: true,
        });
    };

    var manageOnLoad = function(id = '', name = '') {
        var playersList = '';
        $.each(Site.players, function(key, value) {
            playersList += '<option value=' + value.id + '>' + value.name + '</option>';
        });

        // Match home team player
        $(document).on('click', ".js-add-home-team-player", function() {

            if($('.add-home-team').attr('id') >= 0) {
                var addHomeTeamId = parseInt($('#add_home_team_player').children().last().attr('id'))+1;
            }
            else {
                var addHomeTeamId = 0;
            }

            addHomeTeamPlayer(addHomeTeamId, playersList);
            uiHelperSelect2();
        });

        // Match away team player
        $(document).on('click', ".js-add-away-team-player", function() {

            if($('.add-away-team').attr('id') >= 0) {
                var addAwayTeamId = parseInt($('#add_away_team_player').children().last().attr('id'))+1;
            }
            else {
                var addAwayTeamId = 0
            }

            addAwayTeamPlayer(addAwayTeamId, playersList);
            uiHelperSelect2();
        });

        $(document).on('click', ".js-add-player-save", function() {
            if(!addPlayerForm.valid()) {
                return  false;
            }
            var name = $("#player_name").val();
            var data = "name=" + name;

            ajaxCall("addPlayer", data, 'POST', 'json', addPlayerDataSuccess);
        });

        function addPlayerDataSuccess(addPlayerData) {
            $('#add_player').modal('hide');
            $('#player_name').val('');
            toastr.success('Player added successfully.', 'Success!');
            playersList += '<option value=' + addPlayerData['id'] + '>' + addPlayerData['name'] + '</option>';

            $('.add-home-team .line-ups-home-name').each(function(){
                $(this).append('<option value=' + addPlayerData['id'] + '>' + addPlayerData['name'] + '</option>');
            })

            $('.add-away-team .line-ups-away-name').each(function(){
                $(this).append('<option value=' + addPlayerData['id'] + '>' + addPlayerData['name'] + '</option>');
            })
        }
    };

    return {
        init: function() {
            manageOnLoad();
            initFormRepeaters();
            initFormValidations();
            uiHelperDateTimePicker();
            uiHelperDatePicker();
            uiHelperHospitalityDatePicker();
            addTicketingPackageDatesValidationRules();
            addHospitalityPackageDatesValidationRules();
        }
    };
}();

// Initialize when page loads
jQuery(function() {
    MatchCreate.init();
});

function formLineUpsValidation() {
    $('.line-ups-home-number, .line-ups-away-number, .match_events_time').each(function () {
        $(this).rules("add", {
            required: true,
            number: true,
            min: 0,
            messages: {
                required: "This field is required."
            }
        });
    });

    $('.line-ups-home-name, .line-ups-away-name, .match-event-player, .match-type-of-event, .select2-substitution-player').each(function () {
        $(this).rules('add', {
            required: true,
            messages: {
                required: "This field is required."
            }
        });
    });
};

var uiHelperSelect2 = function(){        // Init Select2 (with .js-select2-allow-clear class)
    jQuery('.js-select2-allow-clear:not(.js-select2-enabled), .js-select2:not(.js-select2-enabled)').each(function(){
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

var uiHelperCoreCustomFileInput = function() {
// Bootstrap Custom File Input Filename
    // Populate custom Bootstrap file inputs with selected filename
    jQuery('[data-toggle="custom-file-input"]:not(.js-custom-file-input-enabled)').each(function(index, element) {
        var el = jQuery(element);

        // Add .js-custom-file-input-enabled class to tag it as activated
        el.addClass('js-custom-file-input-enabled').on('change', function(e) {
            var fileName = (e.target.files.length > 1) ? e.target.files.length + ' ' + (el.data('lang-files') || 'Files') : e.target.files[0].name;

            el.next('.custom-file-label').css('overflow-x', 'hidden').html(fileName);
        });
    });
};

function addHomeTeamPlayer(addHomeTeamId, playersList)
{
    var addLineUpsHomeTeamPlayer = '<div class="block block-bordered block-default block-rounded js-home-main-div"> <div class="block-header block-header-default"> <div></div><div class="block-options"><button type="button" class="btn-block-option js-add-home-team-delete text-danger" title="" data-original-title="Delete"><i class="fal fa-trash"></i></button></div></div><div class="block-content"> <div class="row"> <div class="col-xl-4"><div class="form-group"><label for="line_ups_home_number" class="required">No:</label><input type="number" min="0" class="form-control line-ups-home-number" id="line_ups_home_number'+addHomeTeamId+'" name="line_ups_home_number['+addHomeTeamId+']" value=""></div></div><div class="col-xl-4"><div class="form-group"><label for="line_ups_home_name" class="required">Name:</label><select class="js-select2 form-control line-ups-home-name" id="line_ups_home_name'+addHomeTeamId+'" name="line_ups_home_name['+addHomeTeamId+']"><option value="">Please select</option>' + playersList + '</select></div></div><div class="col-xl-4"><div class="form-group row"> <label class="col-12">Sub</label> <div class="col-12"> <div class="custom-control custom-checkbox mb-5"> <input class="custom-control-input sub-home" type="checkbox" name="sub_home['+addHomeTeamId+']" id="sub_home'+addHomeTeamId+'"><label class="custom-control-label" for="sub_home'+addHomeTeamId+'"></label> </div></div></div></div></div></div></div>';

    $('#add_home_team_player').append('<div class="col-xl-12 add-home-team" id='+addHomeTeamId+'>'+addLineUpsHomeTeamPlayer+'</div>');
    formLineUpsValidation();

}

$(document).on('click','.js-add-home-team-delete',function(){
    $(this).closest('.add-home-team').remove();
});

function addAwayTeamPlayer(addAwayTeamId, playersList)
{
    var addLineUpsAwayTeamPlayer = '<div class="block block-bordered block-default block-rounded"><div class="block-header block-header-default"> <div></div><div class="block-options"><button type="button" class="btn-block-option js-add-home-team-delete text-danger" title="" data-original-title="Delete"><i class="fal fa-trash"></i></button></div></div><div class="block-content"> <div class="row"> <div class="col-xl-4"><div class="form-group"><label for="line_ups_away_number" class="required">No:</label><input type="number" min="0" class="form-control line-ups-away-number" id="line_ups_away_number'+addAwayTeamId+'" name="line_ups_away_number['+addAwayTeamId+']" value=""><input type="hidden" name="line_ups_home_number_edit" value="1"></div></div><div class="col-xl-4"><div class="form-group"><label for="line_ups_away_name" class="required">Name:</label><select class="js-select2 form-control line-ups-away-name" id="line_ups_away_name'+addAwayTeamId+'" name="line_ups_away_name['+addAwayTeamId+']"><option value="">Please select</option>' + playersList + '</select></div></div><div class="col-xl-4"><div class="form-group row"> <label class="col-12">Sub</label> <div class="col-12"> <div class="custom-control custom-checkbox mb-5"> <input class="custom-control-input sub-away" type="checkbox" name="sub_away['+addAwayTeamId+']" id="sub_away'+addAwayTeamId+'"> <label class="custom-control-label" for="sub_away'+addAwayTeamId+'"></label> </div></div></div></div></div></div></div>';

    $('#add_away_team_player').append('<div class="col-xl-12 add-away-team" id='+addAwayTeamId+'>'+addLineUpsAwayTeamPlayer+'</div>');
    formLineUpsValidation();
}


$(document).on('click','.js-add-home-team-delete',function(){
    $(this).closest('.add-away-team').remove();
});

// match hospitality
$(document).on('click','.manage-hide-show',function(){
    if(this.checked)
    {
        $(this).closest('.tab-pane').find('.manage-hide-show-div').removeClass('d-none');
        $(this).closest('.tab-pane').find('.manage-hide-show-div').addClass('d-block');
    }else
    {
        $(this).closest('.tab-pane').find('.manage-hide-show-div').removeClass('d-block');
        $(this).closest('.tab-pane').find('.manage-hide-show-div').addClass('d-none');
    }
});

$(document).on('click','.manage-ticket-type-amount',function(){
    if(this.checked)
    {
        $(this).closest('.tab-pane').find('.manage-ticket-type-amount-container').removeClass('d-none');
        $(this).closest('.tab-pane').find('.manage-ticket-type-amount-container').addClass('d-block');
    }else
    {
        $(this).closest('.tab-pane').find('.manage-ticket-type-amount-container').removeClass('d-block');
        $(this).closest('.tab-pane').find('.manage-ticket-type-amount-container').addClass('d-none');
    }
});
// Match event
$(document).on('click', ".js-add-match-event", function() {

    if($('.add-match-event').attr('id') >= 0) {
        var addMatchEventId = parseInt($('#add_match_event').children().last().attr('id'))+1;
    }
    else {
        var addMatchEventId = 0;

    }

    addPlayerMatchEvent(addMatchEventId);
    uiHelperCoreCustomFileInput();
    uiHelperSelect2();

    var matchEventValue = [];
    var options = {};
    options.id = '';
    options.text = 'Please select';
    matchEventValue.push(options);

    $.each(Site.matchEventtype, function(value, key) {
       var options = {};
        options.id = value;
        options.text = key;
        matchEventValue.push(options);
    });

    $('.select2-match-event-type').select2({
        data:matchEventValue,
        placeholder: "Please select",
        allowClear: true,
    });

});




function addPlayerMatchEvent(addMatchEventId)
{
    var addMatchEvents = '<div class="block block-bordered block-default"><div class="block-header block-header-default"><div></div><div class="block-options"><button type="button" class="btn-block-option js-match-event-delete text-danger" title="" data-original-title="Delete"><i class="fal fa-trash"></i></button></div></div><div class="block-content"><div class="row"><div class="col-xl-4"><div class="form-group row"> <label class="col-12">Team</label><div class="col-12"><div class="custom-control custom-radio custom-control-inline mb-5"><input class="custom-control-input player-team-radio" type="radio" name="match_event['+addMatchEventId+']" id="match_event_home'+addMatchEventId+'" checked="" value="home"><label class="custom-control-label" for="match_event_home'+addMatchEventId+'">Home</label></div><div class="custom-control custom-radio custom-control-inline mb-5"><input class="custom-control-input player-team-radio" type="radio" name="match_event['+addMatchEventId+']" id="match_event_away'+addMatchEventId+'" value="away"><label class="custom-control-label" for="match_event_away'+addMatchEventId+'">Away</label></div></div></div></div><div class="col-xl-8"><div class="row"><div class="col-xl-6"><div class="form-group"><label for="match_event_player'+addMatchEventId+'" class="required">Player:</label><select class="js-select2 form-control match-event-player" id="match_event_player'+addMatchEventId+'" name="match_event_player['+addMatchEventId+']" style="width:100%"><option value="">Please select</option></select></div></div><div class="col-xl-6"><div class="form-group"><label for="match_events_time'+addMatchEventId+'" class="required">Time (mins):</label><input type="number" min="0" class="form-control match_events_time" id="match_events_time'+addMatchEventId+'" name="match_events_time['+addMatchEventId+']" value=""></div></div><div class="col-xl-12"><div class="row type-of-event-main-div"><div class="col-xl-6"><div class="form-group" data-select2-id="21"><label for="match_type_of_event'+addMatchEventId+'" class="required">Type of event:</label><select class="form-control select2-match-event-type match-type-of-event js-select2" id="match_type_of_event'+addMatchEventId+'" name="match_type_of_event['+addMatchEventId+']" style="width:100%"><option value="">Please select</option></select></div></div></div></div><div class="col-xl-6"><div class="form-group"><label>Action replay video:</label><div class="custom-file"><input type="file" class="custom-file-input js-custom-file-input-enabled action_replay_video" id="action_replay_video'+addMatchEventId+'" name="action_replay_video['+addMatchEventId+']" data-toggle="custom-file-input" accept="video/*"><label class="custom-file-label" for="action_replay_video'+addMatchEventId+'">Choose file</label></div></div></div></div></div></div></div>';

    $('#add_match_event').append('<div class="col-xl-12 add-match-event" id='+addMatchEventId+'>'+addMatchEvents+'</div>');
    formLineUpsValidation();
}

$(document).on('change','.match-type-of-event',function(){
    manageSubstitution($(this));
});

function manageSubstitution(me){

    var addMatchEventId = me.closest('.add-match-event').attr('id');
    var type = (me.val()).trim();

    me.closest('.add-match-event').find('.substitution-player').remove();
    if (type && type == 'substitution')
    {
        me.closest('.add-match-event').find('.type-of-event-main-div').append('<div class="col-xl-6 substitution-player"><div class-match-event="form-group" data-select2-id="21"><label for="match_type_of_event'+addMatchEventId+'">Subbed for:</label><select class="js-select2 form-control select2-substitution-player" id="substitution-player'+addMatchEventId+'" name="substitution_player['+addMatchEventId+']" style="width:100%"><option>Please select</option></select></div>');

        var returnData = [];
        var options = {};
        options.id = '';
        options.text = 'Please select';
        returnData.push(options);
        me.closest('.row').find('.select2-substitution-player').select2({
            data:returnData,
            placeholder: "Please select",
            allowClear: true,
        });

        me.closest('.add-match-event').find('.select2-substitution-player').rules('add', {
            required: true,
            messages: {
                required: "This field is required."
            }
        });
    }
}

$(document).on('click','.js-match-event-delete',function(){
    $(this).closest('.add-match-event').remove();
});

function readLogoURL(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            $('.js-manage-unavailable-seats-width').removeClass('col-12').addClass('col-9');
            if(input.id=='unavailable_seats'){
                $('#unavailable_seats_preview_container').html('<a download href="' + e.target.result + '">Download</a>');
            }
            $('#unavailable_seats_preview_container').removeClass('d-md-none');
			$('#unavailable_seats_preview_remove').removeClass('d-md-none');
        }
        reader.readAsDataURL(input.files[0]);
    }
}


$("#unavailable_seats_preview_remove").on('click', function () {
	$('#unavailable_seats').val('');
	let lbl = document.getElementById('lbl_unavailable_seats');
	lbl.innerText = "Choose file";
	$('#unavailable_seats_preview_container').addClass('d-md-none');
	$('.js-manage-unavailable-seats-width').removeClass('col-9').addClass('col-12');
	//$('#logo_preview').attr('src', '');
	$('#unavailable_seats_preview_remove').addClass('d-md-none');
});

$(document).on('change','#unavailable_seats',function(){
    readLogoURL(this);
});

$(document).on('change','#hospitality_unavailable_seats',function(){
    if (this.files && this.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            $('.js-manage-hospitality-unavailable-seats-width').removeClass('col-12').addClass('col-9');
            $('#unavailable_hospitality_seats_preview_container').html('<a download href="' + e.target.result + '">Download</a>');
            $('#unavailable_hospitality_seats_preview_container').removeClass('d-md-none');
			$('#unavailable_hospitality_seats_preview_remove').removeClass('d-md-none');
        }
        reader.readAsDataURL(this.files[0]);
    }
});

$("#unavailable_hospitality_seats_preview_remove").on('click', function () {
	$('#hospitality_unavailable_seats').val('');
	let lbl = document.getElementById('lbl_hospitality_unavailable_seats');
	lbl.innerText = "Choose file";
	$('#unavailable_hospitality_seats_preview_container').addClass('d-md-none');
	$('.js-manage-hospitality-unavailable-seats-width').removeClass('col-9').addClass('col-12');
	//$('#logo_preview').attr('src', '');
	$('#unavailable_hospitality_seats_preview_remove').addClass('d-md-none');
});

$(document).on('change','.uploadimage',function(){
    var fileName = this.files[0].name;
    $(this).next('.custom-file-label').css('overflow-x', 'hidden').html(fileName);
    var ext = $(this).val().split('.').pop().toLowerCase();
    if($.inArray(ext, ['png','jpg','jpeg']) != -1) {
        readSponsereURL(this);
    }
});
function readSponsereURL(input) {
	var data =input.name
	if (input.files && input.files[0]) {
       var reader = new FileReader();
       reader.onload = function(e) {
        $('img[name="'+data.replace("[sponsor]", "[preview]")+'"]').attr('src', e.target.result);
        $('div[name="'+data.replace("[sponsor]", "[preview_container]")+'"]').removeClass('d-md-none');
       }
       reader.readAsDataURL(input.files[0]);
   }
}

$(document).on('click', ".logo-delete", function() {
	$(this).closest('.js-manage-sponsor-logo-width').remove();
});

$(document).on('change','#home',function(){
    var optionText = $('#home option[value="' + Site.clubId + '"]').text();
    if ($('#home').select2('val') == Site.clubId) {
        $('#is_enable_ticket').removeAttr('disabled');
        $('#is_enable_hospitality').removeAttr('disabled');
        $('#away').select2('val','');
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

$(document).on('change','#away',function(){
    var optionText = $('#away option[value="' + Site.clubId + '"]').text();
    if ($('#away').select2('val') == Site.clubId) {
        $('#is_enable_ticket').attr('disabled','disabled');
        $('#is_enable_hospitality').attr('disabled','disabled');
        $('#home').select2('val','');
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

var addPlayerForm =  $('#add_player_form');

addPlayerForm.validate({
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
        'player_name': {
           required: true
        }
    },

});

$("#available_blocks").select2();
$("#pricing_bands").select2();
$("#hospitality_suites").select2();
