@extends('layouts.backend')

@section('page-scripts')
	<script src="{{ asset('js/backend/pages/settings/settings.js') }}"></script>
@endsection

@section('content')
	<div class="block">
		<div class="block-header block-header-default">
			<h3 class="block-title">Update settings</h3>
			<div class="block-options">
			</div>
		</div>
		<div class="block-content block-content-full">
			<div class="row">
				<div class="col-xl-12">
					<form class="update-transaction-settings-form" action="{{ route('backend.setting.update') }}" method="post"
						  enctype="multipart/form-data">
						{{ csrf_field() }}
						<div class="row">
							<div class="col-xl-6">
								<div
									class="form-group{{ $errors->has('minimum_card_fee_amount') ? ' is-invalid' : '' }}">
									<label for="minimum_card_fee_amount" class="required">Minimum card fee
										amount:</label>
									<input type="text" class="form-control" id="minimum_card_fee_amount"
										   name="minimum_card_fee_amount"
										   value="{{ $settings['minimum_card_fee_amount'] ? $settings['minimum_card_fee_amount'] : '' }}"
										   placeholder="Enter the minimum card fee amount that should be charged">
									@if ($errors->has('minimum_card_fee_amount'))
										<div class="invalid-feedback animated fadeInDown">
											<strong>{{ $errors->first('minimum_card_fee_amount') }}</strong>
										</div>
									@endif
								</div>
							</div>
							<div class="col-xl-6">
								<div class="form-group{{ $errors->has('card_fee_percentage') ? ' is-invalid' : '' }}">
									<label for="card_fee_percentage" class="required">Card fee percentage:</label>
									<div class="input-group">
										<input type="text" class="form-control" id="card_fee_percentage" name="card_fee_percentage"
											   value="{{ $settings['card_fee_percentage'] ? $settings['card_fee_percentage'] : '' }}"
											   placeholder="Enter the % card fee to apply to transactions">
										<div class="input-group-text">
											<i class="font-size-sm font-w600 text-uppercase text-muted" id="basic-addon2">%</i>
										</div>
									</div>
									@if ($errors->has('card_fee_percentage'))
										<div class="invalid-feedback animated fadeInDown">
											<strong>{{ $errors->first('card_fee_percentage') }}</strong>
										</div>
									@endif
								</div>
							</div>

							<div class="col-xl-6">
								<div class="form-group{{ $errors->has('bank_fee') ? ' is-invalid' : '' }}">
									<label for="bank_fee" class="required">Bank fee:</label>
									<input type="text" class="form-control" id="bank_fee" name="bank_fee"
										   value="{{ $settings['bank_fee'] ? $settings['bank_fee'] : ''}}"
										   placeholder="Enter the bank fee amount">
									@if ($errors->has('bank_fee'))
										<div class="invalid-feedback animated fadeInDown">
											<strong>{{ $errors->first('bank_fee') }}</strong>
										</div>
									@endif
								</div>
							</div>

							<div class="col-xl-6">
								<div class="form-group{{ $errors->has('footer_text_for_receipt') ? ' is-invalid' : '' }}">
									<label for="footer_text_for_receipt" class="required">Footer text for receipts:</label>
									<input type="text" class="form-control" id="footer_text_for_receipt" name="footer_text_for_receipt"
										   value="{{ $settings['footer_text_for_receipt'] ? $settings['footer_text_for_receipt'] : '' }}"
										   placeholder="Enter the minimum card fee amount that should be charged">
									@if ($errors->has('footer_text_for_receipt'))
										<div class="invalid-feedback animated fadeInDown">
											<strong>{{ $errors->first('footer_text_for_receipt') }}</strong>
										</div>
									@endif
								</div>
							</div>
							<div class="col-xl-6">
								<div class="form-group{{ $errors->has('max_transaction_amount') ? ' is-invalid' : '' }}">
									<label for="max_transaction_amount" class="required">Threshold transaction amount:</label>
									<input type="text" class="form-control" id="max_transaction_amount" name="max_transaction_amount"
										   value="{{ $settings['max_transaction_amount'] ? $settings['max_transaction_amount'] : ''  }}"
										   placeholder="Enter the amount that should be considered as high transaction.">
									@if ($errors->has('max_transaction_amount'))
										<div class="invalid-feedback animated fadeInDown">
											<strong>{{ $errors->first('max_transaction_amount') }}</strong>
										</div>
									@endif
								</div>
							</div>
							<div class="col-xl-6">
								<div class="form-group{{ $errors->has('threshold_transaction_minutes') ? ' is-invalid' : '' }}">
									<label for="threshold_transaction_minutes" class="required">Threshold transaction minutes:</label>
									<input type="text" class="form-control" id="threshold_transaction_minutes" name="threshold_transaction_minutes"
										   value="{{$settings['threshold_transaction_minutes'] ? $settings['threshold_transaction_minutes'] : '' }}"
										   placeholder="Enter the minimum minutes between two transactions.">
									@if ($errors->has('threshold_transaction_minutes'))
										<div class="invalid-feedback animated fadeInDown">
											<strong>{{ $errors->first('threshold_transaction_minutes') }}</strong>
										</div>
									@endif
								</div>
							</div>
							<div class="col-xl-12">
								<div class="form-group">
									<button type="submit" class="btn btn-hero btn-noborder btn-primary min-width-125">
										Update
									</button>
								</div>
							</div>
						</div>

					</form>
				</div>
			</div>
		</div>
	</div>
@endsection
