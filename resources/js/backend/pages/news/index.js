var vueNews;

jQuery(function () {
	$(document).on('change', '#pagination_length', function () {
		$.cookie('pagination_length', $(this).val());
		vueNews.newsListData(1, vueNews.sortby, vueNews.sorttype, vueNews.searchdata);
	});
	getNewsData();
	initPaginationRecord();
});

function getNewsData() {
	vueNews = new Vue({
		el: "#news_list",
		components: {
			'pagination': paginationComponent,
		},
		data: {
			newsData: [],
			newsCount: 0,
			sortKey: '',
			sortOrder: 1,
			sortby: 'news.id',
			sorttype: 'desc',
			searchdata: ''
		},
		created: function () {
			this.newsListData();
		},
		filters: {
			formatDate: function (value) {
				if (value) {
					var utcFormat = moment.tz(String(value), "UTC");
                    return utcFormat.clone().tz(Site.clubTimezone).format(Site.dateTimeCmsFormat);
				}
			},
			excerpt: function (text, length, clamp) {
				clamp = clamp || '...';
				var node = document.createElement('div');
				node.innerHTML = text;
				var content = node.textContent;
				return content.length > length ? content.slice(0, length) + clamp : content;
			}
		},
		methods: {
			newsListData: function (page, sortby, sorttype, searchdata) {
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
					ajaxCall("getNewsData", data, 'POST', 'json', NewsDataSuccess);
				} else {
					ajaxCall("getNewsData?page=" + page, data, 'POST', 'json', NewsDataSuccess);
				}
			},
			searchNewsData: function () {
				var name = $("#name").val();
				var from_date = $("#from_date input").val();
				var to_date = $("#to_date input").val();
				var searchdata = "&name=" + name + "&from_date=" + from_date + "&to_date=" + to_date;
				if ($('#news_pagination').data("twbs-pagination")) {
					$('#news_pagination').twbsPagination('destroy');
				}
				vueNews.searchdata = searchdata;
				this.newsListData(1, this.sortby, this.sorttype, searchdata);
			},
			sortByKey: function (key) {
				this.sortOrder = this.sortOrder * -1;
				this.sortby = key;
				this.sortKey = key;
				var stype = this.sortOrder == 1 ? 'asc' : 'desc';
				this.sorttype = stype;
				this.newsListData(this.currPage, key, stype, this.searchdata);
			},
			reloadData: function () {
				clearFormData('frm_search_data');
				setDefaultData(vueNews);
				this.newsListData();
			},
			clearForm: function (formid) {
				this.reloadData();
			}
		}
	});
}

function NewsDataSuccess(newsData, status, xhr) {
	vueNews.newsData = newsData['data'];
	vueNews.newsCount = newsData['data'].length;

	if (newsData['data'].length > 0 && $.cookie('pagination_length') != '-1') {
		vueNews.currPage = newsData.current_page;
		var current_page = newsData.current_page;

		if (current_page == 1) {
			$('#news_pagination').off("page").removeData("twbs-pagination").empty();
		}

		var per_page = newsData.per_page;

		var startIndex = 0;
		if (current_page > 1) {
			startIndex = (current_page - 1) * parseInt(per_page);
		}
		vueNews.page_index = startIndex + 1;
		setTimeout(function () {
			$('#news_pagination').twbsPagination({
				totalPages: newsData.last_page,
				visiblePages: 5,
				initiateStartPageClick: false,
				onPageClick: function (event, page) {
					vueNews.newsListData(page, vueNews.sortby, vueNews.sorttype, vueNews.searchdata);
				}
			});

			setPaginationRecords(startIndex + 1, startIndex + parseInt($.cookie('pagination_length')), newsData.total);
			$("#pagination_length").select2({minimumResultsForSearch: Infinity});
			$('#pagination_length').val($.cookie('pagination_length')).trigger('change.select2');
		}, 20);
	} else {
		vueNews.page_index = 1;
		setTimeout(function () {
			setPaginationRecords(1, newsData.total, newsData.total);
			$("#pagination_length").select2({minimumResultsForSearch: Infinity});
			if ($('#news_pagination').data("twbs-pagination")) {
				$('#news_pagination').twbsPagination('destroy');
			}

			$('#pagination_length').val($.cookie('pagination_length')).trigger('change.select2');
		}, 20);
	}
}

function setDefaultData(vueId) {
	vueId.currPage = 1;
	vueId.sortby = 'news.id';
	vueId.sorttype = 'desc';
	vueId.searchdata = '';
}

var initFormValidations = function () {
	var newsForm = $('.news-search-form');
	var validate = newsForm.validate({
		ignore: [],
		errorClass: 'invalid-feedback animated fadeInDown',
		errorElement: 'div',
		errorPlacement: function (error, e) {
			$(e).parents('.js-datepicker').append(error);
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
			vueNews.searchNewsData();
			return false;
		},
		rules: {
			'to_date': {
				greaterThanDate: "#fromdate"
			},
		},
	});
};

var NewsIndex = function () {
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

// Initialize when page loads
jQuery(function () {
	NewsIndex.init();
	$("#searchNews").on("click", function () {
		initFormValidations();
	});
});
