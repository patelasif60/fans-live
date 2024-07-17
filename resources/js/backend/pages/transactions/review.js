var vueTransaction;

jQuery(function() {
    $(document).on('change', '#pagination_length', function(){
        $.cookie('pagination_length', $(this).val());
        vueTransaction.transactionListData(1, vueTransaction.sortby, vueTransaction.sorttype, vueTransaction.searchdata);
    });
    getTransactionData();
    initPaginationRecord();
});

function getTransactionData() {
    vueTransaction = new Vue({
        el: "#transaction_list",
        components: {
            'pagination': paginationComponent,
        },
        data: {
            transactionData: [],
            transactionCount: 0,
            sortKey: '',
            sortOrder: 1,
            sortby: 'transaction_timestamp',
            sorttype: 'desc',
            searchdata: ''
        },
        created: function() {
            this.transactionListData();
        },
        filters: {
            name: function(value) {
                return value[0].name;
            },
            email: function(value) {
                return value[0].email;
            },
            consumerId: function(value) {
                return value[0].consumer_id;
            },
            transactionType: function(value) {
                return value[0].transaction_type;
            },
            totalTransactions: function(value) {
                let transactions = Object.entries(value);
                return transactions.length;
            },
            totalGross: function(value) {
                let total = 0;
                Object.entries(value).forEach(([key, val]) => {
                    total+=parseFloat(val.price);
                });
                return Site.currencySymbol+total.toFixed(2);
            },
            totalNet: function(value) {
                let totalGross = 0;
                let totalFeeAmount = 0;
                Object.entries(value).forEach(([key, val]) => {
                    totalGross+=parseFloat(val.price);
                    totalFeeAmount+=parseFloat(val.fee_amount);
                });
                return Site.currencySymbol+(totalGross-totalFeeAmount).toFixed(2);
            },
            totalOwed: function(value) {
                let totalGross = 0;
                let totalFeeAmount = 0;
                Object.entries(value).forEach(([key, val]) => {
                    totalGross+=parseFloat(val.price);
                    totalFeeAmount+=parseFloat(val.fee_amount);
                });
                let totalNet = totalGross-totalFeeAmount;
                let totalOwed = totalNet-Site.bankFee;
                return Site.currencySymbol+(totalOwed).toFixed(2);
            },
            dateRange: function(value) {
                var data = Object.entries(value);
                var lastElement = data.length-1;
                var utcFromDateFormat = moment.tz(String(data[lastElement][1].transaction_timestamp), "UTC");
                var utcToDateFormat = moment.tz(String(data[0][1].transaction_timestamp), "UTC");
                var fromDate = utcFromDateFormat.clone().tz(data[0][1].club_time_zone).format(Site.dateTimeCmsFormat);
                var toDate = utcToDateFormat.clone().tz(data[0][1].club_time_zone).format(Site.dateTimeCmsFormat);
                return toDate + " TO " + fromDate;
            },
        },
        methods: {
        	transactionListData: function(page, sortby, sorttype, searchdata) {
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

                var transaction_status = $(".active-transaction").data('value');
                data += "&transaction_status=" + transaction_status;

                if (typeof(Site.currency) != "undefined") {
                    data += "&currency=" + Site.currency;
                }

                data += setPaginationAmount();

                if(typeof(page) == "undefined"){
                    ajaxCall(Site.currency+"/reviewData", data, 'POST', 'json', TransactionDataSuccess);
                } else {
                    ajaxCall(Site.currency+"/reviewData?page="+page, data, 'POST', 'json', TransactionDataSuccess);
                }
            },
            searchFeedItemData: function() {
                var consumer_id = $("#consumer_id").val();
                var from_date= $("#from_date input").val();
                var to_date = $("#to_date input").val();
                var amount = $("#amount").val();
                var payment_brand = $("#payment_brand").val();
                var payment_status = $("#payment_status").val();
                var last_four_digit = $("#last_four_digit").val();

                var searchdata = "&amount=" + amount + "&consumer_id=" + consumer_id + "&from_date=" + from_date + "&to_date=" + to_date + "&payment_brand=" + payment_brand + "&last_four_digit=" + last_four_digit + "&payment_status=" + payment_status;

                if($('#transaction_pagination').data("twbs-pagination")){
                    $('#transaction_pagination').twbsPagination('destroy');
                }
                vueTransaction.searchdata = searchdata;
                this.transactionListData(1, this.sortby, this.sorttype, searchdata);
            },
            sortByKey: function (key) {
                this.sortOrder = this.sortOrder * -1;
                this.sortby = key;
                this.sortKey = key;
                var stype = this.sortOrder == 1 ? 'asc':'desc';
                this.sorttype = stype;
                this.transactionListData(this.currPage, key, stype, this.searchdata);
            },
           	reloadData: function() {
                clearFormData('frm_search_data');
                setDefaultData(vueTransaction);
                this.transactionListData();
            },
            clearForm: function(formid) {
                this.reloadData();
            }
        }
    });
}

function TransactionDataSuccess(transactionData, status, xhr){
    vueTransaction.transactionData = transactionData['data'];
    vueTransaction.transactionCount = transactionData['data'].length;

    if(transactionData['data'].length>0 && $.cookie('pagination_length') != '-1') {
        vueTransaction.currPage = transactionData.current_page;
        var current_page = transactionData.current_page;

        if(current_page == 1) {
            $('#transaction_pagination').off("page").removeData( "twbs-pagination" ).empty();
        }

        var per_page = transactionData.per_page;

        var startIndex = 0;
        if(current_page > 1) {
            startIndex = (current_page - 1) * parseInt(per_page);
        }
        vueTransaction.page_index = startIndex+1;
        setTimeout(function() {
            $('#transaction_pagination').twbsPagination({
                totalPages: transactionData.last_page,
                visiblePages: 5,
                initiateStartPageClick: false,
                onPageClick: function (event, page) {
                    vueTransaction.transactionListData(page, vueTransaction.sortby, vueTransaction.sorttype, vueTransaction.searchdata);
                }
            });

            setPaginationRecords(startIndex+1, startIndex+parseInt($.cookie('pagination_length')), transactionData.total);
            $("#pagination_length").select2({ minimumResultsForSearch: Infinity });
            $('#pagination_length').val($.cookie('pagination_length')).trigger('change.select2');
        }, 20);
    } else {
        vueTransaction.page_index = 1;
        setTimeout(function() {
            setPaginationRecords(1, transactionData.total, transactionData.total);
            $("#pagination_length").select2({ minimumResultsForSearch: Infinity });
            if($('#transaction_pagination').data("twbs-pagination")){
                $('#transaction_pagination').twbsPagination('destroy');
            }

            $('#pagination_length').val($.cookie('pagination_length')).trigger('change.select2');
        }, 20);
    }
}

function setDefaultData(vueId) {
    vueId.currPage = 1;
    vueId.sortby = 'transaction_timestamp';
    vueId.sorttype = 'desc';
    vueId.searchdata = '';
}

jQuery(function() {
    $(document).on('click', '.js-view-review-transaction-detail', function (e) {
        e.preventDefault();
        $.ajax({
            url: $(this).data('currency') + '/' + $(this).data('id'),
            type: 'GET',
            dataType: 'html',
            cache: false,
            processData: false
        })
        .done(function(data) {
            $('#transaction_review_content .review-content-wrapper').html(data);
            $('#transaction_review_content').modal('show');
        })
        .fail(function(jqXHR, textStatus, errorThrown) {

        });
    });

    $(document).on('click', '.js-export-button', function(e) {
        e.preventDefault();
        var url = $(this).data('url');
        window.open(url, '_blank');
        location.reload();
    });
});