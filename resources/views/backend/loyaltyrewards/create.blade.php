@extends('layouts.backend')

@section('plugin-styles')
@endsection

@section('plugin-scripts')
    <script src="{{ asset('plugins/ckeditor/ckeditor.js') }}"></script>
	<script src="{{ asset('plugins/jquery-repeater/jquery.repeater.min.js') }}"></script>
@endsection

@section('page-scripts')
    <script src="{{ asset('js/backend/pages/loyaltyrewards/create.js') }}"></script>
@endsection

@section('content')
	<div class="block">
		<div class="block-header block-header-default">
            <h3 class="block-title">Add loyalty reward</h3>
            <div class="block-options">
            </div>
        </div>
        <div class="block-content block-content-full">
            <div class="row">
                <div class="col-xl-12">
		              <form class="create-loyaltyreward-form repeater" action="{{ route('backend.loyaltyreward.store', ['club' => app()->request->route('club')]) }}" method="post" enctype="multipart/form-data">
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
                                                                <input type="file" class="form-control custom-file-input uploadimage" id="image" name="image" data-toggle="custom-file-input" accept="image/png">
                                                                <label class="form-control custom-file-label" for="image">Choose file</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div id="image_preview_container" class="ml-3 d-md-none">
                                                    <div class="logo_preview_container">
                                                        <img src="" id="image_preview" alt="Image">
                                                    </div>
                                                </div>
                                            </div>
                                            <label class="helper m-0">Image dimensions: 840px X 630px (png only)</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-6">
                                    <div class="form-group">
                                        <label for="description" class="required">Description:</label>
                                        <textarea id="js-ckeditor" name="description" class="content_description jsckeditor"></textarea>
                                     </div>
                                </div>
                                <div class="col-xl-6">
                                    <div class="form-group">
                                        <label>Collection points:</label>

                                        @foreach($collectionPoints as $key => $title)
                                            <div>
                                                <div class="custom-control custom-checkbox custom-control-inline mb-5">
                                                    <input disabled checked type="checkbox" class="custom-control-input" id="collection_points_{{ $key }}" name="collection_points[{{ $key }}]">
                                                    <label class="custom-control-label" for="collection_points_{{ $key }}">{{ $title }}</label>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                    <div class="form-group{{ $errors->has('price_in_points') ? ' is-invalid' : '' }}">
                                        <label for="price_in_points" class="required">Price in points:</label>
                                        <input type="text" class="form-control" id="price_in_points" name="price_in_points" min="0">
                                        @if ($errors->has('price_in_points'))
                                            <div class="invalid-feedback animated fadeInDown">
                                                <strong>{{ $errors->first('price_in_points') }}</strong>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="form-group">
                                        <label>Restrictions:</label>
                                        <div>
                                            <div class="custom-control custom-checkbox custom-control-inline mb-5">
                                                <input type="checkbox" class="custom-control-input" id="is_restricted_to_over_age" name="is_restricted_to_over_age">
                                                <label class="custom-control-label" for="is_restricted_to_over_age">Restricted to over 18s</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="required">Status:</label>
                                        <div>
                                            @foreach($loyaltyRewardsStatus as $key => $status)
                                                <div class="custom-control custom-radio custom-control-inline mb-5">
                                                    <input class="custom-control-input" type="radio" name="status" id="status_{{$key}}" value="{{$status}}" {{ $key == 'published' ? 'checked': '' }}>
                                                    <label class="custom-control-label" for="status_{{$key}}">{{$status}}</label>
                                                </div>
                                            @endforeach()
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!--Custom Option Html-->
                            <div class="row">
                                <div class="col-xl-12">
                                    <div class="content-heading">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <h5 class="mb-0">Options</h5>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row js-custom-option-div" id="add_custom_option"></div>

                            <div class="row js-line-ups-detail-div">
                                <div class="col-xl-4">
                                    <div class="form-group">
                                        <button type="button" class="btn btn-block btn-noborder btn-primary js-added-home js-custom-option-btn">Add option</span></button>
                                    </div>
                                </div>
                            </div>
                            <!--End - Custom option Html-->

                            <div class="row">
                                <div class="col-xl-12">
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-hero btn-noborder btn-primary min-width-125 js-loyaltyrewards-save">
                                            Create
                                        </button>
                                        <a href="{{ route('backend.loyaltyreward.index', ['club' => app()->request->route('club')])}}" class="btn btn-hero btn-noborder btn-alt-secondary">
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
