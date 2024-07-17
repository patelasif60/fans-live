var vueProduct;

jQuery(function() {
    $(document).on('change', '#pagination_length', function(){
        $.cookie('pagination_length', $(this).val());
        vueProduct.productListData(1, vueProduct.sortby, vueProduct.sorttype, vueProduct.searchdata);
    });
    getProductData();
    initPaginationRecord();
});

function getProductData() {
    vueProduct = new Vue({
        el: "#product_list",
        components: {
            'pagination': paginationComponent,
        },
        data: {
            productData: [],
            productCount: 0,
            sortKey: '',
            sortOrder: 1,
            sortby: 'products.id',
            sorttype: 'desc',
            searchdata: ''
        },
        created: function() {
            this.productListData();
        },
        filters: {
            formatDate: function(value) {
                if (value) {
                    var utcFormat = moment.tz(String(value), "UTC");
                    return utcFormat.clone().tz(Site.clubTimezone).format(Site.dateTimeCmsFormat);
                }
            },
            excerpt: function(text, length, clamp){
                clamp = clamp || '...';
                var node = document.createElement('div');
                node.innerHTML = text;
                var content = node.textContent;
                return content.length > length ? content.slice(0, length) + clamp : content;
            }
        },
        methods: {
            productListData: function(page, sortby, sorttype, searchdata) {
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
                    ajaxCall("getProductData", data, 'POST', 'json', ProductDataSuccess);
                } else {
                    ajaxCall("getProductData?page="+page, data, 'POST', 'json', ProductDataSuccess);
                }
            },
            searchProductData: function() {
                var title = $("#title").val();
                var category = $("#categorylist").val();
                var categoryType = $("#categoryType").val();
                var searchdata = "&title="+ title+"&category="+ category+"&category_type="+categoryType;

                if($('#product_pagination').data("twbs-pagination")){
                    $('#product_pagination').twbsPagination('destroy');
                }
                vueProduct.searchdata = searchdata;
                this.productListData(1, this.sortby, this.sorttype, searchdata);
            },
            sortByKey: function (key) {
                this.sortOrder = this.sortOrder * -1;
                this.sortby = key;
                this.sortKey = key;
                var stype = this.sortOrder == 1 ? 'asc':'desc';
                this.sorttype = stype;
                this.productListData(this.currPage, key, stype, this.searchdata);
            },
            reloadData: function() {
                clearFormData('frm_search_data');
                setDefaultData(vueProduct);
                this.productListData();
            },
            clearForm: function(formid) {
                this.reloadData();
            },
            deleteData: function(productId) {
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
                           var deleteUrl = 'product/'+productId;
                            $.ajax({
                                type: 'DELETE',
                                processData: false,
                                contentType: false,
                                url: deleteUrl,
                                success: function(response) {
                                    if (response.status == 'error') {
                                        swal({
                                            title: "Product error", 
                                            html: response.message, 
                                            type: "error"});
                                    } else {
                                        swal({
                                            title: "Product success", 
                                            html: response.message, 
                                            type: "success"});
                                    }
                                    vueProduct.productListData(this.currPage, this.sortKey, this.sorttype, this.searchdata);
                                }
                            }); 
                        }
                    }
                );
            }
        }
    });
}

function ProductDataSuccess(productData , status, xhr){
    vueProduct.productData = productData['data'];
    vueProduct.productCount = productData['data'].length;

    if(productData['data'].length>0 && $.cookie('pagination_length') != '-1') {
        vueProduct.currPage = productData.current_page;
        var current_page = productData.current_page;

        if(current_page == 1) {
            $('#product_pagination').off("page").removeData( "twbs-pagination" ).empty();
        }

        var per_page = productData.per_page;

        var startIndex = 0;
        if(current_page > 1) {
            startIndex = (current_page - 1) * parseInt(per_page);
        }
        vueProduct.page_index = startIndex+1;
        setTimeout(function() {
            $('#product_pagination').twbsPagination({
                totalPages: productData.last_page,
                visiblePages: 5,
                initiateStartPageClick: false,
                onPageClick: function (event, page) {
                    vueProduct.productListData(page, vueProduct.sortby, vueProduct.sorttype, vueProduct.searchdata);
                }
            });

            setPaginationRecords(startIndex+1, startIndex+parseInt($.cookie('pagination_length')), productData.total);
            $("#pagination_length").select2({ minimumResultsForSearch: Infinity });
            $('#pagination_length').val($.cookie('pagination_length')).trigger('change.select2');
        }, 20);
    } else {
        vueProduct.page_index = 1;
        setTimeout(function() {
            setPaginationRecords(1, productData.total, productData.total);
            $("#pagination_length").select2({ minimumResultsForSearch: Infinity });
            if($('#product_pagination').data("twbs-pagination")){
                $('#product_pagination').twbsPagination('destroy');
            }
        
            $('#pagination_length').val($.cookie('pagination_length')).trigger('change.select2');
        }, 20);
    }
}

function setDefaultData(vueId) {
    vueId.currPage = 1;
    vueId.sortby = 'products.id';
    vueId.sorttype = 'desc';
    vueId.searchdata = '';
    clearCategoryData();
}

function categoryDataSuccess(categoryData, status, xhr)
{
    clearCategoryData();
	$.each(categoryData, function( index, value ) {
		$('#categorylist').append($('<option>', {
			value: index,
			text : value
		}));
	});
}

function clearCategoryData()
{
    $('#categorylist').empty();
    $('#categorylist').append($('<option>', {
            value: "",
            text: "Select category"
    }));
}

function getCategoryTypeData(categoryType)
{
    if(categoryType != '') {
        ajaxCall("getProductCategoryData?type=" + categoryType,'', 'POST', '', categoryDataSuccess);
    } else {
        clearCategoryData();
    }
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

	var getCategoryList = function getCategoryList(){		
		var categoryType = $("#categoryType").val();
        getCategoryTypeData(categoryType);

		$("#categoryType").change(function(){
			getCategoryTypeData($(this).val());
		});			
	};

    return {
        init: function() {
            uiHelperDatePicker();
			getCategoryList();
        }
    };
}();

// Initialize when page loads
jQuery(function() { 
    FeedItemIndex.init();
});