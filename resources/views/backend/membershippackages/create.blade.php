@extends('layouts.backend')

@section('plugin-styles')

@endsection

@section('plugin-scripts')
	<script src="{{ asset('plugins/ckeditor/ckeditor.js') }}"></script>
@endsection

@section('page-scripts')
	<script src="{{ asset('js/backend/pages/membershippackages/create.js') }}"></script>
@endsection

@section('content')
	<div class="block">
		<div class="block-header block-header-default">
			<h3 class="block-title">Add membership package</h3>
			<div class="block-options">
			</div>
		</div>
		<div class="block-content block-content-full">
			<div class="row">
				<div class="col-xl-12">
					<form class="create-membership-package-form"
						  action="{{ route('backend.membershippackages.store', ['club' => app()->request->route('club')]) }}"
						  method="post" enctype="multipart/form-data">
						@csrf
						<div class="row">
							<div class="col-xl-6">
								<div class="form-group{{ $errors->has('title') ? ' is-invalid' : '' }}">
									<label for="title" class="required">Name:</label>
									<input type="text" class="form-control" name="title" value="{{ old('title') }}">
									@if ($errors->has('title'))
										<div class="invalid-feedback animated fadeInDown">
											<strong>{{ $errors->first('title') }}</strong>
										</div>
									@endif
								</div>
							</div>

							<div class="col-xl-6">
								<div class="form-group{{ $errors->has('membership_duration') ? ' is-invalid' : '' }}">
									<label for="membership_duration" class="required">Membership duration
										(months):</label>
									<input type="number" class="form-control" name="membership_duration"
										   value="{{ old('membership_duration') }}" min="0">
									@if ($errors->has('membership_duration'))
										<div class="invalid-feedback animated fadeInDown">
											<strong>{{ $errors->first('membership_duration') }}</strong>
										</div>
									@endif
								</div>
							</div>

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
									<input type="text" class="form-control" name="vat_rate"
										   value="{{ old('vat_rate') }}">
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
									<input type="text" class="form-control" name="rewards_percentage_override"
										   value="{{ old('rewards_percentage_override') }}" min="0">
								</div>
							</div>

							<div class="col-xl-6">
								<div class="form-group">
                                    <div class="logo-fields-wrapper">
                                        <div class="d-flex">
                                            <div class="logo-input flex-grow-1">
                                                <label class="required">Icon:</label>
                                                <div class="input-group">
                                                    <div class="custom-file">
                                                        <div>
                                                            <input type="file" class="form-control custom-file-input uploadimage" id="icon" name="icon" data-toggle="custom-file-input" accept="image/png">
                                                            <label class="form-control custom-file-label" for="icon">Choose file</label>
                                                        </div>
                                                    </div>
                                                </div>          
												@if ($errors->has('icon'))
													<div class="invalid-feedback animated fadeInDown">
														<strong>{{ $errors->first('icon') }}</strong>
													</div>
												@endif
                                            </div>
                                            <div id="icon_preview_container" class="d-md-none">
                                                <div class="logo_preview_container ml-3">
                                                    <img src="" id="icon_preview" name="icon_preview" alt="icon">
                                                </div>
                                            </div>
                                        </div>
                                        <label class="helper m-0">Image dimensions: 150px X 150px ( png only )</label>
                                    </div>
                                </div>
								{{-- <div class="form-group row">
									<div class="col-12 js-manage-logo-width">
										<label class="required">Icon:</label>
										<div class="row">
											<div class="col-12">
												<div class="custom-file">
													<input type="file" class="custom-file-input uploadimage" id="icon"
														   name="icon" data-toggle="custom-file-input"
														   accept="image/png">
													<label class="custom-file-label" for="icon">Choose file</label>
												</div>
												<label class="helper mt-5">Image dimensions: 150px X 150px ( png only
													)</label>
											</div>
										</div>
									</div>
									<div class="col-3">
										<div class="d-flex justify-content-center d-md-none"
											 id="icon_preview_container">
											<img src="" id="icon_preview" name="icon_preview"
												 class="img-avatar img-avatar-square mb-2 pull-right" alt="icon">
										</div>
									</div>
								</div>
								@if ($errors->has('icon'))
									<div class="invalid-feedback animated fadeInDown">
										<strong>{{ $errors->first('icon') }}</strong>
									</div>
								@endif --}}
							</div>
							<div class="col-xl-12">
								<div class="form-group">
									<label class="required">Status:</label>
									<div>
										@foreach($membershipPackageStatus as $key => $status)
											<div class="custom-control custom-radio custom-control-inline mb-5">
												<input class="custom-control-input" type="radio" name="status"
													   id="status_{{$key}}"
													   value="{{$status}}" {{ $key == 'published' ? 'checked' : '' }}>
												<label class="custom-control-label"
													   for="status_{{$key}}">{{$status}}</label>
											</div>
										@endforeach()
									</div>
								</div>
							</div>

							<div class="col-xl-6">
								<label for="js-ckeditor">Benefits:</label>
								<div class="form-group row">
									<div class="col-12">
										<textarea id="js-ckeditor" name="benefits"></textarea>
									</div>
								</div>
							</div>

							<div class="col-xl-12">
								<div class="form-group">
									<button type="submit" class="btn btn-hero btn-noborder btn-primary min-width-125">
										Create
									</button>
									<a href="{{ route('backend.membershippackages.index', ['club' => app()->request->route('club')]) }}"
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
