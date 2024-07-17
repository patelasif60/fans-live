import _ from 'lodash';
import select2 from '../../components/select2.vue';

var vueCompetitionClubs;

var CategoryCreate = function() {

    var initFormValidations = function () {
        var competitionForm = $('.create-competition-form');

        competitionForm.validate({
            ignore: [],
            errorClass: 'invalid-feedback animated fadeInDown',
            errorElement: 'div',
            errorPlacement: function(error, e)
            {
				if (e.attr("name") == "logo") {
					$(e).parents('.form-group .logo-input').append(error);
				} else {
					$(e).parents('.form-group').append(error);
				}
            },
            highlight: function(e) {
                if ($(e).attr("name") == "logo") {
                    $(e).removeClass('is-invalid').addClass('is-invalid');
                }
                $(e).closest('.form-group').removeClass('is-invalid').addClass('is-invalid');
            },
            unhighlight: function (e) {
                if ($(e).attr("name") == "logo") {
                    $(e).removeClass('is-invalid');
                }
                $(e).closest('.form-group').removeClass('is-invalid').removeClass('is-invalid');
            },
            success: function(e) {
                $(e).closest('.form-group').removeClass('is-invalid');
                $(e).remove();
            },
            rules: {
                'name' : {
                    required : true,
                },
				'logo' : {
					required : true,
					extension: "jpg|jpeg|png"
				},
                'external_app_id': {
                    required: true,
                },
            },
            submitHandler: function(form) {
                $('#competition_clubs').val(JSON.stringify(_.map(_.cloneDeep(vueCompetitionClubs.competitionClubs), 'id')));
                return true;
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
    CategoryCreate.init();
    initializeClubs();
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

function initializeClubs() {
    vueCompetitionClubs = new Vue({
        el: ".js-competition-clubs",
        components: {
            select2,
        },
        data: {
            allClubs: Site.clubs,
            selectedClub: '',
            competitionClubs: [],
        },
        created: function() {
        },
        computed: {
            clubOptions() {
                let clubOptions = [{id: '', text: 'Select club'}];
                let competitionClubs = _.map(_.cloneDeep(this.competitionClubs), 'id');
                _.forEach(this.allClubs, function(o) {
                    if(_.indexOf(competitionClubs, o.id) === -1) {
                        clubOptions.push({id: o.id, text: o.name});
                    }
                });
                return clubOptions;
            },
        },
        methods: {
            addClub() {
                if(this.selectedClub === '') {
                    // Display an error toast, with a title
                    toastr.error('Please select club.', 'Error!');
                    return false;
                }
                let vm = this;
                let club = _.find(vm.allClubs, function(o) { return o.id == vm.selectedClub; });
                this.competitionClubs.push(club);
                this.selectedClub = '';
            },
            removeClub(clubId) {
                let vm = this;
                let index = _.findIndex(this.competitionClubs, function(o) { return o.id == clubId; });
                this.competitionClubs.splice(index, 1);
            }
        }
    });
}

$("#logo").change(function() {
    readLogoURL(this);
});
