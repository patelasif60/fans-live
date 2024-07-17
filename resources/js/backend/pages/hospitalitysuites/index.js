var vueHospitalitySuites;

jQuery(function() {
    $(document).on('change', '#pagination_length', function(){
        $.cookie('pagination_length', $(this).val());
        vueHospitalitySuites.HospitalitySuitesListData(1, vueHospitalitySuites.sortby, vueHospitalitySuites.sorttype, vueHospitalitySuites.searchdata);
    });
    getHospitalitySuitesData();
   initPaginationRecord();
});

function getHospitalitySuitesData() {
    vueHospitalitySuites = new Vue({
        el: "#HospitalitySuites_list",
        components: {
            'pagination': paginationComponent,
        },
        data: {
            HospitalitySuitesData: [],
            HospitalitySuitesCount: 0,
            sortKey: '',
            sortOrder: 1,
            sortby: 'hospitality_suites.id',
            sorttype: 'desc',
            searchdata: ''
        },
        created: function() {
            this.HospitalitySuitesListData();
        },
        methods: {
            HospitalitySuitesListData: function(page, sortby, sorttype, searchdata) { 
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
                    ajaxCall("getHospitalitySuitesData", data, 'POST', 'json', HospitalitySuitesDataSuccess);
                } else {
                    ajaxCall("getHospitalitySuitesData?page="+page, data, 'POST', 'json', HospitalitySuitesDataSuccess);
                }
            },
            searchHospitalitySuitesData: function() {
                var name = $("#name").val();
                var fromdate = $("#fromdate input").val();
                var todate = $("#todate input").val();
                var searchdata = "&name="+ name+"&fromdate="+ fromdate+"&todate="+ todate;
                if($('#HospitalitySuites_pagination').data("twbs-pagination")){
                    $('#HospitalitySuites_pagination').twbsPagination('destroy');
                }
                vueHospitalitySuites.searchdata = searchdata;
                this.HospitalitySuitesListData(1, this.sortby, this.sorttype, searchdata);
            },
            sortByKey: function (key) {
                this.sortOrder = this.sortOrder * -1;
                this.sortby = key;
                this.sortKey = key;
                var stype = this.sortOrder == 1 ? 'asc':'desc';
                this.sorttype = stype;
                this.HospitalitySuitesListData(this.currPage, key, stype, this.searchdata);
            },
            reloadData: function() {
                clearFormData('frm_search_data');
                setDefaultData(vueHospitalitySuites);
                this.HospitalitySuitesListData();
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
                        var deleteUrl = 'hospitalitysuite/'+id;
                        $.ajax({
                            type: 'DELETE',
                            processData: false,
                            contentType: false,
                            url: deleteUrl,
                            success: function(response) {
                                if (response.status == 'error') {
                                    swal({
                                        title: "Hospitality suite error", 
                                        html: response.message, 
                                        type: "error"});
                                } else {
                                    swal({
                                        title: "Hospitality suite success", 
                                        html: response.message, 
                                        type: "success"});
                                }
                                vueHospitalitySuites.HospitalitySuitesListData(this.currPage, this.sortKey, this.sorttype, this.searchdata);
                            }
                        });
                    }, function (dismiss) {
                    }
                );
            }
        }
    });
}

function HospitalitySuitesDataSuccess(HospitalitySuitesData , status, xhr){
    vueHospitalitySuites.HospitalitySuitesData = HospitalitySuitesData['data'];
    vueHospitalitySuites.HospitalitySuitesCount = HospitalitySuitesData['data'].length;

    if(HospitalitySuitesData['data'].length>0 && $.cookie('pagination_length') != '-1') {
        vueHospitalitySuites.currPage = HospitalitySuitesData.current_page;
        var current_page = HospitalitySuitesData.current_page;

        if(current_page == 1) {
            $('#HospitalitySuites_pagination').off("page").removeData( "twbs-pagination" ).empty();
        }

        var per_page = HospitalitySuitesData.per_page;

        var startIndex = 0;
        if(current_page > 1) {
            startIndex = (current_page - 1) * parseInt(per_page);
        }
        vueHospitalitySuites.page_index = startIndex+1;
        setTimeout(function() {
            $('#HospitalitySuites_pagination').twbsPagination({
                totalPages: HospitalitySuitesData.last_page,
                visiblePages: 5,
                initiateStartPageClick: false,
                onPageClick: function (event, page) {
                    vueHospitalitySuites.HospitalitySuitesListData(page, vueHospitalitySuites.sortby, vueHospitalitySuites.sorttype, vueHospitalitySuites.searchdata);
                }
            });

            setPaginationRecords(startIndex+1, startIndex+parseInt($.cookie('pagination_length')), HospitalitySuitesData.total);
            $("#pagination_length").select2({ minimumResultsForSearch: Infinity });
            $('#pagination_length').val($.cookie('pagination_length')).trigger('change.select2');
        }, 20);
    } else {
        vueHospitalitySuites.page_index = 1;
        setTimeout(function() {
            setPaginationRecords(1, HospitalitySuitesData.total, HospitalitySuitesData.total);
            $("#pagination_length").select2({ minimumResultsForSearch: Infinity });
            if($('#HospitalitySuites_pagination').data("twbs-pagination")){
                $('#HospitalitySuites_pagination').twbsPagination('destroy');
            }
        
            $('#pagination_length').val($.cookie('pagination_length')).trigger('change.select2');
        }, 20);
    }
}

function setDefaultData(vueId) {
    vueId.currPage = 1;
    vueId.sortby = 'hospitality_suites.id';
    vueId.sorttype = 'desc';
    vueId.searchdata = '';
}

