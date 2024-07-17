var vueTravelWarnings;

jQuery(function() {
    $(document).on('change', '#pagination_length', function(){
        $.cookie('pagination_length', $(this).val());
        vueTravelWarnings.travelWarningsListData(1, vueTravelWarnings.sortby, vueTravelWarnings.sorttype, vueTravelWarnings.searchdata);
    });
    getTravelWarningsData();
   initPaginationRecord();
});

function getTravelWarningsData() {
    vueTravelWarnings = new Vue({
        el: "#travelWarnings_list",
        components: {
            'pagination': paginationComponent,
        },
        data: {
            travelWarningsData: [],
            travelWarningsCount: 0,
            sortKey: '',
            sortOrder: 1,
            sortby: 'travel_warnings.id',
            sorttype: 'desc',
            searchdata: ''
        },
        created: function() {
            this.travelWarningsListData();
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
            travelWarningsListData: function(page, sortby, sorttype, searchdata) {
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
                    ajaxCall("getTravelWarningsData", data, 'POST', 'json', TravelWarningsDataSuccess);
                } else {
                    ajaxCall("getTravelWarningsData?page="+page, data, 'POST', 'json', TravelWarningsDataSuccess);
                }
            },
            searchTravelWarningsData: function() {
                var text = $("#text").val();
                var fromdate = $("#fromdate input").val();
                var todate = $("#todate input").val();
                var searchdata = "&text="+ text+"&fromdate="+ fromdate+"&todate="+ todate;
                if($('#travelWarnings_pagination').data("twbs-pagination")){
                    $('#travelWarnings_pagination').twbsPagination('destroy');
                }
                vueTravelWarnings.searchdata = searchdata;
                this.travelWarningsListData(1, this.sortby, this.sorttype, searchdata);
            },
            sortByKey: function (key) {
                this.sortOrder = this.sortOrder * -1;
                this.sortby = key;
                this.sortKey = key;
                var stype = this.sortOrder == 1 ? 'asc':'desc';
                this.sorttype = stype;
                this.travelWarningsListData(this.currPage, key, stype, this.searchdata);
            },
            reloadData: function() {
                setDefaultData(vueTravelWarnings);
                clearFormData('frm_search_data');
                this.travelWarningsListData();
            },
            clearForm: function(formid) {
                this.reloadData();
            }
        }
    });
}

function TravelWarningsDataSuccess(travelWarningsData , status, xhr){
    vueTravelWarnings.travelWarningsData = travelWarningsData['data'];
    vueTravelWarnings.travelWarningsCount = travelWarningsData['data'].length;

    if(travelWarningsData['data'].length>0 && $.cookie('pagination_length') != '-1') {
        vueTravelWarnings.currPage = travelWarningsData.current_page;
        var current_page = travelWarningsData.current_page;

        if(current_page == 1) {
            $('#travelWarnings_pagination').off("page").removeData( "twbs-pagination" ).empty();
        }

        var per_page = travelWarningsData.per_page;

        var startIndex = 0;
        if(current_page > 1) {
            startIndex = (current_page - 1) * parseInt(per_page);
        }
        vueTravelWarnings.page_index = startIndex+1;
        setTimeout(function() {
            $('#travelWarnings_pagination').twbsPagination({
                totalPages: travelWarningsData.last_page,
                visiblePages: 5,
                initiateStartPageClick: false,
                onPageClick: function (event, page) {
                    vueTravelWarnings.travelWarningsListData(page, vueTravelWarnings.sortby, vueTravelWarnings.sorttype, vueTravelWarnings.searchdata);
                }
            });

            setPaginationRecords(startIndex+1, startIndex+parseInt($.cookie('pagination_length')), travelWarningsData.total);
            $("#pagination_length").select2({ minimumResultsForSearch: Infinity });
            $('#pagination_length').val($.cookie('pagination_length')).trigger('change.select2');
        }, 20);
    } else {
        vueTravelWarnings.page_index = 1;
        setTimeout(function() {
            setPaginationRecords(1, travelWarningsData.total, travelWarningsData.total);
            $("#pagination_length").select2({ minimumResultsForSearch: Infinity });
            if($('#travelWarnings_pagination').data("twbs-pagination")){
                $('#travelWarnings_pagination').twbsPagination('destroy');
            }

            $('#pagination_length').val($.cookie('pagination_length')).trigger('change.select2');
        }, 20);
    }
}

function setDefaultData(vueId) {
    vueId.currPage = 1;
    vueId.sortby = 'travel_warnings.id';
    vueId.sorttype = 'desc';
    vueId.searchdata = '';
}

var initFormValidations = function () {
    var travelWarningForm = $('.travel-warnings-search-form');
    var validate = travelWarningForm.validate({
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
            vueTravelWarnings.searchTravelWarningsData();
            return false;
        },
        rules: {
            'todate': {
                greaterThanDate: "#from_date"
            },
        },
    });
};

var TravelWarningsIndex = function() {
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
    TravelWarningsIndex.init();
    $("#searchTravelWarnings").on("click", function() {
        initFormValidations();
    })
});
