$(function() {
	$.fn.googleMap = function(params) { 
		params = $.extend(true,{
			zoom : 17,
			center: new google.maps.LatLng(params.latitude,params.longitude),
			type : "ROADMAP",
			debug : false,
			langage : "english",
			overviewMapControl: false,
			streetViewControl: false,
			scrollwheel: false,
			mapTypeControl: false
		}, params);
		this.each(function() {
			var map = new google.maps.Map(this,params);
			var id=this.id;
			google.maps.event.addListener(map,'click', function(event) {
				$("#"+id).userfunction(event,id)
			});
			$(this).data('googleMap', map);
			$(this).data('googleMarker', new Array());
		});
		return this;
	}
	$.fn.addMarker = function(params) {
		params = $.extend( {
			coords : false,
			address : false,
			url : false,
			id : false,
			icon : Site.markers.red,
			draggable : false,
			title : "",
			text : "",
			success : function() {}
		}, params);
		this.each(function() {
			var id=this.id;
			$this = $(this);
			if(params.icon) {
				var marker = new google.maps.Marker({
					map: $this.data('googleMap'),
					position: new google.maps.LatLng(params.coords[0], params.coords[1]),
					title: params.title,
					icon: params.icon,
					draggable: params.draggable
				});
			} else {
				var marker = new google.maps.Marker({
					map: $this.data('googleMap'),
					position: new google.maps.LatLng(params.coords[0], params.coords[1]),
					title: params.title,
					draggable: params.draggable
				});
			}
			google.maps.event.addListener(marker, 'dragend', function() { 
				var location = marker.getPosition();
				var coords = {};
				if(params.id=='Genral_Setting'){
					var data = "latitude="+location.lat() + "&logitude=" + location.lng() + "&id=" + $("#gsettingdata").attr('gset-id');
					ajaxCall("../getGenralSettingData", data, 'POST', 'json','');
				}
				else{$("#"+id).assignvalue(location)}
			});
			$this.data('googleMarker')[params.id] = marker;
    		marker.addListener('click', function() {
				$this.data('googleMap').setZoom(17);
				$this.data('googleMap').setCenter(marker.getPosition());
	        });
		});
	}
	$.fn.removeMarker = function(id) {
		this.each(function() {
			var $this = $(this);
			var $markers = $this.data('googleMarker');
			if(typeof $markers[id] != 'undefined') {
				$markers[id].setMap(null);
				return true;
			}
		});
	}
	$.fn.customMarker = function(id,tempid,flag='') {
		this.each(function() {
			var $this = $(this);
			var $markers = $this.data('googleMarker');
			if(typeof $markers[id] != 'undefined') {
				$markers[id].setIcon(flag);
				$markers[id].setDraggable(true);
				if(tempid!=''){
					$markers[tempid].setIcon(Site.markers.red);
				}
				return true;
			}
		});
	}
	
});
