var vueEvent;

jQuery(function () {
	$(document).on('change', '#pagination_length', function () {
		$.cookie('pagination_length', $(this).val());
		vueEvent.eventListData(1, vueEvent.sortby, vueEvent.sorttype, vueEvent.searchdata);
	});
	getEventData();
	initPaginationRecord();
});

function getEventData() {
	vueEvent = new Vue({
		el: "#event_list",
		components: {
			'pagination': paginationComponent,
		},
		data: {
			eventData: [],
			eventCount: 0,
			sortKey: '',
			sortOrder: 1,
			sortby: 'events.id',
			sorttype: 'desc',
			searchdata: ''
		},
		created: function () {
			this.eventListData();
		},
		filters: {
			formatDate: function (value) {
				if (value) {
					var utcFormat = moment.tz(String(value), "UTC");
                    return utcFormat.clone().tz(Site.clubTimezone).format(Site.dateTimeCmsFormat);
				}
			},
			formattext: function(str, length, clamp) {
	            if (str) {
	            	str = str.toString();
	            	str.replace(/<[^>]*>/g, '');
                    clamp = clamp || '...';
                    var node = document.createElement('div');
                    node.innerHTML = str;
                    var content = node.textContent;
                    return content.length > length ? content.slice(0, length) + clamp : content;
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
			eventListData: function (page, sortby, sorttype, searchdata) {
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
					ajaxCall("getEventData", data, 'POST', 'json', EventDataSuccess);
				} else {
					ajaxCall("getEventData?page=" + page, data, 'POST', 'json', EventDataSuccess);
				}
			},
			searchEventData: function () {
				var title = $("#title").val();
				var from_date = $("#from_date input").val();
				var to_date = $("#to_date input").val();
				var searchdata = "&title=" + title + "&from_date=" + from_date + "&to_date=" + to_date;
				if ($('#event_pagination').data("twbs-pagination")) {
					$('#event_pagination').twbsPagination('destroy');
				}
				vueEvent.searchdata = searchdata;
				this.eventListData(1, this.sortby, this.sorttype, searchdata);
			},
			sortByKey: function (key) {
				this.sortOrder = this.sortOrder * -1;
				this.sortby = key;
				this.sortKey = key;
				var stype = this.sortOrder == 1 ? 'asc' : 'desc';
				this.sorttype = stype;
				this.eventListData(this.currPage, key, stype, this.searchdata);
			},
			reloadData: function () {
				clearFormData('frm_search_data');
				setDefaultData(vueEvent);
				this.eventListData();
			},
			clearForm: function (formid) {
				this.reloadData();
			},
			deleteData: function(id) {
                swal({
                    title: 'Are you sure?',
                    text: 'This information will be permanently deleted!',
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, delete it!',
                    html: false,
                }).then(
                    function (result) {
                    	if(result.value)
                        {
	                        var deleteUrl = 'event/'+id;
	                        $.ajax({
	                            type: 'DELETE',
	                            processData: false,
	                            contentType: false,
	                            url: deleteUrl,
	                            success: function(response) {
	                                if (response.status == 'error') {
	                                    swal({
	                                        title: "Event error",
	                                        html: response.message,
	                                        type: "error"});
	                                } else {
	                                    swal({
	                                        title: "Event success",
	                                        html: response.message,
	                                        type: "success"});
	                                }
	                                vueEvent.eventListData(this.currPage, this.sortKey, this.sorttype, this.searchdata);
	                            }
	                        });
	                    }
                    }
                );
            }
		}
	});
}

function EventDataSuccess(eventData, status, xhr) {
	console.log('eventData', eventData);
	vueEvent.eventData = eventData['data'];
	vueEvent.eventCount = eventData['data'].length;

	if (eventData['data'].length > 0 && $.cookie('pagination_length') != '-1') {
		vueEvent.currPage = eventData.current_page;
		var current_page = eventData.current_page;

		if (current_page == 1) {
			$('#event_pagination').off("page").removeData("twbs-pagination").empty();
		}

		var per_page = eventData.per_page;

		var startIndex = 0;
		if (current_page > 1) {
			startIndex = (current_page - 1) * parseInt(per_page);
		}
		vueEvent.page_index = startIndex + 1;
		setTimeout(function () {
			$('#event_pagination').twbsPagination({
				totalPages: eventData.last_page,
				visiblePages: 5,
				initiateStartPageClick: false,
				onPageClick: function (event, page) {
					vueEvent.eventListData(page, vueEvent.sortby, vueEvent.sorttype, vueEvent.searchdata);
				}
			});

			setPaginationRecords(startIndex + 1, startIndex + parseInt($.cookie('pagination_length')), eventData.total);
			$("#pagination_length").select2({minimumResultsForSearch: Infinity});
			$('#pagination_length').val($.cookie('pagination_length')).trigger('change.select2');
		}, 20);
	} else {
		vueEvent.page_index = 1;
		setTimeout(function () {
			setPaginationRecords(1, eventData.total, eventData.total);
			$("#pagination_length").select2({minimumResultsForSearch: Infinity});
			if ($('#event_pagination').data("twbs-pagination")) {
				$('#event_pagination').twbsPagination('destroy');
			}

			$('#pagination_length').val($.cookie('pagination_length')).trigger('change.select2');
		}, 20);
	}
}

function setDefaultData(vueId) {
	vueId.currPage = 1;
	vueId.sortby = 'events.id';
	vueId.sorttype = 'desc';
	vueId.searchdata = '';
}

var FeedItemIndex = function () {
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
	var eventForm = $('.event-search-form');
	var validate = eventForm.validate({
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
			vueEvent.searchEventData();
			return false;
		},
		rules: {
			'to_date': {
				greaterThanDate: "#fromdate"
			},
		},
	});
};

// Initialize when page loads
jQuery(function () {
	FeedItemIndex.init();
	$("#searchEvent").on("click", function () {
		initFormValidations();
	});

});
