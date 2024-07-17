var vueTravelOffers;

jQuery(function() {
    $(document).on('change', '#pagination_length', function(){
        $.cookie('pagination_length', $(this).val());
        vueTravelOffers.travelOffersListData(1, vueTravelOffers.sortby, vueTravelOffers.sorttype, vueTravelOffers.searchdata);
    });
    getTravelOffersData();
    initPaginationRecord();
});

function getTravelOffersData() {
    vueTravelOffers = new Vue({
        el: "#travelOffers_list",
        components: {
            'pagination': paginationComponent,
        },
        data: {
            travelOffersData: [],
            travelOffersCount: 0,
            sortKey: '',
            sortOrder: 1,
            sortby: 'travel_offers.id',
            sorttype: 'desc',
            searchdata: ''
        },
        created: function() {
            this.travelOffersListData();
        },
        filters: {
            formatDate: function(value) {
                if (value) {
                    var utcFormat = moment.tz(String(value), "UTC");
                    return utcFormat.clone().tz(Site.clubTimezone).format(Site.dateTimeCmsFormat);
                }
            },
             dataCompare: function(untilDateTime) {
                if (untilDateTime) {
                    let untilDateTimeUTC = moment.tz(String(untilDateTime), "UTC");
                    let dbDateTime = untilDateTimeUTC.clone().tz(Site.clubTimezone);
                    let curDateTime = moment().tz(Site.clubTimezone);
                    if(dbDateTime.isAfter(curDateTime)== true){
                        return dbDateTime.format(Site.dateTimeCmsFormat);
                    }else{
                        return 'Expired';
                    }
                }
            }
        },
        methods: {
            travelOffersListData: function(page, sortby, sorttype, searchdata) {
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
                    ajaxCall("getTraveloffersData", data, 'POST', 'json', TravelOffersDataSuccess);
                } else {
                    ajaxCall("getTraveloffersData?page="+page, data, 'POST', 'json', TravelOffersDataSuccess);
                }
            },
            searchTravelOffersData: function() {
                var name = $("#name").val();
				var from_date = $("#from_date input").val();
				var to_date = $("#to_date input").val();
				var searchdata = "&name=" + name + "&from_date=" + from_date + "&to_date=" + to_date;
                if($('#travelOffers_pagination').data("twbs-pagination")){
                    $('#travelOffers_pagination').twbsPagination('destroy');
                }
                vueTravelOffers.searchdata = searchdata;
                this.travelOffersListData(1, this.sortby, this.sorttype, searchdata);
            },
            sortByKey: function (key) {
                this.sortOrder = this.sortOrder * -1;
                this.sortby = key;
                this.sortKey = key;
                var stype = this.sortOrder == 1 ? 'asc':'desc';
                this.sorttype = stype;
                this.travelOffersListData(this.currPage, key, stype, this.searchdata);
            },
            reloadData: function() {
                clearFormData('frm_search_data');
                setDefaultData(vueTravelOffers);
                this.travelOffersListData();
            },
            clearForm: function(formid) {
                this.reloadData();
            }
        }
    });
}

function TravelOffersDataSuccess(travelOffersData , status, xhr){
    vueTravelOffers.travelOffersData = travelOffersData['data'];
    vueTravelOffers.travelOffersCount = travelOffersData['data'].length;

    if(travelOffersData['data'].length>0 && $.cookie('pagination_length') != '-1') {
        vueTravelOffers.currPage = travelOffersData.current_page;
        var current_page = travelOffersData.current_page;

        if(current_page == 1) {
            $('#travelOffers_pagination').off("page").removeData( "twbs-pagination" ).empty();
        }

        var per_page = travelOffersData.per_page;

        var startIndex = 0;
        if(current_page > 1) {
            startIndex = (current_page - 1) * parseInt(per_page);
        }
        vueTravelOffers.page_index = startIndex+1;
        setTimeout(function() {
            $('#travelOffers_pagination').twbsPagination({
                totalPages: travelOffersData.last_page,
                visiblePages: 5,
                initiateStartPageClick: false,
                onPageClick: function (event, page) {
                    vueTravelOffers.travelOffersListData(page, vueTravelOffers.sortby, vueTravelOffers.sorttype, vueTravelOffers.searchdata);
                }
            });

            setPaginationRecords(startIndex+1, startIndex+parseInt($.cookie('pagination_length')), travelOffersData.total);
            $("#pagination_length").select2({ minimumResultsForSearch: Infinity });
            $('#pagination_length').val($.cookie('pagination_length')).trigger('change.select2');
        }, 20);
    } else {
        vueTravelOffers.page_index = 1;
        setTimeout(function() {
            setPaginationRecords(1, travelOffersData.total, travelOffersData.total);
            $("#pagination_length").select2({ minimumResultsForSearch: Infinity });
            if($('#travelOffers_pagination').data("twbs-pagination")){
                $('#travelOffers_pagination').twbsPagination('destroy');
            }

            $('#pagination_length').val($.cookie('pagination_length')).trigger('change.select2');
        }, 20);
    }
}

function setDefaultData(vueId) {
    vueId.currPage = 1;
    vueId.sortby = 'travel_offers.id';
    vueId.sorttype = 'desc';
    vueId.searchdata = '';
}

var TravelOffersIndex = function() {
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

var initFormValidations = function () {
	var travelOfferForm = $('.travel-offer-search-form');
	var validate = travelOfferForm.validate({
		ignore: [],
		errorClass: 'invalid-feedback animated fadeInDown',
		errorElement: 'div',
		errorPlacement: function (error, e) {
			$(e).parents('.js-datepicker').append(error);
		},
		highlight: function (e) {
			$(e).closest('.form-group').removeClass('is-invalid').addClass('is-invalid');
		},
		unhighlight: function (e) {
			$(e).closest('.form-group').removeClass('is-invalid').removeClass('is-invalid');
		},
		success: function (e) {
			$(e).closest('.form-group').removeClass('is-invalid');
			$(e).remove();
		},
		submitHandler: function (form) {
			vueTravelOffers.searchTravelOffersData();
			return false;
		},
		rules: {
			'to_date': {
				greaterThanDate: "#fromdate"
			},
		},
	});
};


// Initialize when page loads
jQuery(function() {
    TravelOffersIndex.init();
	$("#searchTravelOffer").on("click", function () {
		initFormValidations();
	});
});
