@extends('layouts.backend')

@section('plugin-styles')
    <link rel="stylesheet" href="{{ asset('plugins/summernote/summernote-bs4.css')}}">
@endsection

@section('plugin-scripts')
    <script src="{{ asset('plugins/ckeditor/ckeditor.js') }}"></script>
@endsection

@section('page-scripts')
    <script src="{{ asset('js/backend/pages/categories/create.js') }}"></script>
@endsection

@section('content')
	<div class="block">
		<div class="block-header block-header-default">
            <h3 class="block-title">Add category</h3>
            <div class="block-options">
            </div>
        </div>
        <div class="block-content block-content-full">
        	<div class="row">
                <div class="col-xl-12">
		            <form class="create-category-form" action="{{ route('backend.category.store', ['club' => app()->request->route('club')]) }}" method="post" enctype="multipart/form-data">
		    			@csrf
                        <div class="row">
                            <div class="col-xl-6">
                                <div class="form-group{{ $errors->has('title') ? ' is-invalid' : '' }}">
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
                                    <div class="logo-fields-wrapper">
                                        <div class="d-flex">
                                            <div class="logo-input flex-grow-1">
                                                <label class="required">Image:</label>
                                                <div class="input-group">
                                                    <div class="custom-file">
                                                        <div>
                                                            <input type="file" class="form-control custom-file-input" id="logo" name="logo" data-toggle="custom-file-input" accept="image/png">
                                                            <label class="text-truncate form-control custom-file-label" for="logo">Choose file</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div id="logo_preview_container" class="d-md-none">
                                                <div class="logo_preview_container ml-3">
                                                    <img src="" id="logo_preview" alt="Category logo">
                                                </div>
                                            </div>
                                        </div>
                                        <label class="helper m-0">Image dimensions: 840px X 630px (png only)</label>
                                    </div>
                                </div>
                            </div>

							<div class="col-xl-6">
								<div class="form-group">
									<label for="rewards_percentage_override">Rewards percentage override:</label>
									<input type="text" class="form-control" name="rewards_percentage_override" id="rewards_percentage_override" value="{{ old('rewards_percentage_override') }}">
								</div>
							</div>

							<div class="col-xl-6">
								<div class="form-group">
									<label>Restrictions:</label>
									<div>
										<div class="custom-control custom-checkbox custom-control-inline mb-5">
											<input type="checkbox" class="custom-control-input" id="is_restricted_to_over_age" name="is_restricted_to_over_age" value="0">
											<label class="custom-control-label" for="is_restricted_to_over_age">Restricted to over 18s</label>
										</div>
									</div>
								</div>
							</div>

							<div class="col-xl-6">
								<div class="form-group">
									<label class="required">Type:</label>
									<div>
										@foreach($categoryType as $typeKey => $type)
											<div class="custom-control custom-radio custom-control-inline mb-5">
												<input class="custom-control-input" type="radio" name="type" id="type_{{$typeKey}}" value="{{$typeKey}}" {{ $typeKey == 'food_and_drink' ? 'checked': '' }}>
												<label class="custom-control-label" for="type_{{$typeKey}}">{{$type}}</label>
											</div>
										@endforeach()
									</div>
								</div>
                            </div>

							<div class="col-xl-6">
								<div class="form-group">
									<label class="required">Status:</label>
									<div>
										@foreach($categoryStatus as $statusKey => $status)
											<div class="custom-control custom-radio custom-control-inline mb-5">
												<input class="custom-control-input" type="radio" name="status" id="status_{{$statusKey}}" value="{{$status}}" {{ $statusKey == 'published' ? 'checked': '' }}>
												<label class="custom-control-label" for="status_{{$statusKey}}">{{$status}}</label>
											</div>
										@endforeach()
									</div>
								</div>
                            </div>


							<div class="col-xl-12">
                                <div class="form-group">
                                    <button type="submit" class="btn btn-hero btn-noborder btn-primary min-width-125">
                                        Create
                                    </button>
                                    <a href="{{ route('backend.category.index', ['club' => app()->request->route('club')])}}" class="btn btn-hero btn-noborder btn-alt-secondary">
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
