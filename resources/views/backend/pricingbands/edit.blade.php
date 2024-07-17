@extends('layouts.backend')

@section('plugin-scripts')
@endsection

@section('page-scripts')
	<script src="{{ asset('js/backend/pages/pricingbands/edit.js') }}"></script>
@endsection

@section('content')
	<div class="block">
		<div class="block-header block-header-default">
			<h3 class="block-title">Edit pricing band</h3>
			<div class="block-options">
			</div>
		</div>
		<div class="block-content block-content-full">
			<div class="row">
				<div class="col-xl-12">
					<form class="edit-pricing-band-form align-items-center"
						  action="{{ route('backend.pricingbands.update', ['club' => app()->request->route('club'), 'pricingBand' => $pricingBand]) }}"
						  method="post" enctype="multipart/form-data">
						{{ method_field('PUT') }}
						{{ csrf_field() }}

						<div class="row">
							<div class="col-xl-6">
								<div class="form-group{{ $errors->has('display_name') ? ' is-invalid' : '' }}">
									<label for="title" class="required">Display name:</label>
									<input type="text" class="form-control" name="display_name"
										   value="{{$pricingBand->display_name}}">
									@if ($errors->has('display_name'))
										<div class="invalid-feedback animated fadeInDown">
											<strong>{{ $errors->first('display_name') }}</strong>
										</div>
									@endif
								</div>
							</div>

							<div class="col-xl-6">
								<div class="form-group{{ $errors->has('internal_name') ? ' is-invalid' : '' }}">
									<label for="title" class="required">Internal name:</label>
									<input type="text" class="form-control" name="internal_name"
										   value="{{$pricingBand->internal_name}}">
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
										<input type="text" class="form-control" name="price"
											   value="{{$pricingBand->price}}" min="0">
									</div>
									@if ($errors->has('price'))
										<div class="invalid-feedback animated fadeInDown">
											<strong>{{ $errors->first('price') }}</strong>
										</div>
									@endif
								</div>
							</div>
							<input type="hidden" name="seatValidation" id="seatValidation" value="{{isset($clubDetail->stadium->is_using_allocated_seating)? $clubDetail->stadium->is_using_allocated_seating : 0}}">
							@if(isset($clubDetail->stadium->is_using_allocated_seating) && $clubDetail->stadium->is_using_allocated_seating == 1 )
							<div class="col-xl-6">
								<div class="form-group">
                                    <div class="logo-fields-wrapper">
                                        <label class="required">Seats:</label>
                                        <div class="d-flex align-items-center">
                                            <div class="logo-input flex-grow-1">
                                                <div class="input-group">
                                                    <div class="custom-file">
                                                        <div>
                                                        	<input type="hidden" value="{{ $pricingBand->seat_file_name }}" id="seat_file_name" name="seat_file_name">
                                                            <input data-url="{{route('backend.pricingbands.validateSeatData', ['club' => app()->request->route('club')])}}" type="file" class="form-control custom-file-input uploadPricingBandSeatFile" id="seat" name="seat" data-toggle="custom-file-input">
                                                            <label class="js-label-change custom-file-label text-truncate pr-100" for="seat">{{ $pricingBand->seat_file_name ? $pricingBand->seat_file_name  : 'Choose file'}}</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="{{ $pricingBand->seat ? '' : 'd-none' }} d-flex align-items-center ml-3" id ="seat_preview">
                                                <div id="seating_plan_preview_container">
                                                    <a download href="{{ $pricingBand->seat }}"
												   v-if='{{ $pricingBand->seat }}'>Download</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
								{{-- <div class="form-group">
									<label class="required">Seats:</label>
									<div class="row align-items-center">
										<div class="{{ $pricingBand->seat ? 'col-9' : 'col-12' }}">
											<div class="custom-file">
												<input data-url="{{route('backend.pricingbands.validateSeatData', ['club' => app()->request->route('club')])}}" type="file"
													   class="form-control custom-file-input uploadPricingBandSeatFile"
													   id="seat" name="seat" data-toggle="custom-file-input">
												<input type="hidden" value="{{ $pricingBand->seat_file_name }}" id="seat_file_name" name="seat_file_name">
												<label class="form-control custom-file-label"
													   for="seat">{{ $pricingBand->seat_file_name ? $pricingBand->seat_file_name  : 'Choose file'}}
											</div>
										</div>
										<div class="col-3 {{ $pricingBand->seat ? '' : 'd-none' }}" id="seat_preview">
											<div class="d-flex justify-content-center"
												 id="seating_plan_preview_container">
												<a download href="{{ $pricingBand->seat }}"
												   v-if='{{ $pricingBand->seat }}'>Download</a>
											</div>
										</div>
									</div>
									</label>
								</div> --}}
							</div>
							@endif
							<div class="col-xl-6">
								<div class="form-group {{ $errors->has('vat_Rate') ? ' is-invalid' : '' }}">
									<label for="vat_Rate" class="required">VAT (%):</label>
									<input type="text" class="form-control" id="vat_Rate" name="vat_Rate" min="0"
										   value="{{ $pricingBand->vat_rate }}">
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
											   id="is_active" {{ $pricingBand->is_active == 1 ? 'checked' : ''}}>
										<label class="custom-control-label" for="is_active"></label>
										<label for="is_active">Is active?</label>
									</div>
								</div>
							</div>

							<div class="col-xl-12">
								<div class="form-group">
									<button type="submit" class="btn btn-hero btn-noborder btn-primary min-width-125">
										Update
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
