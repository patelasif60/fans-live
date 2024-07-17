@extends('layouts.backend')

@section('plugin-styles')
@endsection

@section('plugin-scripts')
	<script src="{{ asset('plugins/ckeditor/ckeditor.js') }}"></script>
	<script src="{{ asset('plugins/jquery-repeater/jquery.repeater.min.js') }}"></script>
@endsection

@section('page-scripts')
	<script src="{{ asset('js/backend/pages/hospitalitysuites/edit.js') }}"></script>
@endsection

@section('content')
	<div class="block">
		<div class="block-header block-header-default">
			<h3 class="block-title">Edit hospitality suite</h3>
			<div class="block-options">
			</div>
		</div>
		<div class="block-content block-content-full">
			<div class="row">
				<div class="col-xl-12">
					<form class="edit-hospitalitysuite-form repeater"
						  action="{{ route('backend.hospitalitysuite.update', ['club' => app()->request->route('club'), 'hospitalitySuites' => $hospitalitySuites]) }}"
						  method="post" enctype="multipart/form-data">
						{{ method_field('PUT') }}
						{{ csrf_field() }}
						<div class="row">
							<div class="col-xl-6">
								<div class="form-group{{ $errors->has('name') ? ' is-invalid' : '' }}">
									<label for="name" class="required">Name:</label>
									<input type="text" class="form-control" id="name" name="name"
										   value="{{ $hospitalitySuites->title }}">
									@if ($errors->has('name'))
										<div class="invalid-feedback animated fadeInDown">
											<strong>{{ $errors->first('name') }}</strong>
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
                                                        	<input type="hidden" value="{{ isset($hospitalitySuites->image_file_name) ? $hospitalitySuites->image_file_name : '' }}" id="image_file_name" name="image_file_name">
                                                            <input type="file" class="form-control custom-file-input uploadimage" id="image" name="image" data-toggle="custom-file-input" accept="image/*">
                                                            <label class="custom-file-label text-truncate pr-100" for="image">{{ $hospitalitySuites->image_file_name ? $hospitalitySuites->image_file_name  : 'Choose file'}}</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div id="image_preview_div" class="{{ $hospitalitySuites->image ? '' : 'd-md-none' }}">
                                                <div id="image_preview_container">
                                                    <div class="logo_preview_container ml-3">
                                                        <img id="image_preview" name="logo_preview" src="{{ $hospitalitySuites->image }}" alt="Image">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <label class="helper m-0">Image dimensions: 840px X 630px (png only)</label>
                                    </div>
                                </div>
							</div>
							<div class="col-xl-6">
								<div class="form-group{{ $errors->has('price') ? ' is-invalid' : '' }}">
									<label for="price" class="required">Price:</label>
									<div class="input-group">
										<div class="input-group-text">
											<i class="font-size-sm font-w600 text-uppercase text-muted">{{ $currencySymbol[$club->currency] }}</i>
										</div>
										<input type="text" class="form-control" id="price" name="price"
											   value="{{ $hospitalitySuites->price }}" min="0">
									</div>
									@if ($errors->has('price'))
										<div class="invalid-feedback animated fadeInDown">
											<strong>{{ $errors->first('price') }}</strong>
										</div>
									@endif
								</div>

								{{-- <div class="form-group row">
									<div
										class="{{ $hospitalitySuites->image ? 'col-9' : 'col-12' }} {{ $hospitalitySuites->image }} js-manage-logo-width">
										<label class="required">Image:</label>
										<div class="row">
											<div class="col-12">
												<div class="custom-file">
													<input type="hidden" value="{{ isset($hospitalitySuites->image_file_name) ? $hospitalitySuites->image_file_name : '' }}" id="image_file_name" name="image_file_name">
													<input type="file"
														   class="form-control custom-file-input uploadimage" id="image"
														   name="image" data-toggle="custom-file-input" accept="image/*">
													<label class="form-control custom-file-label text-truncate pr-100" for="image">{{ $hospitalitySuites->image_file_name ? $hospitalitySuites->image_file_name  : 'Choose file'}}</label>
												</div>
											</div>
										</div>
									</div>
									<div class="col-3 {{ $hospitalitySuites->image ? '' : 'd-md-none' }}" id="image_preview_div">
										<div class="d-flex justify-content-center" id="image_preview_container">
											<img id="image_preview" src="{{ $hospitalitySuites->image }}"
												 class="img-avatar img-avatar-square" alt="image">
										</div>
									</div>
								</div> --}}
							</div>
							<div class="col-xl-6">
								<div class="form-group{{ $errors->has('vat_rate') ? ' is-invalid' : '' }}">
									<label for="vat_rate" class="required">VAT (%):</label>
									<input type="text" class="form-control" name="vat_rate"
										   value="{{ $hospitalitySuites->vat_rate }}">
									@if ($errors->has('vat_rate'))
										<div class="invalid-feedback animated fadeInDown">
											<strong>{{ $errors->first('vat_rate') }}</strong>
										</div>
									@endif
								</div>
							</div>
							<div class="col-xl-6">
								<div class="tab-pane active" id="btabs-content" role="tabpanel">
									<div class="form-group{{ $errors->has('long_description') ? ' is-invalid' : '' }}">
										<label class="required" for="long_description">Long description:</label>
										<textarea id="js-ckeditor" name="long_description"
												  class="content_description jsckeditor">{{$hospitalitySuites->long_description}}</textarea>
									</div>
								</div>
							</div>
							<div class="col-xl-6">
								<div class="form-group{{ $errors->has('short_description') ? ' is-invalid' : '' }}">
									<label class="required" for="short_description">Short description:</label>
									<div>
										<textarea class="form-control" id="short_description" name="short_description"
												  rows="6">{{$hospitalitySuites->short_description}}</textarea>
									</div>
								</div>
								<div class="form-group">
									<div class="custom-control custom-checkbox mb-5">
										<input class="custom-control-input" type="checkbox" value="1" name="is_active"
											   id="is_active" {{ $hospitalitySuites->is_active == 1 ? 'checked' : ''}}>
										<label class="custom-control-label" for="is_active"></label>
										<label for="is_active">Is active?</label>
									</div>
								</div>
							</div>
							<div class="col-xl-6">
								<div class="form-group {{ $errors->has('number_of_seat') ? ' is-invalid' : '' }}">
									<label class="required">Number of seats:</label>
									<select class="js-select2 js-select2-allow-clear form-control" id="number_of_seat"
											name="number_of_seat">
										<option value="">Please select</option>
										@for ($i = 1; $i <= 40; $i++)
											<option
												{{ $hospitalitySuites->number_of_seat == $i ? 'selected':''}} value="{{ $i }}">{{ $i }}</option>
										@endfor()
									</select>
								</div>
							</div>
							<div class="col-xl-12">
								<div class="form-group row">
									<div class="col-xl-6">
										<div class="row align-items-center">
											<div class="col-8">
												<h5 class="mb-0">Dietary options</h5>
											</div>
											<div class="col-4 text-right">
												<button type="button" class="btn btn-primary btn-noborder"
														data-repeater-create>Add
												</button>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="col-xl-6" data-repeater-list="dietary_options">
								@if($hospitalityDietaryOptions!= null)
									@foreach($hospitalityDietaryOptions as $key=>$dietaryOptions)
										<div class="form-group" data-repeater-item>
											<label for="dietary_options" class="">Option:</label>
											<div class="input-group">
												<input type="text" class="form-control answer-group"
													   name="dietary_options"
													   value="{{ $dietaryOptions }}">
												<div class="input-group-append">
													<button type="button" class="btn btn-danger btn-noborder"
															data-repeater-delete><i class="fal fa-times"></i></button>
												</div>
											</div>
										</div>
									@endforeach
								@else
									<div class="form-group" data-repeater-item>
										<label for="dietary_options" class="">Option</label>
										<div class="input-group">
											<input type="text" class="form-control answer-group" name="dietary_options">
											<div class="input-group-append">
												<button type="button" class="btn btn-danger btn-noborder"
														data-repeater-delete><i class="fal fa-times"></i></button>
											</div>
										</div>
									</div>
								@endif
							</div>
							<div class="col-xl-12">
								<div class="form-group">
									<button type="submit" class="btn btn-hero btn-noborder btn-primary min-width-125 js-hospitalitysuite-update">
										Update
									</button>
									<a href="{{ route('backend.hospitalitysuite.index', ['club' => app()->request->route('club')]) }}"
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
