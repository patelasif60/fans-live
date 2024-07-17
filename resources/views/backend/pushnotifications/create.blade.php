@extends('layouts.backend')

@section('plugin-styles')
@endsection

@section('plugin-scripts')
@endsection

@section('page-scripts')
	<script src="{{ asset('js/backend/pages/pushnotifications/create.js') }}"></script>
@endsection

@section('content')
	<div class="block">
		<div class="block-header block-header-default">
			<h3 class="block-title">Add push notification</h3>
			<div class="block-options">
			</div>
		</div>
		<div class="block-content block-content-full">
			<div class="row">
				<div class="col-xl-12">
					<form class="create-pushnotification-form" action="{{ route('backend.pushnotification.store',['club' => app()->request->route('club')]) }}" method="post">
						@csrf
						<div class="row">
							<div class="col-xl-6">
								<div class="form-group{{ $errors->has('title') ? ' is-invalid' : '' }}">
									<label for="title" class="required">Title:</label>
                                	<input id="title"  class="form-control" name="title" value="{{ old('title') }}">
									@if ($errors->has('title'))
										<div class="invalid-feedback animated fadeInDown">
											<strong>{{ $errors->first('title') }}</strong>
										</div>
									@endif
								</div>
							</div>

							<div class="col-xl-6">
                                <div class="form-group {{ $errors->has('publication_date') ? ' is-invalid' : '' }}">
                                    <label for="publication_date" class="required">Publication date:</label>
                                    <div class='input-group date js-datetimepicker' data-target-input="nearest" id="publication_date">
					                    <input type="text" class="form-control datetimepicker-input" name="publication_date" data-target="#publication_date" readonly value="{{ old('publication_date') }}" id="publication_datetime" data-toggle="datetimepicker"/>
					                    <div class="input-group-append" data-target="#publication_date" data-toggle="datetimepicker">
					                        <div class="input-group-text"><i class="fal fa-calendar-alt"></i></div>
					                    </div>
					                </div>

					                @if ($errors->has('publication_date'))
                                        <div class="invalid-feedback animated fadeInDown">
                                            <strong>{{ $errors->first('publication_date') }}</strong>
                                        </div>
                                    @endif
                                </div>
                            </div>

							<div class="col-xl-6">
								<div class="form-group{{ $errors->has('message') ? ' is-invalid' : '' }}">
									<label for="message" class="required">Message:</label>
                                	<textarea id="message"  class="form-control" maxlength="250" name="message"value="{{ old('message') }}"></textarea>
									<span id="push_notification_chars_count">250</span> characters remaining
									@if ($errors->has('message'))
										<div class="invalid-feedback animated fadeInDown">
											<strong>{{ $errors->first('message') }}</strong>
										</div>
									@endif
								</div>
							</div>

                            <div class="col-xl-6">
	                            <div class="form-group {{ $errors->has('swipe_action_category') ? ' is-invalid' : '' }}">
									<label for="swipe_action_category" class="required">Swipe action - category:</label>
	                                <select class="js-select2 js-select2-allow-clear form-control" id="swipe_action_category" name="swipe_action_category">
	                                	<option value="">Please select</option>
											@foreach($categories as $key => $category)
												<option value="{{ $key }}">{{ $category }}</option>
											@endforeach()
	                                </select>
	                                @if ($errors->has('swipe_action_category'))
                                        <div class="invalid-feedback animated fadeInDown">
                                            <strong>{{ $errors->first('swipe_action_category') }}</strong>
                                        </div>
                                    @endif
	                       		</div>
	                       	</div>

                            <div class="col-xl-6">
	                            <div class="form-group {{ $errors->has('send_to_user_attending_this_match') ? ' is-invalid' : '' }}">
									<label for="send_to_user_attending_this_match" class="required">Send to users attending this match:</label>
	                                <select class="js-select2 js-select2-allow-clear form-control" id="send_to_user_attending_this_match" name="send_to_user_attending_this_match">
	                                	<option value="">Please select</option>
	                                		@foreach($matches as $match)
												<option value="{{ $match->id }}">{{ $match->homeTeam->name }} vs {{ $match->awayTeam->name }} ({{ convertDateTimezone($match->kickoff_time, null, null,'jS F Y') }})</option>
											@endforeach()
	                                </select>
	                                @if ($errors->has('send_to_user_attending_this_match'))
                                        <div class="invalid-feedback animated fadeInDown">
                                            <strong>{{ $errors->first('send_to_user_attending_this_match') }}</strong>
                                        </div>
                                    @endif
	                       		</div>
	                       	</div>

	                       	<div class="col-xl-6 d-none" id="swipe_action_item_container">
	                            <div class="form-group {{ $errors->has('swipe_action_item') ? ' is-invalid' : '' }}">
	                            	<label for="swipe_action_item" class="required">Swipe action - item:</label>
	                                <select class="js-select2 js-select2-allow-clear form-control" id="swipe_action_item" name="swipe_action_item" style="width: 100%">
	                                	<option value="">Please select</option>
	                                </select>
	                                @if ($errors->has('swipe_action_item'))
                                        <div class="invalid-feedback animated fadeInDown">
                                            <strong>{{ $errors->first('swipe_action_item') }}</strong>
                                        </div>
                                    @endif
	                       		</div>
	                       	</div>
	                       	<div class="col-xl-6">
								<div class="form-group">
									<label class="required">Send to users with membership level:</label>
									@foreach($memberships as $membership)
										<div>
											<div class="custom-control custom-checkbox custom-control-inline mb-5">
												<input type="checkbox" class="custom-control-input custom-users-with-membership @if($membership->id == config('fanslive.ALL_FANS_MEMBERSHIP_PACKAGE_ID')) default-package @else premium-package @endif" id="membership_level_{{ $membership->id }}" name="membership_level[]" value="{{ $membership->id }}">
												<label class="custom-control-label" for="membership_level_{{ $membership->id }}">{{ $membership->title }}</label>
											</div>
										</div>
									@endforeach
								</div>
							</div>
						</div>
						<div class="row">
			                <div class="col-xl-12">
								<div class="form-group">
									<button type="submit" class="btn btn-hero btn-noborder btn-primary min-width-125">
										Create
									</button>
									<a href="{{ route('backend.pushnotification.index', ['club' => app()->request->route('club')]) }}" class="btn btn-hero btn-noborder btn-alt-secondary">
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
