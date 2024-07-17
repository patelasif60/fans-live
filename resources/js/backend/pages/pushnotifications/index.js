var vuePushnotification;

jQuery(function() {
    $(document).on('change', '#pagination_length', function(){
        $.cookie('pagination_length', $(this).val());
        vuePushnotification.PushnotificationListData(1, vuePushnotification.sortby, vuePushnotification.sorttype, vuePushnotification.searchdata);
    });
    getPushnotificationData();
    initPaginationRecord();
});

function getPushnotificationData() {
    vuePushnotification = new Vue({
        el: "#push_notification_list",
        components: {
            'pagination': paginationComponent,
        },
        data: {
            pushnotificationData: [],
            pushnotificationCount: 0,
            sortKey: '',
            sortOrder: 1,
            sortby: 'push_notifications.id',
            sorttype: 'desc',
            searchdata: '',
            clubId: window.clubId
        },

        created: function() {
            this.PushnotificationListData();
        },
        filters: {
            formatDate: function(value) {
                if (value) {
                    var utcFormat = moment.tz(String(value), "UTC");
                    return utcFormat.clone().tz(Site.clubTimezone).format(Site.dateTimeCmsFormat);
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
            PushnotificationListData: function(page, sortby, sorttype, searchdata) {
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
                    ajaxCall("getPushnotificationData", data, 'POST', 'json', pushnotificationDataSuccess);
                } else {
                    ajaxCall("getPushnotificationData?page="+page, data, 'POST', 'json', pushnotificationDataSuccess);
                }
            },
            searchPollData: function() {
                var message = $("#message").val();
                var title = $("#title").val();
				var from_date = $("#from_date input").val();
				var to_date = $("#to_date input").val();
				var searchdata = "&message=" + message + "&from_date=" + from_date + "&to_date=" + to_date + "&title=" + title;
				if ($('#pushnotification_pagination').data("twbs-pagination")) {
					$('#pushnotification_pagination').twbsPagination('destroy');
				}
				vuePushnotification.searchdata = searchdata;
				this.PushnotificationListData(1, this.sortby, this.sorttype, searchdata);
            },
            sortByKey: function (key) {
                this.sortOrder = this.sortOrder * -1;
                this.sortby = key;
                this.sortKey = key;
                var stype = this.sortOrder == 1 ? 'asc':'desc';
                this.sorttype = stype;
                this.PushnotificationListData(this.currPage, key, stype, this.searchdata);
            },
            reloadData: function() {
                clearFormData('frm_search_data');
                setDefaultData(vuePushnotification);
                this.PushnotificationListData();
            },
            clearForm: function(formid) {
                this.reloadData();
            }
        }
    });
}

function pushnotificationDataSuccess(pushnotificationData, status, xhr){
    vuePushnotification.pushnotificationData = pushnotificationData['data'];
    vuePushnotification.pushnotificationCount = pushnotificationData['data'].length;

    if(pushnotificationData['data'].length>0 && $.cookie('pagination_length') > 0) {
        vuePushnotification.currPage = pushnotificationData.current_page;
        var current_page = pushnotificationData.current_page;

        if(current_page == 1) {
            $('#pushnotification_pagination').off("page").removeData( "twbs-pagination" ).empty();
        }

        var per_page = pushnotificationData.per_page;

        var startIndex = 0;
        if(current_page > 1) {
            startIndex = (current_page - 1) * parseInt(per_page);
        }
        vuePushnotification.page_index = startIndex+1;
        setTimeout(function() {
            $('#pushnotification_pagination').twbsPagination({
                totalPages: pushnotificationData.last_page,
                visiblePages: 5,
                initiateStartPageClick: false,
                onPageClick: function (event, page) {
                    vuePushnotification.PushnotificationListData(page, vuePushnotification.sortby, vuePushnotification.sorttype, vuePushnotification.searchdata);
                }
            });

            setPaginationRecords(startIndex+1, startIndex+parseInt($.cookie('pagination_length')), pushnotificationData.total);
            $("#pagination_length").select2({ minimumResultsForSearch: Infinity });
        }, 10);
    } else {
        vuePushnotification.page_index = 1;
        setPaginationRecords(1, pushnotificationData.total, pushnotificationData.total);
        $("#pagination_length").select2({ minimumResultsForSearch: Infinity });
        if($('#pushnotification_pagination').data("twbs-pagination")){
            $('#pushnotification_pagination').twbsPagination('destroy');
        }
    }

    $('#pagination_length').val($.cookie('pagination_length'));
}

function setDefaultData(vueId) {
    vueId.currPage = 1;
    vueId.sortby = 'push_notifications.id';
    vueId.sorttype = 'desc';
    vueId.searchdata = '';
}


var initFormValidations = function () {
    var pollForm = $('.pushnotification-search-form');
    var validate = pollForm.validate({
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
            vuePushnotification.searchPollData();
            return false;
        },
        rules: {
            'to_date': {
                greaterThanDate: "#fromdate"
            },
        },
    });
};


var PollIndex = function() {
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
    PollIndex.init();
    $("#searchPushnotification").on("click", function() {
        initFormValidations();
    })
});
