import _ from 'lodash';
var currentView = 'block';
var currentDevice = null;
var initialDetails = '{"total_seats": 2, "selected_seats": [], "token": ""}';
var selectedSeatsArray = [];
var totalSeats = 2;
var StadiumBlockCreate = function() {
    var initFormValidations = function () {
        var StadiumBlockForm = $('.create-stadium-block-form');

        StadiumBlockForm.validate({
            ignore: [],
            errorClass: 'invalid-feedback animated fadeInDown',
            errorElement: 'div',
            errorPlacement: function(error, e)
            {
                if (e.attr("name") == "seating_plan") {
                    $(e).parents('.form-group .custom-file').append(error);
                } else {
                    $(e).parents('.form-group').append(error);
                }
            },
            highlight: function(e) {
                $(e).closest('.form-group').removeClass('is-invalid').addClass('is-invalid');
            },
            unhighlight: function (e) {
                $(e).closest('.form-group').removeClass('is-invalid').removeClass('is-invalid');
            },
            success: function(e) {
                $(e).closest('.form-group').removeClass('is-invalid');
                $(e).remove();
            },
            rules: {
                'name' : {
                    required : true
                },
                'seating_plan' : {
                    extension: "csv|xlsx|xls|xlsm"
                },
            },
        });
    };

    return {
        init: function() {
            initFormValidations();
        }
    };
}();

// Initialize when page loads
$(document).ready(function() {
	let userAgent = window.navigator.userAgent.toLowerCase(),
    safari = /safari/.test(userAgent),
    ios = /iphone|ipod|ipad/.test(userAgent);

	if(ios) {
	    if(safari) {
	    } else if ( !safari ) {
	    	currentDevice = 'ios';
	    	if(currentDevice === 'ios') {
				window.webkit.messageHandlers.currentView.postMessage(currentView);
			}
	    };
	} else {
		if (userAgent.includes('wv')) {
			currentDevice = 'android';
			if(currentDevice === 'android') {
				Ticket.setCurrentView(currentView);
			}
		} else {
		}
	};

    // StadiumBlockCreate.init();
    // clearCanvas(false);
	$('a.js-stadium-block[rel=popover]').popover({
	    html: true,
	    container: 'body',
	    placement:'top',
	    sanitize: false,
	    content:function() {
	    	let popoverHtml = '';
		    var shapeCoords = $(this).find('area').attr('coords').split(',');
		    if($(this).find('area').attr('shape') == 'rect') {
		    	$(this).css({"position": "absolute", "top": + shapeCoords[shapeCoords.length - 3] + "px", "left": + shapeCoords[shapeCoords.length-2] + "px"});
		    }
		    if($(this).find('area').attr('shape') == 'poly') {
		    	$(this).css({"position": "absolute", "top": + shapeCoords[shapeCoords.length - (shapeCoords.length - 1)] + "px", "left": + shapeCoords[shapeCoords.length - shapeCoords.length]+"px"});
		    }
		    if($(this).find('area').attr('shape') == 'circle') {
		    	$(this).css({"position": "absolute","top": + shapeCoords[shapeCoords.length - (shapeCoords.length - 1)] + "px", "left": + shapeCoords[shapeCoords.length - shapeCoords.length] + "px"});
		    }

		    popoverHtml= '<div class="title">' + $(this).attr("data-block-name") + '</div>';
	    	popoverHtml+= '<ul>';
			$("input[name='pricedetail["+$(this).attr("id")+"]']").each(function() {
			    popoverHtml += '<li>' + $(this).attr('data-display-name') + ': ' + $(this).attr('data-price') + '</li>';
			});
			popoverHtml+= '</ul>';

			if($(this).find('area').attr('data-seat') <= 0) {
				popoverHtml+= '<button data-ref-id=' + $(this).attr('id') + ' class="btn btn-sm btn-danger btn-block js-notify-sold-out">Sold out - notify me</button>';
			} else {
				popoverHtml+= '<button data-ref-id=' + $(this).attr('id') + ' class="btn btn-sm btn-primary btn-block js-getseatData">' + $(this).find('area').attr('data-seat') + ' seats available</button>';
			}

			return popoverHtml;
	    }
	}).on("show.bs.popover", function(e){
	    // hide all other popovers
	    $("a.js-stadium-block[rel=popover]").not(e.target).popover("hide");
	});
});
$(document).on('click', '.js-select-seat', function(){
	$('[rel="popover"]').popover('hide');
	var seatId=$(this).attr('data-ref-id');
	var selectedSeatFlag = _.findIndex( selectedSeatsArray, function(o) {
		return o.stadium_block_seat_id == seatId;
	});
	if(selectedSeatFlag != -1){
		if($(".js-block-seat#"+seatId).hasClass('is-picked'))
		{
			$(".js-block-seat#"+seatId).addClass('is-available');
			$(".js-block-seat#"+seatId).removeClass('is-picked');
		}
		selectedSeatsArray.splice(selectedSeatFlag,1);
		if(currentDevice == 'android') {
			Ticket.updateSeatsSelection(JSON.stringify(selectedSeatsArray));
		}
		if(currentDevice == 'ios') {
			window.webkit.messageHandlers.updateSeatsSelection.postMessage(JSON.stringify(selectedSeatsArray));
		}
		return;
	}
	if(totalSeats == selectedSeatsArray.length) {
		selectedSeatsArray = [];
		$("li.is-picked").removeClass('is-picked').addClass('is-available');
	}
	if(totalSeats > selectedSeatsArray.length)
	{
		var pricing = [];
		$("input[name='pricedetail["+$(".js-block-seat#"+seatId).attr("id")+"]']").each(function() {
		    pricing.push({
		    	id : parseInt($(this).attr('data-pricing-band-id')),
		    	price : parseFloat($(this).attr('data-price')),
		    	display_name : $(this).attr('data-display-name'),
		    	is_selected : false
		    })
		});

		if($(".js-block-seat#"+seatId).hasClass('is-available'))
		{
			$(".js-block-seat#"+seatId).removeClass('is-available');
			$(".js-block-seat#"+seatId).addClass('is-picked');

		}
		selectedSeatsArray.push({
            stadium_block_seat_id : parseInt(seatId),
            row :  $(".js-block-seat#"+seatId).attr("data-row"),
            seat : $(".js-block-seat#"+seatId).attr("data-seat"),
            type : $(".js-block-seat#"+seatId).attr("data-type"),
            stadium_block_name : $(".js-block-seat#"+seatId).attr("data-block-name"),
            stadium_block_id : parseInt($(".js-block-seat#"+seatId).attr("data-block-id")),
            pricing_bands : pricing
        });
	}
	if(currentDevice == 'android') {
		Ticket.updateSeatsSelection(JSON.stringify(selectedSeatsArray));
	}
	if(currentDevice == 'ios') {
		window.webkit.messageHandlers.updateSeatsSelection.postMessage(JSON.stringify(selectedSeatsArray));
	}
	// if(totalSeats == selectedSeatsArray.length) {
	// 	if(currentDevice == 'android') {
	// 		Ticket.redirectToPriceSelectionScreen();
	// 	}
	// 	if(currentDevice == 'ios') {
	// 		window.webkit.messageHandlers.redirectToPriceSelectionScreen.postMessage('afda');
	// 	}
	// }
});
$(document).on('click','.js-getseatData',function(){
	let areaId = $(this).attr('data-ref-id');
	let data = "blockId="+ $("a#"+areaId).attr("data-block-id") + "&blockName="+ $("a#"+areaId).attr("data-block-name") + "&selectedSeats="+ JSON.stringify(selectedSeatsArray) + "&matchId="+ Site.matchId;
	$('[rel="popover"]').popover('hide');

	$.ajax({
	  url: route('frontend.block.seat'),
	  headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
	  data: data,
	  type: "POST",
	  success: function(html){
			$("#pickblock").hide();
			$("#blockseat").show();
			$("#blockseat").html(html);
			$('li.js-block-seat[rel=popover]').popover({
			    html: true,
			    sanitize: false,
			    placement: 'top',
			    content:function() {
			    	let popoverHtml = '';
			    	popoverHtml = '<div class="title">' + $(this).attr("data-row") + $(this).attr("data-seat") + '</div>';
			    	popoverHtml += '<ul>';
					$("input[name='pricedetail["+$(this).attr("id")+"]']").each(function() {
					    popoverHtml += '<li>' + $(this).attr('data-display-name') + ': ' + $(this).attr('data-price') + '</li>';
					});
					popoverHtml += '</ul>';
					let label = 'Pick seat';
					if($("li#" + $(this).attr('id')).hasClass('is-picked')) {
						label = 'Remove seat';
					}
					popoverHtml += '<button data-ref-id=' + $(this).attr('id') + ' class="btn btn-sm btn-primary btn-block js-select-seat">' + label + '</button>';
					console.log('popoverHtml', popoverHtml);
					return popoverHtml;
			    }
			}).on("show.bs.popover", function(e){
			    // hide all other popovers
			    $("li.js-block-seat[rel=popover]").not(e.target).popover("hide");
			});

		  	// Set current view
			currentView = 'seat';
			if(currentDevice == 'android') {
				Ticket.setCurrentView(currentView);
			}
			if(currentDevice == 'ios') {
				window.webkit.messageHandlers.currentView.postMessage(currentView);
			}
		}
	});
});
$(document).on('click','.js-backtoblock',function(){
	$("#pickblock").show();
	$("#blockseat").hide();
});
$(document).on('click', '.js-notify-sold-out', function(){
	var data = new Object();
	data.match_id = Site.matchId;
	data.reason = "sold_out";
	data.stadium_block_id = $(this).attr('data-ref-id');
	data.stadium_block_name = $("area#" + data.stadium_block_id).data('row');
	if(currentDevice == 'android') {
		Ticket.notifySoldOut(JSON.stringify(data));
	}
	if(currentDevice == 'ios') {
		window.webkit.messageHandlers.notifySoldOut.postMessage(JSON.stringify(data));
	}
	$("a.js-stadium-block[rel=popover]").popover("hide");
});

$(document).on('click', function (e) {
    //did not click a popover toggle, or icon in popover toggle, or popover
    $('[data-toggle="popover"]').each(function () {
        //the 'is' for buttons that trigger popups
        //the 'has' for icons within a button that triggers a popup
        if (!$(this).is(e.target) && $(this).has(e.target).length === 0 && $('.popover').has(e.target).length === 0) {
            $(this).popover('hide');
        }
    });
});

window.setBlockView = function() {
	$("#blockseat").hide();
	$("#pickblock").show();
	currentView = 'block';
	Ticket.setCurrentView(currentView);
}

window.getInitialDetails = function(data) {
	if(currentDevice == 'android') {
		initialDetails = JSON.parse(data.replace(/'/g, '"'));
		totalSeats = data.total_seats;
	}
	if(currentDevice == 'ios') {
		initialDetails = JSON.parse(data);
	}
	selectedSeatsArray = initialDetails.selected_seats;
	totalSeats = initialDetails.total_seats;
}
