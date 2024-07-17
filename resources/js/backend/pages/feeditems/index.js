var vueFeedItem;

jQuery(function() {
    $(document).on('change', '#pagination_length', function(){
        $.cookie('pagination_length', $(this).val());
        vueFeedItem.feedItemListData(1, vueFeedItem.sortby, vueFeedItem.sorttype, vueFeedItem.searchdata);
    });
    getFeedItemData();
    initPaginationRecord();
});

function getFeedItemData() {
    vueFeedItem = new Vue({
        el: "#feed_item_list",
        components: {
            'pagination': paginationComponent,
        },
        data: {
            feedItemData: [],
            feedItemCount: 0,
            sortKey: '',
            sortOrder: 1,
            sortby: 'feed_items.id',
            sorttype: 'desc',
            searchdata: ''
        },
        created: function() {
            this.feedItemListData();
        },
        filters: {
            formatDate: function(value) {
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
	        }
        },
        methods: {
        	feedItemListData: function(page, sortby, sorttype, searchdata) {
                if(typeof(sortby) == "undefined"){
                    sortby = this.sortby;
                    sorttype = this.sorttype;
                } else {
                    this.sortby = sortby;
                    this.sorttype = sorttype;
                }

                var data = "sortby="+sortby + "&sorttype=" + sorttype;

                if(typeof(searchdata) != "undefined") {
                    data += searchdata;
                }

                data += setPaginationAmount();

                if(typeof(page) == "undefined"){
                    ajaxCall("getFeedItemData", data, 'POST', 'json', FeedItemDataSuccess);
                } else {
                    ajaxCall("getFeedItemData?page="+page, data, 'POST', 'json', FeedItemDataSuccess);
                }
            },
            searchFeedItemData: function() {
                var text = $("#text").val();
                var feed_id = $("#feed_name").val();
                var from_date= $("#from_date input").val();
                var to_date = $("#to_date input").val();

                var searchdata = "&text=" + text + "&feed_id=" + feed_id + "&from_date=" + from_date + "&to_date=" + to_date;

                if($('#feed_item_pagination').data("twbs-pagination")){
                    $('#feed_item_pagination').twbsPagination('destroy');
                }
                vueFeedItem.searchdata = searchdata;
                this.feedItemListData(1, this.sortby, this.sorttype, searchdata);
            },
            sortByKey: function (key) {
                this.sortOrder = this.sortOrder * -1;
                this.sortby = key;
                this.sortKey = key;
                var stype = this.sortOrder == 1 ? 'asc':'desc';
                this.sorttype = stype;
                this.feedItemListData(this.currPage, key, stype, this.searchdata);
            },
           	reloadData: function() {
                clearFormData('frm_search_data');
                setDefaultData(vueFeedItem);
                this.feedItemListData();
            },
            clearForm: function(formid) {
                this.reloadData();
            }
        }
    });
}

function FeedItemDataSuccess(feedItemData, status, xhr){
    vueFeedItem.feedItemData = feedItemData['data'];
    vueFeedItem.feedItemCount = feedItemData['data'].length;

    if(feedItemData['data'].length>0 && $.cookie('pagination_length') != '-1') {
        vueFeedItem.currPage = feedItemData.current_page;
        var current_page = feedItemData.current_page;

        if(current_page == 1) {
            $('#feed_item_pagination').off("page").removeData( "twbs-pagination" ).empty();
        }

        var per_page = feedItemData.per_page;

        var startIndex = 0;
        if(current_page > 1) {
            startIndex = (current_page - 1) * parseInt(per_page);
        }
        vueFeedItem.page_index = startIndex+1;
        setTimeout(function() {
            $('#feed_item_pagination').twbsPagination({
                totalPages: feedItemData.last_page,
                visiblePages: 5,
                initiateStartPageClick: false,
                onPageClick: function (event, page) {
                    vueFeedItem.feedItemListData(page, vueFeedItem.sortby, vueFeedItem.sorttype, vueFeedItem.searchdata);
                }
            });

            setPaginationRecords(startIndex+1, startIndex+parseInt($.cookie('pagination_length')), feedItemData.total);
            $("#pagination_length").select2({ minimumResultsForSearch: Infinity });
            $('#pagination_length').val($.cookie('pagination_length')).trigger('change.select2');
        }, 20);
    } else {
        vueFeedItem.page_index = 1;
        setTimeout(function() {
            setPaginationRecords(1, feedItemData.total, feedItemData.total);
            $("#pagination_length").select2({ minimumResultsForSearch: Infinity });
            if($('#feed_item_pagination').data("twbs-pagination")){
                $('#feed_item_pagination').twbsPagination('destroy');
            }

            $('#pagination_length').val($.cookie('pagination_length')).trigger('change.select2');
        }, 20);
    }
}

function setDefaultData(vueId) {
    vueId.currPage = 1;
    vueId.sortby = 'feed_items.id';
    vueId.sorttype = 'desc';
    vueId.searchdata = '';
}

var initFormValidations = function () {
	var feedForm = $('.feed-search-form');
	var validate = feedForm.validate({
		ignore: [],
		errorClass: 'invalid-feedback animated fadeInDown',
		errorElement: 'div',
		errorPlacement: function(error, e)
		{
			$(e).parents('.js-datepicker').append(error);
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
		submitHandler: function(form) {
			vueFeedItem.searchFeedItemData();
			return false;
		},
		rules: {
			'to_date': {
				greaterThanDate: "#fromdate"
			},
		},
	});
};

var FeedItemIndex = function() {
    var uiHelperDatePicker = function(){
        $(".js-datepicker").datetimepicker({
            ignoreReadonly: true,
            format: Site.dateCmsFormat,
            timeZone: Site.clubTimezone
        });

        $("body").on("click", ".datetimepickerClear", function(e) {
            e.preventDefault();
            var $datetimepicker = $(this).closest('.input-group.date');
            $datetimepicker.datetimepicker('clear');
        });
    };

    return {
        init: function() {
            uiHelperDatePicker();
        }
    };
}();

// Initialize when page loads
jQuery(function() {
    FeedItemIndex.init();
	$("#searchFeed").on("click", function() {
		initFormValidations();
	})
});
