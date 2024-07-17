var vueRole;

jQuery(function() {
    $(document).on('change', '#pagination_length', function(){
        $.cookie('pagination_length', $(this).val());
        vueRole.roleListData(1, vueRole.sortby, vueRole.sorttype, vueRole.searchdata);
    });
    getRoleData();
    initPaginationRecord();
});

function getRoleData() {
    vueRole = new Vue({
        el: "#role_list",
        components: {
            'pagination': paginationComponent,
        },
        data: {
            roleData: [],
            roleCount: 0,
            sortKey: '',
            sortOrder: 1,
            sortby: 'id',
            sorttype: 'desc',
            searchdata: ''
        },
        created: function() {
            this.roleListData();
        },
        methods: {
            roleListData: function(page, sortby, sorttype, searchdata) {
                if(typeof(sortby) == "undefined"){
                    sortby = this.sortby;
                    sorttype = this.sorttype;
                } else {
                    this.sortby = sortby;
                    this.sorttype = sorttype;
                }

                var data = "sortby="+sortby + "&sorttype=" + sorttype;

                
                data += setPaginationAmount();

                if(typeof(page) == "undefined"){
                    ajaxCall("getRoleData", data, 'POST', 'json', roleDataSuccess);
                } else {
                    ajaxCall("getRoleData?page="+page, data, 'POST', 'json', roleDataSuccess);
                }
            },
            sortByKey: function (key) {
                this.sortOrder = this.sortOrder * -1;
                this.sortby = key;
                this.sortKey = key;
                var stype = this.sortOrder == 1 ? 'asc':'desc';
                this.sorttype = stype;
                this.roleListData(this.currPage, key, stype, this.searchdata);
            },
            reloadData: function() {
                clearFormData('frm_search_data');
                setDefaultData(vueRole);
                this.roleListData();
            },
            clearForm: function(formid) {
                this.reloadData();
            },
            getPermissions: function(roleObj) {
                var result = new Array();
                $.each(roleObj.permissions, function(key, value) {
                    result[key] = Site.permissions[value.name];
                });
                return result.join(', ');
            },
        }
    });
}

function roleDataSuccess(roleData, status, xhr){
    vueRole.roleData = roleData['data'];
    vueRole.roleCount = roleData['data'].length;

    if(roleData['data'].length>0 && $.cookie('pagination_length') != '-1') {
        vueRole.currPage = roleData.current_page;
        var current_page = roleData.current_page;

        if(current_page == 1) {
            $('#role_pagination').off("page").removeData( "twbs-pagination" ).empty();
        }

        var per_page = roleData.per_page;

        var startIndex = 0;
        if(current_page > 1) {
            startIndex = (current_page - 1) * parseInt(per_page);
        }
        vueRole.page_index = startIndex+1;
        setTimeout(function() {
            $('#role_pagination').twbsPagination({
                totalPages: roleData.last_page,
                visiblePages: 5,
                initiateStartPageClick: false,
                onPageClick: function (event, page) {
                    vueRole.roleListData(page, vueRole.sortby, vueRole.sorttype, vueRole.searchdata);
                }
            });

            setPaginationRecords(startIndex+1, startIndex+parseInt($.cookie('pagination_length')), roleData.total);
            $("#pagination_length").select2({ minimumResultsForSearch: Infinity });
            $('#pagination_length').val($.cookie('pagination_length')).trigger('change.select2');
        }, 20);
    } else {
        vueRole.page_index = 1;
        setTimeout(function() {
            setPaginationRecords(1, roleData.total, roleData.total);
            $("#pagination_length").select2({ minimumResultsForSearch: Infinity });
            if($('#role_pagination').data("twbs-pagination")){
                $('#role_pagination').twbsPagination('destroy');
            }
        
            $('#pagination_length').val($.cookie('pagination_length')).trigger('change.select2');
        }, 20);
    }
}

function setDefaultData(vueId) {
    vueId.currPage = 1;
    vueId.sortby = 'roles.id';
    vueId.sorttype = 'desc';
    vueId.searchdata = '';
}
