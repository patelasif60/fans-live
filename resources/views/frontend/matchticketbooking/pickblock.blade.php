@extends('layouts.frontend')
@section('plugin-scripts')
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
	<script type="text/javascript" src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
@endsection
@section('page-scripts')
	<script type="text/javascript" src="{{ asset('js/frontend/pages/matchticketbooking/pickblock.js') . '?' . time() }}"></script>
@endsection
@section('page-styles')
	@php($lightOrDark=getBrightness($clubPrimaryColor))
	@php($ratio=$lightOrDark == 'dark' ? 0.3 : -0.3)
	<style type="text/css">
        :root {
            --primary: #007bff;
            --danger: #D0021B;
            --bg-seat-block: #d9d9d9;
            --bg-selected-seat-block: var(--primary);
            --popover-bg: {{ $clubPrimaryColor }};
            --popover-text: #FFFFFF;
            --button-bg: {{ colorLuminance($clubPrimaryColor, $ratio) }};
            --button-text: #007bff;
            /*--bg-selected-seat-block-light: #9bcaff;*/
        }
    </style>
@endsection
@section('content')
	<div id="pickblock">
		@if(isset($stadiumGeneralSetting->aerial_view_ticketing_graphic))
	        <div class="stadium-image-wrapper">
	        	<div class="img-width" style="display: inline-block; position: relative;">
		            <img src="{{ $stadiumGeneralSetting->aerial_view_ticketing_graphic }}" usemap="#pickblock" class="stadium-img">

		            <map name="pickblock">
	        			@foreach( $area as $areaval )
	        				{{-- @if( isset($priceBandSeatArray) )
	        					@foreach( $priceBandSeatArray as  $priceBandSeatArrayVal )
	            					@foreach( $priceBandSeatArrayVal as $priceBandSeatArrayValKey => $priceBandSeatArrayValue )
	            						@if( $areaval['stadiumBlockId'] == $priceBandSeatArrayValue['blockId'] )
	            							<input type="hidden" data-price="{{ $priceBandSeatArrayValue['pricingRange'] }}" data-display-name="{{ $priceBandSeatArrayValue['displayName'] }}" name="pricedetail[{{ $priceBandSeatArrayValue['blockId'] }}]">
	            						@endif
	            					@endforeach
	        					@endforeach
	        				@endif --}}
	                        @foreach($priceRangeArray as $priceRangeArrayVal)
	                            @if( $areaval['stadiumBlockId'] == $priceRangeArrayVal['stadium_block_id'])
	                              	<input type="hidden" data-price="{{ $priceRangeArrayVal['priceRange']}}" data-display-name="{{ $priceRangeArrayVal['display_name']}}" name="pricedetail[{{ $priceRangeArrayVal['stadium_block_id']}}]">
	                            @endif
	                        @endforeach
	        				<a data-toggle="popover" class="js-stadium-block" rel="popover" data-content-wrapper=".mycontent" data-trigger="click" id="{{ $areaval['stadiumBlockId'] }}" data-block-id="{{ $areaval['stadiumBlockId'] }}" data-block-name="{{ $areaval['stadiumBlockName'] }}"><area data-seat="{{isset($availableSeat[$areaval['stadiumBlockId']])?$availableSeat[$areaval['stadiumBlockId']]:0}}" shape="{{ $areaval['type'] }}" coords="{{ $areaval['coords'] }}" /></a>
	            		@endforeach
	                </map>
	            </div>
	        </div>
        @endif
        <div class="mycontent hide">
    	 	<div class="block-details">
			</div>
		</div>
    </div>
	<div id="blockseat"></div>
@endsection
