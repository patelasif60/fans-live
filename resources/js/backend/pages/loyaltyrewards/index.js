var vueLoyaltyRewards;

jQuery(function() {
    $(document).on('change', '#pagination_length', function(){
        $.cookie('pagination_length', $(this).val());
        vueLoyaltyRewards.LoyaltyRewardsListData(1, vueLoyaltyRewards.sortby, vueLoyaltyRewards.sorttype, vueLoyaltyRewards.searchdata);
    });
    getLoyaltyRewardsData();
   initPaginationRecord();
});

function getLoyaltyRewardsData() {
    vueLoyaltyRewards = new Vue({
        el: "#LoyaltyRewards_list",
        components: {
            'pagination': paginationComponent,
        },
        data: {
            loyaltyRewardsData: [],
            loyaltyRewardsCount: 0,
            sortKey: '',
            sortOrder: 1,
            sortby: 'loyalty_rewards.id',
            sorttype: 'desc',
            searchdata: ''
        },
        created: function() {
            this.LoyaltyRewardsListData();
        },
        methods: {
            LoyaltyRewardsListData: function(page, sortby, sorttype, searchdata) { 
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
                    ajaxCall("getLoyaltyRewardsData", data, 'POST', 'json', LoyaltyRewardsDataSuccess);
                } else {
                    ajaxCall("getLoyaltyRewardsData?page="+page, data, 'POST', 'json', LoyaltyRewardsDataSuccess);
                }
            },
            searchLoyaltyRewardsData: function() {
                var name = $("#name").val();
                var fromdate = $("#fromdate input").val();
                var todate = $("#todate input").val();
                var searchdata = "&name="+ name+"&fromdate="+ fromdate+"&todate="+ todate;
                if($('#loyaltyrewards_pagination').data("twbs-pagination")){
                    $('#loyaltyrewards_pagination').twbsPagination('destroy');
                }
                vueLoyaltyRewards.searchdata = searchdata;
                this.LoyaltyRewardsListData(1, this.sortby, this.sorttype, searchdata);
            },
            sortByKey: function (key) {
                this.sortOrder = this.sortOrder * -1;
                this.sortby = key;
                this.sortKey = key;
                var stype = this.sortOrder == 1 ? 'asc':'desc';
                this.sorttype = stype;
                this.LoyaltyRewardsListData(this.currPage, key, stype, this.searchdata);
            },
            reloadData: function() {
                clearFormData('frm_search_data');
                setDefaultData(vueLoyaltyRewards);
                this.LoyaltyRewardsListData();
            },
            clearForm: function(formid) {
                this.reloadData();
            }
        }
    });
}

function LoyaltyRewardsDataSuccess(loyaltyRewardsData , status, xhr){
    vueLoyaltyRewards.loyaltyRewardsData = loyaltyRewardsData['data'];
    vueLoyaltyRewards.loyaltyRewardsCount = loyaltyRewardsData['data'].length;

    if(loyaltyRewardsData['data'].length>0 && $.cookie('pagination_length') != '-1') {
        vueLoyaltyRewards.currPage = loyaltyRewardsData.current_page;
        var current_page = loyaltyRewardsData.current_page;

        if(current_page == 1) {
            $('#loyaltyrewards_pagination').off("page").removeData( "twbs-pagination" ).empty();
        }

        var per_page = loyaltyRewardsData.per_page;

        var startIndex = 0;
        if(current_page > 1) {
            startIndex = (current_page - 1) * parseInt(per_page);
        }
        vueLoyaltyRewards.page_index = startIndex+1;
        setTimeout(function() {
            $('#loyaltyrewards_pagination').twbsPagination({
                totalPages: loyaltyRewardsData.last_page,
                visiblePages: 5,
                initiateStartPageClick: false,
                onPageClick: function (event, page) {
                    vueLoyaltyRewards.LoyaltyRewardsListData(page, vueLoyaltyRewards.sortby, vueLoyaltyRewards.sorttype, vueLoyaltyRewards.searchdata);
                }
            });

            setPaginationRecords(startIndex+1, startIndex+parseInt($.cookie('pagination_length')), loyaltyRewardsData.total);
            $("#pagination_length").select2({ minimumResultsForSearch: Infinity });
            $('#pagination_length').val($.cookie('pagination_length')).trigger('change.select2');
        }, 20);
    } else {
        vueLoyaltyRewards.page_index = 1;
        setTimeout(function() {
            setPaginationRecords(1, loyaltyRewardsData.total, loyaltyRewardsData.total);
            $("#pagination_length").select2({ minimumResultsForSearch: Infinity });
            if($('#loyaltyrewards_pagination').data("twbs-pagination")){
                $('#loyaltyrewards_pagination').twbsPagination('destroy');
            }
        
            $('#pagination_length').val($.cookie('pagination_length')).trigger('change.select2');
        }, 20);
    }
}

function setDefaultData(vueId) {
    vueId.currPage = 1;
    vueId.sortby = 'loyalty_rewards.id';
    vueId.sorttype = 'desc';
    vueId.searchdata = '';
}

