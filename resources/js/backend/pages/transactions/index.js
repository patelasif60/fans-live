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
            formatDate: function(value, timezone) {
                if (value) {
                    var utcFormat = moment.tz(String(value), "UTC");
                    return utcFormat.clone().tz(timezone).format(Site.dateTimeCmsFormat) + ' (' + timezone + ')';
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
	        },
            ucFirst: function(value) {
                if (value != '') {
                    return value.charAt(0).toUpperCase() + value.slice(1);
                }
            },
            formatCurrency: function(value) {
                if (value !== '') {
                    return value.toFixed(2);
                }
            },
            netamount: function(value, fee) {
                if ((fee != '') && (value != '')) {
                    var netamount = value - fee;
                    return Number.parseFloat(netamount).toFixed(2);
                }
                return Number.parseFloat(value).toFixed(2);
            },
            formattype: function(value) {
                var transactionType = '';
                if (value == 'product') {
                    transactionType = 'Product';
                } else if (value == 'food_and_drink') {
                    transactionType = 'Food and Drink';
                } else if (value == 'merchandise') {
                    transactionType = 'Merchandise';
                } else if (value == 'event') {
                    transactionType = 'Event';
                } else if (value == 'ticket') {
                    transactionType = 'Tickets';
                } else if (value == 'membership') {
                    transactionType = 'Membership';
                } else if (value == 'hospitality') {
                    transactionType = 'Hospitality';
                } else {
                    return value;
                }
                return transactionType;
            }
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
                    if(Site.currentPanel == 'superadmin') {
                        ajaxCall(Site.currency+"/getData", data, 'POST', 'json', TransactionDataSuccess);
                    } else {
                        ajaxCall("getTransactionsData", data, 'POST', 'json', TransactionDataSuccess);
                    }
                } else {
                    if(Site.currentPanel == 'superadmin') {
                        ajaxCall(Site.currency+"/getData?page="+page, data, 'POST', 'json', TransactionDataSuccess);
                    } else {
                        ajaxCall("getTransactionsData?page="+page, data, 'POST', 'json', TransactionDataSuccess);
                    }
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
                if(Site.currentPanel == 'superadmin') {
                    var club = $("#club").val();
                    searchdata += "&club_id=" + club;
                }

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
            },
            viewTransactionDetail: function(event) {
                var id = event.currentTarget.getAttribute('data-id');
                var type = event.currentTarget.getAttribute('data-type');
                $.ajax({
                    url: "transaction/" + id + "/" + type,
                    type: 'GET',
                    dataType: 'html',
                    cache: false,
                    processData: false
                })
                .done(function(data) {
                    $('#transaction_info_content .edit-content-wrapper').html(data);
                    $('#transaction_info_content').modal('show');
                })
                .fail(function(jqXHR, textStatus, errorThrown) {

                });
            },
            editTransactionDetail: function(event) {
                var id = event.currentTarget.getAttribute('data-id');
                var type = event.currentTarget.getAttribute('data-type');
                $.ajax({
                    url: 'transaction/' + id + '/' + type + '/status',
                    type: 'GET',
                    dataType: 'html',
                    cache: false,
                    processData: false
                })
                .done(function(data) {
                    $('#transaction_payment_status .edit-status-wrapper').html(data);
                    $('#transaction_payment_status').modal('show');
                    $("#form_payment_status").select2({ minimumResultsForSearch: Infinity });
                    $('#form_payment_status').trigger('change.select2');
                })
                .fail(function(jqXHR, textStatus, errorThrown) {

                });
            },
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

var initFormValidations = function () {
	var transactionForm = $('.transaction-search-form');
	var validate = transactionForm.validate({
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
			vueTransaction.searchFeedItemData();
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

jQuery(function() {
    $(document).on('click', '.js-transaction-type', function(){
        var transactionStatus = $(this).data('value');
        if (transactionStatus == 'successful') {
            $("#successful_transaction").addClass("active btn-primary active-transaction");
            $("#successful_transaction").removeClass("btn-alt-secondary");
            $("#failed_transaction").removeClass("active btn-primary active-transaction");
            $("#failed_transaction").addClass("btn-alt-secondary");
        } else if (transactionStatus == 'failed') {
            $("#failed_transaction").addClass("active btn-primary active-transaction");
            $("#failed_transaction").removeClass("btn-alt-secondary");
            $("#successful_transaction").removeClass("active btn-primary active-transaction");
            $("#successful_transaction").addClass("btn-alt-secondary");
        }
        vueTransaction.searchFeedItemData();
    });

    $(document).on('click', '.js-view-transaction-detail', function (e) {
        e.preventDefault();
        $.ajax({
            url: "transaction/" + $(this).data('id') + "/" + $(this).data('type'),
            type: 'GET',
            dataType: 'html',
            cache: false,
            processData: false
        })
        .done(function(data) {
            $('#transaction_info_content .edit-content-wrapper').html(data);
            $('#transaction_info_content').modal('show');
        })
        .fail(function(jqXHR, textStatus, errorThrown) {

        });
    });
});
