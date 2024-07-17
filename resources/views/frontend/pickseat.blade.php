<div class="block">
	<div class="block-header block-header-default">
        <h3 class="block-title">Select Seat</h3>
        <div class="block-options">
        	<button class="btn btn-sm btn-outline-primary h-100 pull-right js-backtoblock"><i class="fal fa-angle-left mr-5"></i>
                Back
            </button>
        </div>
    </div>
    <div class="block-content block-content-full">
    	<div class="row">
            <div class="col-xl-12">
            	@foreach($seatData as $seatDatakey=>$seatDataVal)
        			<div class="boxdiv">
        				{{ $seatDatakey }}
        			</div>
        			@foreach( $seatDataVal as $seatDataValue )
        				@if( isset($seatDataValue['pricing_band']) )
        					@foreach( $seatDataValue['pricing_band'] as $seatDataPricekey => $seatDataPriceValue )
            					<input type="hidden" data-pricing-band-id="{{ $seatDataPriceValue['pricingBandId'] }}" data-price="{{ $seatDataPriceValue['price'] }}" data-display-name="{{ $seatDataPriceValue['displayName'] }}" name="pricedetail[{{ $seatDataValue['id'] }}]">
        					@endforeach
    					@endif
    					<div data-block-id="{{ $requestData['blockId'] }}" data-block-name="{{ $requestData['blockName'] }}" data-type="{{ $seatDataValue['type'] }}" data-row="{{ $seatDatakey }}" data-seat="{{ $seatDataValue['seat'] }}" data-content-wrapper=".mycontent" data-trigger="click" rel="{{ $seatDataValue['type'] == 'seat' ? 'popover' : '' }}" id="{{ $seatDataValue['id'] }}" class =" boxdiv {{ $seatDataValue['class'] }}"> {{ $seatDataValue['sign'] }}</div>
					@endforeach
					<br/>
        			<br/>     		
        		@endforeach
            </div>
        </div>
    </div>
</div>
