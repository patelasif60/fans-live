var vueVideos;

jQuery(function () {
	$(document).on('change', '#pagination_length', function () {
		$.cookie('pagination_length', $(this).val());
		vueVideos.videosListData(1, vueVideos.sortby, vueVideos.sorttype, vueVideos.searchdata);
	});
	getNewsData();
	initPaginationRecord();
});

function getNewsData() {
	vueVideos = new Vue({
		el: "#video_list",
		components: {
			'pagination': paginationComponent,
		},
		data: {
			videosData: [],
			videoCount: 0,
			sortKey: '',
			sortOrder: 1,
			sortby: 'videos.id',
			sorttype: 'desc',
			searchdata: ''
		},
		created: function () {
			this.videosListData();
		},
		methods: {
			videosListData: function (page, sortby, sorttype, searchdata) {
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
					ajaxCall("getVideosData", data, 'POST', 'json', NewsDataSuccess);
				} else {
					ajaxCall("getVideosData?page=" + page, data, 'POST', 'json', NewsDataSuccess);
				}
			},
			searchVideosData: function () {
				var title = $("#title").val();
                var from_date= $("#from_date input").val();
                var to_date = $("#to_date input").val();

                var searchdata = "&title="+ title + "&from_date=" + from_date + "&to_date=" + to_date;
                if($('#video_pagination').data("twbs-pagination")){
                   $('#video_pagination').twbsPagination('destroy');
                }
                vueVideos.searchdata = searchdata;
				this.videosListData(1, this.sortby, this.sorttype, searchdata);
			},
			sortByKey: function (key) {
				this.sortOrder = this.sortOrder * -1;
				this.sortby = key;
				this.sortKey = key;
				var stype = this.sortOrder == 1 ? 'asc' : 'desc';
				this.sorttype = stype;
				this.videosListData(this.currPage, key, stype, this.searchdata);
			},
			reloadData: function () {
				clearFormData('frm_search_data');
				setDefaultData(vueVideos);
				this.videosListData();
			},
			clearForm: function (formid) {
				this.reloadData();
			}
		}
	});
}

function NewsDataSuccess(videoData, status, xhr) {
	vueVideos.videosData = videoData['data'];
	vueVideos.videoCount = videoData['data'].length;

	if (videoData['data'].length > 0 && $.cookie('pagination_length') != '-1') {
		vueVideos.currPage = videoData.current_page;
		var current_page = videoData.current_page;

		if (current_page == 1) {
			$('#video_pagination').off("page").removeData("twbs-pagination").empty();
		}

		var per_page = videoData.per_page;

		var startIndex = 0;
		if (current_page > 1) {
			startIndex = (current_page - 1) * parseInt(per_page);
		}
		vueVideos.page_index = startIndex + 1;
		setTimeout(function () {
			$('#video_pagination').twbsPagination({
				totalPages: videoData.last_page,
				visiblePages: 5,
				initiateStartPageClick: false,
				onPageClick: function (event, page) {
					vueVideos.videosListData(page, vueVideos.sortby, vueVideos.sorttype, vueVideos.searchdata);
				}
			});

			setPaginationRecords(startIndex + 1, startIndex + parseInt($.cookie('pagination_length')), videoData.total);
			$("#pagination_length").select2({minimumResultsForSearch: Infinity});
			$('#pagination_length').val($.cookie('pagination_length')).trigger('change.select2');
		}, 20);
	} else {
		vueVideos.page_index = 1;
		setTimeout(function () {
			setPaginationRecords(1, videoData.total, videoData.total);
			$("#pagination_length").select2({minimumResultsForSearch: Infinity});
			if ($('#video_pagination').data("twbs-pagination")) {
				$('#video_pagination').twbsPagination('destroy');
			}

			$('#pagination_length').val($.cookie('pagination_length')).trigger('change.select2');
		}, 20);
	}
}

function setDefaultData(vueId) {
	vueId.currPage = 1;
	vueId.sortby = 'videos.id';
	vueId.sorttype = 'desc';
	vueId.searchdata = '';
}

var initFormValidations = function () {
	var newsForm = $('.videos-search-form');
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
			vueVideos.searchVideosData();
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
	$("#searchVideo").on("click", function () {
		initFormValidations();
	});
});
