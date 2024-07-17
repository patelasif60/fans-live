var vueMatch;

jQuery(function () {
	$(document).on('change', '#pagination_length', function () {
		$.cookie('pagination_length', $(this).val());
		vueMatch.matchListData(1, vueMatch.sortby, vueMatch.sorttype, vueMatch.searchdata);
	});
	getMatchData();
	initPaginationRecord();
});

function getMatchData() {
	vueMatch = new Vue({
		el: "#matches",
		components: {
			'pagination': paginationComponent,
		},
		data: {
			matchData: [],
			matchCount: 0,
			sortKey: '',
			sortOrder: 1,
			sortby: 'matches.id',
			sorttype: 'desc',
			searchdata: '',
			clubId: window.clubId
		},
		created: function () {
			this.matchListData();
		},
		filters: {
			formatDate: function (value) {
				if (value) {
					var utcFormat = moment.tz(String(value), "UTC");
                    return utcFormat.clone().tz(Site.clubTimezone).format(Site.dateTimeCmsFormat);
				}
				return value;
			}
		},
		methods: {
			matchListData: function (page, sortby, sorttype, searchdata) {
				if (typeof (sortby) == "undefined") {
					sortby = this.sortby;
					sorttype = this.sorttype;
				} else {
					this.sortby = sortby;
					this.sorttype = sorttype;
				}

				var data = "sortby=" + sortby + "&sorttype=" + sorttype;

				if (typeof (searchdata) != "undefined") {
					data += searchdata;
				}

				data += setPaginationAmount();

				if (typeof (page) == "undefined") {
					ajaxCall("getMatchData", data, 'POST', 'json', MatchDataSuccess);
				} else {
					ajaxCall("getMatchData?page=" + page, data, 'POST', 'json', MatchDataSuccess);
				}
			},
			searchMatchData: function () {
				var opposition = $("#opposition").val();
				var competition = $("#competition").val();
				var from_date = $("#from_date input").val();
				var to_date = $("#to_date input").val();
				var searchdata = "&opposition=" + opposition + "&competition=" + competition + "&from_date=" + from_date + "&to_date=" + to_date;

				if ($('#match_pagination').data("twbs-pagination")) {
					$('#match_pagination').twbsPagination('destroy');
				}
				vueMatch.searchdata = searchdata;
				this.matchListData(1, this.sortby, this.sorttype, searchdata);
			},
			sortByKey: function (key) {
				this.sortOrder = this.sortOrder * -1;
				this.sortby = key;
				this.sortKey = key;
				var stype = this.sortOrder == 1 ? 'asc' : 'desc';
				this.sorttype = stype;
				this.matchListData(this.currPage, key, stype, this.searchdata);
			},
			reloadData: function () {
				clearFormData('frm_search_data');
				setDefaultData(vueMatch);
				this.matchListData();
			},
			clearForm: function (formid) {
				this.reloadData();
			}
		}
	});
}

function MatchDataSuccess(matchData, status, xhr) {
	vueMatch.matchData = matchData['data'];
	vueMatch.matchCount = matchData['data'].length;

	if (matchData['data'].length > 0 && $.cookie('pagination_length') != '-1') {
		vueMatch.currPage = matchData.current_page;
		var current_page = matchData.current_page;

		if (current_page == 1) {
			$('#match_pagination').off("page").removeData("twbs-pagination").empty();
		}

		var per_page = matchData.per_page;

		var startIndex = 0;
		if (current_page > 1) {
			startIndex = (current_page - 1) * parseInt(per_page);
		}
		vueMatch.page_index = startIndex + 1;
		setTimeout(function () {
			$('#match_pagination').twbsPagination({
				totalPages: matchData.last_page,
				visiblePages: 5,
				initiateStartPageClick: false,
				onPageClick: function (event, page) {
					vueMatch.matchListData(page, vueMatch.sortby, vueMatch.sorttype, vueMatch.searchdata);
				}
			});

			setPaginationRecords(startIndex + 1, startIndex + parseInt($.cookie('pagination_length')), matchData.total);
			$("#pagination_length").select2({minimumResultsForSearch: Infinity});
			$('#pagination_length').val($.cookie('pagination_length')).trigger('change.select2');
		}, 20);
	} else {
		vueMatch.page_index = 1;
		setTimeout(function () {
			setPaginationRecords(1, matchData.total, matchData.total);
			$("#pagination_length").select2({minimumResultsForSearch: Infinity});
			if ($('#match_pagination').data("twbs-pagination")) {
				$('#match_pagination').twbsPagination('destroy');
			}

			$('#pagination_length').val($.cookie('pagination_length')).trigger('change.select2');
		}, 20);
	}
}

function setDefaultData(vueId) {
	vueId.currPage = 1;
	vueId.sortby = 'matches.id';
	vueId.sorttype = 'desc';
	vueId.searchdata = '';
}

var MatchIndex = function () {
	var uiHelperDatePicker = function () {
		$(".js-datepicker").datetimepicker({
			ignoreReadonly: true,
			format: Site.dateCmsFormat,
			timeZone: Site.clubTimezone
		});

		$("body").on("click", ".datetimepickerClear", function (e) {
			e.preventDefault();
			var $datetimepicker = $(this).closest('.input-group.date');
			$datetimepicker.datetimepicker('clear');
		});
	};

	return {
		init: function () {
			uiHelperDatePicker();
		}
	};
}();

var initFormValidations = function () {
	var matchForm = $('.match-search-form');
	var validate = matchForm.validate({
		ignore: [],
		errorClass: 'invalid-feedback animated fadeInDown',
		errorElement: 'div',
		errorPlacement: function (error, e) {
			$(e).parents('.form-group').append(error);
			$("#to_date-error").addClass('col-12');
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
		submitHandler: function (form) {
			vueMatch.searchMatchData();
			return false;
		},
		rules: {
			'to_date': {
				greaterThanDate: "#fromdate"
			},
		},
	});
};

//Initialize when page loads
jQuery(function () {
	MatchIndex.init();
	$("#searchMatch").on("click", function () {
		initFormValidations();
	});
});
