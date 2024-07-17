var vueCategory;

jQuery(function() {
    $(document).on('change', '#pagination_length', function(){
        $.cookie('pagination_length', $(this).val());
        vueCategory.categoryListData(1, vueCategory.sortby, vueCategory.sorttype);
    });
    getCategoryData();
    initPaginationRecord();
});

function getCategoryData() {
    vueCategory = new Vue({
        el: "#category_list",
        components: {
            'pagination': paginationComponent,
        },
        data: {
            categoryData: [],
            categoryCount: 0,
            sortKey: '',
            sortOrder: 1,
            sortby: 'categories.id',
            sorttype: 'desc'
        },
        created: function() {
            this.categoryListData();
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
            categoryListData: function(page, sortby, sorttype) {
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
                    ajaxCall("getCategoryData", data, 'POST', 'json', CategoryDataSuccess);
                } else {
                    ajaxCall("getCategoryData?page="+page, data, 'POST', 'json', CategoryDataSuccess);
                }
            },
            sortByKey: function (key) {
                this.sortOrder = this.sortOrder * -1;
                this.sortby = key;
                this.sortKey = key;
                var stype = this.sortOrder == 1 ? 'asc':'desc';
                this.sorttype = stype;
                this.categoryListData(this.currPage, key, stype);
            },
            reloadData: function() {
                clearFormData('frm_search_data');
                setDefaultData(vueCategory);
                this.categoryListData();
            },
            clearForm: function(formid) {
                this.reloadData();
            }
        }
    });
}

function CategoryDataSuccess(categoryData , status, xhr){
    vueCategory.categoryData = categoryData['data'];
    vueCategory.categoryCount = categoryData['data'].length;

    if(categoryData['data'].length>0 && $.cookie('pagination_length') != '-1') {
        vueCategory.currPage = categoryData.current_page;
        var current_page = categoryData.current_page;

        if(current_page == 1) {
            $('#category_pagination').off("page").removeData( "twbs-pagination" ).empty();
        }

        var per_page = categoryData.per_page;

        var startIndex = 0;
        if(current_page > 1) {
            startIndex = (current_page - 1) * parseInt(per_page);
        }
        vueCategory.page_index = startIndex+1;
        setTimeout(function() {
            $('#category_pagination').twbsPagination({
                totalPages: categoryData.last_page,
                visiblePages: 5,
                initiateStartPageClick: false,
                onPageClick: function (category, page) {
                    vueCategory.categoryListData(page, vueCategory.sortby, vueCategory.sorttype);
                }
            });

            setPaginationRecords(startIndex+1, startIndex+parseInt($.cookie('pagination_length')), categoryData.total);
            $("#pagination_length").select2({ minimumResultsForSearch: Infinity });
            $('#pagination_length').val($.cookie('pagination_length')).trigger('change.select2');
        }, 20);
    } else {
        vueCategory.page_index = 1;
        setTimeout(function() {
            setPaginationRecords(1, categoryData.total, categoryData.total);
            $("#pagination_length").select2({ minimumResultsForSearch: Infinity });
            if($('#category_pagination').data("twbs-pagination")){
                $('#category_pagination').twbsPagination('destroy');
            }
        
            $('#pagination_length').val($.cookie('pagination_length')).trigger('change.select2');
        }, 20);
    }
}

function setDefaultData(vueId) {
    vueId.currPage = 1;
    vueId.sortby = 'categories.id';
    vueId.sorttype = 'desc';
}