@extends('layouts.backend')

@section('plugin-styles')
@endsection

@section('plugin-scripts')
    <script src="{{asset('plugins/jquery-ui/jquery-ui.min.js') }}"></script>
    <script src="{{asset('plugins/jquery-ui/jquery.ui.touch-punch.min.js') }}"></script>
    <script src="{{ asset('plugins/ckeditor/ckeditor.js') }}"></script>
@endsection

@section('page-scripts')
    <script src="{{ asset('js/backend/pages/travelinformationpages/edit.js') }}"></script>
@endsection

@section('content')
	<div class="block">
		<div class="block-header block-header-default">
            <h3 class="block-title">Edit travel information</h3>
            <div class="block-options">
            </div>
        </div>
        <div class="block-content block-content-full">
        	<div class="row">
                <div class="col-xl-12">
		            <form class="edit-travel-information-form js-edit-travel-info-content align-items-center" action="{{ route('backend.travelinformationpages.update', ['club' => app()->request->route('club'), 'travelInformationPage' => $travelInformationPage]) }}" method="post"
                        enctype="multipart/form-data">
		    			{{ method_field('PUT') }}
                        {{ csrf_field() }}

                        <div class="row">
                            <div class="col-xl-6">
        		    			<div class="form-group {{ $errors->has('title') ? ' is-invalid' : '' }}">
        		                    <label for="title" class="required">Title:</label>
        		                    <input type="text" class="form-control" id="title" name="title" value="{{ $travelInformationPage->title }}">
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
                                                            <input type="hidden" name="logo_edit" id="logo_edit" value="{{$travelInformationPage->photo}}">
                                                            <input type="file" class="form-control custom-file-input" id="logo" name="logo" data-toggle="custom-file-input" accept="image/*">
                                                            <label id="lbl_logo" name="lbl_logo" class="form-control custom-file-label" for="logo">Choose file:</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div id="logo_preview_container" class="{{ $travelInformationPage->photo ? '' : 'd-md-none' }}">
                                                <div class="logo_preview_container ml-3">
                                                    <img src="{{ $travelInformationPage->photo }}" id="logo_preview" alt="Competition logo">
                                                    <a href="" id="remove" name="remove" class="close-preview" data-toggle="tooltip" title="Delete">
                                                        <i class="far fa-trash-alt text-muted"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                        <label class="helper m-0">Image dimensions: 840px X 630px (png only)</label>
                                    </div>
                                </div>
                                {{-- <div class="form-group row">
                                    <div class="{{ $travelInformationPage->photo ? 'col-9' : 'col-12' }} js-manage-logo-width">
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
                                    <input type="hidden" name="logo_edit" id="logo_edit" value="{{$travelInformationPage->photo}}">
                                    <div class="col-3 {{ $travelInformationPage->photo ? '' : 'd-md-none' }}"  id="logo_preview_container">
										<div class="logo_preview_container m-auto">
                                            <img src="{{ $travelInformationPage->photo }}" id="logo_preview" alt="Competition logo">
											<a href="#" id="remove" name="remove" class="close-preview" data-toggle="tooltip" title="Delete">
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
                                        <input type="text" class="form-control datetimepicker-input" id="publication_date" name="publication_date" data-target="#publication_date" value="{{ convertDateTimezone($travelInformationPage->publication_date, null, $clubDetail->time_zone, config('fanslive.DATE_TIME_CMS_FORMAT.php')) }}" readonly data-toggle="datetimepicker"/>
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
                                            <div id="icon_preview_container" class="{{ $travelInformationPage->icon ? '' : 'd-md-none' }}">
                                                <div class="logo_preview_container ml-3">
                                                    <img src="{{ $travelInformationPage->icon }}" id="icon_preview" name="icon_preview" alt="icon">
                                                </div>
                                            </div>
                                        </div>
                                        <label class="helper m-0">Icon dimensions: 150px X 150px (png only)</label>
                                    </div>
                                </div>

                                {{-- <div class="form-group row">
                                    <div class="{{ $travelInformationPage->icon ? 'col-9' : 'col-12' }} {{ $travelInformationPage->icon }} js-manage-icon-width">
                                        <label class="required">Icon:</label>
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="custom-file">
                                                    <input type="file" class="custom-file-input uploadimage" id="icon" name="icon" data-toggle="custom-file-input" accept="image/png">
                                                    <label class="custom-file-label" for="icon">Choose file:</label>
                                                </div>
												<label class="helper mt-5">Icon dimensions: 150px X 150px ( png only )</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-3 {{ $travelInformationPage->icon ? '' : 'd-md-none' }}"id ="icon_preview_container">
                                            <div class="d-flex justify-content-center" id="icon_preview_container">
                                                <img id="icon_preview" name="icon_preview" src="{{ $travelInformationPage->icon }}" class="img-avatar img-avatar-square" alt="icon">
                                            </div>
                                    </div>
                                </div> --}}
                            </div>
                            <div class="col-xl-6">
                                <div class="form-group">
                                   <label class="required">Status:</label>
                                    <div>
                                        @foreach($travelInformationPageStatus as $key => $status)
                                           <div class="custom-control custom-radio custom-control-inline mb-5">
                                                <input class="custom-control-input" type="radio" name="status" id="status_{{$key}}" value="{{$status}}" {{ $travelInformationPage->status == $status ? 'checked' : '' }}>
                                                <label class="custom-control-label" for="status_{{$key}}">{{$status}}</label>
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
												<label class="mb-0 required text-black">Contents section:</label>
                                                <button type="button" class="btn btn-primary btn-noborder js-travel-info-content-section" data-toggle="modal" data-target="#js_edit_travel_info_section"><i class="fal fa-plus mr-5"></i>Add contents section</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
								<div id="mcq_draggable_content_validation_error" class="text-danger animated fadeInDown"></div>
                                <div class="row js-draggable-items">
                                    <div class="col-xl-12 js-draggable-items draggable-column" id="edit_travel_info_content">
                                        @foreach($travelInformationPage->travelInformationPageContent as $key =>  $travelInformationPageContent)
                                            <div class="block block-rounded draggable-item js-draggable-display-order">
                                                <div class="block-header block-header-default">
                                                    <h3 class="block-title">{{$travelInformationPageContent->title}}</h3>
                                                    <div class="block-options">
                                                        <button type="button" class="btn-block-option js-tooltip-enabled js-travel-edit-content" title="" data-index="{{$key}}" data-original-title="Edit" data-toggle="modal" data-target="#js_edit_travel_info_section">
                                                            <i class="fal fa-pencil"></i>
                                                        </button>
                                                        <button type="button" class="btn-block-option js-tooltip-enabled js-travel-info-content-section-delete text-danger" data-toggle="tooltip" data-index={{$key}} title="" data-original-title="Delete">
                                                            <i class="fal fa-trash"></i>
                                                        </button>
                                                        <a class="btn-block-option draggable-handler" href="javascript:void(0)"><i class="si si-cursor-move"></i></a>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach()
                                    </div>
                                </div>
                                <input type="hidden" name="editTravelContent[]" value="" id="edit_travel_info_content_section">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xl-12">
                                <button type="submit" class="btn btn-hero btn-noborder btn-primary min-width-125 js-save-travel-info-content-section">
                                    Update
                                </button>
                                <a href="{{ route('backend.travelinformationpages.index', ['club' => app()->request->route('club')]) }}" class="btn btn-hero btn-noborder btn-alt-secondary">
                                    Cancel
                                </a>
                            </div>
                        </div>
		            </form>
	            </div>
                <!-- Fade In Modal -->
                <div class="modal fade" id="js_edit_travel_info_section" tabindex="-1" role="dialog" aria-labelledby="js_edit_travel_info_section" aria-hidden="true">
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
                                        <input type="text" class="form-control js-travel-info-content-section-title" id="content_title" name="content_title" value="{{ old('content_title') }}">
                                    </div>
                                    <div class="form-group">
										<label for="content_description" class="required">Content description:</label>
                                        <textarea id="js-ckeditor" name="content_description" class=""></textarea>
                                    </div>
                                    <input type="hidden" name="addContent" id="js_add_edit_travel_info" value="add">
                                    <input type="hidden" name="addEditIndex" id="js-add_edit_travel_index" value="">
                                </form>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-hero btn-noborder btn-alt-secondary" data-dismiss="modal">Close</button>
                                <button type="button" class="btn btn-hero btn-noborder btn-primary
                                js-edit-travel-info-save-value">Save</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
	</div>
@endsection
