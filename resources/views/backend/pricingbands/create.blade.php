@extends('layouts.backend')

@section('plugin-scripts')
@endsection

@section('page-scripts')
	<script src="{{ asset('js/backend/pages/pricingbands/create.js') }}"></script>
@endsection

@section('content')
	<div class="block">
		<div class="block-header block-header-default">
			<h3 class="block-title">Add pricing band</h3>
			<div class="block-options">
			</div>
		</div>
		<div class="block-content block-content-full">
			<div class="row">
				<div class="col-xl-12">
					<form class="create-pricing-band-form"
						  action="{{ route('backend.pricingbands.store', ['club' => app()->request->route('club')]) }}"
						  method="post" enctype="multipart/form-data">
						@csrf
						<div class="row">
							<div class="col-xl-6">
								<div class="form-group{{ $errors->has('display_name') ? ' is-invalid' : '' }}">
									<label for="display_name" class="required">Display name:</label>
									<input type="text" class="form-control" name="display_name"
										   value="{{ old('display_name') }}">
									@if ($errors->has('display_name'))
										<div class="invalid-feedback animated fadeInDown">
											<strong>{{ $errors->first('display_name') }}</strong>
										</div>
									@endif
								</div>
							</div>

							<div class="col-xl-6">
								<div class="form-group{{ $errors->has('internal_name') ? ' is-invalid' : '' }}">
									<label for="internal_name" class="required">Internal name:</label>
									<input type="text" class="form-control" name="internal_name"
										   value="{{ old('internal_name') }}">
									@if ($errors->has('internal_name'))
										<div class="invalid-feedback animated fadeInDown">
											<strong>{{ $errors->first('internal_name') }}</strong>
										</div>
									@endif
								</div>
							</div>

							<div class="col-xl-6">
								<div class="form-group{{ $errors->has('price') ? ' is-invalid' : '' }}">
									<label for="price" class="required">Price:</label>
									<div class="input-group">
										<div class="input-group-text">
											<i class="font-size-sm font-w600 text-uppercase text-muted">{{ $currencySymbol[$club->currency] }}</i>
										</div>
										<input type="text" class="form-control" name="price" value="{{ old('price') }}"
											   min="0">
									</div>

									@if ($errors->has('price'))
										<div class="invalid-feedback animated fadeInDown">
											<strong>{{ $errors->first('price') }}</strong>
										</div>
									@endif
								</div>
							</div>
							<input type="hidden" name="seatValidation" id="seatValidation" value="{{isset($clubDetail->stadium->is_using_allocated_seating) ? $clubDetail->stadium->is_using_allocated_seating : 0}}">
							@if(isset($clubDetail->stadium->is_using_allocated_seating) && $clubDetail->stadium->is_using_allocated_seating == 1 )
							<div class="col-xl-6">
								<div class="form-group {{ $errors->has('seat') ? ' is-invalid' : '' }}">
                                    <div class="logo-fields-wrapper">
                                        <label class="required">Seats:</label>
                                        <div class="d-flex align-items-center">
                                            <div class="logo-input flex-grow-1">
                                                <div class="input-group">
                                                    <div class="custom-file">
                                                        <div>
                                                            <input type="file" class="form-control custom-file-input uploadPricingBandSeatFile" id="seat" name="seat" data-toggle="custom-file-input">
                                                            @if ($errors->has('seat'))
																<div class="invalid-feedback animated fadeInDown">
																	<strong>{{ $errors->first('seat') }}</strong>
																</div>
															@endif
                                                            <label class="js-label-change custom-file-label text-truncate pr-100" for="seat">Choose file</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="d-md-none align-items-center ml-3" id="seat_preview_container"></div>
                                        </div>
                                    </div>
                                </div>
								{{-- <div class="form-group{{ $errors->has('seat') ? ' is-invalid' : '' }}">
									<label class="required">Seats:</label>
									<div class="row align-items-center">
										<div class="col-12 js-manage-file-width">
											<div class="custom-file">
												<input type="file" class="custom-file-input uploadPricingBandSeatFile"
													   id="seat" name="seat" data-toggle="custom-file-input">
												@if ($errors->has('seat'))
													<div class="invalid-feedback animated fadeInDown">
														<strong>{{ $errors->first('seat') }}</strong>
													</div>
												@endif
	                                            <label class="js-label-change custom-file-label" for="seat">Choose file</label>
	                                        </div>
	                                    </div>
	                                    <div class="col-3">
	                                        <div class="d-flex justify-content-center d-md-none" id="seat_preview_container"></div>
	                                    </div>
	                                </div>
	                            </div> --}}
	                        </div>
	                        @endif
	                        <div class="col-xl-6">
								<div class="form-group {{ $errors->has('vat_Rate') ? ' is-invalid' : '' }}">
									<label for="vat_Rate" class="required">VAT (%):</label>
									<input type="text" class="form-control" id="vat_Rate" name="vat_Rate" min="0">
									@if ($errors->has('vat_Rate'))
										<div class="invalid-feedback animated fadeInDown">
											<strong>{{ $errors->first('vat_Rate') }}</strong>
										</div>
									@endif
								</div>
							</div>

							<div class="col-xl-6">
								<div class="form-group">
									<div class="custom-control custom-checkbox mb-5">
										<input class="custom-control-input" type="checkbox" value="1" name="is_active"
											   id="is_active">
										<label class="custom-control-label" for="is_active"></label>
										<label for="is_active">Is active?</label>
									</div>
								</div>
							</div>

							<div class="col-xl-12">
								<div class="form-group">
									<button type="submit" class="btn btn-hero btn-noborder btn-primary min-width-125">
										Create
									</button>
									<a href="{{ route('backend.pricingbands.index', ['club' => app()->request->route('club')]) }}"
									   class="btn btn-hero btn-noborder btn-alt-secondary">
										Cancel
									</a>
								</div>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
@endsection
