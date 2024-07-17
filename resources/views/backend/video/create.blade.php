@extends('layouts.backend')

@section('plugin-scripts')
@endsection

@section('page-scripts')
    <script src="{{ asset('js/backend/pages/videos/create.js') }}"></script>
@endsection

@section('content')
	<div class="block">
		<div class="block-header block-header-default">
            <h3 class="block-title">Add video</h3>
            <div class="block-options">
            </div>
        </div>
        <div class="block-content block-content-full">
        	<div class="row">
                <div class="col-xl-12">
		            <form class="create-video-form" action="{{ route('backend.video.store', ['club' => app()->request->route('club')]) }}" method="post" enctype="multipart/form-data">
		    			@csrf
                        <div class="row">
                            <div class="col-xl-6">
                                <div class="form-group {{ $errors->has('title') ? ' is-invalid' : '' }}">
                                    <label for="title" class="required">Title:</label>
                                    <input type="text" class="form-control" id="title" name="title" value="{{ old('title') }}">
                                    @if ($errors->has('title'))
                                        <div class="invalid-feedback animated fadeInDown">
                                            <strong>{{ $errors->first('title') }}</strong>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-xl-6">
                                <div class="form-group">
                                    <label class="required">Status:</label>
                                    <div>
                                        @foreach($videoStatus as $key => $status)
                                            <div class="custom-control custom-radio custom-control-inline mb-5">
                                                <input class="custom-control-input" type="radio" name="status" id="status_{{$key}}" value="{{$status}}" {{ $key == 'published' ? 'checked': '' }}>
                                                <label class="custom-control-label" for="status_{{$key}}">{{$status}}</label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-6">
                                <div class="form-group {{ $errors->has('description') ? ' is-invalid' : '' }}">
                                    <label for="description" class="required">Description:</label>
                                    <textarea  class="form-control" name="description" id="description">{{ old('description') }}</textarea>
                                    @if ($errors->has('description'))
                                        <div class="invalid-feedback animated fadeInDown">
                                            <strong>{{ $errors->first('description') }}</strong>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-xl-6">
                                <div class="form-group">
                                    <div class="logo-fields-wrapper">
                                        <div class="d-flex">
                                            <div class="logo-input flex-grow-1">
                                                <label for="thumbnail" class="required">Thumbnail:</label>
                                                <div class="input-group">
                                                    <div class="custom-file">
                                                        <div>
                                                            <input type="file" class="form-control custom-file-input" id="thumbnail" name="thumbnail" data-toggle="custom-file-input" accept="image/png">
                                                            <label class="form-control custom-file-label text-truncate pr-100" for="thumbnail">Choose file</label>
                                                        </div>
                                                    </div>
                                                </div> 
                                                @if ($errors->has('thumbnail'))
                                                    <div class="invalid-feedback animated fadeInDown">
                                                        <strong>{{ $errors->first('thumbnail') }}</strong>
                                                    </div>
                                                @endif
                                            </div>
                                            <div id="thumbnail_preview_container" class="ml-3 d-md-none">
                                                <div class="logo_preview_container">
                                                    <img src="" id="thumbnail_preview" alt="Video thumbnail">
                                                </div>
                                            </div>
                                        </div>
                                        <label class="helper m-0">Thumbnail dimensions: 840px X 525px ( png only )</label>
                                    </div>
                                </div>
                                {{-- <div class="form-group row">
                                    <div class="col-12 js-manage-thumbnail-width">
                                        <label for="thumbnail" class="required">Thumbnail:</label>
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="custom-file">
                                                    <input type="file" class="form-control custom-file-input" id="thumbnail" name="thumbnail" data-toggle="custom-file-input" accept="image/png">
                                                    <label class="form-control custom-file-label text-truncate pr-100" for="thumbnail">Choose file</label>
                                                </div>
												<label class="helper mt-5">Thumbnail dimensions: 840px X 525px ( png only )</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-3">
                                        <div class="d-flex justify-content-center d-md-none" id="thumbnail_preview_container">
                                            <img src="" id="thumbnail_preview" class="img-avatar img-avatar-square mb-2 pull-right" alt="Video thumbnail">
                                        </div>
                                    </div>
                                </div>
								@if ($errors->has('thumbnail'))
									<div class="invalid-feedback animated fadeInDown">
										<strong>{{ $errors->first('thumbnail') }}</strong>
									</div>
								@endif --}}
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
                                    <div class="logo-fields-wrapper">
                                        <div class="d-flex">
                                            <div class="logo-input flex-grow-1">
                                                <label for="video" class="required">Video:</label>
                                                <div class="input-group">
                                                    <div class="custom-file">
                                                        <div>
                                                            <input type="file" class="form-control custom-file-input" id="video" name="video" data-toggle="custom-file-input" accept="video/*">
                                                            <label class="form-control custom-file-label text-truncate pr-100" for="video">Choose file</label>
                                                        </div>
                                                    </div>
                                                </div> 
                                            </div>
                                            <div id="video_preview_container" class="ml-3 d-md-none">
                                                <div class="logo_preview_container">
                                                    <img src="{{ asset('img/backend/video.png') }}" id="video_preview" alt="Category logo" data-toggle="modal" data-target="#view_video">
                                                </div>
                                            </div>
                                        </div>
                                        <label class="helper m-0">Extension (.mov, .mp4)</label>
                                    </div>
                                </div>
                                {{-- <div class="form-group row">
                                    <div class="col-12 js-manage-video-width">
                                        <label for="video" class="required">Video:</label>
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="custom-file">
                                                    <input type="file" class="form-control custom-file-input" id="video" name="video" data-toggle="custom-file-input" accept="video/*">
                                                    <label class="form-control custom-file-label text-truncate pr-100" for="video">Choose file</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-3">
                                        <div class="d-flex justify-content-center d-md-none" id="video_preview_container">
                                            <img src="{{ asset('img/backend/video.png') }}" id="video_preview" class="img-avatar img-avatar-square mt-2 pull-right" alt="Category logo" data-toggle="modal" data-target="#view_video">
                                        </div>
                                    </div>
                                </div> --}}
                            </div>

							<div class="col-xl-12">
								<div class="form-group">
									<label class="required">Access:</label>
									<div class="row custom-check-box">
										@foreach($membershipPackages as $key => $package)
											{{-- @if($key == 0 || $key%2 != 0)
												<div class="col-xl-3">
                                                    <div class="custom-control custom-checkbox custom-control-inline">
                                                        <input type="checkbox" class="custom-control-input @if($key == config('fanslive.ALL_FANS_MEMBERSHIP_PACKAGE_ID')) default-package @else premium-package @endif" name="access[]" id="access_{{ $package->id }}" value="{{ $package->id }}">
                                                        <label class="custom-control-label" for="access_{{ $package->id }}">{{ $package->title }}</label>
                                                    </div>
												</div>
											@else
												<div class="col-xl-3">
                                                    <div class="custom-control custom-checkbox custom-control-inline">
                                                        <input type="checkbox" class="custom-control-input @if($key == config('fanslive.ALL_FANS_MEMBERSHIP_PACKAGE_ID')) default-package @else premium-package @endif" name="access[]" id="access_{{ $package->id }}" value="{{ $package->id }}">
                                                        <label class="custom-control-label" for="access_{{ $package->id }}">{{ $package->title }}</label>
                                                    </div>
												</div>
											@endif --}}
                                            <div class="col-xl-3">
                                                <div class="custom-control custom-checkbox custom-control-inline">
                                                    <input type="checkbox" class="custom-control-input @if($package->id == config('fanslive.ALL_FANS_MEMBERSHIP_PACKAGE_ID')) default-package @else premium-package @endif" name="access[]" id="access_{{ $package->id }}" value="{{ $package->id }}">
                                                    <label class="custom-control-label" for="access_{{ $package->id }}">{{ $package->title }}</label>
                                                </div>
                                            </div>
										@endforeach
									</div>
								</div>
							</div>

							<div class="col-xl-12">
                                <div class="form-group">
                                    <button type="submit" class="btn btn-hero btn-noborder btn-primary min-width-125">
                                        Create
                                    </button>
                                    <a href="{{ route('backend.video.index', ['club' => app()->request->route('club')])}}" class="btn btn-hero btn-noborder btn-alt-secondary">
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

<!-- Fade In Modal -->
    <div class="modal fade" id="view_video"  role="dialog" aria-labelledby="view_video" aria-hidden="true">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
                <div class="block block-themed block-transparent mb-0">
                    <div class="block-header bg-primary-dark">
                        <h3 class="block-title">Video</h3>
                        <div class="block-options">
                            <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                                <i class="si si-close"></i>
                            </button>
                        </div>
                    </div>
                    <div class="block-content">
                        <video width="100%" controls>
                            <source src="" id="video_here">
                            Your browser does not support HTML5 video.
                        </video>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-hero btn-noborder btn-alt-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
<!-- END Fade In Modal -->
@endsection
