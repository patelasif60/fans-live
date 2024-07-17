@extends('layouts.backend')

@section('plugin-styles')
	<link rel="stylesheet" href="{{ asset('plugins/summernote/summernote-bs4.css')}}">
@endsection

@section('plugin-scripts')
    <script src="{{ asset('plugins/ckeditor/ckeditor.js') }}"></script>
@endsection

@section('page-scripts')
    <script src="{{ asset('js/backend/pages/categories/edit.js') }}"></script>
@endsection

@section('content')
	<div class="block">
		<div class="block-header block-header-default">
            <h3 class="block-title">Edit category</h3>
            <div class="block-options">
            </div>
        </div>
        <div class="block-content block-content-full">
        	<div class="row">
                <div class="col-xl-12">
		            <form class="edit-category-form" action="{{ route('backend.category.update', ['club' => app()->request->route('club'), 'category' => $category]) }}" method="post" enctype="multipart/form-data">
		    			{{ method_field('PUT') }}
                        {{ csrf_field() }}
                        <div class="row">
                            <div class="col-xl-6">
                                <div class="form-group{{ $errors->has('title') ? ' is-invalid' : '' }}">
                                    <label for="title" class="required">Title:</label>
                                    <input type="text" class="form-control" id="title" name="title" value="{{ $category->title }}">
                                    @if ($errors->has('title'))
                                        <div class="invalid-feedback animated fadeInDown">
                                            <strong>{{ $errors->first('title') }}</strong>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div class="col-xl-6">
                                <div class="form-group {{ $category->image }}">
                                    <div class="logo-fields-wrapper">
                                        <div class="d-flex">
                                            <div class="logo-input flex-grow-1">
                                                <label class="required">Image:</label>
                                                <div class="input-group">
                                                    <div class="custom-file">
                                                        <div>
                                                            <input type="hidden" value="{{ isset($category->image_file_name) ? $category->image_file_name : '' }}" id="image_file_name" name="image_file_name">
                                                            <input type="file" class="form-control custom-file-input" id="logo" name="logo" data-toggle="custom-file-input" accept="image/png">
                                                            <label class="text-truncate form-control custom-file-label" for="logo">{{ isset($category->image_file_name) ? $category->image_file_name : 'Choose file' }}</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div id="logo_preview_div" class="ml-3 {{ $category->image ? '' : 'd-md-none' }}">
                                                <div id="logo_preview_container">
                                                    <div class="logo_preview_container">
                                                        <img src="{{ $category->image }}" alt="Category logo" id="logo_preview">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <label class="helper m-0">Image dimensions: 840px X 630px (png only)</label>
                                    </div>
                                </div>
                                {{-- <div class="form-group row">
                                    <div class="{{ $category->image ? 'col-9' : 'col-12' }} {{ $category->image }} js-manage-logo-width">
                                        <label class="required">Image:</label>
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="custom-file">
													<input type="hidden" value="{{ isset($category->image_file_name) ? $category->image_file_name : '' }}" id="image_file_name" name="image_file_name">
                                                    <input type="file" class="form-control custom-file-input" id="logo" name="logo" data-toggle="custom-file-input" accept="image/png">
                                                    <label class="form-control custom-file-label" for="logo">{{ isset($category->image_file_name) ? $category->image_file_name : 'Choose file' }}</label>
                                                </div>
												<label class="helper mt-5">Image dimensions: 840px X 630px ( png only )</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-3 {{ $category->image ? '' : 'd-md-none' }}"
                                     id="logo_preview_div">
                                            <div class="d-flex justify-content-center" id="logo_preview_container">
                                                <img src="{{ $category->image }}" class="img-avatar img-avatar-square" alt="Category logo">
                                            </div>
                                    </div>
                                </div> --}}
                            </div>

							<div class="col-xl-6">
								<div class="form-group">
									<label for="rewards_percentage_override">Rewards percentage override:</label>
									<input type="text" class="form-control" name="rewards_percentage_override" id="rewards_percentage_override" value="{{ $category->rewards_percentage_override }}">
								</div>
							</div>

							<div class="col-xl-6">
								<div class="form-group">
									<label>Restrictions:</label>
									<div>
										<div class="custom-control custom-checkbox custom-control-inline mb-5">
											<input type="checkbox" class="custom-control-input" id="is_restricted_to_over_age" name="is_restricted_to_over_age" value="{{ $category->is_restricted_to_over_age  }}" @if($category->is_restricted_to_over_age == 1) checked @endif>
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
												<input class="custom-control-input" type="radio" name="type" id="type_{{$typeKey}}" value="{{$typeKey}}" {{ $typeKey == $category->type ? 'checked': '' }}>
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
													<input class="custom-control-input" type="radio" name="status" id="status_{{$statusKey}}" value="{{$status}}" {{ $category->status == $status ? 'checked' : '' }}>
													<label class="custom-control-label" for="status_{{$statusKey}}">{{$status}}</label>
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
                                    <a href="{{ route('backend.category.index', ['club' => app()->request->route('club')]) }}" class="btn btn-hero btn-noborder btn-alt-secondary">
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
