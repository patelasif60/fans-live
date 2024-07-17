import _ from 'lodash';
import select2 from '../../components/select2.vue';

var vueClubCompetitions;

var ClubCreate = function () {
	var initFormValidations = function () {
		var userForm = $('.create-club-form');

		userForm.validate({
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
				'name': {
					required: true,
				},
				'category': {
					required: true,
				},
				'external_api_team_id': {
					required: true,
				},
				'logo': {
					required: true,
					accept: "image/png",
					extension: "png",
					icondimension: [150, 150,'logo'],
				},
				'bank_name': {
					required: true,
				},
				'account_name': {
					required: true,
				},
				'sort_code': {
					required: true,
				},
				'account_number': {
					required: true,
				},
				'time_zone':{
					required: true,
				},
				'bic': {
					required: {
						depends: function (element) {
							return $(element).closest('form').find("#currency_EUR").prop("checked");
						}
					},
					number: true,
				},
				'iban': {
					required: {
						depends: function (element) {
							return $(element).closest('form').find("#currency_EUR").prop("checked");
						}
					},
					number: true,
				},
			},
			messages: {
				'logo': {
					accept: 'Please upload the correct file format.',
					icondimension: 'Please upload the correct file size.'
				}
			},
			submitHandler: function (form) {
				$('#club_competitions').val(JSON.stringify(_.map(_.cloneDeep(vueClubCompetitions.clubCompetitions), 'id')));
				return true;
			}
		});
	};

	var uiHelperColorpicker = function () {
		// Get each colorpicker element (with .js-colorpicker class)
		jQuery('.js-colorpicker:not(.js-colorpicker-enabled)').each(function () {
			var el = jQuery(this);

			// Add .js-enabled class to tag it as activated
			el.addClass('js-colorpicker-enabled');

			// Init colorpicker
			el.colorpicker();
		});
	};

	return {
		init: function () {
			initFormValidations();
			uiHelperColorpicker();
		}
	};
}();

// Initialize when page loads
jQuery(function () {
	ClubCreate.init();
	initializeCompetitions();
});

function initializeCompetitions() {
	vueClubCompetitions = new Vue({
		el: ".js-club-competitions",
		components: {
			select2,
		},
		data: {
			allCompetitions: Site.competitions,
			selectedCompetition: '',
			clubCompetitions: [],
		},
		computed: {
			competitionOptions() {
				let competitionOptions = [{id: '', text: 'Select competition'}];
				let clubCompetitions = _.map(_.cloneDeep(this.clubCompetitions), 'id');
				_.forEach(this.allCompetitions, function (o) {
					if (_.indexOf(clubCompetitions, o.id) === -1) {
						competitionOptions.push({id: o.id, text: o.name});
					}
				});
				return competitionOptions;
			},
		},
		methods: {
			addCompetition() {
				if (this.selectedCompetition === '') {
					// Display an error toast, with a title
					toastr.error('Please select competition.', 'Error!');
					return false;
				}
				let vm = this;
				let club = _.find(vm.allCompetitions, function (o) {
					return o.id == vm.selectedCompetition;
				});
				this.clubCompetitions.push(club);
				this.selectedCompetition = '';
			},
			removeCompetition(competitionId) {
				let vm = this;
				let index = _.findIndex(this.clubCompetitions, function (o) {
					return o.id == competitionId;
				});
				this.clubCompetitions.splice(index, 1);
			}
		}
	});
}

function readLogoURL(input) {
	if (input.files && input.files[0]) {
		var reader = new FileReader();
		reader.onload = function (e) {
			$('.js-manage-logo-width').removeClass('col-12').addClass('col-9');
			$('#logo_preview').attr('src', e.target.result);
			$('#logo_preview_container').removeClass('d-md-none');
		}
		reader.readAsDataURL(input.files[0]);
	}
}

$("#logo").change(function () {
	readLogoURL(this);
});

$(document).ready(function () {
	$(".radio_EUR").show();
	$("input[name$='currency']").click(function () {
		var radio = $(this).val();
		if (radio == 'GBP') {
			$(".radio_EUR").hide();
		} else {
			$(".radio_EUR").show();
		}
	});
});
