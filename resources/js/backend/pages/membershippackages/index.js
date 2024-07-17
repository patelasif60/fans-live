var vueMembershipPackage;

jQuery(function() {
    $(document).on('change', '#pagination_length', function(){
        $.cookie('pagination_length', $(this).val());
        vueMembershipPackage.membershipPackageListData(1, vueMembershipPackage.sortby, vueMembershipPackage.sorttype, vueMembershipPackage.searchdata);
    });
    getMembershipPackageData();
    initPaginationRecord();
});

function getMembershipPackageData() {
    vueMembershipPackage = new Vue({
        el: "#membership_package",
        components: {
            'pagination': paginationComponent,
        },
        data: {
            membershipPackageData: [],
            membershipPackageCount: 0,
            sortKey: '',
            sortOrder: 1,
            sortby: 'membership_packages.id',
            sorttype: 'desc',
            searchdata: ''
        },
        created: function() {
            this.membershipPackageListData();
        },
        methods: {
        	membershipPackageListData: function(page, sortby, sorttype, searchdata) {
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
                    ajaxCall("getMembershipPackageData", data, 'POST', 'json', membershipPackageDataSuccess);
                } else {
                    ajaxCall("getMembershipPackageData?page="+page, data, 'POST', 'json', membershipPackageDataSuccess);
                }
            },
            sortByKey: function (key) {
                this.sortOrder = this.sortOrder * -1;
                this.sortby = key;
                this.sortKey = key;
                var stype = this.sortOrder == 1 ? 'asc':'desc';
                this.sorttype = stype;
                this.membershipPackageListData(this.currPage, key, stype, this.searchdata);
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
                        var deleteUrl = 'membershippackage/'+id;
                        $.ajax({
                            type: 'DELETE',
                            processData: false,
                            contentType: false,
                            url: deleteUrl,
                            success: function(response) {
                                if (response.status == 'error') {
                                    swal({
                                        title: "Membership package error", 
                                        html: response.message, 
                                        type: "error"});
                                } else {
                                    swal({
                                        title: "Membership package success", 
                                        html: response.message, 
                                        type: "success"});
                                }
                                vueMembershipPackage.membershipPackageListData(this.currPage, this.sortKey, this.sorttype, this.searchdata);
                            }
                        });
                    }, function (dismiss) {
                    }
                );
            }
        }
    });
}

function membershipPackageDataSuccess(membershipPackageData, status, xhr){
    vueMembershipPackage.membershipPackageData = membershipPackageData['data'];
    vueMembershipPackage.membershipPackageCount = membershipPackageData['data'].length;

    if(membershipPackageData['data'].length>0 && $.cookie('pagination_length') != '-1') {
        vueMembershipPackage.currPage = membershipPackageData.current_page;
        var current_page = membershipPackageData.current_page;

        if(current_page == 1) {
            $('#membership_package_pagination').off("page").removeData( "twbs-pagination" ).empty();
        }

        var per_page = membershipPackageData.per_page;

        var startIndex = 0;
        if(current_page > 1) {
            startIndex = (current_page - 1) * parseInt(per_page);
        }
        vueMembershipPackage.page_index = startIndex+1;
        setTimeout(function() {
            $('#membership_package_pagination').twbsPagination({
                totalPages: membershipPackageData.last_page,
                visiblePages: 5,
                initiateStartPageClick: false,
                onPageClick: function (event, page) {
                    vueMembershipPackage.membershipPackageListData(page, vueMembershipPackage.sortby, vueMembershipPackage.sorttype, vueMembershipPackage.searchdata);
                }
            });

            setPaginationRecords(startIndex+1, startIndex+parseInt($.cookie('pagination_length')), membershipPackageData.total);
            $("#pagination_length").select2({ minimumResultsForSearch: Infinity });
            $('#pagination_length').val($.cookie('pagination_length')).trigger('change.select2');
        }, 20);
    } else {
        vueMembershipPackage.page_index = 1;
        setTimeout(function() {
            setPaginationRecords(1, membershipPackageData.total, membershipPackageData.total);
            $("#pagination_length").select2({ minimumResultsForSearch: Infinity });
            if($('#membership_package_pagination').data("twbs-pagination")){
                $('#membership_package_pagination').twbsPagination('destroy');
            }
        
            $('#pagination_length').val($.cookie('pagination_length')).trigger('change.select2');
        }, 20);
    }
} 