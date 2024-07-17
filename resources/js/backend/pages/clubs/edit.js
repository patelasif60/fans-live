import _ from 'lodash';
import select2 from '../../components/select2.vue';

var vueClubCompetitions;

var ClubEdit = function () {
	var initFormValidations = function () {
		var competitionForm = $('.edit-club-form');

		competitionForm.validate({
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
					required: function(text) {
						return $('#logo_file_name').val() ? false : true;
					},
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
							return $("#currency_EUR").is(":checked");
						}
					},
					number: {
						depends: function (element) {
							return $("#currency_EUR").is(":checked");
						}
					},
				},
				'iban': {
					required: {
						depends: function (element) {
							return $("#currency_EUR").is(":checked");
						}
					},
					number: {
						depends: function (element) {
							return $("#currency_EUR").is(":checked");
						}
					},
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
	ClubEdit.init();
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
			clubCompetitions: Site.clubCompetitions,
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
				swal({
		            title: 'Are you sure?',
		            text: "This information will be permanently deleted!",
		            type: 'warning',
		            showCancelButton: true,
		            confirmButtonColor: '#3085d6',
		            cancelButtonColor: '#d33',
		            confirmButtonText: 'Yes, delete it!'
		        }).then((result)=> {	                
	               		if(result.value)
	               		{
	                 		let vm = this;
							let index = _.findIndex(this.clubCompetitions, function (o) {
							return o.id == competitionId;
							});
							this.clubCompetitions.splice(index, 1);
	                	}
	            });
            }
		}
	});
}

function readLogoURL(input) {
	if (input.files && input.files[0]) {
		var reader = new FileReader();
		reader.onload = function (e) {
			$('.js-manage-logo-width').removeClass('col-12').addClass('col-8');
			if ($('#club-logo').hasClass('d-md-none')) {
				$('#club-logo').removeClass('d-md-none');
			}
			$('#logo_preview_container').html('<div class="logo_preview_container ml-3"><img id="logo_preview" class="" src="' + e.target.result + '" /></div>');
		}
		reader.readAsDataURL(input.files[0]);
	}
}

$("#logo").change(function () {
	readLogoURL(this);
});


$(document).ready(function () {
	if (Site.clubCurrency == 'GBP')
		$(".radio_EUR").hide();
	else {
		$(".radio_EUR").show();
	}
	$("input[name$='currency']").click(function () {
		var radio = $(this).val();
		if (radio == 'GBP') {
			$(".radio_EUR").hide();
		} else {
			$(".radio_EUR").show();
		}
	});
});
