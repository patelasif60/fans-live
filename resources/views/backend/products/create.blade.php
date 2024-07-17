@extends('layouts.backend')

@section('plugin-styles')
    <link rel="stylesheet" href="{{ asset('plugins/summernote/summernote-bs4.css')}}">
@endsection

@section('plugin-scripts')
    <script src="{{ asset('plugins/ckeditor/ckeditor.js') }}"></script>
@endsection

@section('page-scripts')
    <script src="{{ asset('js/backend/pages/products/create.js') }}"></script>
@endsection

@section('content')
	<div class="block">
		<form class="create-product-form repeater" id="add_product_form" action="{{ route('backend.product.store', ['club' => app()->request->route('club')]) }}" method="post" enctype="multipart/form-data">
		@csrf
			<div class="block-header block-header-default">
				<h3 class="block-title">Add product</h3>
			</div>

			<div class="block-content">
				<div class="block block-bordered">
					<div class="block-content">
						<ul class="nav nav-tabs nav-tabs-alt nav-tabs-block nav-justified" data-toggle="tabs" role="tablist">
			                <li class="nav-item">
			                    <a class="nav-link active" href="#btabs-settings">Settings</a>
			                </li>
			                <li class="nav-item">
			                    <a class="nav-link" href="#btabs-pricing">Pricing</a>
			                </li>
			                <li class="nav-item">
			                    <a class="nav-link" href="#btabs-categories">Categories</a>
			                </li>
			                <li class="nav-item">
			                    <a class="nav-link" href="#btabs-collection-points">Collection points</a>
			                </li>
			            </ul>
					</div>

					<div class="block-content tab-content">
						<div class="tab-pane active" id="btabs-settings" role="tabpanel">
							<div class="row">
								<div class="col-xl-6">
									<div class="form-group{{ $errors->has('title') ? ' is-invalid' : '' }}">
										<label for="title" class="required">Title:</label>
										<input type="text" class="form-control" id="title" name="title" value="{{ old('title') }}">
										@if ($errors->has('title'))
											<div class="invalid-feedback animated fadeInDown">
												<strong>{{ $errors->first('title') }}</strong>
											</div>
										@endif
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
	                                                            <input type="file" class="form-control custom-file-input" id="logo" name="logo" data-toggle="custom-file-input" accept="image/*">
	                                                            <label class="form-control custom-file-label" for="logo">Choose file</label>
	                                                        </div>
	                                                    </div>
	                                                </div>
	                                            </div>
	                                            <div id="logo_preview_container" class="d-md-none">
	                                                <div class="logo_preview_container ml-3">
	                                                    <img src="" id="logo_preview" alt="Product logo">
	                                                </div>
	                                            </div>
	                                        </div>
	                                        <label class="helper m-0">Image dimensions: 840px X 630px (png only)</label>
	                                    </div>
	                                </div>
								</div>

								<div class="col-xl-6">
									<div class="form-group{{ $errors->has('short_description') ? ' is-invalid' : '' }}">
										<label for="title" class="required">Short description:</label>
										<input type="text" class="form-control" id="short_description" name="short_description" value="{{ old('short_description') }}">
										@if ($errors->has('title'))
											<div class="invalid-feedback animated fadeInDown">
												<strong>{{ $errors->first('short_description') }}</strong>
											</div>
										@endif
									</div>
								</div>

								<div class="col-xl-6">
									<div class="form-group">
										<label class="required">Status:</label>
										<div>
											@foreach($productStatus as $productStatusKey => $status)
												<div class="custom-control custom-radio custom-control-inline mb-5">
													<input class="custom-control-input" type="radio" name="status" id="status_{{$productStatusKey}}" value="{{$status}}" {{ $productStatusKey == 'published' ? 'checked': '' }}>
													<label class="custom-control-label" for="status_{{$productStatusKey}}">{{$status}}</label>
												</div>
											@endforeach()
										</div>
									</div>
								</div>

								<div class="col-xl-6">
									<div class="form-group {{ $errors->has('description') ? ' is-invalid' : '' }}">
										<label for="description" class="required">Description:</label>
										<div class="row">
											<div class="col-12">
												<textarea  id="js-ckeditor" name="description"></textarea>
											</div>
											@if ($errors->has('description'))
												<div class="invalid-feedback animated fadeInDown">
													<strong>{{ $errors->first('description') }}</strong>
												</div>
											@endif
										</div>
									</div>
								</div>

								<div class="col-xl-6">
									<div class="form-group">
										<label>Restrictions:</label>
										<div>
											<div class="custom-control custom-checkbox custom-control-inline mb-5">
												<input type="checkbox" class="custom-control-input" id="is_restricted_to_over_age" name="is_restricted_to_over_age">
												<label class="custom-control-label" for="is_restricted_to_over_age">Restricted to over 18s</label>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>

						<div class="tab-pane" id="btabs-pricing" role="tabpanel">
							<div class="row">
								<div class="col-xl-6">
									<div class="form-group{{ $errors->has('price') ? ' is-invalid' : '' }}">
										<label for="price" class="required">Base price:</label>
										<div class="input-group">
											<div class="input-group-text">
												<i class="font-size-sm font-w600 text-uppercase text-muted">{{ $currencySymbol[$club->currency] }}</i>
											</div>
											<input type="text" class="form-control" name="price" value="{{ old('price') }}">
										</div>

										@if ($errors->has('price'))
											<div class="invalid-feedback animated fadeInDown">
												<strong>{{ $errors->first('price') }}</strong>
											</div>
										@endif
									</div>
								</div>

								<div class="col-xl-6">
									<div class="form-group{{ $errors->has('vat_rate') ? ' is-invalid' : '' }}">
										<label for="vat_rate" class="required">VAT (%):</label>
										<input type="text" class="form-control" name="vat_rate" value="{{ old('vat_rate') }}">
										@if ($errors->has('vat_rate'))
											<div class="invalid-feedback animated fadeInDown">
												<strong>{{ $errors->first('vat_rate') }}</strong>
											</div>
										@endif
									</div>
								</div>

								<div class="col-xl-6">
									<div class="form-group">
										<label for="rewards_percentage_override">Rewards percentage override:</label>
										<input type="text" class="form-control" name="rewards_percentage_override" value="{{ old('rewards_percentage_override') }}" min="0">
									</div>
								</div>
							</div>

							<!--Custom Option Html-->
							<div class="row">
								<div class="col-xl-12">
									<div class="content-heading">
										<div class="d-flex align-items-center justify-content-between">
											<h5 class="mb-0">Options</h5>
										</div>
									</div>
								</div>
							</div>

							<div class="row js-custom-option-div" id="add_custom_option"></div>

							<div class="row js-line-ups-detail-div">
								<div class="col-xl-4">
									<div class="form-group">
										<button type="button" class="btn btn-block btn-noborder btn-primary js-added-home js-custom-option-btn">Add option</span></button>
									</div>
								</div>
							</div>
							<!--End - Custom option Html-->

						</div>

						<div class="tab-pane" id="btabs-categories" role="tabpanel">
							<div class="row">
								@foreach($categoryTypes as $categoryTypeKey => $categoryType)
									<div class="col-xl-6">
										<div class="form-group">
											<label>{{ $categoryType }}:</label>
											@if(isset($categories[$categoryTypeKey]))
												@foreach($categories[$categoryTypeKey] as $categoryKey => $category)
													<div>
														<div class="custom-control custom-checkbox custom-control-inline mb-5">
															<input type="checkbox" class="custom-control-input" id="category_{{$category['id'] }}" name="category[{{$category['id'] }}]">
															<label class="custom-control-label" for="category_{{ $category['id'] }}">{{ $category['title'] }}</label>
														</div>
													</div>
												@endforeach
											@endif
										</div>
									</div>
								@endforeach
							</div>
						</div>

						<div class="tab-pane" id="btabs-collection-points" role="tabpanel">
							<div class="row">
								<div class="col-xl-12">
									<div class="form-group">
										@foreach($collectionPoints as $collectionPointsKey => $collectionPointsValue)
												<div>
													<div class="custom-control custom-checkbox custom-control-inline mb-5 ">
														<input disabled checked type="checkbox" class="custom-control-input" id="collectionPoints_{{$collectionPointsKey}}" name="collectionPoints[{{$collectionPointsKey}}]">
														<label class="custom-control-label" for="collectionPoints_{{$collectionPointsKey}}">{{$collectionPointsValue}}</label>
													</div>
												</div>
										@endforeach
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>

				<div class="row">
					<div class="col-xl-12">
						<div class="form-group">
							<button type="submit" id="btn_product_submit" class="btn btn-hero btn-noborder btn-primary min-width-125">Create
							</button>
							<a href="{{ route('backend.product.index', ['club' => app()->request->route('club')]) }}" class="btn btn-hero btn-noborder btn-alt-secondary">
								Cancel
							</a>
						</div>
					</div>
				</div>
			</div>
		</form>
	</div>
@endsection

