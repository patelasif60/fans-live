var vueCTA;

jQuery(function() {
    $(document).on('change', '#pagination_length', function(){
        $.cookie('pagination_length', $(this).val());
        vueCTA.ctaListData(1, vueCTA.sortby, vueCTA.sorttype, vueCTA.searchdata);
    });
    getCTAData();
    initPaginationRecord();
});

function getCTAData() {
    vueCTA = new Vue({
        el: "#cta_list",
        components: {
            'pagination': paginationComponent,
        },
        data: {
            ctaData: [],
            ctaCount: 0,
            sortKey: '',
            sortOrder: 1,
            sortby: 'ctas.id',
            sorttype: 'desc',
            searchdata: ''
        },
        created: function() {
            this.ctaListData();
        },
        filters: {
            formatDate: function(value) {
                if (value) {
                    var utcFormat = moment.tz(String(value), "UTC");
                    return utcFormat.clone().tz(Site.clubTimezone).format(Site.dateTimeCmsFormat);
                }
            }
        },
        methods: {
        	ctaListData: function(page, sortby, sorttype, searchdata) {
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
                    ajaxCall("getCTAData", data, 'POST', 'json', ctaDataSuccess);
                } else {
                    ajaxCall("getCTAData?page="+page, data, 'POST', 'json', ctaDataSuccess);
                }
            },
            searchFeedItemData: function() {
                var text = $("#text").val();
                var from_date= $("#from_date input").val();
                var to_date = $("#to_date input").val();

                var searchdata = "&text=" + text + "&from_date=" + from_date + "&to_date=" + to_date;

                if($('#cta_pagination').data("twbs-pagination")){
                    $('#cta_pagination').twbsPagination('destroy');
                }
                vueCTA.searchdata = searchdata;
                this.ctaListData(1, this.sortby, this.sorttype, searchdata);
            },
            sortByKey: function (key) {
                this.sortOrder = this.sortOrder * -1;
                this.sortby = key;
                this.sortKey = key;
                var stype = this.sortOrder == 1 ? 'asc':'desc';
                this.sorttype = stype;
                this.ctaListData(this.currPage, key, stype, this.searchdata);
            },
           	reloadData: function() {
                clearFormData('frm_search_data');
                setDefaultData(vueCTA);
                this.ctaListData();
            },
            clearForm: function(formid) {
                this.reloadData();
            }
        }
    });
}

function ctaDataSuccess(ctaData, status, xhr){
    vueCTA.ctaData = ctaData['data'];
    vueCTA.ctaCount = ctaData['data'].length;

    if(ctaData['data'].length>0 && $.cookie('pagination_length') != '-1') {
        vueCTA.currPage = ctaData.current_page;
        var current_page = ctaData.current_page;

        if(current_page == 1) {
            $('#cta_pagination').off("page").removeData( "twbs-pagination" ).empty();
        }

        var per_page = ctaData.per_page;

        var startIndex = 0;
        if(current_page > 1) {
            startIndex = (current_page - 1) * parseInt(per_page);
        }
        vueCTA.page_index = startIndex+1;
        setTimeout(function() {
            $('#cta_pagination').twbsPagination({
                totalPages: ctaData.last_page,
                visiblePages: 5,
                initiateStartPageClick: false,
                onPageClick: function (event, page) {
                    vueCTA.ctaListData(page, vueCTA.sortby, vueCTA.sorttype, vueCTA.searchdata);
                }
            });

            setPaginationRecords(startIndex+1, startIndex+parseInt($.cookie('pagination_length')), ctaData.total);
            $("#pagination_length").select2({ minimumResultsForSearch: Infinity });
            $('#pagination_length').val($.cookie('pagination_length')).trigger('change.select2');
        }, 20);
    } else {
        vueCTA.page_index = 1;
        setTimeout(function() {
            setPaginationRecords(1, ctaData.total, ctaData.total);
            $("#pagination_length").select2({ minimumResultsForSearch: Infinity });
            if($('#cta_pagination').data("twbs-pagination")){
                $('#cta_pagination').twbsPagination('destroy');
            }

            $('#pagination_length').val($.cookie('pagination_length')).trigger('change.select2');
        }, 20);
    }
}

function setDefaultData(vueId) {
    vueId.currPage = 1;
    vueId.sortby = 'ctas.id';
    vueId.sorttype = 'desc';
    vueId.searchdata = '';
}

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


var initFormValidations = function () {
	var ctaForm = $('.cta-search-form');
	var validate = ctaForm.validate({
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
			vueCTA.searchFeedItemData();
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
    FeedItemIndex.init();
	$("#searchCta").on("click", function () {
		initFormValidations();
	})

});
