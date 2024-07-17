var vueClubs;

jQuery(function() {
    $(document).on('change', '#pagination_length', function(){
        $.cookie('pagination_length', $(this).val());
        vueClubs.clubListData(1, vueClubs.sortby, vueClubs.sorttype, vueClubs.searchdata);
    });
    getClubData();
    initPaginationRecord();
});

function getClubData() {
    vueClubs = new Vue({
        el: "#clubs_list",
        components: {
            'pagination': paginationComponent,
        },
        data: {
            clubData: [],
            clubCount: 0,
            sortKey: '',
            sortOrder: 1,
            sortby: 'clubs.id',
            sorttype: 'desc',
            searchdata: ''
        },
        mounted() {
        },
        created: function() {
            this.clubListData();
        },
        methods: {
            clubListData: function(page, sortby, sorttype, searchdata) {
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
                    ajaxCall("getClubData", data, 'POST', 'json', clubDataSuccess);
                } else {
                    ajaxCall("getClubData?page="+page, data, 'POST', 'json', clubDataSuccess);
                }
            },
            searchClubData: function() {
                var name = $("#name").val();
                var category = $("#search_category").val();

                var searchdata = "&name="+ name + "&category_id=" + category;
                if($('#club_pagination').data("twbs-pagination")){
                    $('#club_pagination').twbsPagination('destroy');
                }
                vueClubs.searchdata = searchdata;
                this.clubListData(1, this.sortby, this.sorttype, searchdata);
            },
            sortByKey: function (key) {
                this.sortOrder = this.sortOrder * -1;
                this.sortby = key;
                this.sortKey = key;
                var stype = this.sortOrder == 1 ? 'asc':'desc';
                this.sorttype = stype;
                this.clubListData(this.currPage, key, stype, this.searchdata);
            },
            reloadData: function() {
                clearFormData('frm_search_data');
                setDefaultData(vueClubs);
                this.clubListData();
            },
            clearForm: function(formid) {
                this.reloadData();
            }
        }
    });
}

function clubDataSuccess(clubData, status, xhr){
    vueClubs.clubData = clubData['data'];
    vueClubs.clubCount = clubData['data'].length;

    if(clubData['data'].length>0 && $.cookie('pagination_length') > 0) {
        vueClubs.currPage = clubData.current_page;
        var current_page = clubData.current_page;

        if(current_page == 1) {
            $('#club_pagination').off("page").removeData( "twbs-pagination" ).empty();
        }

        var per_page = clubData.per_page;

        var startIndex = 0;
        if(current_page > 1) {
            startIndex = (current_page - 1) * parseInt(per_page);
        }
        vueClubs.page_index = startIndex+1;
        setTimeout(function() {
            $('#club_pagination').twbsPagination({
                totalPages: clubData.last_page,
                visiblePages: 5,
                initiateStartPageClick: false,
                onPageClick: function (event, page) {
                    vueClubs.clubListData(page, vueClubs.sortby, vueClubs.sorttype, vueClubs.searchdata);
                }
            });

            setPaginationRecords(startIndex+1, startIndex+parseInt($.cookie('pagination_length')), clubData.total);
            $("#pagination_length").select2({ minimumResultsForSearch: Infinity });
            $('#pagination_length').val($.cookie('pagination_length')).trigger('change.select2');
        }, 20);
    } else {
        vueClubs.page_index = 1;
        setTimeout(function() {
            setPaginationRecords(1, clubData.total, clubData.total);
            $("#pagination_length").select2({ minimumResultsForSearch: Infinity });
            if($('#club_pagination').data("twbs-pagination")){
                $('#club_pagination').twbsPagination('destroy');
            }
            $('#pagination_length').val($.cookie('pagination_length')).trigger('change.select2');
        }, 20);
    }
}

function setDefaultData(vueId) {
    vueId.currPage = 1;
    vueId.sortby = 'clubs.id';
    vueId.sorttype = 'desc';
    vueId.searchdata = '';
}

var ClubIndex = function() {
    var uiHelperSelect2 = function(){
        // Init Select2 (with .js-select2-allow-clear class)
        jQuery('.js-select2-allow-clear:not(.js-select2-enabled)').each(function(){
            var el = jQuery(this);

            // Add .js-select2-enabled class to tag it as activated
            el.addClass('js-select2-enabled');

            // Init
            el.select2({
                allowClear: true,
                placeholder: "Select category"
            });
        });
    };
    return {
        init: function() {
            uiHelperSelect2();
        }
    };
}();
jQuery(function() {
    ClubIndex.init();
});