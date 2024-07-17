var vueCMSUser;

jQuery(function() {
    $(document).on('change', '#pagination_length', function(){
        $.cookie('pagination_length', $(this).val());
        vueCMSUser.CMSUserListData(1, vueCMSUser.sortby, vueCMSUser.sorttype, vueCMSUser.searchdata);
    });
    getCMSUserData();
    var defaultPageLength = 20;
    if (typeof ($.cookie('pagination_length')) != "undefined") {
        $("#pagination_length").val(defaultPageLength).trigger('change.select2');
    } else {
        $.cookie('pagination_length', defaultPageLength);
    }
});

function getCMSUserData() {
    vueCMSUser = new Vue({
        el: "#cms_users_list",
        components: {
            'pagination': paginationComponent,
        },
        data: {
            cmsUserData: [],
            cmsUserCount: 0,
            sortKey: '',
            sortOrder: 1,
            sortby: 'cms.id',
            sorttype: 'desc',
            searchdata: ''
        },
        created: function() {
            this.CMSUserListData();
        },
        methods: {
            CMSUserListData: function(page, sortby, sorttype, searchdata) {
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
                    ajaxCall("getCMSUserData", data, 'POST', 'json', CMSUserDataSuccess);
                } else {
                    ajaxCall("getCMSUserData?page="+page, data, 'POST', 'json', CMSUserDataSuccess);
                }
            },
            searchCMSUserData: function() {
                var firstName = $("#first_name").val();
                var lastName = $("#last_name").val();
                var club = $("#club").val();

                var searchdata = "&first_name="+ firstName + "&last_name=" + lastName + "&club_id=" + club;
                if($('#cms_user_pagination').data("twbs-pagination")){
                    $('#cms_user_pagination').twbsPagination('destroy');
                }
                vueCMSUser.searchdata = searchdata;
                this.CMSUserListData(1, this.sortby, this.sorttype, searchdata);
            },
            sortByKey: function (key) {
                this.sortOrder = this.sortOrder * -1;
                // vueCMSUser.sortOrder = this.sortOrder;
                this.sortby = key;
                this.sortKey = key;
                var stype = this.sortOrder == 1 ? 'asc':'desc';
                this.sorttype = stype;
                this.CMSUserListData(this.currPage, key, stype, this.searchdata);
            },
            reloadData: function() {
                clearFormData('frm_search_data');
                setDefaultData(vueCMSUser);
                this.CMSUserListData();
            },
            clearForm: function(formid) {
                this.reloadData();
            },
            resendEmail: function(userId) {
                $("#resend_email_form_"+userId).submit();
            }
        }
    });
}

function CMSUserDataSuccess(cmsUserData, status, xhr){
    vueCMSUser.cmsUserData = cmsUserData['data'];
    vueCMSUser.cmsUserCount = cmsUserData['data'].length;

    if(cmsUserData['data'].length>0 && $.cookie('pagination_length') != '-1') {
        vueCMSUser.currPage = cmsUserData.current_page;
        var current_page = cmsUserData.current_page;

        if(current_page == 1) {
            $('#cms_user_pagination').off("page").removeData( "twbs-pagination" ).empty();
        }

        var per_page = cmsUserData.per_page;

        var startIndex = 0;
        if(current_page > 1) {
            startIndex = (current_page - 1) * parseInt(per_page);
        }
        vueCMSUser.page_index = startIndex+1;
        setTimeout(function() {
            $('#cms_user_pagination').twbsPagination({
                totalPages: cmsUserData.last_page,
                visiblePages: 5,
                initiateStartPageClick: false,
                onPageClick: function (event, page) {
                    vueCMSUser.CMSUserListData(page, vueCMSUser.sortby, vueCMSUser.sorttype, vueCMSUser.searchdata);
                }
            });

            setPaginationRecords(startIndex+1, startIndex+parseInt($.cookie('pagination_length')), cmsUserData.total);
            $("#pagination_length").select2({ minimumResultsForSearch: Infinity });
            $('#pagination_length').val($.cookie('pagination_length')).trigger('change.select2');
        }, 20);
    } else {
        vueCMSUser.page_index = 1;
        setTimeout(function() {
            setPaginationRecords(1, cmsUserData.total, cmsUserData.total);
            $("#pagination_length").select2({ minimumResultsForSearch: Infinity });
            if($('#cms_user_pagination').data("twbs-pagination")){
                $('#cms_user_pagination').twbsPagination('destroy');
            }
        
            $('#pagination_length').val($.cookie('pagination_length')).trigger('change.select2');
        }, 20);
    }
}

function setDefaultData(vueId) {
    vueId.currPage = 1;
    vueId.sortby = 'cms.id';
    vueId.sorttype = 'desc';
    vueId.searchdata = '';
}