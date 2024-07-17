var vueQuiz;

jQuery(function() {
	$(document).on('change', '#pagination_length', function(){
		$.cookie('pagination_length', $(this).val());
		vueQuiz.quizListData(1, vueQuiz.sortby, vueQuiz.sorttype);
	});
	getQuizData();
	initPaginationRecord();
});

function getQuizData() {
	vueQuiz = new Vue({
		el: "#quiz_list",
		components: {
			'pagination': paginationComponent,
		},
		data: {
			quizData: [],
			quizCount: 0,
			sortKey: '',
			sortOrder: 1,
			sortby: 'quizzes.id',
			sorttype: 'desc'
		},
		created: function() {
			this.quizListData();
		},
		filters: {
			formatDate: function (value) {
				if (value) {
					var utcFormat = moment.tz(String(value), "UTC");
                    return utcFormat.clone().tz(Site.clubTimezone).format(Site.dateTimeCmsFormat);
				}
			},
			excerpt: function(text, length, clamp){
				if (text) {
					clamp = clamp || '...';
					var node = document.createElement('div');
					node.innerHTML = text;
					var content = node.textContent;
					return content.length > length ? content.slice(0, length) + clamp : content;
				}
			},
			formattype: function(value) {
	            if (value) {
	            	var quizType = JSON.parse(Site.quizType);
	            	return quizType[value];
	            }
	        },
		},
		methods: {
			quizListData: function(page, sortby, sorttype) {
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
					ajaxCall("getQuizData", data, 'POST', 'json', QuizDataSuccess);
				} else {
					ajaxCall("getQuizData?page="+page, data, 'POST', 'json', QuizDataSuccess);
				}
			},
			sortByKey: function (key) {
				this.sortOrder = this.sortOrder * -1;
				this.sortby = key;
				this.sortKey = key;
				var stype = this.sortOrder == 1 ? 'asc':'desc';
				this.sorttype = stype;
				this.quizListData(this.currPage, key, stype);
			},
			reloadData: function() {
				clearFormData('frm_search_data');
				setDefaultData(vueQuiz);
				this.quizListData();
			},
			clearForm: function(formid) {
				this.reloadData();
			}
		}
	});
}

function QuizDataSuccess(quizData , status, xhr){
	vueQuiz.quizData = quizData['data'];
	vueQuiz.quizCount = quizData['data'].length;

	if(quizData['data'].length>0 && $.cookie('pagination_length') != '-1') {
		vueQuiz.currPage = quizData.current_page;
		var current_page = quizData.current_page;

		if(current_page == 1) {
			$('#quiz_pagination').off("page").removeData( "twbs-pagination" ).empty();
		}

		var per_page = quizData.per_page;

		var startIndex = 0;
		if(current_page > 1) {
			startIndex = (current_page - 1) * parseInt(per_page);
		}
		vueQuiz.page_index = startIndex+1;
		setTimeout(function() {
			$('#quiz_pagination').twbsPagination({
				totalPages: quizData.last_page,
				visiblePages: 5,
				initiateStartPageClick: false,
				onPageClick: function (quiz, page) {
					vueQuiz.quizListData(page, vueQuiz.sortby, vueQuiz.sorttype);
				}
			});

			setPaginationRecords(startIndex+1, startIndex+parseInt($.cookie('pagination_length')), quizData.total);
			$("#pagination_length").select2({ minimumResultsForSearch: Infinity });
			$('#pagination_length').val($.cookie('pagination_length')).trigger('change.select2');
		}, 20);
	} else {
		vueQuiz.page_index = 1;
		setTimeout(function() {
			setPaginationRecords(1, quizData.total, quizData.total);
			$("#pagination_length").select2({ minimumResultsForSearch: Infinity });
			if($('#quiz_pagination').data("twbs-pagination")){
				$('#quiz_pagination').twbsPagination('destroy');
			}

			$('#pagination_length').val($.cookie('pagination_length')).trigger('change.select2');
		}, 20);
	}
}

function setDefaultData(vueId) {
	vueId.currPage = 1;
	vueId.sortby = 'quizzes.id';
	vueId.sorttype = 'desc';
}
