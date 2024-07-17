@extends('layouts.backend')

@section('plugin-styles')
	<link rel="stylesheet" href="{{ asset('plugins/summernote/summernote-bs4.css')}}">
@endsection

@section('plugin-scripts')
	<script src="{{ asset('plugins/ckeditor/ckeditor.js') }}"></script>
@endsection

@section('page-scripts')
    <script src="{{ asset('js/backend/pages/collectionpoints/edit.js') }}"></script>
@endsection

@section('content')
	<div class="block">
		<div class="block-header block-header-default">
			<h3 class="block-title">Edit collection point</h3>
			<div class="block-options">
			</div>
		</div>
		<div class="block-content block-content-full">
			<div class="row">
				<div class="col-xl-12">
					<form class="edit-collection-point-form" action="{{ route('backend.collectionpoint.update', ['club' => app()->request->route('club'), 'collectionpoint' => $collectionpoint ]) }}" method="post" enctype="multipart/form-data">
						{{ method_field('PUT') }}
						{{ csrf_field() }}
						<div class="row">
							<div class="col-xl-6">
								<div class="form-group{{ $errors->has('title') ? ' is-invalid' : '' }}">
									<label for="title" class="required">Title:</label>
									<input type="text" class="form-control" id="title" name="title" value="{{ $collectionpoint->title }}">
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
										@foreach($collectionPointStatus as $statusKey => $status)
											<div class="custom-control custom-radio custom-control-inline mb-5">
												<input class="custom-control-input" type="radio" name="status" id="status_{{$statusKey}}" value="{{$status}}" {{  $collectionpoint->status == $status ? 'checked' : '' }}>
												<label class="custom-control-label" for="status_{{$statusKey}}">{{$status}}</label>
											</div>
										@endforeach
									</div>
								</div>
							</div>
							<div class="col-xl-6 js-blocks {{ ($clubDetail->stadium && $clubDetail->stadium->is_using_allocated_seating == 0) ? 'd-none' : '' }}">
								<div class="form-group">
									<label class="{{ ($clubDetail->stadium && $clubDetail->stadium->is_using_allocated_seating == 1) ? 'required' : '' }}" for="blocks">Available for blocks:</label>
									<select class="custom-select form-control answer-group" id="blocks" name="blocks[]" multiple size="5">
										@foreach($stadiumBlocks as $blockKey => $block)
											<option {{ in_array($blockKey, $selectedStadiumBlocks) ? "selected" : "" }} value="{{ $blockKey }}">{{ $block }}</option>
										@endforeach
									</select>
								</div>
							</div>
							<div class="col-xl-12">
								<div class="form-group">
									<button type="submit" class="btn btn-hero btn-noborder btn-primary min-width-125">
										Update
									</button>
									<a href="{{ route('backend.collectionpoint.index', ['club' => app()->request->route('club')])}}" class="btn btn-hero btn-noborder btn-alt-secondary">
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
