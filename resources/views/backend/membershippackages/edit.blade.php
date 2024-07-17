@extends('layouts.backend')

@section('plugin-styles')

@endsection

@section('plugin-scripts')
	<script src="{{ asset('plugins/ckeditor/ckeditor.js') }}"></script>
@endsection

@section('page-scripts')
	<script src="{{ asset('js/backend/pages/membershippackages/edit.js') }}"></script>
@endsection

@section('content')
	<div class="block">
		<div class="block-header block-header-default">
			<h3 class="block-title">Edit membership package</h3>
			<div class="block-options">
			</div>
		</div>
		<div class="block-content block-content-full">
			<div class="row">
				<div class="col-xl-12">
					<form class="edit-membership-package-form align-items-center"
						  action="{{ route('backend.membershippackages.update', ['club' => app()->request->route('club'), 'membershipPackage' => $membershipPackage]) }}"
						  method="post" enctype="multipart/form-data">
						{{ method_field('PUT') }}
						{{ csrf_field() }}

						<div class="row">
							<div class="col-xl-6">
								<div class="form-group{{ $errors->has('title') ? ' is-invalid' : '' }}">
									<label for="title" class="required">Name:</label>
									<input type="text" class="form-control" name="title"
										   value="{{$membershipPackage->title}}">
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
										   value="{{$membershipPackage->membership_duration}}" min="0">
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
										<input type="text" class="form-control" name="price"
											   value="{{$membershipPackage->price}}" min="0">
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
										   value="{{$membershipPackage->vat_rate}}" min="0">
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
										   value="{{$membershipPackage->rewards_percentage_override}}" min="0">
								</div>
							</div>

							<div class="col-xl-6">
								<div class="form-group {{ $membershipPackage->icon }}">
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
                                            </div>
                                            <div id="icon_image" class="{{ $membershipPackage->icon ? '' : 'd-md-none' }}">
                                                <div id="icon_preview_container" class="logo_preview_container ml-3">
                                                    <img src="{{ $membershipPackage->icon }}" id="icon_preview" name="icon_preview" alt="icon">
                                                </div>
                                            </div>
                                        </div>
                                        <label class="helper m-0">Image dimensions: 150px X 150px ( png only )</label>
                                    </div>
                                </div>

								{{-- <div class="form-group row">
									<div
										class="{{ $membershipPackage->icon ? 'col-9' : 'col-12' }} {{ $membershipPackage->icon }} js-manage-logo-width">
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
									<div class="col-3 {{ $membershipPackage->icon ? '' : 'd-md-none' }}"
										 id="icon_image">
										<div class="d-flex justify-content-center" id="icon_preview_container">
											<img id="icon_preview" name="icon_preview"
												 src="{{ $membershipPackage->icon }}"
												 class="img-avatar img-avatar-square" alt="icon">
										</div>
									</div>
								</div> --}}
							</div>

							<div class="col-xl-12">
								<div class="form-group">
									<label class="required">Status:</label>
									<div>
										@foreach($membershipPackageStatus as $key => $status)
											<div class="custom-control custom-radio custom-control-inline mb-5">
												<input class="custom-control-input" type="radio" name="status"
													   id="status_{{$key}}"
													   value="{{$status}}" {{ $status == $membershipPackage->status ? 'checked' : '' }}>
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
										<textarea id="js-ckeditor"
												  name="benefits">{{$membershipPackage->benefits}}</textarea>
									</div>
								</div>
							</div>

							<div class="col-xl-12">
								<div class="form-group">
									<button type="submit" class="btn btn-hero btn-noborder btn-primary min-width-125">
										Update
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
