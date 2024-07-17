<div class="screen-wrapper">
	<button class="js-backtoblock" value="Back">Back</button>
    <ul class="seat-area" style="position: relative;">
        @foreach($seatData as $seatDatakey=>$seatDataVal)
            <li class="seat-line">
                <span>{{ $seatDatakey }}</span>
                <ul>
                    @foreach( $seatDataVal as $seatDataValue )
                        @if( isset($seatDataValue['pricing_band']) )
                            @foreach( $seatDataValue['pricing_band'] as $seatDataPricekey => $seatDataPriceValue )
                                <input type="hidden" data-pricing-band-id="{{ $seatDataPriceValue['pricingBandId'] }}" data-price="{{ $seatDataPriceValue['price'] }}" data-display-name="{{ $seatDataPriceValue['displayName'] }}" name="pricedetail[{{ $seatDataValue['id'] }}]">
                            @endforeach
                        @endif
                        <li data-block-id="{{ $requestData['blockId'] }}" data-block-name="{{ $requestData['blockName'] }}" data-type="{{ $seatDataValue['type'] }}" data-row="{{ $seatDatakey }}" data-seat="{{ $seatDataValue['seat'] }}" data-content-wrapper=".mycontent" data-trigger="click" rel="{{ ($seatDataValue['class'] == 'is-available' || $seatDataValue['class'] == 'is-picked') ? 'popover' : '' }}" data-toggle="{{ ($seatDataValue['class'] == 'is-available' || $seatDataValue['class'] == 'is-picked') ? 'popover' : '' }}" id="{{ $seatDataValue['id'] }}"
                        class="single-seat js-block-seat {{ $seatDataValue['class'] }}">
                            @if($seatDataValue['class']=='is-disabled')
                                @include('partials.frontend.matchticketbooking.bookedseat')
                            @elseif($seatDataValue['class']=='is-stairs')
                                @include('partials.frontend.matchticketbooking.stairs')
                            @else
                                <div class="seat-block"></div>
                            @endif
                        </li>
                    @endforeach
                </ul>
            </li>
        @endforeach
    </ul>
</div>
