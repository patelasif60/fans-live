var vuePoll;

jQuery(function() {
    $(document).on('change', '#pagination_length', function(){
        $.cookie('pagination_length', $(this).val());
        vuePoll.PollListData(1, vuePoll.sortby, vuePoll.sorttype, vuePoll.searchdata);
    });
    getPollData();
    initPaginationRecord();
});

function getPollData() {
    vuePoll = new Vue({
        el: "#poll_list",
        components: {
            'pagination': paginationComponent,
        },
        data: {
            pollData: [],
            pollCount: 0,
            sortKey: '',
            sortOrder: 1,
            sortby: 'polls.id',
            sorttype: 'desc',
            searchdata: '',
            clubId: window.clubId
        },

        created: function() {
            this.PollListData();
        },
        filters: {
            formatDate: function(value) {
                if (value) {
                    var utcFormat = moment.tz(String(value), "UTC");
                    return utcFormat.clone().tz(Site.clubTimezone).format(Site.dateTimeCmsFormat);
                }
            },
            checkStatus: function(poll) {
                var utcCurrentDateFormat = moment.tz(Site.clubTimezone);
                var currentDateTime = utcCurrentDateFormat.clone().tz(Site.clubTimezone);
                var utcPublicationDateTime = moment.tz(String(poll.publication_date), "UTC");
                var publicationDateTime = utcPublicationDateTime.clone().tz(Site.clubTimezone);
                var utcClosingDateTime = '';
                var closingDateTime = '';
                if (poll.closing_date != null) {
                    utcClosingDateTime = moment.tz(String(poll.closing_date), "UTC");
                    closingDateTime = utcClosingDateTime.clone().tz(Site.clubTimezone);
                }
                var utcDisplayResultDateTime = moment.tz(String(poll.display_results_date), "UTC");
                var displayResultDateTime = utcDisplayResultDateTime.clone().tz(Site.clubTimezone);

                if (currentDateTime < publicationDateTime) {
                    // If published date is greater than current date
                    return 'Published';
                } else if ((publicationDateTime < currentDateTime) && (currentDateTime < displayResultDateTime)) {
                    // If published date is less than current date and display result date is greater than current date
                    return 'Open';
                } else if (displayResultDateTime < currentDateTime) {
                    // If display result date is less then current date time
                    return 'Closed';
                }
            }
        },
        methods: {
            PollListData: function(page, sortby, sorttype, searchdata) {
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
                    ajaxCall("getPollData", data, 'POST', 'json', pollDataSuccess);
                } else {
                    ajaxCall("getPollData?page="+page, data, 'POST', 'json', pollDataSuccess);
                }
            },
            searchPollData: function() {
                var title = $("#title").val();
                var from_date= $("#from_date input").val();
                var to_date = $("#to_date input").val();

                var searchdata = "&title="+ title + "&from_date=" + from_date + "&to_date=" + to_date;
                if($('#poll_pagination').data("twbs-pagination")){
                   $('#poll_pagination').twbsPagination('destroy');
                }
                vuePoll.searchdata = searchdata;
                this.PollListData(1, this.sortby, this.sorttype, searchdata);
            },
            sortByKey: function (key) {
                this.sortOrder = this.sortOrder * -1;
                this.sortby = key;
                this.sortKey = key;
                var stype = this.sortOrder == 1 ? 'asc':'desc';
                this.sorttype = stype;
                this.PollListData(this.currPage, key, stype, this.searchdata);
            },
            reloadData: function() {
                clearFormData('frm_search_data');
                setDefaultData(vuePoll);
                this.PollListData();
            },
            clearForm: function(formid) {
                this.reloadData();
            }
        }
    });
}

function pollDataSuccess(pollData, status, xhr){
    vuePoll.pollData = pollData['data'];
    vuePoll.pollCount = pollData['data'].length;

    if(pollData['data'].length>0 && $.cookie('pagination_length') > 0) {
        vuePoll.currPage = pollData.current_page;
        var current_page = pollData.current_page;

        if(current_page == 1) {
            $('#poll_pagination').off("page").removeData( "twbs-pagination" ).empty();
        }

        var per_page = pollData.per_page;

        var startIndex = 0;
        if(current_page > 1) {
            startIndex = (current_page - 1) * parseInt(per_page);
        }
        vuePoll.page_index = startIndex+1;
        setTimeout(function() {
            $('#poll_pagination').twbsPagination({
                totalPages: pollData.last_page,
                visiblePages: 5,
                initiateStartPageClick: false,
                onPageClick: function (event, page) {
                    vuePoll.PollListData(page, vuePoll.sortby, vuePoll.sorttype, vuePoll.searchdata);
                }
            });

            setPaginationRecords(startIndex+1, startIndex+parseInt($.cookie('pagination_length')), pollData.total);
            $("#pagination_length").select2({ minimumResultsForSearch: Infinity });
            $('#pagination_length').val($.cookie('pagination_length')).trigger('change.select2');
        }, 10);
    } else {
        setTimeout(function() {
            vuePoll.page_index = 1;
            setPaginationRecords(1, pollData.total, pollData.total);
            $("#pagination_length").select2({ minimumResultsForSearch: Infinity });
            if($('#poll_pagination').data("twbs-pagination")){
                $('#poll_pagination').twbsPagination('destroy');
            }
            $('#pagination_length').val($.cookie('pagination_length')).trigger('change.select2');
        },10);
    }
}

function setDefaultData(vueId) {
    vueId.currPage = 1;
    vueId.sortby = 'polls.id';
    vueId.sorttype = 'desc';
    vueId.searchdata = '';
}


var initFormValidations = function () {
    var pollForm = $('.poll-search-form');
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
            vuePoll.searchPollData();
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
            // allowInputToggle: true,
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
    $("#searchPoll").on("click", function() {
        initFormValidations();
    })
});
