var vuePricingBand;

jQuery(function() {
    $(document).on('change', '#pagination_length', function(){
        $.cookie('pagination_length', $(this).val());
        vuePricingBand.pricingBandListData(1, vuePricingBand.sortby, vuePricingBand.sorttype, vuePricingBand.searchdata);
    });
    getPricingBandData();
    initPaginationRecord();
});

function getPricingBandData() {
    vuePricingBand = new Vue({
        el: "#pricing_band",
        components: {
            'pagination': paginationComponent,
        },
        data: {
            pricingBandData: [],
            pricingBandCount: 0,
            sortKey: '',
            sortOrder: 1,
            sortby: 'pricing_bands.id',
            sorttype: 'desc',
            searchdata: ''
        },
        created: function() {
            this.pricingBandListData();
        },
        methods: {
        	pricingBandListData: function(page, sortby, sorttype, searchdata) {
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
                    ajaxCall("getPricingBandData", data, 'POST', 'json', pricingBandDataSuccess);
                } else {
                    ajaxCall("getPricingBandData?page="+page, data, 'POST', 'json', pricingBandDataSuccess);
                }
            },
            sortByKey: function (key) {
                this.sortOrder = this.sortOrder * -1;
                this.sortby = key;
                this.sortKey = key;
                var stype = this.sortOrder == 1 ? 'asc':'desc';
                this.sorttype = stype;
                this.pricingBandListData(this.currPage, key, stype, this.searchdata);
            }
        }
    });
}

function pricingBandDataSuccess(pricingBandData, status, xhr){
    vuePricingBand.pricingBandData = pricingBandData['data'];
    vuePricingBand.pricingBandCount = pricingBandData['data'].length;

    if(pricingBandData['data'].length>0 && $.cookie('pagination_length') != '-1') {
        vuePricingBand.currPage = pricingBandData.current_page;
        var current_page = pricingBandData.current_page;

        if(current_page == 1) {
            $('#pricing_band_pagination').off("page").removeData( "twbs-pagination" ).empty();
        }

        var per_page = pricingBandData.per_page;

        var startIndex = 0;
        if(current_page > 1) {
            startIndex = (current_page - 1) * parseInt(per_page);
        }
        vuePricingBand.page_index = startIndex+1;
        setTimeout(function() {
            $('#pricing_band_pagination').twbsPagination({
                totalPages: pricingBandData.last_page,
                visiblePages: 5,
                initiateStartPageClick: false,
                onPageClick: function (event, page) {
                    vuePricingBand.pricingBandListData(page, vuePricingBand.sortby, vuePricingBand.sorttype, vuePricingBand.searchdata);
                }
            });

            setPaginationRecords(startIndex+1, startIndex+parseInt($.cookie('pagination_length')), pricingBandData.total);
            $("#pagination_length").select2({ minimumResultsForSearch: Infinity });
            $('#pagination_length').val($.cookie('pagination_length')).trigger('change.select2');
        }, 20);
    } else {
        vuePricingBand.page_index = 1;
        setTimeout(function() {
            setPaginationRecords(1, pricingBandData.total, pricingBandData.total);
            $("#pagination_length").select2({ minimumResultsForSearch: Infinity });
            if($('#pricing_band_pagination').data("twbs-pagination")){
                $('#pricing_band_pagination').twbsPagination('destroy');
            }
        
            $('#pagination_length').val($.cookie('pagination_length')).trigger('change.select2');
        }, 20);
    }
} 