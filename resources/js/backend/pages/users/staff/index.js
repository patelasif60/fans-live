var vueStaffUser;

jQuery(function() {
    $(document).on('change', '#pagination_length', function(){
        $.cookie('pagination_length', $(this).val());
        vueStaffUser.StaffUserListData(1, vueStaffUser.sortby, vueStaffUser.sorttype, vueStaffUser.searchdata);
    });
    getStaffAPPUserData();
    if (typeof ($.cookie('pagination_length')) != "undefined") {
        $("#pagination_length").val($.cookie('pagination_length'));
    } else {
        $.cookie('pagination_length', 20);
    }
});

function getStaffAPPUserData() {
    vueStaffUser = new Vue({
        el: "#staff_users_list",
        components: {
            'pagination': paginationComponent,
        },
        data: {
            staffUserData: [],
            staffUserCount: 0,
            sortKey: '',
            sortOrder: 1,
            sortby: 'staff.id',
            sorttype: 'desc',
            searchdata: ''
        },
        mounted() {
        },
        created: function() {
            this.StaffUserListData();
        },
        methods: {
            StaffUserListData: function(page, sortby, sorttype, searchdata) {
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
                    ajaxCall("getStaffAPPUserData", data, 'POST', 'json', staffUserDataSuccess);
                } else {
                    ajaxCall("getStaffAPPUserData?page="+page, data, 'POST', 'json', staffUserDataSuccess);
                }
            },
            searchAPPUserData: function() {
                var firstName = $("#first_name").val();
                var lastName = $("#last_name").val();
                var club = $("#club").val();

                var searchdata = "&first_name="+ firstName + "&last_name=" + lastName + "&club_id=" + club;
                if($('#staff_user_pagination').data("twbs-pagination")){
                    $('#staff_user_pagination').twbsPagination('destroy');
                }
                vueStaffUser.searchdata = searchdata;
                this.StaffUserListData(1, this.sortby, this.sorttype, searchdata);
            },
            sortByKey: function (key) {
                this.sortOrder = this.sortOrder * -1;
                this.sortby = key;
                this.sortKey = key;
                var stype = this.sortOrder == 1 ? 'asc':'desc';
                this.sorttype = stype;
                this.StaffUserListData(this.currPage, key, stype, this.searchdata);
            },
            reloadData: function() {
                clearFormData('frm_search_data');
                setDefaultData(vueStaffUser);
                this.StaffUserListData();
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
                            var deleteUrl = 'staffuser/'+id;
                            $.ajax({
                                type: 'DELETE',
                                processData: false,
                                contentType: false,
                                url: deleteUrl,
                                success: function(response) {
                                    if (response.status == 'error') {
                                        swal({
                                            title: "Staff user error", 
                                            html: response.message, 
                                            type: "error"});
                                    } else {
                                        swal({
                                            title: "Staff user success", 
                                            html: response.message, 
                                            type: "success"});
                                    }
                                    vueStaffUser.StaffUserListData(this.currPage, this.sortKey, this.sorttype, this.searchdata);
                                }
                            });
                        }
                    }
                );
            }
        }
    });
}

function staffUserDataSuccess(staffUserData, status, xhr){
    vueStaffUser.staffUserData = staffUserData['data'];
    vueStaffUser.staffUserCount = staffUserData['data'].length;

    if(staffUserData['data'].length>0 && $.cookie('pagination_length') > 0) {
        vueStaffUser.currPage = staffUserData.current_page;
        var current_page = staffUserData.current_page;

        if(current_page == 1) {
            $('#staff_user_pagination').off("page").removeData( "twbs-pagination" ).empty();
        }

        var per_page = staffUserData.per_page;

        var startIndex = 0;
        if(current_page > 1) {
            startIndex = (current_page - 1) * parseInt(per_page);
        }
        vueStaffUser.page_index = startIndex+1;
        setTimeout(function() {
            $('#staff_user_pagination').twbsPagination({
                totalPages: staffUserData.last_page,
                visiblePages: 5,
                initiateStartPageClick: false,
                onPageClick: function (event, page) {
                    vueStaffUser.StaffUserListData(page, vueStaffUser.sortby, vueStaffUser.sorttype, vueStaffUser.searchdata);
                }
            });

            setPaginationRecords(startIndex+1, startIndex+parseInt($.cookie('pagination_length')), staffUserData.total);
            $("#pagination_length").select2({ minimumResultsForSearch: Infinity });
            $('#pagination_length').val($.cookie('pagination_length')).trigger('change.select2');
        }, 20);
    } else {
        vueStaffUser.page_index = 1;
        setTimeout(function() {
            setPaginationRecords(1, staffUserData.total, staffUserData.total);
            $("#pagination_length").select2({ minimumResultsForSearch: Infinity });
            if($('#staff_user_pagination').data("twbs-pagination")){
                $('#staff_user_pagination').twbsPagination('destroy');
            }
            $('#pagination_length').val($.cookie('pagination_length')).trigger('change.select2');
        }, 20);
    }
}

function setDefaultData(vueId) {
    vueId.currPage = 1;
    vueId.sortby = 'staff.id';
    vueId.sorttype = 'desc';
    vueId.searchdata = '';
}

var staffIndex = function() {
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
    staffIndex.init();
});