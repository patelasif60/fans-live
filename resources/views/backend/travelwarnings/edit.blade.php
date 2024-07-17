@extends('layouts.backend')

@section('plugin-styles')
<link rel="stylesheet" href="{{ asset('plugins/bootstrap-colorpicker/css/bootstrap-colorpicker.min.css') }}">
@endsection

@section('plugin-scripts')
    <script src="{{ asset('plugins/ckeditor/ckeditor.js') }}"></script>
    <script src="{{ asset('plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.min.js') }}"></script>
@endsection

@section('page-scripts')
    <script src="{{ asset('js/backend/pages/travelwarnings/edit.js') }}"></script>
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
                   <form class="edit-travelwarnings-form" action="{{ route('backend.travelwarnings.update', ['club' => app()->request->route('club'), 'travelWarning' => $travelWarning]) }}" method="post" enctype="multipart/form-data">
		    			{{ method_field('PUT') }}
                        {{ csrf_field() }}
                        <div class="row">
                            <div class="col-xl-6">
                                <div class="form-group{{ $errors->has('text') ? ' is-invalid' : '' }}">
                                    <label for="name" class="required">Name:</label>
                                    <input type="text" class="form-control" id="travel_warning_text" maxlength="100" name="text" value="{{ $travelWarning->text }}">
									<span id="travel_warning_chars_count">100</span> characters remaining

                                    @if ($errors->has('text'))
                                        <div class="invalid-feedback animated fadeInDown">
                                            <strong>{{ $errors->first('text') }}</strong>
                                        </div>
                                    @endif
                                </div>
                            </div>
                           <div class="col-xl-6">
                                <div class="form-group {{ $errors->has('publication_date_time') ? ' is-invalid' : '' }}">
                                    <label for="publication_date_time" class="required">Publication date:</label>
                                    <div class='input-group date js-datetimepicker' data-target-input="nearest" id="publication_date_time">
                                        <input type="text" class="form-control datetimepicker-input" name="publication_date_time" data-target="#publication_date_time" value="{{ convertDateTimezone($travelWarning->publication_date_time, null, $clubDetail->time_zone, config('fanslive.DATE_TIME_CMS_FORMAT.php')) }}" readonly id="publication_datetime" data-toggle="datetimepicker"/>
                                        <div class="input-group-append" data-target="#publication_date_time" data-toggle="datetimepicker">
                                            <div class="input-group-text"><i class="fal fa-calendar-alt"></i></div>
                                        </div>
                                    </div>

                                    @if ($errors->has('publication_date_time'))
                                        <div class="invalid-feedback animated fadeInDown">
                                            <strong>{{ $errors->first('publication_date_time') }}</strong>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-xl-6">
                                <div class="form-group {{ $errors->has('show_until') ? ' is-invalid' : '' }}">
                                    <label for="show_until" class="required">Show until:</label>
                                    <div class='input-group date js-datetimepicker' data-target-input="nearest" id="show_until">
                                        <input type="text" class="form-control datetimepicker-input" name="show_until" data-target="#show_until"  value="{{ convertDateTimezone($travelWarning->show_until, null, $clubDetail->time_zone, config('fanslive.DATE_TIME_CMS_FORMAT.php')) }}" readonly data-toggle="datetimepicker"/>
                                        <div class="input-group-append" data-target="#show_until" data-toggle="datetimepicker">
                                            <div class="input-group-text"><i class="fal fa-calendar-alt"></i></div>
                                        </div>
                                    </div>
                                    @if ($errors->has('show_until'))
                                        <div class="invalid-feedback animated fadeInDown">
                                            <strong>{{ $errors->first('show_until') }}</strong>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-xl-6">
                                <div class="form-group">
                                    <label>Colour:</label>
                                    <div>
                                        @foreach($travelWarningsColors as $key => $color)
                                           <div class="custom-control custom-radio custom-control-inline mb-5">
                                                <input class="custom-control-input" type="radio" name="color" id="color_{{$key}}" value="{{$key}}" {{ $travelWarning->color == $key ? 'checked' : '' }}>
                                                <label class="custom-control-label" for="color_{{$key}}">{{$color}}</label>
                                            </div>
                                        @endforeach()
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-6">
                                <div class="form-group">
                                   <label class="required">Status</label>
                                    <div>
                                        @foreach($travelWarningsStatus as $key => $status)
                                           <div class="custom-control custom-radio custom-control-inline mb-5">
                                                <input class="custom-control-input" type="radio" name="status" id="status_{{$key}}" value="{{$status}}" {{ $travelWarning->status == $status ? 'checked' : '' }}>
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
                                    <a href="{{ route('backend.travelwarnings.index', ['club' => app()->request->route('club')]) }}" class="btn btn-hero btn-noborder btn-alt-secondary">
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
