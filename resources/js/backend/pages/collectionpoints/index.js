var vueCollectionPoint;

jQuery(function() {
	$(document).on('change', '#pagination_length', function(){
		$.cookie('pagination_length', $(this).val());
		vueCollectionPoint.collectionPointListData(1, vueCollectionPoint.sortby, vueCollectionPoint.sorttype, vueCollectionPoint.searchdata);
	});
	getCollectionPointData();
	initPaginationRecord();
});

function getCollectionPointData() {
	vueCollectionPoint = new Vue({
		el: "#collection_point_list",
		components: {
			'pagination': paginationComponent,
		},
		data: {
			collectionPointData: [],
			collectionPointCount: 0,
			sortKey: '',
			sortOrder: 1,
			sortby: 'collection_points.id',
			sorttype: 'desc',
			searchdata: ''
		},
		created: function() {
			this.collectionPointListData();
		},
		methods: {
			collectionPointListData: function(page, sortby, sorttype, searchdata) {
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
					ajaxCall("getCollectionPointData", data, 'POST', 'json', CollectionPointDataSuccess);
				} else {
					ajaxCall("getCollectionPointData?page="+page, data, 'POST', 'json', CollectionPointDataSuccess);
				}
			},
			searchCollectionPointData: function() {
				var firstName = $("#name").val();

				var searchdata = "&name="+ firstName;
				if($('#collection_point_pagination').data("twbs-pagination")){
					$('#collection_point_pagination').twbsPagination('destroy');
				}
				vueCollectionPoint.searchdata = searchdata;
				this.collectionPointListData(1, this.sortby, this.sorttype, searchdata);
			},
			sortByKey: function (key) {
				this.sortOrder = this.sortOrder * -1;
				this.sortby = key;
				this.sortKey = key;
				var stype = this.sortOrder == 1 ? 'asc':'desc';
				this.sorttype = stype;
				this.collectionPointListData(this.currPage, key, stype, this.searchdata);
			},
			reloadData: function() {
				clearFormData('frm_search_data');
				setDefaultData(vueCollectionPoint);
				this.collectionPointListData();
			},
			clearForm: function(formid) {
				this.reloadData();
			}
		}
	});
}

function CollectionPointDataSuccess(collectionPointData, status, xhr){
	vueCollectionPoint.collectionPointData = collectionPointData['data'];
	vueCollectionPoint.collectionPointCount = collectionPointData['data'].length;

	if(collectionPointData['data'].length>0 && $.cookie('pagination_length') != '-1') {
		vueCollectionPoint.currPage = collectionPointData.current_page;
		var current_page = collectionPointData.current_page;

		if(current_page == 1) {
			$('#collection_point_pagination').off("page").removeData( "twbs-pagination" ).empty();
		}

		var per_page = collectionPointData.per_page;

		var startIndex = 0;
		if(current_page > 1) {
			startIndex = (current_page - 1) * parseInt(per_page);
		}
		vueCollectionPoint.page_index = startIndex+1;
		setTimeout(function() {
			$('#collection_point_pagination').twbsPagination({
				totalPages: collectionPointData.last_page,
				visiblePages: 5,
				initiateStartPageClick: false,
				onPageClick: function (event, page) {
					vueCollectionPoint.collectionPointListData(page, vueCollectionPoint.sortby, vueCollectionPoint.sorttype, vueCollectionPoint.searchdata);
				}
			});

			setPaginationRecords(startIndex+1, startIndex+parseInt($.cookie('pagination_length')), collectionPointData.total);
			$("#pagination_length").select2({ minimumResultsForSearch: Infinity });
			$('#pagination_length').val($.cookie('pagination_length')).trigger('change.select2');
		}, 20);
	} else {
		vueCollectionPoint.page_index = 1;
		setTimeout(function() {
			setPaginationRecords(1, collectionPointData.total, collectionPointData.total);
			$("#pagination_length").select2({ minimumResultsForSearch: Infinity });
			if($('#collection_point_pagination').data("twbs-pagination")){
				$('#collection_point_pagination').twbsPagination('destroy');
			}

			$('#pagination_length').val($.cookie('pagination_length')).trigger('change.select2');
		}, 20);
	}
}

function setDefaultData(vueId) {
	vueId.currPage = 1;
	vueId.sortby = 'coollection_points.id';
	vueId.sorttype = 'desc';
	vueId.searchdata = '';
}
