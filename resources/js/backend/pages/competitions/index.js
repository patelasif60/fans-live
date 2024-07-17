var vueCompetition;

jQuery(function() {
    $(document).on('change', '#pagination_length', function(){
        $.cookie('pagination_length', $(this).val());
        vueCompetition.competitionListData(1, vueCompetition.sortby, vueCompetition.sorttype, vueCompetition.searchdata);
    });
    getCompetitionData();
    initPaginationRecord();
});

function getCompetitionData() {
    vueCompetition = new Vue({
        el: "#competition_list",
        components: {
            'pagination': paginationComponent,
        },
        data: {
            competitionData: [],
            competitionCount: 0,
            sortKey: '',
            sortOrder: 1,
            sortby: 'competitions.id',
            sorttype: 'desc',
            searchdata: ''
        },
        created: function() {
            this.competitionListData();
        },
        methods: {
        	competitionListData: function(page, sortby, sorttype, searchdata) {
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
                    ajaxCall("getCompetitionData", data, 'POST', 'json', CompetitionDataSuccess);
                } else {
                    ajaxCall("getCompetitionData?page="+page, data, 'POST', 'json', CompetitionDataSuccess);
                }
            },
            searchCompetitionData: function() {
                var firstName = $("#name").val();

                var searchdata = "&name="+ firstName;
                if($('#competition_pagination').data("twbs-pagination")){
                    $('#competition_pagination').twbsPagination('destroy');
                }
                vueCompetition.searchdata = searchdata;
                this.competitionListData(1, this.sortby, this.sorttype, searchdata);
            },
            sortByKey: function (key) {
                this.sortOrder = this.sortOrder * -1;
                this.sortby = key;
                this.sortKey = key;
                var stype = this.sortOrder == 1 ? 'asc':'desc';
                this.sorttype = stype;
                this.competitionListData(this.currPage, key, stype, this.searchdata);
            },
           	reloadData: function() {
                clearFormData('frm_search_data');
                setDefaultData(vueCompetition);
                this.competitionListData();
            },
            clearForm: function(formid) {
                this.reloadData();
            }
        }
    });
}

function CompetitionDataSuccess(competitionData, status, xhr){
    vueCompetition.competitionData = competitionData['data'];
    vueCompetition.competitionCount = competitionData['data'].length;

    if(competitionData['data'].length>0 && $.cookie('pagination_length') != '-1') {
        vueCompetition.currPage = competitionData.current_page;
        var current_page = competitionData.current_page;

        if(current_page == 1) {
            $('#competition_pagination').off("page").removeData( "twbs-pagination" ).empty();
        }

        var per_page = competitionData.per_page;

        var startIndex = 0;
        if(current_page > 1) {
            startIndex = (current_page - 1) * parseInt(per_page);
        }
        vueCompetition.page_index = startIndex+1;
        setTimeout(function() {
            $('#competition_pagination').twbsPagination({
                totalPages: competitionData.last_page,
                visiblePages: 5,
                initiateStartPageClick: false,
                onPageClick: function (event, page) {
                    vueCompetition.competitionListData(page, vueCompetition.sortby, vueCompetition.sorttype, vueCompetition.searchdata);
                }
            });

            setPaginationRecords(startIndex+1, startIndex+parseInt($.cookie('pagination_length')), competitionData.total);
            $("#pagination_length").select2({ minimumResultsForSearch: Infinity });
            $('#pagination_length').val($.cookie('pagination_length')).trigger('change.select2');
        }, 20);
    } else {
        vueCompetition.page_index = 1;
        setTimeout(function() {
            setPaginationRecords(1, competitionData.total, competitionData.total);
            $("#pagination_length").select2({ minimumResultsForSearch: Infinity });
            if($('#competition_pagination').data("twbs-pagination")){
                $('#competition_pagination').twbsPagination('destroy');
            }
        
            $('#pagination_length').val($.cookie('pagination_length')).trigger('change.select2');
        }, 20);
    }
}

function setDefaultData(vueId) {
    vueId.currPage = 1;
    vueId.sortby = 'competitions.id';
    vueId.sorttype = 'desc';
    vueId.searchdata = '';
}