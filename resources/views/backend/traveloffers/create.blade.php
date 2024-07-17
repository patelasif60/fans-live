@extends('layouts.backend')

@section('plugin-styles')
    <link rel="stylesheet" href="{{ asset('plugins/bootstrap-colorpicker/css/bootstrap-colorpicker.min.css') }}">
@endsection

@section('plugin-scripts')
    <script src="{{ asset('plugins/ckeditor/ckeditor.js') }}"></script>
    <script src="{{ asset('plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.min.js') }}"></script>
@endsection

@section('page-scripts')
    <script src="{{ asset('js/backend/pages/traveloffers/create.js') }}"></script>
@endsection

@section('content')
	<div class="block">
		<div class="block-header block-header-default">
            <h3 class="block-title">Add travel offer</h3>
            <div class="block-options">
            </div>
        </div>
        <div class="block-content block-content-full">
            <div class="row">
                <div class="col-xl-12">
		              <form class="create-traveloffers-form" action="{{ route('backend.traveloffers.store', ['club' => app()->request->route('club')]) }}" method="post" enctype="multipart/form-data">
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
                                    <div class="form-group {{ $errors->has('pubdate') ? ' is-invalid' : '' }}">
                                        <label for="email" class="required">Publication date:</label>

                                        <div class='input-group date js-datetimepicker' data-target-input="nearest" id="pubdate">
                                            <input type="text" class="form-control datetimepicker-input" name="pubdate" data-target="#pubdate" id="publication_datetime" readonly data-toggle="datetimepicker"/>
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
                                    <div class="form-group {{ $errors->has('showuntil') ? ' is-invalid' : '' }}">
                                        <label for="email" class="required">Show until:</label>

                                        <div class='input-group date js-datetimepicker' data-target-input="nearest" id="showuntil">
                                            <input type="text" class="form-control datetimepicker-input" name="showuntil" data-target="#showuntil" readonly data-toggle="datetimepicker"/>
                                            <div class="input-group-append" data-target="#showuntil" data-toggle="datetimepicker">
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
                                                        <img src="" id="icon_preview" alt="Thumbnail">
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
                                                    <input class="custom-control-input" type="radio" name="status" id="status_{{$key}}" value="{{$status}}" {{ $key == 'published' ? 'checked': '' }}>
                                                    <label class="custom-control-label" for="status_{{$key}}">{{$status}}</label>
                                                </div>
                                            @endforeach()
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-12">
                                    <div class="block">
                                        <ul class="nav nav-tabs nav-tabs-alt nav-tabs-block" data-toggle="tabs" role="tablist">
                                            <li class="nav-item">
                                                <a class="nav-link active" href="#btabs-content">Content</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" href="#btabs-image">Image</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link js-tab-error" href="#btabs-buttom">Button</a>
                                            </li>
                                        </ul>
                                        <div class="block-content tab-content px-0">
                                            <div class="tab-pane active" id="btabs-content" role="tabpanel">
                                                <div class="form-group">
                                                	<textarea id="js-ckeditor" name="content_description" class="content_description jsckeditor"></textarea>
                                                </div>
                                            </div>
                                            <div class="tab-pane" id="btabs-image" role="tabpanel">
                                                <div class="row">
                                                    <div class="col-xl-6">
                                                        <div class="form-group">
                                                            <div class="logo-fields-wrapper">
                                                                <div class="d-flex">
                                                                    <div class="logo-input flex-grow-1">
                                                                        <label>Thumbnail image:</label>
                                                                        <div class="input-group">
                                                                            <div class="custom-file thumbnails">
                                                                                <div>
                                                                                    <input type="file" class="form-control custom-file-input uploadthumbnail" id="thumbnail" name="thumbnail" data-toggle="custom-file-input" accept="image/*">
                                                                                    <label class="form-control custom-file-label" for="thumbnail" name="lbl_thumbnail" id="lbl_thumbnail">Choose file</label>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div id="thumbnail_preview_container" class="d-md-none">
                                                                        <div class="logo_preview_container ml-3">
                                                                            <img src="" id="thumbnail_preview" name="thumbnail_preview" alt="icon">
                                                                            <a href="javascript:void(0);" id="remove_thumbnail" name="remove_thumbnail" class="close-preview" data-toggle="tooltip" title="Delete">
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
                                                                                    <input type="file" class="form-control custom-file-input uploadbanner" id="logo" name="logo" data-toggle="custom-file-input" accept="image/png">
                                                                                    <label class="form-control custom-file-label" id="lbl_logo" name="lbl_logo" for="logo">Choose file</label>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div id="logo_preview_container" class="d-md-none">
                                                                        <div class="logo_preview_container ml-3">
                                                                            <img src="" id="logo_preview" name="logo_preview" alt="icon">
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
                                                        <div class="form-group{{ $errors->has('button_colour') ? ' is-invalid' : '' }}">
                                                            <label class="required" for="button_colour">Button colour:</label>
                                                            <div>
                                                                <div class="js-colorpicker input-group" data-format="hex">
                                                                    <input type="text" class="form-control" id="button_colour" name="button_colour" value="#ffba08">
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
                                                        <div class="form-group{{ $errors->has('button_text_colour') ? ' is-invalid' : '' }}">
                                                            <label class="required" for="button_text_colour">Button text colour:</label>
                                                            <div>
                                                                <div class="js-colorpicker input-group" data-format="hex">
                                                                    <input type="text" class="form-control" id="button_text_colour" name="button_text_colour" value="#12214C">
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
                                                        <div class="form-group{{ $errors->has('button_text') ? ' is-invalid' : '' }}">
                                                            <label for="button_text" class="required">Button text:</label>
                                                            <input type="text" class="form-control" id="button_text" name="button_text">
                                                            @if ($errors->has('button_text'))
                                                                <div class="invalid-feedback animated fadeInDown">
                                                                    <strong>{{ $errors->first('button_text') }}</strong>
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                     <div class="col-xl-6">
                                                        <div class="form-group{{ $errors->has('button_url') ? ' is-invalid' : '' }}">
                                                            <label for="button_url" class="required">Button url:</label>
                                                            <input type="text" class="form-control" id="button_url" name="button_url">
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
                                            Create
                                        </button>
                                        <a href="{{ route('backend.traveloffers.index', ['club' => app()->request->route('club')])}}" class="btn btn-hero btn-noborder btn-alt-secondary">
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
