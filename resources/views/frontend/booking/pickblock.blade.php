@extends('layouts.frontend')
@section('page-scripts')
    <script type="text/javascript" src="{{ asset('js/frontend/pages/stadiumblocks/pickblock.js') . '?' . time() }}"></script>
@endsection
@section('content')
    <div id="pickblock">
        <div class="stadium-image-wrapper">
            <img src="https://fanslive-dev.s3.amazonaws.com/stadium_general_setting/Stadium block_1603948285.png" usemap="#pickblock" class="stadium-img">
        </div>

        {{-- <input type="button" value="testing" class="js-call-android">
        <input type="hidden" id="totalseat" value="2">
        <img src="https://fanslive-dev.s3.amazonaws.com/stadium_general_setting/Stadium block_1603948285.png" usemap="#pickblock">
        <map name="pickblock">
            <input type="hidden" data-price="£20.00 - £20.00" data-display-name="Adult" name="pricedetail[1]">
            <input type="hidden" data-price="£8.00 - £8.00" data-display-name="Child" name="pricedetail[1]">
            <input type="hidden" data-price="£10.00 - £10.00" data-display-name="test band" name="pricedetail[1]">
            <a rel="popover" data-content-wrapper=".mycontent" data-trigger="click" id="1" data-block-id="1" data-original-title="" title="">
                <area data-seat="342" class="" shape="circle" coords="166,213,30" data-row="W1H">
            </a>
            <input type="hidden" data-price="£20.00 - £20.00" data-display-name="Adult" name="pricedetail[4]">
            <a rel="popover" data-content-wrapper=".mycontent" data-trigger="click" id="4" data-block-id="4" data-original-title="" title="">
                <area data-seat="344" class="" shape="rect" coords="173,85,214,126" data-row="test">
            </a>
            <a rel="popover" data-content-wrapper=".mycontent" data-trigger="click" id="6" data-block-id="6" data-original-title="" title="">
                <area data-seat="0" class="" shape="circle" coords="315,191,27" data-row="test123">
            </a>
            <a rel="popover" data-content-wrapper=".mycontent" data-trigger="click" id="7" data-block-id="7" data-original-title="" title="">
                <area data-seat="0" class="" shape="poly" coords="368,85,412,85,413,129,367,127" data-row="E1F">
            </a>
            <a rel="popover" data-content-wrapper=".mycontent" data-trigger="click" id="9" data-block-id="9" data-original-title="" title="">
                <area data-seat="344" class="" shape="rect" coords="46,158,114,287" data-row="W1H">
            </a>
            <a rel="popover" data-content-wrapper=".mycontent" data-trigger="click" id="10" data-block-id="10" data-original-title="" title="">
                <area data-seat="344" class="" shape="rect" coords="520,157,589,285" data-row="W1H">
            </a>
            <a rel="popover" data-content-wrapper=".mycontent" data-trigger="click" id="11" data-block-id="11" data-original-title="" title="" aria-describedby="popover204786" style="position: absolute; top: 19px; left: 275px;">
                <area data-seat="344" class="" shape="poly" coords="275,19,310,18,313,68,271,63" data-row="test">
            </a>
        </map> --}}
        {{-- <div class="mycontent hide">
            <div class="block-details">
            </div>
        </div> --}}
    </div>
    <div id="blockseat">
        @include('frontend.booking.pickseat')
    </div>
@endsection