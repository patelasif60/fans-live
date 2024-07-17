var vueTravelInformationPage;

jQuery(function() {
    $(document).on('change', '#pagination_length', function(){
        $.cookie('pagination_length', $(this).val());
        vueTravelInformationPage.travelInformationListData(1, vueTravelInformationPage.sortby, vueTravelInformationPage.sorttype, vueTravelInformationPage.searchdata);
    });
    getTravelInformationPageData();
    initPaginationRecord();
});

function getTravelInformationPageData() {
    vueTravelInformationPage = new Vue({
        el: "#travel_information_list",
        components: {
            'pagination': paginationComponent,
        },
        data: {
            travelInformationData: [],
            travelInformationCount: 0,
            sortKey: '',
            sortOrder: 1,
            sortby: 'travel_information_pages.id',
            sorttype: 'desc',
            searchdata: ''
        },
        created: function() {
            this.travelInformationListData();
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
            travelInformationListData: function(page, sortby, sorttype, searchdata) {
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
                    ajaxCall("getTravelInformationPageData", data, 'POST', 'json', travelInformationDataSuccess);
                } else {
                    ajaxCall("getTravelInformationPageData?page="+page, data, 'POST', 'json', travelInformationDataSuccess);
                }
            },
            sortByKey: function (key) {
                this.sortOrder = this.sortOrder * -1;
                this.sortby = key;
                this.sortKey = key;
                var stype = this.sortOrder == 1 ? 'asc':'desc';
                this.sorttype = stype;
                this.travelInformationListData(this.currPage, key, stype, this.searchdata);
            }
        }
    });
}

function travelInformationDataSuccess(travelInformationData , status, xhr){
    vueTravelInformationPage.travelInformationData = travelInformationData['data'];
    vueTravelInformationPage.travelInformationCount = travelInformationData['data'].length;

    if(travelInformationData['data'].length>0 && $.cookie('pagination_length') != '-1') {
        vueTravelInformationPage.currPage = travelInformationData.current_page;
        var current_page = travelInformationData.current_page;

        if(current_page == 1) {
            $('#travel_information_pagination').off("page").removeData( "twbs-pagination" ).empty();
        }

        var per_page = travelInformationData.per_page;

        var startIndex = 0;
        if(current_page > 1) {
            startIndex = (current_page - 1) * parseInt(per_page);
        }
        vueTravelInformationPage.page_index = startIndex+1;
        setTimeout(function() {
            $('#travel_information_pagination').twbsPagination({
                totalPages: travelInformationData.last_page,
                visiblePages: 5,
                initiateStartPageClick: false,
                onPageClick: function (event, page) {
                    vueTravelInformationPage.travelInformationListData(page, vueTravelInformationPage.sortby, vueTravelInformationPage.sorttype, vueTravelInformationPage.searchdata);
                }
            });

            setPaginationRecords(startIndex+1, startIndex+parseInt($.cookie('pagination_length')), travelInformationData.total);
            $("#pagination_length").select2({ minimumResultsForSearch: Infinity });
            $('#pagination_length').val($.cookie('pagination_length')).trigger('change.select2');
        }, 20);
    } else {
        vueTravelInformationPage.page_index = 1;
        setTimeout(function() {
            setPaginationRecords(1, travelInformationData.total, travelInformationData.total);
            $("#pagination_length").select2({ minimumResultsForSearch: Infinity });
            if($('#travel_information_pagination').data("twbs-pagination")){
                $('#travel_information_pagination').twbsPagination('destroy');
            }

            $('#pagination_length').val($.cookie('pagination_length')).trigger('change.select2');
        }, 20);
    }
}
