@extends('layouts.backend')

@section('plugin-styles')
	<link rel="stylesheet" href="{{ asset('plugins/summernote/summernote-bs4.css')}}">
@endsection

@section('plugin-scripts')
	<script src="{{ asset('plugins/ckeditor/ckeditor.js') }}"></script>
@endsection

@section('page-scripts')
	<script src="{{ asset('js/backend/pages/specialoffer/edit.js') }}"></script>
@endsection

@section('content')
	<div class="block">
		<div class="block-header block-header-default">
			<h3 class="block-title">Edit special offer</h3>
			<div class="block-options">
			</div>
		</div>
		<div class="block-content block-content-full">
			<div class="row">
				<div class="col-xl-12">
					<form class="edit-offer-form"
						  action="{{ route('backend.specialoffer.update', ['club' => app()->request->route('club'),'specialoffer' => $specialoffer]) }}"
						  method="post" enctype="multipart/form-data">
						{{ method_field('PUT') }}
						{{ csrf_field() }}
						<div class="row">

							<div class="col-xl-6">
								<div class="form-group{{ $errors->has('title') ? ' is-invalid' : '' }}">
									<label for="name" class="required">Title:</label>
									<input type="text" class="form-control" id="title" name="title"
										   value="{{ $specialoffer->title }}">
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
													   value="{{$status}}" {{  $specialoffer->status == $status ? 'checked' : '' }}>
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
												<input data-url="{{ route('backend.specialoffer.getTypewiseProduct', ['club' => app()->request->route('club')]) }}" class="custom-control-input" type="radio" name="type" id="type_{{$offerKey}}" value="{{$offerKey}}" {{  $specialoffer->type == $offerKey ? 'checked' : '' }}>
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
												   value="{{ $specialoffer->is_restricted_to_over_age  }}"
												   @if($specialoffer->is_restricted_to_over_age == 1) checked @endif>
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
                                                        	<input type="hidden" value="{{ isset($specialoffer->image_file_name) ? $specialoffer->image_file_name : '' }}" id="image_file_name" name="image_file_name">
                                                            <input type="file" class="form-control custom-file-input" id="image" name="image" data-toggle="custom-file-input" accept="image/*">
                                                            <label class="form-control custom-file-label text-truncate pr-100" for="image">{{ isset($specialoffer->image_file_name) ? $specialoffer->image_file_name : 'Choose file'}}</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div id="special_offer_image" class="ml-3 {{ $specialoffer->image ? '' : 'd-md-none' }}">
                                            	<div id="logo_preview_container">
	                                                <div class="logo_preview_container">
	                                                    <img name="image_preview" src="{{ $specialoffer->image }}" id="image_preview" alt="Image">
	                                                </div>
	                                            </div>
                                            </div>
                                        </div>
                                        <label class="helper m-0">Image dimensions: 840px X 630px (png only)</label>
                                    </div>
                                </div>
								{{-- <div class="form-group row">
									<div class="{{ $specialoffer->image ? 'col-9' : 'col-12' }} js-manage-logo-width">
										<label class="required">Image:</label>
										<div class="row">
											<div class="col-12">
												<div class="custom-file"> --}}
													<!-- Populating custom file input label with the selected filename (data-toggle="custom-file-input" is initialized in Codebase() -> uiHelperCoreCustomFileInput()) -->
													{{-- <input type="hidden" value="{{ isset($specialoffer->image_file_name) ? $specialoffer->image_file_name : '' }}" id="image_file_name" name="image_file_name">
													<input type="file" class="form control custom-file-input" id="image" name="image"
														   data-toggle="custom-file-input" accept="image/*">
													<label class="custom-file-label text-truncate pr-100" for="image">{{ isset($specialoffer->image_file_name) ? $specialoffer->image_file_name : 'Choose file'}}</label>
												</div>
												<label class="helper mt-5">Image dimensions: 840px X 630px ( png only )</label>
											</div>
										</div>
									</div>
									<div class="col-3 {{ $specialoffer->image ? '' : 'd-md-none' }}"
										 id="special_offer_image">
										<div class="d-flex justify-content-center" id="image_preview_container">
											<img id="image_preview" src="{{ $specialoffer->image }}" class="img-avatar img-avatar-square"
												 alt="Image">
										</div>
									</div>
								</div> --}}
							</div>

							<div class="col-xl-6">
								<div class="form-group">
									<label class="required">Available to fans:</label>
									<div>
										@if(in_array(config('fanslive.ALL_FANS_MEMBERSHIP_PACKAGE_ID'), $offerMembershipPackageList))
											@foreach($membershipPackageList as $packageListKey => $packageList)
												<div class="custom-control custom-checkbox custom-control-inline mb-5">
													<input class="custom-control-input custom-avail-fans-cls @if($packageListKey == config('fanslive.ALL_FANS_MEMBERSHIP_PACKAGE_ID')) default-package @else premium-package @endif" type="checkbox" name="packageList[]"
														   id="packageList_{{$packageListKey}}"
														   @if(in_array($packageListKey, $offerMembershipPackageList)  && ($packageListKey == config('fanslive.ALL_FANS_MEMBERSHIP_PACKAGE_ID'))) checked @else checked disabled @endif value="{{$packageListKey}}">
													<label class="custom-control-label"
														   for="packageList_{{$packageListKey}}">{{$packageList}}</label>
												</div>
											@endforeach()
										@else
											@foreach($membershipPackageList as $packageListKey => $packageList)
												<div class="custom-control custom-checkbox custom-control-inline mb-5">
													<input class="custom-control-input custom-avail-fans-cls @if($packageListKey == config('fanslive.ALL_FANS_MEMBERSHIP_PACKAGE_ID')) default-package @else premium-package @endif" type="checkbox" name="packageList[]"
														   id="packageList_{{$packageListKey}}"
														   @if(in_array($packageListKey, $offerMembershipPackageList)) checked
														   @endif value="{{$packageListKey}}">
													<label class="custom-control-label"
														   for="packageList_{{$packageListKey}}">{{$packageList}}</label>
												</div>
											@endforeach()
										@endif
									</div>
								</div>
							</div>

							<div class="col-xl-6">
								<div class="form-group" id="productsFilter">
									<label for="blocks" class="required">Products:</label>
									<select class="custom-select form-control answer-group" id="products"
											name="products[]" multiple size="5">
										@if ($productList)
											@foreach($productList as $productKey => $product)
												<option
													{{ in_array($productKey, $selectedProductList) ? "selected" : "" }} value="{{ $productKey }}" data-final-price="{{ $product['final_price'] }}">{{ $product['title'] }}</option>
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
												   value="fixed_amount" {{  $specialoffer->discount_type == 'fixed_amount' ? 'checked' : '' }}>
											<label class="custom-control-label"
												   for="fixed_amount">Fixed amount</label>
										</div>
										<div class="custom-control custom-radio custom-control-inline mb-5">

											<input class="custom-control-input" type="radio" name="discount_type"
												   id="percentage"
												   value="percentage" {{  $specialoffer->discount_type == 'percentage' ? 'checked' : '' }}>
											<label class="custom-control-label"
												   for="percentage">Percentage</label>
										</div>
									</div>
								</div>
							</div>

							<div class="col-xl-6 js-custom-option-div"
								 id="edit_custom_option">
								@foreach($selectedProductListEdit as $selectedProductKey => $productOption)
									<div class="block block-bordered block-default block-rounded js-home-main-div"
										 id="{{$productOption['product_id']}}">
										<div class="block-header block-header-default">
											<label for="discount_amount">{{ isset($productList[$productOption['product_id']]) ? $productList[$productOption['product_id']]['title'] : '' }}:</label>
										</div>
										<div class="block-content">
											<div class="row">
												<div class="col-xl-7">
													<div class="form-group">
														<label for="discount_amount" class="required">Discount:</label>
														<input type="text" min="0"
															   class="form-control custom-option-discount-cls"
															   data-final-price="{{ $productList[$productOption['product_id']]['final_price'] }}"
															   id="{{$productOption['product_id']}}"
															   name="discount_amount[{{$productOption['product_id']}}]" value="{{$productOption['discount_amount'] }}">
														<input type="hidden" name="product_id[{{$productOption['product_id']}}]"
															   value="{{$productOption['product_id']}}">
													</div>
												</div>
											</div>
										</div>
									</div>
								@endforeach
							</div>

							<div class="col-xl-12">
								<div class="form-group">
									<button type="submit" class="btn btn-hero btn-noborder btn-primary min-width-125">
										Update
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
