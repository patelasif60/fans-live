window.ajaxCall = function (url, data, method, dataType, successHandlerFunction, token,processDataFlag, contentTypeFlag) {
	if (typeof (processDataFlag) == 'undefined') {
		processDataFlag = true;
	}

	if (typeof (contentTypeFlag) == 'undefined') {
		contentTypeFlag = 'application/x-www-form-urlencoded';
	}
	geturl = $.ajax({
		url: url,
		data: data,
		processData: processDataFlag,
		contentType: contentTypeFlag,
		type: method,
		dataType: dataType,
		cache: false,
		success: successHandlerFunction,
		headers: {"Authorization": token},
		complete: function () {
			$(".js-data-table .overlay").hide();
		}
	});
}
