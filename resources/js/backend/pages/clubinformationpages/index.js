var vueClubInformationPage;

jQuery(function() {
    $(document).on('change', '#pagination_length', function(){
        $.cookie('pagination_length', $(this).val());
        vueClubInformationPage.clubInformationListData(1, vueClubInformationPage.sortby, vueClubInformationPage.sorttype, vueClubInformationPage.searchdata);
    });
    getClubInformationPageData();
    initPaginationRecord();
});

function getClubInformationPageData() {
    vueClubInformationPage = new Vue({
        el: "#club_information_list",
        components: {
            'pagination': paginationComponent,
        },
        data: {
            clubInformationData: [],
            clubInformationCount: 0,
            sortKey: '',
            sortOrder: 1,
            sortby: 'club_information_pages.id',
            sorttype: 'desc',
            searchdata: ''
        },
        created: function() {
            this.clubInformationListData();
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
            clubInformationListData: function(page, sortby, sorttype, searchdata) {
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
                    ajaxCall("getClubInformationPageData", data, 'POST', 'json', clubInformationDataSuccess);
                } else {
                    ajaxCall("getClubInformationPageData?page="+page, data, 'POST', 'json', clubInformationDataSuccess);
                }
            },
            sortByKey: function (key) {
                this.sortOrder = this.sortOrder * -1;
                this.sortby = key;
                this.sortKey = key;
                var stype = this.sortOrder == 1 ? 'asc':'desc';
                this.sorttype = stype;
                this.clubInformationListData(this.currPage, key, stype, this.searchdata);
            }
        }
    });
}

function clubInformationDataSuccess(clubInformationData , status, xhr){
    vueClubInformationPage.clubInformationData = clubInformationData['data'];
    vueClubInformationPage.clubInformationCount = clubInformationData['data'].length;

    if(clubInformationData['data'].length>0 && $.cookie('pagination_length') != '-1') {
        vueClubInformationPage.currPage = clubInformationData.current_page;
        var current_page = clubInformationData.current_page;

        if(current_page == 1) {
            $('#club_information_pagination').off("page").removeData( "twbs-pagination" ).empty();
        }

        var per_page = clubInformationData.per_page;

        var startIndex = 0;
        if(current_page > 1) {
            startIndex = (current_page - 1) * parseInt(per_page);
        }
        vueClubInformationPage.page_index = startIndex+1;
        setTimeout(function() {
            $('#club_information_pagination').twbsPagination({
                totalPages: clubInformationData.last_page,
                visiblePages: 5,
                initiateStartPageClick: false,
                onPageClick: function (event, page) {
                    vueClubInformationPage.clubInformationListData(page, vueClubInformationPage.sortby, vueClubInformationPage.sorttype, vueClubInformationPage.searchdata);
                }
            });

            setPaginationRecords(startIndex+1, startIndex+parseInt($.cookie('pagination_length')), clubInformationData.total);
            $("#pagination_length").select2({ minimumResultsForSearch: Infinity });
            $('#pagination_length').val($.cookie('pagination_length')).trigger('change.select2');
        }, 20);
    } else {
        vueClubInformationPage.page_index = 1;
        setTimeout(function() {
            setPaginationRecords(1, clubInformationData.total, clubInformationData.total);
            $("#pagination_length").select2({ minimumResultsForSearch: Infinity });
            if($('#club_information_pagination').data("twbs-pagination")){
                $('#club_information_pagination').twbsPagination('destroy');
            }

            $('#pagination_length').val($.cookie('pagination_length')).trigger('change.select2');
        }, 20);
    }
}
