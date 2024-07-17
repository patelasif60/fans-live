@extends('layouts.frontend')
@section('page-scripts')
	<script type="text/javascript" src='https://pay.paymentiq.io/cashier/cashier.js'></script>
	<script type="text/javascript" src="{{ asset('js/frontend/pages/payment/payment.js') . '?' . time() }}"></script>
@endsection
@section('content')
	<div id='cashier'></div>
@endsection
