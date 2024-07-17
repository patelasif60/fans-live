var vueSpecialOffer;

jQuery(function() {
	$(document).on('change', '#pagination_length', function(){
		$.cookie('pagination_length', $(this).val());
		vueSpecialOffer.specialOfferListData(1, vueSpecialOffer.sortby, vueSpecialOffer.sorttype, vueSpecialOffer.searchdata);
	});
	getSpecialOfferData();
	initPaginationRecord();
});

function getSpecialOfferData() {
	vueSpecialOffer = new Vue({
		el: "#special_offer_list",
		components: {
			'pagination': paginationComponent,
		},
		data: {
			specialOfferData: [],
			specialOfferCount: 0,
			sortKey: '',
			sortOrder: 1,
			sortby: 'special_offers.id',
			sorttype: 'desc',
			searchdata: ''
		},
		created: function() {
			this.specialOfferListData();
		},
		filters: {
			formattext: function(str, length, clamp) {
	            if (str) {
	            	str = str.toString();
	            	str.replace(/<[^>]*>/g, '');
                    clamp = clamp || '...';
                    var node = document.createElement('div');
                    node.innerHTML = str;
                    var content = node.textContent;
                    return content.length > length ? content.slice(0, length) + clamp : content;
                }
            },
        	formattype: function(str) {
	            if (str) {
	            	var specialOfferCategoryType = JSON.parse(Site.specialOfferType);
	            	return specialOfferCategoryType[str];
	            }
	        },
        },
		methods: {
			specialOfferListData: function(page, sortby, sorttype, searchdata) {
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
					ajaxCall("getSpecialOfferData", data, 'POST', 'json', SpecialOfferDataSuccess);
				} else {
					ajaxCall("getSpecialOfferData?page="+page, data, 'POST', 'json', SpecialOfferDataSuccess);
				}
			},
			searchSpecialOfferData: function() {
				var firstName = $("#name").val();

				var searchdata = "&name="+ firstName;
				if($('#special_offer_pagination').data("twbs-pagination")){
					$('#special_offer_pagination').twbsPagination('destroy');
				}
				vueSpecialOffer.searchdata = searchdata;
				this.specialOfferListData(1, this.sortby, this.sorttype, searchdata);
			},
			sortByKey: function (key) {
				this.sortOrder = this.sortOrder * -1;
				this.sortby = key;
				this.sortKey = key;
				var stype = this.sortOrder == 1 ? 'asc':'desc';
				this.sorttype = stype;
				this.specialOfferListData(this.currPage, key, stype, this.searchdata);
			},
			reloadData: function() {
				clearFormData('frm_search_data');
				setDefaultData(vueSpecialOffer);
				this.specialOfferListData();
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
	                        var deleteUrl = 'specialoffer/'+id;
	                        $.ajax({
	                            type: 'DELETE',
	                            processData: false,
	                            contentType: false,
	                            url: deleteUrl,
	                            success: function(response) {
	                                if (response.status == 'error') {
	                                    swal({
	                                        title: "Special offer error", 
	                                        html: response.message, 
	                                        type: "error"});
	                                } else {
	                                    swal({
	                                        title: "Special offer success", 
	                                        html: response.message, 
	                                        type: "success"});
	                                }
	                                vueSpecialOffer.specialOfferListData(this.currPage, this.sortKey, this.sorttype, this.searchdata);
	                            }
	                        });
	                    }
                    }, function (dismiss) {
                    }
                );
            }
		}
	});
}

function SpecialOfferDataSuccess(specialOfferData, status, xhr){
	vueSpecialOffer.specialOfferData = specialOfferData['data'];
	vueSpecialOffer.specialOfferCount = specialOfferData['data'].length;

	if(specialOfferData['data'].length>0 && $.cookie('pagination_length') != '-1') {
		vueSpecialOffer.currPage = specialOfferData.current_page;
		var current_page = specialOfferData.current_page;

		if(current_page == 1) {
			$('#special_offer_pagination').off("page").removeData( "twbs-pagination" ).empty();
		}

		var per_page = specialOfferData.per_page;

		var startIndex = 0;
		if(current_page > 1) {
			startIndex = (current_page - 1) * parseInt(per_page);
		}
		vueSpecialOffer.page_index = startIndex+1;
		setTimeout(function() {
			$('#special_offer_pagination').twbsPagination({
				totalPages: specialOfferData.last_page,
				visiblePages: 5,
				initiateStartPageClick: false,
				onPageClick: function (event, page) {
					vueSpecialOffer.specialOfferListData(page, vueSpecialOffer.sortby, vueSpecialOffer.sorttype, vueSpecialOffer.searchdata);
				}
			});

			setPaginationRecords(startIndex+1, startIndex+parseInt($.cookie('pagination_length')), specialOfferData.total);
			$("#pagination_length").select2({ minimumResultsForSearch: Infinity });
			$('#pagination_length').val($.cookie('pagination_length')).trigger('change.select2');
		}, 20);
	} else {
		vueSpecialOffer.page_index = 1;
		setTimeout(function() {
			setPaginationRecords(1, specialOfferData.total, specialOfferData.total);
			$("#pagination_length").select2({ minimumResultsForSearch: Infinity });
			if($('#special_offer_pagination').data("twbs-pagination")){
				$('#special_offer_pagination').twbsPagination('destroy');
			}

			$('#pagination_length').val($.cookie('pagination_length')).trigger('change.select2');
		}, 20);
	}
}

function setDefaultData(vueId) {
	vueId.currPage = 1;
	vueId.sortby = 'id';
	vueId.sorttype = 'desc';
	vueId.searchdata = '';
}
