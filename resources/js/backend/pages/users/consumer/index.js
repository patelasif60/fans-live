var vueAPPUser;

jQuery(function() {
    $(document).on('change', '#pagination_length', function(){
        $.cookie('pagination_length', $(this).val());
        vueAPPUser.APPUserListData(1, vueAPPUser.sortby, vueAPPUser.sorttype, vueAPPUser.searchdata);
    });
    getConsumerAPPUserData();
    if (typeof ($.cookie('pagination_length')) != "undefined") {
        $("#pagination_length").val($.cookie('pagination_length'));
    } else {
        $.cookie('pagination_length', 20);
    }
});

function getConsumerAPPUserData() {
    vueAPPUser = new Vue({
        el: "#app_users_list",
        components: {
            'pagination': paginationComponent,
        },
        data: {
            appUserData: [],
            appUserCount: 0,
            sortKey: '',
            sortOrder: 1,
            sortby: 'consumers.id',
            sorttype: 'desc',
            searchdata: ''
        },
        created: function() {
            this.APPUserListData();
        },
        methods: {
            APPUserListData: function(page, sortby, sorttype, searchdata) {
                if(typeof(sortby) == "undefined"){
                    sortby = this.sortby;
                    sorttype = this.sorttype;
                } else {
                    this.sortby = sortby;
                    this.sorttype = sorttype;
                }

                var data = "sortby="+sortby + "&sorttype=" + sorttype;

                if(Site.clubdata)
                var data = "sortby="+sortby + "&sorttype=" + sorttype + "&club_id=" + Site.clubdata.id;

                if(typeof(searchdata) != "undefined") {
                    data += searchdata;
                }

                data += setPaginationAmount();

                if(typeof(page) == "undefined"){
                    ajaxCall("getConsumerAPPUserData", data, 'POST', 'json', appUserDataSuccess);
                } else {
                    ajaxCall("getConsumerAPPUserData?page="+page, data, 'POST', 'json', appUserDataSuccess);
                }
            },
            searchAPPUserData: function() {
                var firstName = $("#first_name").val();
                var lastName = $("#last_name").val();
                var club = $("#club").val();

                var searchdata = "&first_name="+ firstName + "&last_name=" + lastName + "&club_id=" + club;
                if($('#app_user_pagination').data("twbs-pagination")){
                    $('#app_user_pagination').twbsPagination('destroy');
                }
                vueAPPUser.searchdata = searchdata;
                this.APPUserListData(1, this.sortby, this.sorttype, searchdata);
            },
            sortByKey: function (key) {
                this.sortOrder = this.sortOrder * -1;
                this.sortby = key;
                this.sortKey = key;
                var stype = this.sortOrder == 1 ? 'asc':'desc';
                this.sorttype = stype;
                this.APPUserListData(this.currPage, key, stype, this.searchdata);
            },
            reloadData: function() {
                clearFormData('frm_search_data');
                setDefaultData(vueAPPUser);
                this.APPUserListData();
            },
            clearForm: function(formid) {
                this.reloadData();
            },
            deleteData: function(id) {
                swal({
                    title: 'Are you sure?',
                    text: 'This information will be permanently deleted!',
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, delete it!',
                    html: false,
                }).then(
                    function (result) {
                        if(result.value)
                        {
                            var deleteUrl = 'consumer/'+id;
                            $.ajax({
                                type: 'DELETE',
                                processData: false,
                                contentType: false,
                                url: deleteUrl,
                                success: function(response) {
                                    if (response.status == 'error') {
                                        swal({
                                            title: "APP user error", 
                                            html: response.message, 
                                            type: "error"});
                                    } else {
                                        swal({
                                            title: "APP user success", 
                                            html: response.message, 
                                            type: "success"});
                                    }
                                    vueAPPUser.APPUserListData(this.currPage, this.sortKey, this.sorttype, this.searchdata);
                                }
                            });
                        }
                    }
                );
            }
        }
    });
}

function appUserDataSuccess(appUserData, status, xhr){
    vueAPPUser.appUserData = appUserData['data'];
    vueAPPUser.appUserCount = appUserData['data'].length;

    if(appUserData['data'].length>0 && $.cookie('pagination_length') > 0) {
        vueAPPUser.currPage = appUserData.current_page;
        var current_page = appUserData.current_page;

        if(current_page == 1) {
            $('#app_user_pagination').off("page").removeData( "twbs-pagination" ).empty();
        }

        var per_page = appUserData.per_page;

        var startIndex = 0;
        if(current_page > 1) {
            startIndex = (current_page - 1) * parseInt(per_page);
        }
        vueAPPUser.page_index = startIndex+1;
        setTimeout(function() {
            $('#app_user_pagination').twbsPagination({
                totalPages: appUserData.last_page,
                visiblePages: 5,
                initiateStartPageClick: false,
                onPageClick: function (event, page) {
                    vueAPPUser.APPUserListData(page, vueAPPUser.sortby, vueAPPUser.sorttype, vueAPPUser.searchdata);
                }
            });
            setPaginationRecords(startIndex+1, startIndex+parseInt($.cookie('pagination_length')), appUserData.total);
            $("#pagination_length").select2({ minimumResultsForSearch: Infinity });
            $('#pagination_length').val($.cookie('pagination_length')).trigger('change.select2');
        }, 20);
    } else {
        vueAPPUser.page_index = 1;
        setTimeout(function() {
            setPaginationRecords(1, appUserData.total, appUserData.total);
            $("#pagination_length").select2({ minimumResultsForSearch: Infinity });
            if($('#app_user_pagination').data("twbs-pagination")){
                $('#app_user_pagination').twbsPagination('destroy');
            }
            
            $('#pagination_length').val($.cookie('pagination_length')).trigger('change.select2');
        }, 20);
    }
}

function setDefaultData(vueId) {
    vueId.currPage = 1;
    vueId.sortby = 'consumers.id';
    vueId.sorttype = 'desc';
    vueId.searchdata = '';
}

var appUserIndex = function() {
    var uiHelperSelect2 = function(){
        // Init Select2 (with .js-select2-allow-clear class)
        jQuery('.js-select2-allow-clear:not(.js-select2-enabled)').each(function(){
            var el = jQuery(this);

            // Add .js-select2-enabled class to tag it as activated
            el.addClass('js-select2-enabled');

            // Init
            el.select2({
                allowClear: true,
                placeholder: "Select club"
            });
        });
    };
    return {
        init: function() {
            uiHelperSelect2();
        }
    };
}();
jQuery(function() {
    appUserIndex.init();
});