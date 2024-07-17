@extends('layouts.backend')

@section('page-scripts')
	<script src="{{ asset('js/backend/pages/contentfeeds/create.js') }}"></script>
@endsection

@section('content')
	<div class="block">
		<div class="block-header block-header-default">
			<h3 class="block-title">Add content feed</h3>
			<div class="block-options">
			</div>
		</div>
		<div class="block-content block-content-full">
			<div class="row">
				<div class="col-xl-12">
					<form class="create-content-feed-form" action="{{ route('backend.contentfeed.store', ['club' => app()->request->route('club')]) }}" method="post" enctype="multipart/form-data">
						@csrf
						<div class="row">
							<div class="col-xl-6">
								<div class="form-group">
		                            <label class="required">Type:</label>
		                            <div>
		                            	<select class="js-select2 form-control js-content-feed-type" id="type" name="type">
                                            @foreach($feedTypes as $feedType)
                                                <option {{ old('type') == $feedType ? 'selected' : '' }} value="{{ $feedType }}">{{ $feedType }}</option>
                                            @endforeach
                                        </select>
		                            </div>
		                        </div>
			                </div>

							<div class="col-xl-6">
								<div class="form-group{{ $errors->has('name') ? ' is-invalid' : '' }}">
									<label for="name" class="required">Name:</label>
									<input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}">
									@if ($errors->has('name'))
										<div class="invalid-feedback animated fadeInDown">
											<strong>{{ $errors->first('name') }}</strong>
										</div>
									@endif
								</div>
							</div>

	                    	<div class="col-xl-6 js-content-feed-app-id">
		                        <div class="form-group{{ $errors->has('api_app_id') ? ' is-invalid' : '' }}">
									<label for="api_app_id" class="required">App ID:</label>
									<input type="text" class="form-control" id="api_app_id" name="api_app_id" value="{{ old('api_app_id') }}">
									@if ($errors->has('api_app_id'))
										<div class="invalid-feedback animated fadeInDown">
											<strong>{{ $errors->first('api_app_id') }}</strong>
										</div>
									@endif
								</div>
							</div>

							<div class="col-xl-6 js-content-feed-app-key">
		                        <div class="form-group{{ $errors->has('api_key') ? ' is-invalid' : '' }}">
									<label for="api_key" class="required">App key:</label>
									<input type="text" class="form-control" id="api_key" name="api_key" value="{{ old('api_key') }}">
									@if ($errors->has('api_key'))
										<div class="invalid-feedback animated fadeInDown">
											<strong>{{ $errors->first('api_key') }}</strong>
										</div>
									@endif
								</div>
							</div>
							<div class="col-xl-6 js-content-feed-secert-key">
		                        <div class="form-group{{ $errors->has('api_secret_key') ? ' is-invalid' : '' }}">
									<label for="api_secret_key" class="required">API secret key:</label>
									<input type="text" class="form-control" id="api_secret_key" name="api_secret_key" value="{{ old('api_secret_key') }}">
									@if ($errors->has('api_secret_key'))
										<div class="invalid-feedback animated fadeInDown">
											<strong>{{ $errors->first('api_secret_key') }}</strong>
										</div>
									@endif
								</div>
							</div>
							
							<div class="col-xl-6 js-content-feed-app-token">
		                        <div class="form-group{{ $errors->has('api_token') ? ' is-invalid' : '' }}">
									<label for="api_token" class="required">Token:</label>
									<input type="text" class="form-control" id="api_token" name="api_token" value="{{ old('api_token') }}">
									@if ($errors->has('api_token'))
										<div class="invalid-feedback animated fadeInDown">
											<strong>{{ $errors->first('api_token') }}</strong>
										</div>
									@endif
								</div>
							</div>
							<div class="col-xl-6 js-content-feed-token-secert-key">
		                        <div class="form-group{{ $errors->has('api_token_secret_key') ? ' is-invalid' : '' }}">
									<label for="api_token_secret_key" class="required">Token secret key:</label>
									<input type="text" class="form-control" id="api_token_secret_key" name="api_token_secret_key" value="{{ old('api_token_secret_key') }}">
									@if ($errors->has('api_token_secret_key'))
										<div class="invalid-feedback animated fadeInDown">
											<strong>{{ $errors->first('api_token_secret_key') }}</strong>
										</div>
									@endif
								</div>
							</div>
							
							<div class="col-xl-6 js-content-feed-channel-id">
		                        <div class="form-group{{ $errors->has('api_channel_id') ? ' is-invalid' : '' }}">
									<label for="api_channel_id" class="required">Channel ID:</label>
									<input type="text" class="form-control" id="api_channel_id" name="api_channel_id" value="{{ old('api_channel_id') }}">
									@if ($errors->has('api_channel_id'))
										<div class="invalid-feedback animated fadeInDown">
											<strong>{{ $errors->first('api_channel_id') }}</strong>
										</div>
									@endif
								</div>
							</div>
							<div class="col-xl-6 js-content-feed-rss">
		                        <div class="form-group{{ $errors->has('rss_url') ? ' is-invalid' : '' }}">
									<label for="rss_url" class="required">RSS url:</label>
									<input type="text" class="form-control" id="rss_url" name="rss_url" value="{{ old('rss_url') }}">
									@if ($errors->has('rss_url'))
										<div class="invalid-feedback animated fadeInDown">
											<strong>{{ $errors->first('rss_url') }}</strong>
										</div>
									@endif
								</div>
							</div>

							<div class="col-xl-12">
								<div class="form-group">
		                        	<div class="custom-control custom-checkbox mb-5">
			                            <input class="custom-control-input" type="checkbox" name="automatically_publish_items" id="automatically_publish_items" value="1">
			                            <label class="custom-control-label" for="automatically_publish_items">Automatically publish items?</label>
		                        	</div>
		                        </div>
							</div>
			                
			                <div class="col-xl-12">
								<div class="form-group">
									<button type="submit" class="btn btn-hero btn-noborder btn-primary min-width-125">
										Create
									</button>
									<a href="{{ route('backend.contentfeed.index', ['club' => app()->request->route('club')]) }}" class="btn btn-hero btn-noborder btn-alt-secondary">
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
