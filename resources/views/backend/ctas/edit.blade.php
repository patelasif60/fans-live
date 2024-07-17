@extends('layouts.backend')

@section('plugin-scripts')
@endsection

@section('page-scripts')
    <script src="{{ asset('js/backend/pages/ctas/edit.js') }}"></script>
@endsection

@section('content')
	<div class="block">
		<div class="block-header block-header-default">
            <h3 class="block-title">Edit CTA</h3>
            <div class="block-options">
            </div>
        </div>
        <div class="block-content block-content-full">
        	<div class="row">
                <div class="col-xl-12">
		            <form class="edit-cta-form align-items-center" action="{{ route('backend.cta.update', ['club' => app()->request->route('club'), 'cta' => $cta]) }}" method="post" enctype="multipart/form-data">
		    			{{ method_field('PUT') }}
                        {{ csrf_field() }}

                        <div class="row">
                            <div class="col-xl-6">
                                <div class="form-group{{ $errors->has('title') ? ' is-invalid' : '' }}">
                                    <label for="title" class="required">Title:</label>
                                    <input type="text" class="form-control" id="title" name="title" value="{{ $cta->title }}">
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
                                                            <input type="hidden" value="{{ isset($cta->image_file_name) ? $cta->image_file_name : '' }}" id="image_file_name" name="image_file_name">
                                                            <input type="file" class="form-control custom-file-input" id="image" name="image" data-toggle="custom-file-input" accept="image/png">
                                                            <label class="form-control custom-file-label" for="image">{{ isset($cta->image_file_name) ? $cta->image_file_name  : 'Choose file'}}</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div id="cta_image" class="{{ $cta->image ? '' : 'd-md-none' }}">
                                                <div class="logo_preview_container ml-3" id ="image_preview_container">
                                                    <img src="{{ $cta->image }}" id="image_preview" name="image_preview" alt="Image">
                                                </div>
                                            </div>
                                        </div>
                                        <label class="helper m-0">Image dimensions: 840px X 525px ( png only )</label>
                                    </div>
                                </div>

                                {{-- <div class="form-group row">
                                    <div class="{{ $cta->image ? 'col-9' : 'col-12' }} js-manage-image-width">
                                        <label class="required">Image:</label>
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="custom-file"> --}}
                                                    <!-- Populating custom file input label with the selected filename (data-toggle="custom-file-input" is initialized in Codebase() -> uiHelperCoreCustomFileInput()) -->
													{{-- <input type="hidden" value="{{ isset($cta->image_file_name) ? $cta->image_file_name : '' }}" id="image_file_name" name="image_file_name">
													<input type="file" class="form-control custom-file-input" id="image" name="image" data-toggle="custom-file-input" accept="image/png">
                                                    <label class="form-control custom-file-label" for="image">{{ isset($cta->image_file_name) ? $cta->image_file_name  : 'Choose file'}}</label>
                                                </div>
												<label class="helper mt-5">Image dimensions: 840px X 525px ( png only )</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-3 {{ $cta->image ? '' : 'd-md-none' }}" id="cta_image">
                                        <div class="d-flex justify-content-center" id="image_preview_container">
                                            <img src="{{ $cta->image }}" class="img-avatar img-avatar-square" alt="Image">
                                        </div>
                                    </div>
                                </div> --}}
                            </div>

                            <div class="col-xl-6">
                                <div class="form-group{{ $errors->has('first_button_text') ? ' is-invalid' : '' }}">
                                    <label for="first_button_text" class="required">Button 1 text:</label>
                                    <input type="text" class="form-control" id="first_button_text" name="first_button_text" value="{{ $cta->button1_text }}">
                                    @if ($errors->has('first_button_text'))
                                        <div class="invalid-feedback animated fadeInDown">
                                            <strong>{{ $errors->first('first_button_text') }}</strong>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div class="col-xl-6">
                                <div class="form-group">
                                    <label for="second_button_text">Button 2 text:</label>
                                    <input type="text" class="form-control" id="second_button_text" name="second_button_text" value="{{ $cta->button2_text }}">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group{{ $errors->has('first_button_action') ? ' is-invalid' : '' }}"">
                                    <label class="required" for="first_button_action">Button 1 action:</label>
                                    <div>
                                        <select class="js-select2 form-control" id="first_button_action" name="first_button_action">
                                        	<option value="">Please select</option>
                                            @foreach($buttonActions as $key => $buttonAction)
                                                <option value="{{ $key }}" {{ $cta->button1_action == $key ? 'selected' : '' }}>{{ $buttonAction }}</option>
                                            @endforeach
                                        </select>
                                        @if ($errors->has('first_button_action'))
                                            <div class="invalid-feedback animated fadeInDown">
                                                <strong>{{ $errors->first('first_button_action') }}</strong>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="second_button_action">Button 2 action:</label>
                                    <div>
                                        <select class="js-select2 js-select2-allow-clear form-control" id="second_button_action" name="second_button_action">
                                        	<option value="">Please select</option>
                                            @foreach($buttonActions as $key => $buttonAction)
                                                <option value="{{ $key }}" {{ $cta->button2_action == $key ? 'selected' : '' }}>{{ $buttonAction }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div id="first_button_item_container" class="col-md-6 {{ $cta->button1_action == 'merchandise_category' || $cta->button1_action == 'food_and_drink_category' ? '' : 'd-none' }}">
                                <div class="form-group" >
                                    <label for="first_button_item">Button 1 item:</label>
                                    <div>
                                        <select class="js-select2 js-select2-allow-clear form-control" id="first_button_item" name="first_button_item" style="width:100%">
                                        	<option value="">Please select</option>
                                            @foreach($buttonItems as $key => $buttonItem)
                                                <option value="{{ $key }}" {{ $cta->button1_item == $key ? 'selected' : '' }}>{{ $buttonItem }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div  id="second_button_item_container" class="col-md-6 {{ $cta->button2_action == 'merchandise_category' || $cta->button2_action == 'food_and_drink_category' ? '' : 'd-none' }}">
                                <div class="form-group">
                                    <label for="second_button_item">Button 2 item:</label>
                                    <div>
                                        <select  class="js-select2 js-select2-allow-clear form-control" id="second_button_item" name="second_button_item" style="width:100%">
                                        	<option value="">Please select</option>
                                            @foreach($buttonItems as $key => $buttonItem)
                                                <option value="{{ $key }}" {{ $cta->button2_item == $key ? 'selected' : '' }}>{{ $buttonItem }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-6">
                                <div class="form-group {{ $errors->has('publication_date') ? ' is-invalid' : '' }}">
                                    <label for="publication_date" class="required">Publication date:</label>
                                    <div class='input-group date js-datetimepicker' data-target-input="nearest" id="publication_date">
                                        <input type="text" class="form-control datetimepicker-input" name="publication_date" data-target="#publication_date" readonly value="{{ convertDateTimezone($cta->publication_date, null, $clubDetail->time_zone, config('fanslive.DATE_TIME_CMS_FORMAT.php')) }}" data-toggle="datetimepicker"/>
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

                            <div class="col-xl-12">
                                <div class="form-group">
                                    <label class="required">Status:</label>
                                    <div>
                                        @foreach($status as $key => $status)
                                            <div class="custom-control custom-radio custom-control-inline mb-5">
                                                <input class="custom-control-input" type="radio" name="status" id="status_{{$key}}" value="{{$status}}" {{ $status == $cta->status ? 'checked' : '' }}>
                                                <label class="custom-control-label" for="status_{{$key}}">{{$status}}</label>
                                            </div>
                                        @endforeach()
                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-12">
                                <div class="form-group">
                                    <button type="submit" class="btn btn-hero btn-noborder btn-primary min-width-125">
                                        Update
                                    </button>
                                    <a href="{{ route('backend.cta.index', ['club' => app()->request->route('club')]) }}" class="btn btn-hero btn-noborder btn-alt-secondary">
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
