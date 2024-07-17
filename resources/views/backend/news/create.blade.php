@extends('layouts.backend')

@section('plugin-styles')
    <link rel="stylesheet" href="{{ asset('plugins/summernote/summernote-bs4.css')}}">
@endsection

@section('plugin-scripts')
    <script src="{{ asset('plugins/ckeditor/ckeditor.js') }}"></script>
@endsection

@section('page-scripts')
    <script src="{{ asset('js/backend/pages/news/create.js') }}"></script>
@endsection

@section('content')
	<div class="block">
		<div class="block-header block-header-default">
            <h3 class="block-title">Add news</h3>
            <div class="block-options">
            </div>
        </div>
        <div class="block-content block-content-full">
        	<div class="row">
                <div class="col-xl-12">
		            <form class="create-news-form" action="{{ route('backend.news.store', ['club' => app()->request->route('club')]) }}" method="post" enctype="multipart/form-data">
		    			@csrf
                        <div class="row">
                            <div class="col-xl-6">
                                <div class="form-group{{ $errors->has('name') ? ' is-invalid' : '' }}">
                                    <label for="name" class="required">Title:</label>
                                    <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}">
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
                                                <label>Image:</label>
                                                <div class="input-group">
                                                    <div class="custom-file">
                                                        <div>
                                                            <input type="file" class="form-control custom-file-input" id="logo" name="logo" data-toggle="custom-file-input" accept="image/png">
                                                            <label id="lbl_logo" name="lbl_logo" class="form-control custom-file-label" for="logo">Choose file</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div id="logo_preview_container" class="d-md-none">
                                                <div class="logo_preview_container ml-3">
                                                    <img src="" id="logo_preview" alt="Category logo">
                                                    <a href="" id="remove" name="remove" class="close-preview" data-toggle="tooltip" title="Delete">
                                                        <i class="far fa-trash-alt text-muted"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                        <label class="helper m-0">Image dimensions: 840px X 525px ( png only )</label>
                                    </div>
                                </div>
                                {{-- <div class="form-group row">
                                    <div class="col-12 js-manage-logo-width">
                                        <label>Image:</label>
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="custom-file">
                                                    <input type="file" class="custom-file-input" id="logo" name="logo" data-toggle="custom-file-input" accept="image/png">
                                                    <label id="lbl_logo" name="lbl_logo" class="custom-file-label" for="logo">Choose file</label>
                                                </div>
												<label class="helper mt-5">Image dimensions: 840px X 525px ( png only )</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-3 d-md-none" id="logo_preview_container">
                                        <div class="logo_preview_container m-auto">
                                            <img src="" id="logo_preview" alt="Category logo">
                                            <a href="" id="remove" name="remove" class="close-preview" data-toggle="tooltip" title="Delete">
                                                <i class="far fa-trash-alt text-muted"></i>
                                            </a>
                                        </div> --}}
{{--										<div class="d-md-none logo_preview_container m-auto" id="logo_preview_container">--}}
{{--											<img src="" id="logo_preview" alt="Category logo">--}}
{{--										</div>--}}
{{--										<a href="#" id="remove" name="remove" class="close-preview d-md-none" data-toggle="tooltip" title="Delete">--}}
{{--											<i class="far fa-trash-alt text-muted"></i>--}}
{{--										</a>--}}
                                  {{--   </div>
                                </div> --}}
                            </div>

                            <div class="col-xl-6">
                                <div class="form-group {{ $errors->has('pubdate') ? ' is-invalid' : '' }}">
                                    <label for="email" class="required">Publication date:</label>

                                    <div class='input-group date js-datetimepicker' data-target-input="nearest" id="pubdate">
                                        <input type="text" class="form-control datetimepicker-input" name="pubdate" data-target="#pubdate" readonly data-toggle="datetimepicker"/>
                                        <div class="input-group-append" data-target="#pubdate" data-toggle="datetimepicker">
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
                                <div class="form-group">
                                    <label class="required">Status:</label>
                                    <div>
                                        @foreach($newsStatus as $statusKey => $status)
                                            <div class="custom-control custom-radio custom-control-inline mb-5">
                                                <input class="custom-control-input" type="radio" name="status" id="status_{{$statusKey}}" value="{{$status}}" {{ $statusKey == 'published' ? 'checked': '' }}>
                                                <label class="custom-control-label" for="status_{{$statusKey}}">{{$status}}</label>
                                            </div>
                                        @endforeach()
                                    </div>
                                </div>
                            </div>
							<div class="col-xl-6">
								<label for="notes">Description:</label>
                            	<div class="form-group row">
                            		<div class="col-12">
                                        <textarea  id="js-ckeditor" name="notes"></textarea>
                                    </div>
                                </div>
                            </div>
							<div class="col-xl-12">
                                <div class="form-group">
                                    <button type="submit" class="btn btn-hero btn-noborder btn-primary min-width-125">
                                        Create
                                    </button>
                                    <a href="{{ route('backend.news.index', ['club' => app()->request->route('club')])}}" class="btn btn-hero btn-noborder btn-alt-secondary">
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
