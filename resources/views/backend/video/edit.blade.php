@extends('layouts.backend')

@section('plugin-scripts')
@endsection

@section('page-scripts')
    <script src="{{ asset('js/backend/pages/videos/edit.js') }}"></script>
@endsection

@section('content')
	<div class="block">
		<div class="block-header block-header-default">
            <h3 class="block-title">Edit video</h3>
            <div class="block-options">
            </div>
        </div>
        <div class="block-content block-content-full">
        	<div class="row">
                <div class="col-xl-12">
		            <form class="edit-video-form" action="{{ route('backend.video.update', ['club' => app()->request->route('club'), 'video' => $video]) }}" method="post" enctype="multipart/form-data">
		    			{{ method_field('PUT') }}
                        {{ csrf_field() }}
                        <div class="row">
                            <div class="col-xl-6">
                                <div class="form-group {{ $errors->has('title') ? ' is-invalid' : '' }}">
                                    <label for="title" class="required">Title:</label>
                                    <input type="text" class="form-control" id="title" name="title" value="{{ $video->title }}">
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
                                        @foreach($videoStatus as $statuskey => $status)
                                           <div class="custom-control custom-radio custom-control-inline mb-5">
                                                <input class="custom-control-input" type="radio" name="status" id="status_{{$statuskey}}" value="{{$status}}" {{ $video->status == $status ? 'checked' : '' }}>
                                                <label class="custom-control-label" for="status_{{$statuskey}}">{{$status}}</label>
                                            </div>
                                        @endforeach()
                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-6">
                                <div class="form-group {{ $errors->has('description') ? ' is-invalid' : '' }}">
                                    <label for="description" class="required">Description:</label>
                                    <textarea  class="form-control" name="description" id="description">{{ $video->description }}</textarea>
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
                                                            <input type="hidden" value="{{ $video->image_file_name }}" id="image_file_name" name="image_file_name">
                                                            <input type="file" class="form-control custom-file-input" id="thumbnail" name="thumbnail" data-toggle="custom-file-input" accept="image/png">
                                                            <label id="lbl_thumbnail" class="form-control custom-file-label text-truncate pr-100" for="thumbnail">{{ $video->image_file_name ? $video->image_file_name  : 'Choose file'}}</label>
                                                        </div>
                                                    </div>
                                                </div> 
                                            </div>
                                            <div id="thumbnail_preview_container" class="ml-3 {{ $video->image ? '' : 'd-md-none' }}">
                                                <div class="logo_preview_container">
                                                    <img src="{{ $video->image }}" id="thumbnail_preview" alt="Video thumbnail">
                                                </div>
                                            </div>
                                        </div>
                                        <label class="helper m-0">Thumbnail dimensions: 840px X 525px ( png only )</label>
                                    </div>
                                </div>
                                {{-- <div class="form-group row">
                                    <div class="{{ $video->image ? 'col-9' : 'col-12' }} js-manage-thumbnail-width">
										<label for="thumbnail" class="required">Thumbnail:</label>
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="custom-file">
                                                    <input type="file" class="form-control custom-file-input" id="thumbnail" name="thumbnail" data-toggle="custom-file-input" accept="image/png">
                                                    <input type="hidden" value="{{ $video->image_file_name }}" id="image_file_name" name="image_file_name">
                                                    <label id="lbl_thumbnail" class="form-control custom-file-label text-truncate pr-100" for="thumbnail">{{ $video->image_file_name ? $video->image_file_name  : 'Choose file'}}</label>
                                                </div>
												<label class="helper mt-5">Thumbnail dimensions: 840px X 525px ( png only )</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-3 {{ $video->image ? '' : 'd-md-none' }}">
                                        <div class="d-flex justify-content-center" id="thumbnail_preview_container">
                                            <img src="{{ $video->image }}" id="thumbnail_preview" class="img-avatar img-avatar-square mb-2 pull-right" alt="Video thumbnail">
                                        </div>
                                    </div>
                                </div> --}}
                            </div>

                            <div class="col-xl-6">
                                <div class="form-group {{ $errors->has('pubdate') ? ' is-invalid' : '' }}">
                                    <label for="email" class="required">Publication date:</label>
                                    <div class='input-group date js-datetimepicker' data-target-input="nearest" id="pubdate">
                                        <input type="text" class="form-control datetimepicker-input" name="pubdate" data-target="#pubdate" value="{{ convertDateTimezone($video->publication_date, null, $clubDetail->time_zone, config('fanslive.DATE_TIME_CMS_FORMAT.php')) }}" readonly data-toggle="datetimepicker"/>
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
                                                            <input type="hidden" value="{{ $video->video_file_name }}" id="video_file_name" name="video_file_name">
                                                            <input type="file" class="form-control custom-file-input" id="video" name="video" data-toggle="custom-file-input" accept="video/*">
                                                            <label id="lbl_video" class="form-control custom-file-label text-truncate pr-100" for="video">{{ $video->video_file_name ? $video->video_file_name  : 'Choose file'}}</label>
                                                        </div>
                                                    </div>
                                                </div> 
                                            </div>
                                            <div id="video_preview_container" class="ml-3 {{ $video->video ? '' : 'd-md-none' }}">
                                                <div class="logo_preview_container">
                                                    <img src="{{ asset('img/backend/video.png') }}" id="video_preview" alt="Category logo" data-toggle="modal" data-target="#view_video">
                                                </div>
                                            </div>
                                        </div>
                                        <label class="helper m-0">Extension (.mov, .mp4)</label>
                                    </div>
                                </div>
                                {{-- <div class="form-group row">
                                    <div class="{{ $video->video ? 'col-9' : 'col-12' }} js-manage-video-width">
										<label for="video" class="required">Video:</label>
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="custom-file">
                                                    <input type="file" class="form-control custom-file-input" id="video" name="video" data-toggle="custom-file-input" accept="video/*">
                                                    <input type="hidden" value="{{ $video->video_file_name }}" id="video_file_name" name="video_file_name">
                                                    <label id="lbl_video" class="form-control custom-file-label text-truncate pr-100" for="video">{{ $video->video_file_name ? $video->video_file_name  : 'Choose file'}}</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-3 {{ $video->video ? '' : 'd-md-none' }}">
                                        <div class="d-flex justify-content-center" id="video_preview_container">
                                            <img src="{{ asset('img/backend/video.png') }}" id="video_preview" class="img-avatar img-avatar-square mt-2 pull-right" alt="Category logo" data-toggle="modal" data-target="#view_video">
                                        </div>
                                    </div>
                                </div> --}}
                            </div>

							<div class="col-xl-12">
								<div class="form-group">
									<label class="required">Access:</label>
									<div class="row">
                                        @if(in_array(config('fanslive.ALL_FANS_MEMBERSHIP_PACKAGE_ID'), $membershipPackagesIds))
                                            @foreach($membershipPackages as $key => $package)
                                                <div class="col-xl-3">
                                                    <div class="custom-control custom-checkbox custom-control-inline">
                                                        <input type="checkbox" class="custom-control-input @if($package->id == config('fanslive.ALL_FANS_MEMBERSHIP_PACKAGE_ID')) default-package @else premium-package @endif" name="access[]" id="access_{{ $package->id }}" value="{{ $package->id }}" {{ in_array($package->id, $membershipPackagesIds) && ($package->id == config('fanslive.ALL_FANS_MEMBERSHIP_PACKAGE_ID')) ? 'checked' : 'checked disabled' }}>
                                                        <label class="custom-control-label" for="access_{{ $package->id }}">{{ $package->title }}</label>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @else
                                            @foreach($membershipPackages as $key => $package)
                                               <div class="col-xl-3">
                                                    <div class="custom-control custom-checkbox custom-control-inline">
                                                        <input type="checkbox" class="custom-control-input @if($package->id == config('fanslive.ALL_FANS_MEMBERSHIP_PACKAGE_ID')) default-package @else premium-package @endif" name="access[]" id="access_{{ $package->id }}" value="{{ $package->id }}" {{ in_array($package->id, $membershipPackagesIds) ? 'checked' : '' }}>
                                                        <label class="custom-control-label" for="access_{{ $package->id }}">{{ $package->title }}</label>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @endif
									</div>
								</div>
							</div>



                            <div class="col-xl-12">
                                <div class="form-group">
                                    <button type="submit" class="btn btn-hero btn-noborder btn-primary min-width-125">
                                        Update
                                    </button>
                                    <a href="{{ route('backend.video.index', ['club' => app()->request->route('club')]) }}" class="btn btn-hero btn-noborder btn-alt-secondary">
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
                            <source src="{{ $video->video  ? $video->video  : ''}}" id="video_here">
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
