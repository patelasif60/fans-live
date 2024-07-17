var EntranceCreate = function() {
    var initFormValidations = function () {
        var userForm = $('.create-entrance-form');
        userForm.validate({
            ignore: [],
            errorClass: 'invalid-feedback animated fadeInDown',
            errorElement: 'div',
            errorPlacement: function(error, e)
            {
                $(e).parents('.form-group').append(error);
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
                    required : true,
                },
                'latitude' : {
                    required : true,
                },
                'longitude' : {
                    required : true,
                },
				'blocks' : {
                    required: {
                        depends: function(element) {
                            return $('#blockValidaton').val() == 1 ? true : false;
                        }
                    }
                },
            },
        });
    };
	var getblocksList = function getblocksList()
	{
		var blocklists = [];
		$.each($("#blockslist option:selected"), function(){
			blocklists.push($(this).val());
		});
		return blocklists.join(", ");
	}
    return {
        init: function() {
            initFormValidations();

        },
		getblocksList:getblocksList,
    };
}();
function addrow(custId){
	let row = '';
	row = '<td id=name>' + $('#namelist').val() + '</td><td id=latitude>' + $('#latitudelist').val() + '</td><td id=longitude>' + $('#longitudelist').val() + '</td>';
	if($('#blockValidaton').val() == 1) {
		row +='<td id=blocks>'+EntranceCreate.getblocksList()+'</td>';
	}
	row +='<td class="text-center" nowrap="nowrap"><a href="javascript:void(0)" class="btn btn-sm js-edit"><i class="fal fa-pencil"></i></a ><a href="javascript:void(0)" delid=' + custId + ' class="btn btn-sm btn-outline-danger js-delete"><i class="fal fa-trash"></i></a></td>';
	return row;
}
$(".stadiumEntranceList").click(function() {
	 if($('.create-entrance-form').valid())
	 {
		$("#mapdata").val('');
		$('.js-cancel').addClass('d-none');
		if( $('#idlist').val()>0){
			$("#"+$(".stadiumEntranceList").attr('bid')).html(addrow($(this).attr('bid')))
			var obj=JSON.parse($('#dbdata').val());
			obj[$(this).attr('bid')].name = $('#namelist').val();
			obj[$(this).attr('bid')].latitude=$('#latitudelist').val();
			obj[$(this).attr('bid')].longitude =$('#longitudelist').val();
			obj[$(this).attr('bid')].status_flag =$('#status_flag').val('create');
			obj[$(this).attr('bid')].blocks = EntranceCreate.getblocksList();
			$('#dbdata').val(JSON.stringify(obj));
			$("#map").removeMarker($(this).attr('bid'));
			$("#map").addMarker({coords:[$('#latitudelist').val(),$('#longitudelist').val()],title:$('#namelist').val(),id:$(this).attr('bid')});
		}
		else{
				if($('.js-table tr:last').attr('id')>=0)
					var custId=parseInt($('.js-table tr:last').attr('id'))+1;
				else
					var custId=0

				$("#map").removeMarker('temp1');
				var obj=JSON.parse($('#dbdata').val());
				if($(this).attr('bid')>=0){
					obj[$(this).attr('bid')].name = $('#namelist').val();
					obj[$(this).attr('bid')].latitude=$('#latitudelist').val();
					obj[$(this).attr('bid')].longitude =$('#longitudelist').val();
					obj[$(this).attr('bid')].blocks = EntranceCreate.getblocksList();
					$('#dbdata').val(JSON.stringify(obj));
					$("#"+$(this).attr('bid')).html(addrow($(this).attr('bid')));
					$("#map").removeMarker($(this).attr('bid'));
					$("#map").addMarker({coords:[$('#latitudelist').val(),$('#longitudelist').val()],title:$('#namelist').val(),id:$(this).attr('bid')});
				}
				else{
					var element = {};
					element.id = '';
					element.name = $('#namelist').val();
					element.latitude = $('#latitudelist').val();
					element.longitude =$('#longitudelist').val();
					element.blocks = EntranceCreate.getblocksList();
					element.status_flag =$('#status_flag').val('create');
					obj.push(element);
					$('#dbdata').val(JSON.stringify(obj));
					$('#submitData').append('<tr id='+custId+'>'+addrow(custId)+'</tr>');
					$("#map").removeMarker(custId);
					$("#map").addMarker({coords:[$('#latitudelist').val(),$('#longitudelist').val()],title:$('#namelist').val(),id:custId});
				}
				$('.js-hide').removeClass('d-none');
				$('.js-norecord').removeClass('d-block');
				$('.js-norecord').addClass('d-none');
		}
		$('#namelist').val('');$('#latitudelist').val('');$('#longitudelist').val('');$('#idlist').val('');$("#blockslist option:selected").prop("selected", false);

		$(".stadiumEntranceList").attr('bid','-1');
		$(".stadiumEntranceList").text('Add');
		$( "#stadiumentranceForm" ).submit();
	}
});
$(document).on('click', '.js-edit', function () {
	$("#map").removeMarker('temp1');
	$(".js-edit").removeClass('disabled');
	$(".js-delete").removeClass('disabled');
	$(this).addClass('disabled');
	$(this).next("a").addClass('disabled');
	var rawId = $(this).closest("tr").attr('id');
	var elements = $(this).closest("tr").find('td');
	$.each(elements, function (index, element) {
		var id = element.id;
		if(id=="blocks") {
			var blocksselected = $(element).text().split(", ");
			$('#' + id + "list").val(blocksselected);
		}
		else {
			$('#' + id + "list").val($(element).text());
		}
	});
	$('#idlist').val($(this).attr('editid'));
	$("#map").addidmarker();
	$(".stadiumEntranceList").attr('bid', rawId);
	$(".stadiumEntranceList").text('Save');
	$('.js-cancel').removeClass('d-none');
	$("#map").customMarker(rawId, $("#mapdata").val(), Site.markers.green);
	$("#mapdata").val(rawId);
	$("#blockslist").select2();
});
$(document).on('click','.js-delete',function(){
	swal({
  		title: 'Are you sure?',
  		text: "This information will be permanently deleted!",
  		type: 'warning',
  		showCancelButton: true,
  		confirmButtonColor: '#3085d6',
  		cancelButtonColor: '#d33',
  		confirmButtonText: 'Yes, delete it!'
		}).then((userResponse)=> {
			if(userResponse.value){
				$(this).parents("tr").remove();
				var obj=JSON.parse($('#dbdata').val());
				delete obj[$(this).attr('delid')];
				$('#dbdata').val(JSON.stringify(obj));
				$("#map").removeMarker($(this).attr('delid'));
				if($('.js-table tr').length==1)
				{
					$('.js-hide').addClass('d-none');
					$('.js-norecord').removeClass('d-none');
					$('.js-norecord').addClass('d-block');
				}
				$( "#stadiumentranceForm" ).submit();
			}
		})
	});
// $(document).on('click','.delete-confirmation-button',function(){
// 	$('.swal2-confirm').addClass('js-delete');
// });
$(document).on('click','.js-cancel',function(){
	$("#map").addidmarker();
	$('#idlist').val($(this).attr('editid'));
	$(".stadiumEntranceList").attr('bid','-1');
	$(".stadiumEntranceList").text('Add');
	$(".js-edit").removeClass('disabled');
	$(".js-delete").removeClass('disabled');
	$('.js-cancel').addClass('d-none');
	$("#blockslist").val(null).trigger('change');
});

//add marker as per id
$.fn.addidmarker = function (){
	var markid=$(".stadiumEntranceList").attr('bid');
	if(markid >= 0){
		$("#map").removeidmarker();
		var obj=JSON.parse($('#dbdata').val());
		var lat = obj[markid].latitude;
		var lng= obj[markid].longitude;
		var name=obj[markid].name;
		$("#map").addMarker({coords:[lat,lng],title:name,id:markid});
	}
}
//remove marker as per id
$.fn.removeidmarker = function (){
	var tempflag = $("#mapdata").val();
	if(tempflag==''){
		$("#map").removeMarker('temp1');
	}else{
		$("#map").removeMarker(tempflag);
		$("#mapdata").val('');
	}
}
//get letitude and longitude
$.fn.assignvalue = function(event) {
	$("#latitudelist").val(event.lat());
	$("#longitudelist").val(event.lng());
}
//user function on map click
$.fn.userfunction = function(event,id) {
	$("#"+id).assignvalue(event.latLng);
	$("#"+id).removeidmarker();
	$("#"+id).addMarker({coords:[event.latLng.lat(),event.latLng.lng()],title:'temp',id:'temp1',icon:Site.markers.green,draggable:true});
}

// Initialize when page loads
jQuery(function() {
    EntranceCreate.init();
    var obj=JSON.parse($('#dbdata').val());
    var gsetobj=JSON.parse($('#gsettingdata').val());
    $.each(gsetobj, function(index, element){
    	$("#map").googleMap({latitude:element.latitude,longitude:element.longitude});
    	$("#map").addMarker({coords:[element.latitude,element.longitude],title:element.name,id:element.id,icon:Site.markers.yellow,draggable:true});
    });
    $.each(obj, function(index, element){
    	$("#map").addMarker({coords:[element.latitude,element.longitude],title:element.name,id:index});
    });
});
$("#blockslist").select2();
