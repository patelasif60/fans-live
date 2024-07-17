@extends('layouts.backend')

@section('plugin-styles')
	<link rel="stylesheet" href="{{ asset('plugins/summernote/summernote-bs4.css')}}">

@endsection

@section('plugin-scripts')
	<script src="{{ asset('plugins/ckeditor/ckeditor.js') }}"></script>
@endsection

@section('page-scripts')
	<script src="{{ asset('js/backend/pages/specialoffer/create.js') }}"></script>
@endsection

@section('content')
	<div class="block">
		<div class="block-header block-header-default">
			<h3 class="block-title">Add special offer</h3>
			<div class="block-options">
			</div>
		</div>
		<div class="block-content block-content-full">
			<div class="row">
				<div class="col-xl-12">
					<form class="create-offer-form"
						  action="{{ route('backend.specialoffer.store', ['club' => app()->request->route('club')]) }}"
						  method="post" enctype="multipart/form-data">
						@csrf
						<div class="row">

							<div class="col-xl-6">
								<div class="form-group{{ $errors->has('title') ? ' is-invalid' : '' }}">
									<label for="name" class="required">Title:</label>
									<input type="text" class="form-control" id="title" name="title"
										   value="{{ old('title') }}">
									@if ($errors->has('title'))
										<div class="invalid-feedback animated fadeInDown">
											<strong>{{ $errors->first('title') }}</strong>
										</div>
									@endif
								</div>
							</div>

							<div class="col-xl-6">
								<div class="form-group">
									<label class="required">Status:</label>
									<div>
										@foreach($specialOfferStatus as $statusKey => $status)
											<div class="custom-control custom-radio custom-control-inline mb-5">
												<input class="custom-control-input" type="radio" name="status"
													   id="status_{{$statusKey}}"
													   value="{{$status}}" {{ $statusKey == 'published' ? 'checked': '' }}>
												<label class="custom-control-label"
													   for="status_{{$statusKey}}">{{$status}}</label>
											</div>
										@endforeach()
									</div>
								</div>
							</div>

							<div class="col-xl-6">
								<div class="form-group">
									<label class="required">Type:</label>
									<div>
										@foreach($specialOfferType as $offerKey => $offerStatus)
											<div class="custom-control custom-radio custom-control-inline mb-5">
												<input class="custom-control-input" type="radio" name="type"
													   id="type_{{$offerKey}}"
													   value="{{$offerKey}}" {{ $offerKey == 'food_and_drink' ? 'checked': '' }}>
												<label class="custom-control-label"
													   for="type_{{$offerKey}}">{{$offerStatus}}</label>
											</div>
										@endforeach()
									</div>
								</div>
							</div>

							<div class="col-xl-6">
								<div class="form-group">
									<label>Restrictions:</label>
									<div>
										<div class="custom-control custom-checkbox custom-control-inline mb-5">
											<input type="checkbox" class="custom-control-input"
												   id="is_restricted_to_over_age" name="is_restricted_to_over_age"
												   value="0">
											<label class="custom-control-label" for="is_restricted_to_over_age">Restricted
												to over 18s</label>
										</div>
									</div>
								</div>
							</div>

							<div class="col-xl-6">
								<div class="form-group">
                                    <div class="logo-fields-wrapper">
                                        <div class="d-flex">
                                            <div class="logo-input flex-grow-1">
                                                <label class="required">Image:</label>
                                                <div class="input-group">
                                                    <div class="custom-file">
                                                        <div>
                                                            <input type="file" class="form-control custom-file-input" id="image" name="image" data-toggle="custom-file-input" accept="image/png">
                                                            <label class="form-control custom-file-label" for="image">Choose file</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                @if ($errors->has('image'))
													<div class="invalid-feedback animated fadeInDown">
														<strong>{{ $errors->first('image') }}</strong>
													</div>
												@endif
                                            </div>
                                            <div id="image_preview_container" class="ml-3 d-md-none">
                                            	<div id="logo_preview_container">
	                                                <div class="logo_preview_container">
	                                                    <img src="" id="image_preview" alt="Image">
	                                                </div>
	                                            </div>
                                            </div>
                                        </div>
                                        <label class="helper m-0">Image dimensions: 840px X 630px (png only)</label>
                                    </div>
                                </div>
							</div>
							<div class="col-xl-6">
								<div class="form-group">
									<label class="required">Available to fans:</label>
									<div>
										@foreach($membershipPackageList as $packageListKey => $packageList)
											<div class="custom-control custom-checkbox custom-control-inline mb-5">
												<input class="custom-control-input custom-avail-fans-cls @if($packageListKey == config('fanslive.ALL_FANS_MEMBERSHIP_PACKAGE_ID')) default-package @else premium-package @endif" type="checkbox" name="packageList[]"
													   id="packageList_{{$packageListKey}}" value="{{$packageListKey}}">
												<label class="custom-control-label"
													   for="packageList_{{$packageListKey}}">{{$packageList}}</label>
											</div>
										@endforeach()

									</div>
								</div>
							</div>


							<div class="col-xl-6">
								<div class="form-group" id="productsFilter">
									<label for="blocks" class="required">Products:</label>
									<select class="custom-select form-control answer-group" id="products"
											name="products[]" multiple size="5">
									 	@if($productList)
											@foreach($productList as  $productkey => $productval)
												<option data-final-price="{{ $productval['final_price'] }}" value="{{ $productkey }}">{{ $productval['title'] }}</option>
											@endforeach
										@endif
									</select>
								</div>
							</div>

							<div class="col-xl-6">
								<div class="form-group">
									<label class="required">Discount type:</label>
									<div>
										<div class="custom-control custom-radio custom-control-inline mb-5">
											<input class="custom-control-input" type="radio" name="discount_type"
												   id="fixed_amount"
												   value="fixed_amount" checked="checked">
											<label class="custom-control-label"
												   for="fixed_amount">Fixed amount</label>
										</div>
										<div class="custom-control custom-radio custom-control-inline mb-5">

											<input class="custom-control-input" type="radio" name="discount_type"
												   id="percentage"
												   value="percentage">
											<label class="custom-control-label"
												   for="percentage">Percentage</label>
										</div>
									</div>
								</div>
							</div>

							<div class="col-xl-6 js-custom-option-div" id="add_custom_option"></div>

							<div class="col-xl-12">
								<div class="form-group">
									<button type="submit" class="btn btn-hero btn-noborder btn-primary min-width-125">
										Create
									</button>
									<a href="{{ route('backend.specialoffer.index', ['club' => app()->request->route('club')])}}"
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
