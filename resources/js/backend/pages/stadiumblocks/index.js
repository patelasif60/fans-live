var vueStadiumBlock;

jQuery(function() {
    $(document).on('change', '#pagination_length', function(){
        $.cookie('pagination_length', $(this).val());
        vueStadiumBlock.stadiumBlockListData(1, vueStadiumBlock.sortby, vueStadiumBlock.sorttype, vueStadiumBlock.searchdata);
    });
    getStadiumBlockdata();
    initPaginationRecord();
});

function getStadiumBlockdata() {
    vueStadiumBlock = new Vue({
        el: "#stadium_block",
        components: {
            'pagination': paginationComponent,
        },
        data: {
            stadiumBlockData: [],
            stadiumBlockCount: 0,
            sortKey: '',
            sortOrder: 1,
            sortby: 'stadium_blocks.id',
            sorttype: 'desc',
            searchdata: ''
        },
        created: function() {
            this.stadiumBlockListData();
        },
        methods: {
        	stadiumBlockListData: function(page, sortby, sorttype, searchdata) {
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
                    ajaxCall("getStadiumBlockData", data, 'POST', 'json', stadiumBlockDataSuccess);
                } else {
                    ajaxCall("getStadiumBlockData?page="+page, data, 'POST', 'json', stadiumBlockDataSuccess);
                }
            },
            sortByKey: function (key) {
                this.sortOrder = this.sortOrder * -1;
                this.sortby = key;
                this.sortKey = key;
                var stype = this.sortOrder == 1 ? 'asc':'desc';
                this.sorttype = stype;
                this.stadiumBlockListData(this.currPage, key, stype, this.searchdata);
            }
        }
    });
}

function stadiumBlockDataSuccess(stadiumBlockData, status, xhr){
    vueStadiumBlock.stadiumBlockData = stadiumBlockData['data'];
    vueStadiumBlock.stadiumBlockCount = stadiumBlockData['data'].length;

    if(stadiumBlockData['data'].length>0 && $.cookie('pagination_length') != '-1') {
        vueStadiumBlock.currPage = stadiumBlockData.current_page;
        var current_page = stadiumBlockData.current_page;

        if(current_page == 1) {
            $('#stadium_block_pagination').off("page").removeData( "twbs-pagination" ).empty();
        }

        var per_page = stadiumBlockData.per_page;

        var startIndex = 0;
        if(current_page > 1) {
            startIndex = (current_page - 1) * parseInt(per_page);
        }
        vueStadiumBlock.page_index = startIndex+1;
        setTimeout(function() {
            $('#stadium_block_pagination').twbsPagination({
                totalPages: stadiumBlockData.last_page,
                visiblePages: 5,
                initiateStartPageClick: false,
                onPageClick: function (event, page) {
                    vueStadiumBlock.stadiumBlockListData(page, vueStadiumBlock.sortby, vueStadiumBlock.sorttype, vueStadiumBlock.searchdata);
                }
            });

            setPaginationRecords(startIndex+1, startIndex+parseInt($.cookie('pagination_length')), stadiumBlockData.total);
            $("#pagination_length").select2({ minimumResultsForSearch: Infinity });
            $('#pagination_length').val($.cookie('pagination_length')).trigger('change.select2');
        }, 20);
    } else {
        vueStadiumBlock.page_index = 1;
        setTimeout(function() {
            setPaginationRecords(1, stadiumBlockData.total, stadiumBlockData.total);
            $("#pagination_length").select2({ minimumResultsForSearch: Infinity });
            if($('#stadium_block_pagination').data("twbs-pagination")){
                $('#stadium_block_pagination').twbsPagination('destroy');
            }
        
            $('#pagination_length').val($.cookie('pagination_length')).trigger('change.select2');
        }, 20);
    }
} 