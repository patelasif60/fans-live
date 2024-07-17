@extends('layouts.backend')

@section('page-scripts')
    <script src="{{ asset('js/backend/pages/clubcategories/edit.js') }}"></script>
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
		            <form class="edit-category-form" action="{{ route('backend.clubcategory.update', $category) }}" method="post" enctype="multipart/form-data">
		    			{{ method_field('PUT') }}
                        {{ csrf_field() }}
                        <div class="row">
                            <div class="col-xl-6">
                                <div class="form-group{{ $errors->has('name') ? ' is-invalid' : '' }}">
                                    <label for="name" class="required">Name:</label>
                                    <input type="text" class="form-control" id="name" name="name" value="{{ $category->name }}">
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
                                                <label class="required">Logo:</label>
                                                <div class="input-group">
                                                    <div class="custom-file">
                                                        <div>
                                                            <input type="hidden" value="{{ isset($category->logo_file_name) ? $category->logo_file_name : '' }}" id="logo_file_name" name="logo_file_name">
                                                            <input type="file" class="form-control custom-file-input" id="logo" name="logo" data-toggle="custom-file-input" accept="image/png">
                                                            <label class="form-control custom-file-label" for="logo">{{ $category->logo_file_name ? $category->logo_file_name : 'Choose file'}}</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div id="category-logo" class="{{ $category->logo ? '' : 'd-md-none' }}">
                                                <div id="logo_preview_container">
                                                    <div class="logo_preview_container ml-3">
                                                        <img id="logo_preview" src="{{ $category->logo }}" alt="Category logo">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <label class="helper m-0">Image dimensions: 150px X 150px (png only)</label>
                                    </div>
                                </div>

                                {{-- <div class="form-group row">
                                    <div class="{{ $category->logo ? 'col-9' : 'col-12' }} js-manage-logo-width">
                                        <label class="required">Logo:</label>
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="custom-file"> --}}
                                                    <!-- Populating custom file input label with the selected filename (data-toggle="custom-file-input" is initialized in Codebase() -> uiHelperCoreCustomFileInput()) -->
													{{-- <input type="hidden" value="{{ isset($category->logo_file_name) ? $category->logo_file_name : '' }}" id="logo_file_name" name="logo_file_name">
													<input type="file" class="form-control custom-file-input" id="logo" name="logo" data-toggle="custom-file-input" accept="image/png">
                                                    <label class="form-control custom-file-label" for="logo">{{ $category->logo_file_name ? $category->logo_file_name : 'Choose file'}}</label>
												</div>
												<label class="helper mt-5">Image dimensions: 150px X 150px ( png only )</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-3 {{ $category->logo ? '' : 'd-md-none' }}"
                                     id="category-logo">
                                            <div class="d-flex justify-content-center" id="logo_preview_container">
                                                <img id="logo_preview" src="{{ $category->logo }}" class="img-avatar img-avatar-square" alt="Competition logo">
                                            </div>
                                    </div>
                                </div> --}}
                            </div>

                            <div class="col-xl-6">
                                <div class="form-group">
                                    <label class="required">Status:</label>
                                    <div>
                                        @foreach($categoryStatus as $key => $status)
                                            <div class="custom-control custom-radio custom-control-inline mb-5">
                                                <input class="custom-control-input" type="radio" name="status" id="status_{{$key}}" value="{{$status}}" {{ $status == $category->status ? 'checked' : '' }}>
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
                                    <a href="{{ route('backend.clubcategory.index') }}" class="btn btn-hero btn-noborder btn-alt-secondary">
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
