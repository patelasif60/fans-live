var vueContentFeed;

jQuery(function() {
    $(document).on('change', '#pagination_length', function(){
        $.cookie('pagination_length', $(this).val());
        vueContentFeed.contentFeedListData(1, vueContentFeed.sortby, vueContentFeed.sorttype);
    });
    getContentFeedData();
    initPaginationRecord();
});

function getContentFeedData() {
    vueContentFeed = new Vue({
        el: "#content_feed_list",
        components: {
            'pagination': paginationComponent,
        },
        data: {
            contentFeedData: [],
            contentFeedCount: 0,
            sortKey: '',
            sortOrder: 1,
            sortby: 'content_feeds.id',
            sorttype: 'desc'
        },
        created: function() {
            this.contentFeedListData();
        },
		filters: {
			formatDate: function(value) {
				if (value) {
                    var utcFormat = moment.tz(String(value), "UTC");
                    return utcFormat.clone().tz(Site.clubTimezone).format(Site.dateTimeCmsFormat);
				}else{
					return '-';
				}
			}
		},
        methods: {
        	contentFeedListData: function(page, sortby, sorttype) {
                if(typeof(sortby) == "undefined"){
                    sortby = this.sortby;
                    sorttype = this.sorttype;
                } else {
                    this.sortby = sortby;
                    this.sorttype = sorttype;
                }

                var data = "sortby="+sortby + "&sorttype=" + sorttype;

                data += setPaginationAmount();

                if(typeof(page) == "undefined"){
                    ajaxCall("getContentFeedData", data, 'POST', 'json', ContentFeedDataSuccess);
                } else {
                    ajaxCall("getContentFeedData?page="+page, data, 'POST', 'json', ContentFeedDataSuccess);
                }
            },
            sortByKey: function (key) {
                this.sortOrder = this.sortOrder * -1;
                this.sortby = key;
                this.sortKey = key;
                var stype = this.sortOrder == 1 ? 'asc':'desc';
                this.sorttype = stype;
                this.contentFeedListData(this.currPage, key, stype);
            },
           	reloadData: function() {
                setDefaultData(vueContentFeed);
                this.contentFeedListData();
            }
        }
    });
}

function ContentFeedDataSuccess(contentFeedData, status, xhr){
    vueContentFeed.contentFeedData = contentFeedData['data'];
    vueContentFeed.contentFeedCount = contentFeedData['data'].length;

    if(contentFeedData['data'].length>0 && $.cookie('pagination_length') != '-1') {
        vueContentFeed.currPage = contentFeedData.current_page;
        var current_page = contentFeedData.current_page;

        if(current_page == 1) {
            $('#content_feed_pagination').off("page").removeData( "twbs-pagination" ).empty();
        }

        var per_page = contentFeedData.per_page;

        var startIndex = 0;
        if(current_page > 1) {
            startIndex = (current_page - 1) * parseInt(per_page);
        }
        vueContentFeed.page_index = startIndex+1;
        setTimeout(function() {
            $('#content_feed_pagination').twbsPagination({
                totalPages: contentFeedData.last_page,
                visiblePages: 5,
                initiateStartPageClick: false,
                onPageClick: function (event, page) {
                    vueContentFeed.contentFeedListData(page, vueContentFeed.sortby, vueContentFeed.sorttype);
                }
            });

            setPaginationRecords(startIndex+1, startIndex+parseInt($.cookie('pagination_length')), contentFeedData.total);
            $("#pagination_length").select2({ minimumResultsForSearch: Infinity });
            $('#pagination_length').val($.cookie('pagination_length')).trigger('change.select2');
        }, 20);
    } else {
        vueContentFeed.page_index = 1;
        setTimeout(function() {
            setPaginationRecords(1, contentFeedData.total, contentFeedData.total);
            $("#pagination_length").select2({ minimumResultsForSearch: Infinity });
            if($('#content_feed_pagination').data("twbs-pagination")){
                $('#content_feed_pagination').twbsPagination('destroy');
            }

            $('#pagination_length').val($.cookie('pagination_length')).trigger('change.select2');
        }, 20);
    }
}

function setDefaultData(vueId) {
    vueId.currPage = 1;
    vueId.sortby = 'content_feeds.id';
    vueId.sorttype = 'desc';
}
