@extends('layouts.backend')

@section('plugin-styles')
	<link rel="stylesheet" href="{{ asset('plugins/bootstrap-colorpicker/css/bootstrap-colorpicker.min.css') }}">
@endsection

@section('plugin-scripts')
	<script src="{{ asset('plugins/ckeditor/ckeditor.js') }}"></script>
	<script src="{{ asset('plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.min.js') }}"></script>
@endsection

@section('page-scripts')
	<script src="{{ asset('js/backend/pages/traveloffers/edit.js') }}"></script>
@endsection

@section('content')
	<div class="block">
		<div class="block-header block-header-default">
			<h3 class="block-title">Edit travel offer</h3>
			<div class="block-options">
			</div>
		</div>
		<div class="block-content block-content-full">
			<div class="row">
				<div class="col-xl-12">
					<form class="edit-traveloffers-form"
						  action="{{ route('backend.traveloffers.update', ['club' => app()->request->route('club'), 'travelOffers' => $travelOffers]) }}"
						  method="post" enctype="multipart/form-data">
						{{ method_field('PUT') }}
						{{ csrf_field() }}
						<div class="row">
							<div class="col-xl-6">
								<div class="form-group{{ $errors->has('name') ? ' is-invalid' : '' }}">
									<label for="name" class="required">Name:</label>
									<input type="text" class="form-control" id="name" name="name"
										   value="{{ $travelOffers->title }}">
									@if ($errors->has('name'))
										<div class="invalid-feedback animated fadeInDown">
											<strong>{{ $errors->first('name') }}</strong>
										</div>
									@endif
								</div>
							</div>
							<div class="col-xl-6">
								<div class="form-group {{ $errors->has('pubdate') ? ' is-invalid' : '' }}">
									<label for="email" class="required">Publication date:</label>
									<div class='input-group date js-datetimepicker' data-target-input="nearest"
										 id="pubdate">
										<input type="text" class="form-control datetimepicker-input" name="pubdate"
											   data-target="#pubdate" value="{{ convertDateTimezone($travelOffers->publication_date, null, $clubDetail->time_zone, config('fanslive.DATE_TIME_CMS_FORMAT.php')) }}"
											   readonly id="publication_datetime" data-toggle="datetimepicker"/>
										<div class="input-group-append" data-target="#pubdate"
											 data-toggle="datetimepicker">
											<div class="input-group-text"><i class="fal fa-calendar-alt"></i></div>
										</div>
									</div>

									@if ($errors->has('pubdate'))
										<div class="invalid-feedback animated fadeInDown">
											<strong>{{ $errors->first('pubdate') }}</strong>
										</div>
									@endif
								</div>
							</div>
							<div class="col-xl-6">
								<div class="form-group {{ $errors->has('showuntil') ? ' is-invalid' : '' }}">
									<label for="email" class="required">Show until:</label>
									<div class='input-group date js-datetimepicker' data-target-input="nearest"
										 id="showuntil">
										<input type="text" class="form-control datetimepicker-input" name="showuntil"
											   data-target="#showuntil" value="{{ convertDateTimezone($travelOffers->show_until, null, $clubDetail->time_zone, config('fanslive.DATE_TIME_CMS_FORMAT.php')) }}" readonly
											   data-toggle="datetimepicker"/>
										<div class="input-group-append" data-target="#showuntil"
											 data-toggle="datetimepicker">
											<div class="input-group-text"><i class="fal fa-calendar-alt"></i></div>
										</div>
									</div>
									@if ($errors->has('showuntil'))
										<div class="invalid-feedback animated fadeInDown">
											<strong>{{ $errors->first('showuntil') }}</strong>
										</div>
									@endif
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
                                                        	<input type="hidden" value="{{ isset($travelOffers->icon_file_name) ? $travelOffers->icon_file_name : '' }}" id="icon_file_name" name="icon_file_name">
                                                            <input type="file" class="form-control custom-file-input uploadimage" id="icon" name="icon" data-toggle="custom-file-input" accept="image/png">
                                                            <label class="form-control custom-file-label text-truncate pr-100" for="icon">{{ $travelOffers->icon_file_name ? $travelOffers->icon_file_name  : 'Choose file'}}</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div id="icon_image" class="{{ $travelOffers->icon ? '' : 'd-md-none' }}">
                                                <div class="logo_preview_container ml-3">
                                                    <img src="{{ $travelOffers->icon }}" id="icon_preview" name="icon_preview" alt="icon">
                                                </div>
                                            </div>
                                        </div>
                                        <label class="helper m-0">Icon dimensions: 150px X 150px (png only)</label>
                                    </div>
                                </div>
							</div>

							<div class="col-xl-6">
								<div class="form-group">
									<label class="required">Status:</label>
									<div>
										@foreach($travelOffersStatus as $key => $status)
											<div class="custom-control custom-radio custom-control-inline mb-5">
												<input class="custom-control-input" type="radio" name="status"
													   id="status_{{$key}}"
													   value="{{$status}}" {{ $travelOffers->status == $status ? 'checked' : '' }}>
												<label class="custom-control-label"
													   for="status_{{$key}}">{{$status}}</label>
											</div>
										@endforeach()
									</div>
								</div>
							</div>
							<div class="col-xl-12">
								<div class="block">
									<ul class="nav nav-tabs nav-tabs-alt nav-tabs-block" data-toggle="tabs"
										role="tablist">
										<li class="nav-item">
											<a class="nav-link active" href="#btabs-content">Content</a>
										</li>
										<li class="nav-item">
											<a class="nav-link" href="#btabs-image">Image</a>
										</li>
										<li class="nav-item">
											<a class="nav-link" href="#btabs-buttom">Button</a>
										</li>
									</ul>
									<div class="block-content tab-content px-0">
										<div class="tab-pane active" id="btabs-content" role="tabpanel">
											<div class="form-group">
												<textarea id="js-ckeditor" name="content_description"
														  class="content_description jsckeditor">{{$travelOffers->content}}</textarea>
											</div>
										</div>
										<div class="tab-pane" id="btabs-image" role="tabpanel">
											<div class="row">
												<div class="col-xl-6">
													<div class="form-group {{ $travelOffers->thumbnail }} ">
					                                    <div class="logo-fields-wrapper">
					                                        <div class="d-flex">
					                                            <div class="logo-input flex-grow-1">
					                                                <label>Thumbnail image:</label>
					                                                <div class="input-group">
					                                                    <div class="custom-file">
					                                                        <div>
					                                                        	<input type="hidden" name="thumbnail_edit" id="thumbnail_edit" value="{{$travelOffers->thumbnail_file_name}}">
					                                                            <input type="file" class="form-control custom-file-input uploadThumbnail" id="thumbnail" name="thumbnail" data-toggle="custom-file-input" accept="image/*">
					                                                            <label class="form-control custom-file-label text-truncate pr-100" id="lbl_thumbnail" name="lbl_thumbnail" for="thumbnail">Choose file</label>
					                                                        </div>
					                                                    </div>
					                                                </div>
					                                            </div>
					                                            <div id="thumbnail_preview_container" class="{{ $travelOffers->thumbnail ? '' : 'd-md-none' }}">
					                                                <div class="logo_preview_container ml-3">
					                                                    <img src="{{ $travelOffers->thumbnail }}" id="thumbnail_preview" alt="Travel offer logo">
					                                                    <a href="#" id="remove_thumbnail"name="remove_thumbnail" class="close-preview" data-toggle="tooltip" title="Delete">
																			<i class="far fa-trash-alt text-muted"></i>
																		</a>
					                                                </div>
					                                            </div>
					                                        </div>
					                                    </div>
					                                </div>
												</div>
												<div class="col-xl-6">
													<div class="form-group">
                                                        <div class="logo-fields-wrapper">
                                                            <div class="d-flex">
                                                                <div class="logo-input flex-grow-1">
                                                                    <label class="required">Banner image:</label>
                                                                    <div class="input-group">
                                                                        <div class="custom-file">
                                                                            <div>
                                                                            	<input type="hidden" name="banner_edit" id="banner_edit" value="{{$travelOffers->banner_file_name}}">
                                                                                <input type="file" class="form-control custom-file-input uploadBanner" id="logo" name="logo" data-toggle="custom-file-input" accept="image/png">
                                                                                <label class="form-control custom-file-label" id="lbl_banner" name="lbl_banner" for="logo">{{ isset($travelOffers->banner_file_name) ? $travelOffers->banner_file_name  : 'Choose file'}}</label>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div id="banner_preview_container" class="{{ $travelOffers->banner ? '' : 'd-md-none' }}">
                                                                    <div class="logo_preview_container ml-3">
                                                                        <img src="{{ $travelOffers->banner }}" id="banner_preview" alt="Banner image">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <label class="helper m-0">Image dimensions: 840px X 280px ( png only )</label>
                                                        </div>
                                                    </div>
												</div>
											</div>
										</div>
										<div class="tab-pane" id="btabs-buttom" role="tabpanel">
											<div class="row">
												<div class="col-xl-6">
													<div
														class="form-group{{ $errors->has('button_colour') ? ' is-invalid' : '' }}">
														<label class="required" for="button_colour">Button
															colour:</label>
														<div>
															<div class="js-colorpicker input-group" data-format="hex">
																<input type="text" class="form-control"
																	   id="button_colour" name="button_colour"
																	   value="{{ $travelOffers->button_colour }}">
																<div class="input-group-append input-group-addon">
																	<div class="input-group-text">
																		<i></i>
																	</div>
																</div>
															</div>
														</div>
														@if ($errors->has('button_colour'))
															<div class="invalid-feedback animated fadeInDown">
																<strong>{{ $errors->first('button_colour') }}</strong>
															</div>
														@endif
													</div>
												</div>
												<div class="col-xl-6">
													<div
														class="form-group{{ $errors->has('button_text_colour') ? ' is-invalid' : '' }}">
														<label class="required" for="button_text_colour">Button text
															colour:</label>
														<div>
															<div class="js-colorpicker input-group" data-format="hex">
																<input type="text" class="form-control"
																	   id="button_text_colour" name="button_text_colour"
																	   value="{{ $travelOffers->button_text_colour }}">
																<div class="input-group-append input-group-addon">
																	<div class="input-group-text">
																		<i></i>
																	</div>
																</div>
															</div>
														</div>
														@if ($errors->has('button_text_colour'))
															<div class="invalid-feedback animated fadeInDown">
																<strong>{{ $errors->first('button_text_colour') }}</strong>
															</div>
														@endif
													</div>
												</div>
												<div class="col-xl-6">
													<div
														class="form-group{{ $errors->has('button_text') ? ' is-invalid' : '' }}">
														<label for="button_text" class="required">Button text:</label>
														<input type="text" class="form-control" id="button_text"
															   name="button_text"
															   value="{{ $travelOffers->button_text }}">
														@if ($errors->has('button_text'))
															<div class="invalid-feedback animated fadeInDown">
																<strong>{{ $errors->first('button_text') }}</strong>
															</div>
														@endif
													</div>
												</div>
												<div class="col-xl-6">
													<div
														class="form-group{{ $errors->has('button_url') ? ' is-invalid' : '' }}">
														<label for="button_url" class="required">Button url:</label>
														<input type="text" class="form-control" id="button_url"
															   name="button_url"
															   value="{{ $travelOffers->button_url }}">
														@if ($errors->has('button_url'))
															<div class="invalid-feedback animated fadeInDown">
																<strong>{{ $errors->first('button_url') }}</strong>
															</div>
														@endif
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="col-xl-12">
								<div class="form-group">
									<button type="submit" class="btn btn-hero btn-noborder btn-primary min-width-125">
										Update
									</button>
									<a href="{{ route('backend.traveloffers.index', ['club' => app()->request->route('club')]) }}"
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
