@extends('layouts.backend')

@section('plugin-styles')

@endsection

@section('plugin-scripts')
	<script src="{{asset('plugins/jquery-ui/jquery-ui.min.js') }}"></script>
    <script src="{{asset('plugins/jquery-ui/jquery.ui.touch-punch.min.js') }}"></script>
    <script src="{{ asset('plugins/ckeditor/ckeditor.js') }}"></script>
@endsection

@section('page-scripts')
	<script src="{{ asset('js/backend/pages/travelinformationpages/create.js') }}"></script>
@endsection

@section('content')
	<div class="block">
		<div class="block-header block-header-default">
			<h3 class="block-title">Add travel information</h3>
			<div class="block-options">
			</div>
		</div>
		<div class="block-content block-content-full">
			<div class="row">
				<div class="col-xl-12">
					<form class="create-travel-information-form js-create-travel-info-content repeater" action="{{ route('backend.travelinformationpages.store',['club' => app()->request->route('club')]) }}" 	method="post" enctype="multipart/form-data">
						@csrf
						<div class="row">
							<div class="col-xl-6">
								<div class="form-group {{ $errors->has('title') ? ' is-invalid' : '' }}">
									<label for="title" class="required">Title:</label>
									<input type="text" class="form-control" id="title" name="title" value="{{ old('title') }}">
								</div>
								@if ($errors->has('title'))
                                    <div class="invalid-feedback animated fadeInDown">
                                        <strong>{{ $errors->first('title') }}</strong>
                                    </div>
                                @endif
							</div>

							<div class="col-xl-6">
								<div class="form-group">
                                    <div class="logo-fields-wrapper">
                                        <div class="d-flex">
                                            <div class="logo-input flex-grow-1">
                                                <label>Photo:</label>
                                                <div class="input-group">
                                                    <div class="custom-file">
                                                        <div>
                                                            <input type="file" class="form-control custom-file-input" id="logo" name="logo" data-toggle="custom-file-input" accept="image/*">
                                                            <label id="lbl_logo" name="lbl_logo" class="form-control custom-file-label" for="logo">Choose file:</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div id="logo_preview_container" class="d-md-none">
                                                <div class="logo_preview_container ml-3">
                                                    <img src="" id="logo_preview" alt="Category logo">
                                                    <a href="" id="remove" name="remove" class="close-preview d-md-none" data-toggle="tooltip" title="Delete">
														<i class="far fa-trash-alt text-muted"></i>
													</a>
                                                </div>
                                            </div>
                                        </div>
                                        <label class="helper m-0">Image dimensions: 840px X 630px (png only)</label>
                                    </div>
                                </div>

                                {{-- <div class="form-group row">
                                    <div class="col-12 js-manage-logo-width">
                                        <label>Photo:</label>
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="custom-file">
                                                    <input type="file" class="custom-file-input" id="logo" name="logo" data-toggle="custom-file-input" accept="image/*">
													<label id="lbl_logo" name="lbl_logo" class="custom-file-label" for="logo">Choose file:</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
									<div class="col-3">
										<div class="d-md-none logo_preview_container m-auto" id="logo_preview_container">
											<img src="" id="logo_preview" alt="Category logo">
											<a href="" id="remove" name="remove" class="close-preview d-md-none" data-toggle="tooltip" title="Delete">
												<i class="far fa-trash-alt text-muted"></i>
											</a>
										</div>
									</div>
                                </div> --}}
                            </div>

	                    	<div class="col-xl-6">
                                <div class="form-group {{ $errors->has('publication_date') ? ' is-invalid' : '' }}">
                                    <label for="email" class="required">Publication date:</label>
                                    <div class='input-group date js-datetimepicker' data-target-input="nearest" id="publication_date">
					                    <input type="text" class="form-control datetimepicker-input" name="publication_date" data-target="#publication_date" readonly data-toggle="datetimepicker"/>
					                    <div class="input-group-append" data-target="#publication_date" data-toggle="datetimepicker">
					                        <div class="input-group-text"><i class="fal fa-calendar-alt"></i></div>
					                    </div>
					                </div>

					                @if ($errors->has('publication_date'))
                                        <div class="invalid-feedback animated fadeInDown">
                                            <strong>{{ $errors->first('publication_date') }}</strong>
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
                                                            <input type="file" class="form-control custom-file-input uploadimage" id="icon" name="icon" data-toggle="custom-file-input" accept="image/png">
                                                            <label class="form-control custom-file-label" for="icon">Choose file:</label>
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
                                        <label class="helper m-0">Icon dimensions: 150px X 150px (png only)</label>
                                    </div>
                                </div>
                                {{-- <div class="form-group row">
                                    <div class="col-12 js-manage-icon-width">
                                        <label class="required">Icon:</label>
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="custom-file">
                                                    <input type="file" class="custom-file-input uploadimage" id="icon" name="icon" data-toggle="custom-file-input" accept="image/png">
                                                    <label class="custom-file-label" for="icon">Choose file:</label> --}}
													{{--<label id="lblErrorMessageAuthorImage" style="color:red"></label>--}}
													{{--<input id="AuthorImage" name="AuthorImage" onchange="return ValidateAuthorImage(event,this)" type="file">
													<label id="lblErrorMessageAuthorImage" style="color:red"></label>
--}}
												{{-- </div>
												<label class="helper mt-5">Icon dimensions: 150px X 150px ( png only )</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-3">
                                        <div class="d-flex justify-content-center d-md-none" id="icon_preview_container">
                                            <img src="" id="icon_preview" name="icon_preview" class="img-avatar img-avatar-square mb-2 pull-right" alt="icon">
                                        </div>
                                    </div>
                                </div>
                                @if ($errors->has('icon'))
                                    <div class="invalid-feedback animated fadeInDown">
                                        <strong>{{ $errors->first('icon') }}</strong>
                                    </div>
                                @endif --}}
                            </div>
	                    	<div class="col-xl-6">
								<div class="form-group">
									<label class="required">Status:</label>
									<div>
			                        	@foreach($travelInformationPageStatus as $key => $travelInformationPage)
	                                        <div class="custom-control custom-radio custom-control-inline mb-5">
	                                            <input class="custom-control-input" type="radio" name="status" id="travelInformationPage{{$key}}" value="{{$travelInformationPage}}" {{ $key == 'published' ? 'checked': '' }}>
	                                            <label class="custom-control-label" for="travelInformationPage{{$key}}">{{$travelInformationPage}}</label>
	                                        </div>
	                                    @endforeach()
	                                </div>
		                        </div>
							</div>
						</div>

						<div class="row">
							<div class="col-xl-6">
								<div class="row">
									<div class="col-xl-12">
                                        <div class="content-heading">
                                            <div class="d-flex align-items-center justify-content-between">
												<label class="mb-0 required text-black">Contents section</label>
												<input type="hidden" name="addTravelContent[]" value="" id="addTravelContent">

                                                <button type="button" class="btn btn-primary btn-noborder
                                                js-add-travel-info-content" data-toggle="modal" data-target="#add_travel_info_content"><i class="fal fa-plus mr-5"></i>Add contents section</button>
                                            </div>
                                        </div>
                                    </div>
								</div>
								<div id="mcq_draggable_content_validation_error" class="text-danger animated fadeInDown"></div>
								<div class="row">
									<div class="col-xl-12 draggable-column js-draggable-items" id="add_travel_info_section">
									</div>
								</div>
							</div>
						</div>

						<div class="row">
			                <div class="col-xl-12">
								<div class="form-group">
									<button type="button" class="btn btn-hero btn-noborder btn-primary min-width-125 js-travel-info-create-content">
										Create
									</button>
									<a href="{{ route('backend.travelinformationpages.index', ['club' => app()->request->route('club')]) }}" class="btn btn-hero btn-noborder btn-alt-secondary">
										Cancel
									</a>

								</div>
							</div>
						</div>
					</form>
					<!-- Fade In Modal -->
			        <div class="modal fade" id="add_travel_info_content"  role="dialog" aria-labelledby="add_travel_info_content" aria-hidden="true">
			            <div class="modal-dialog modal-lg" role="document">
			                <div class="modal-content">
			                    <div class="block block-themed block-transparent mb-0">
			                        <div class="block-header bg-primary-dark">
			                            <h3 class="block-title js-modal-title">Add contents section</h3>
			                            <div class="block-options">
			                                <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
			                                    <i class="si si-close"></i>
			                                </button>
			                            </div>
			                        </div>
			                        <form class="block-content" id="section_content_form">
			                       	 	<div class="form-group">
											<label for="title" class="required">Title:</label>
											<input type="text" class="form-control js-content-section-title" id="content_title" name="content_title" value="{{ old('content_title') }}">
										</div>
		                                <div class="form-group">
											<label for="content_description" class="required">Content description:</label>
		                                    <textarea id="js-ckeditor" class="form-control" name="content_description" value="{{ old('content_description') }}"></textarea>
		                                </div>
				                    	<input type="hidden" name="addContent" id="add_edit_section_content" value="addTravelInfoContent">
				                    	<input type="hidden" name="addEditIndex" id="add_edit_travel_index" value="">
				                	</form>
			                    </div>
			                    <div class="modal-footer">
			                        <button type="button" class="btn btn-hero btn-noborder btn-alt-secondary" data-dismiss="modal">Close</button>
			                        <button type="button" class="btn btn-hero btn-noborder btn-primary js-travel-info-value-save" >Save</button>
			                    </div>
			                </div>
			            </div>
			        </div>
						<!-- END Fade In Modal -->
				</div>
			</div>
		</div>
	</div>
@endsection
