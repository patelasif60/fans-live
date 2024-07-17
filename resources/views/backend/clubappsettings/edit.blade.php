@extends('layouts.backend')

@section('plugin-scripts')
	<script src="{{ asset('plugins/ckeditor/ckeditor.js') }}"></script>
@endsection

@section('page-scripts')
	<script src="{{ asset('js/backend/pages/clubpagesettings/edit.js') }}"></script>
@endsection

@section('content')
	<div class="block">
		<form class="club-app-settings-form" id="club_app_settings_form" action="{{ route('backend.clubappsetting.update', ['club' => app()->request->route('club')]) }}" method="post" enctype="multipart/form-data">
			{{ method_field('PUT') }}
			@csrf
			<div class="block-header block-header-default">
				<h3 class="block-title">App settings</h3>
			</div>

			<div class="block-content">
				<div class="block block-bordered">
					<div class="block-content">
						<ul class="nav nav-tabs nav-tabs-alt nav-tabs-block nav-justified" data-toggle="tabs" role="tablist">
			                <li class="nav-item">
			                    <a class="nav-link active" href="#btabs-app-modules">Enable app modules</a>
			                </li>
			                <li class="nav-item">
			                    <a class="nav-link" href="#btabs-opening-times">Opening times</a>
			                </li>
			                <li class="nav-item">
			                    <a class="nav-link" href="#btabs-loyalty-points">Loyalty points</a>
			                </li>
			                <li class="nav-item">
			                    <a class="nav-link" href="#btabs-text">Text</a>
			                </li>
			            </ul>
					</div>
					<div class="block-content tab-content">
			            <div class="tab-pane active" id="btabs-app-modules" role="tabpanel">
			            	<div class="row">
			            		@foreach($modules as $module)
				                   	<div class="col-xl-6">
										<div class="form-group">
											<label class="css-control css-control-sm css-control-warning css-switch css-switch-square">
		                                        <input type="checkbox" class="css-control-input" name="modules[]" value="{{ $module->id }}" {{ !empty($activeModules) && in_array($module->id, $activeModules) ? 'checked' : '' }}>
		                                        <span class="css-control-indicator"></span> {{ $module->title }}
		                                    </label>
			                            </div>
			            			</div>
		            			@endforeach
			            	</div>
			            </div>
			            <div class="tab-pane" id="btabs-opening-times" role="tabpanel">
			            	<div class="row">
			                   	<div class="col-xl-6">
									<div class="form-group {{ $errors->has('food_and_drink_minutes_open_before_kickoff') ? ' is-invalid' : '' }}">
										<label for="food_and_drink_minutes_open_before_kickoff" class="required">Food and drink opening before kick-off (mins):</label>
										<input type="text" class="form-control" id="food_and_drink_minutes_open_before_kickoff" name="food_and_drink_minutes_open_before_kickoff" value="{{ isset($club->clubOpeningTimeSettings->food_and_drink_minutes_open_before_kickoff) ? $club->clubOpeningTimeSettings->food_and_drink_minutes_open_before_kickoff : '' }}">
									</div>
									@if ($errors->has('food_and_drink_minutes_open_before_kickoff'))
		                                <div class="invalid-feedback animated fadeInDown">
		                                    <strong>{{ $errors->first('food_and_drink_minutes_open_before_kickoff') }}</strong>
		                                </div>
		                            @endif
								</div>

								<div class="col-xl-6">
									<div class="form-group {{ $errors->has('food_and_drink_minutes_closed_after_fulltime') ? ' is-invalid' : '' }}">
										<label for="food_and_drink_minutes_closed_after_fulltime" class="required">Food and drink closing after kick-off (mins):</label>
										<input type="text" class="form-control" id="food_and_drink_minutes_closed_after_fulltime" name="food_and_drink_minutes_closed_after_fulltime" value="{{ isset($club->clubOpeningTimeSettings->food_and_drink_minutes_closed_after_fulltime) ? $club->clubOpeningTimeSettings->food_and_drink_minutes_closed_after_fulltime : '' }}">
									</div>
									@if ($errors->has('food_and_drink_minutes_closed_after_fulltime'))
		                                <div class="invalid-feedback animated fadeInDown">
		                                    <strong>{{ $errors->first('food_and_drink_minutes_closed_after_fulltime') }}</strong>
		                                </div>
		                            @endif
								</div>

				                <div class="col-xl-6">
									<div class="form-group {{ $errors->has('merchandise_minutes_open_before_kickoff') ? ' is-invalid' : '' }}">
										<label for="merchandise_minutes_open_before_kickoff" class="required">Merchandise opening before kick-off (mins):</label>
										<input type="text" class="form-control" id="merchandise_minutes_open_before_kickoff" name="merchandise_minutes_open_before_kickoff" value="{{ isset($club->clubOpeningTimeSettings->merchandise_minutes_open_before_kickoff) ? $club->clubOpeningTimeSettings->merchandise_minutes_open_before_kickoff : '' }}">
									</div>
									@if ($errors->has('merchandise_minutes_open_before_kickoff'))
			                            <div class="invalid-feedback animated fadeInDown">
			                                <strong>{{ $errors->first('merchandise_minutes_open_before_kickoff') }}</strong>
			                            </div>
			                        @endif
								</div>

								<div class="col-xl-6">
									<div class="form-group {{ $errors->has('merchandise_minutes_closed_after_fulltime') ? ' is-invalid' : '' }}">
										<label for="merchandise_minutes_closed_after_fulltime" class="required">Merchandise closing after kick-off (mins):</label>
										<input type="text" class="form-control" id="merchandise_minutes_closed_after_fulltime" name="merchandise_minutes_closed_after_fulltime" value="{{ isset($club->clubOpeningTimeSettings->merchandise_minutes_closed_after_fulltime) ? $club->clubOpeningTimeSettings->merchandise_minutes_closed_after_fulltime : '' }}">
									</div>
									@if ($errors->has('merchandise_minutes_closed_after_fulltime'))
		                                <div class="invalid-feedback animated fadeInDown">
		                                    <strong>{{ $errors->first('merchandise_minutes_closed_after_fulltime') }}</strong>
		                                </div>
		                            @endif
								</div>

								<div class="col-xl-6">
									<div class="form-group {{ $errors->has('loyalty_rewards_minutes_open_before_kickoff') ? ' is-invalid' : '' }}">
										<label for="loyalty_rewards_minutes_open_before_kickoff" class="required">Loyalty rewards opening before kick-off (mins):</label>
										<input type="text" class="form-control" id="loyalty_rewards_minutes_open_before_kickoff" name="loyalty_rewards_minutes_open_before_kickoff" value="{{ isset($club->clubOpeningTimeSettings->loyalty_rewards_minutes_open_before_kickoff) ? $club->clubOpeningTimeSettings->loyalty_rewards_minutes_open_before_kickoff : '' }}">
									</div>
									@if ($errors->has('loyalty_rewards_minutes_open_before_kickoff'))
		                                <div class="invalid-feedback animated fadeInDown">
		                                    <strong>{{ $errors->first('loyalty_rewards_minutes_open_before_kickoff') }}</strong>
		                                </div>
		                            @endif
								</div>

								<div class="col-xl-6">
									<div class="form-group {{ $errors->has('loyalty_rewards_minutes_closed_after_fulltime') ? ' is-invalid' : '' }}">
										<label for="loyalty_rewards_minutes_closed_after_fulltime" class="required">Loyalty rewards closing after kick-off (mins):</label>
										<input type="text" class="form-control" id="loyalty_rewards_minutes_closed_after_fulltime" name="loyalty_rewards_minutes_closed_after_fulltime" value="{{ isset($club->clubOpeningTimeSettings->loyalty_rewards_minutes_closed_after_fulltime) ? $club->clubOpeningTimeSettings->loyalty_rewards_minutes_closed_after_fulltime : '' }}">
									</div>
									@if ($errors->has('loyalty_rewards_minutes_closed_after_fulltime'))
		                                <div class="invalid-feedback animated fadeInDown">
		                                    <strong>{{ $errors->first('loyalty_rewards_minutes_closed_after_fulltime') }}</strong>
		                                </div>
		                            @endif
								</div>

							</div>
			            </div>
			            <div class="tab-pane" id="btabs-loyalty-points" role="tabpanel">
			                <div class="row">
			                   	<div class="col-xl-6">
									<div class="form-group {{ $errors->has('food_and_drink_reward_percentage') ? ' is-invalid' : '' }}">
										<label for="food_and_drink_reward_percentage" class="required">Food and drink reward percentage:</label>
										<input type="text" class="form-control" id="food_and_drink_reward_percentage" name="food_and_drink_reward_percentage" value="{{ isset($club->clubLoyaltyPointSettings->food_and_drink_reward_percentage) ? $club->clubLoyaltyPointSettings->food_and_drink_reward_percentage : '' }}">
									</div>
									@if ($errors->has('food_and_drink_reward_percentage'))
		                                <div class="invalid-feedback animated fadeInDown">
		                                    <strong>{{ $errors->first('food_and_drink_reward_percentage') }}</strong>
		                                </div>
		                            @endif
								</div>

								<div class="col-xl-6">
									<div class="form-group {{ $errors->has('merchandise_reward_percentage') ? ' is-invalid' : '' }}">
										<label for="merchandise_reward_percentage" class="required">Merchandise reward percentage:</label>
										<input type="text" class="form-control" id="merchandise_reward_percentage" name="merchandise_reward_percentage" value="{{ isset($club->clubLoyaltyPointSettings->merchandise_reward_percentage) ? $club->clubLoyaltyPointSettings->merchandise_reward_percentage : '' }}">
									</div>
									@if ($errors->has('merchandise_reward_percentage'))
		                                <div class="invalid-feedback animated fadeInDown">
		                                    <strong>{{ $errors->first('merchandise_reward_percentage') }}</strong>
		                                </div>
		                            @endif
								</div>

								<div class="col-xl-6">
									<div class="form-group {{ $errors->has('tickets_reward_percentage') ? ' is-invalid' : '' }}">
										<label for="tickets_reward_percentage" class="required">Tickets reward percentage:</label>
										<input type="text" class="form-control" id="tickets_reward_percentage" name="tickets_reward_percentage" value="{{ isset($club->clubLoyaltyPointSettings->tickets_reward_percentage) ? $club->clubLoyaltyPointSettings->tickets_reward_percentage : '' }}">
									</div>
									@if ($errors->has('tickets_reward_percentage'))
		                                <div class="invalid-feedback animated fadeInDown">
		                                    <strong>{{ $errors->first('tickets_reward_percentage') }}</strong>
		                                </div>
		                            @endif
								</div>

								<div class="col-xl-6">
									<div class="form-group {{ $errors->has('membership_packages_reward_percentage') ? ' is-invalid' : '' }}">
										<label for="membership_packages_reward_percentage" class="required">Membership packages reward percentage:</label>
										<input type="text" class="form-control" id="membership_packages_reward_percentage" name="membership_packages_reward_percentage" value="{{ isset($club->clubLoyaltyPointSettings->membership_packages_reward_percentage) ? $club->clubLoyaltyPointSettings->membership_packages_reward_percentage : '' }}">
									</div>
									@if ($errors->has('membership_packages_reward_percentage'))
		                                <div class="invalid-feedback animated fadeInDown">
		                                    <strong>{{ $errors->first('membership_packages_reward_percentage') }}</strong>
		                                </div>
		                            @endif
								</div>

								<div class="col-xl-6">
									<div class="form-group {{ $errors->has('hospitality_reward_percentage') ? ' is-invalid' : '' }}">
										<label for="hospitality_reward_percentage" class="required">Hospitality rewards percentage:</label>
										<input type="text" class="form-control" id="hospitality_reward_percentage" name="hospitality_reward_percentage" value="{{ isset($club->clubLoyaltyPointSettings->hospitality_reward_percentage) ? $club->clubLoyaltyPointSettings->hospitality_reward_percentage : '' }}">
									</div>
									@if ($errors->has('hospitality_reward_percentage'))
		                                <div class="invalid-feedback animated fadeInDown">
		                                    <strong>{{ $errors->first('hospitality_reward_percentage') }}</strong>
		                                </div>
		                            @endif
								</div>

				                <div class="col-xl-6">
									<div class="form-group {{ $errors->has('events_reward_percentage') ? ' is-invalid' : '' }}">
										<label for="events_reward_percentage" class="required">Events rewards percentage:</label>
										<input type="text" class="form-control" id="events_reward_percentage" name="events_reward_percentage" value="{{ isset($club->clubLoyaltyPointSettings->events_reward_percentage) ? $club->clubLoyaltyPointSettings->events_reward_percentage : '' }}">
									</div>
									@if ($errors->has('events_reward_percentage'))
			                            <div class="invalid-feedback animated fadeInDown">
			                                <strong>{{ $errors->first('events_reward_percentage') }}</strong>
			                            </div>
			                        @endif
								</div>
							</div>
			            </div>
			            <div class="tab-pane" id="btabs-text" roel="tabpanel">
			            	<div class="row">
			            		<div class="col-12">
									<label for="hospitality_introduction_text" class="required">Hospitality - introduction text:</label>
		                        	<div class="form-group row">
		                        		<div class="col-12">
		                                    <textarea id="js-introduction-text" name="hospitality_introduction_text">{{ isset($club->clubTextSettings->hospitality_introduction_text) ? $club->clubTextSettings->hospitality_introduction_text : '' }}</textarea>
		                                </div>
		                            </div>
		                        </div>
			            		<div class="col-12">
									<label for="hospitality_post_purchase_text" class="required">Hospitality - post-purchase text:</label>
		                        	<div class="form-group row">
		                        		<div class="col-12">
		                                    <textarea id="js-purchase-text" name="hospitality_post_purchase_text">{{ isset($club->clubTextSettings->hospitality_post_purchase_text) ? $club->clubTextSettings->hospitality_post_purchase_text : '' }}</textarea>
		                                </div>
		                            </div>
		                        </div>
		                        <div class="col-12">
									<label for="membership_packages_introduction_text" class="required">Membership packages - introduction text:</label>
		                        	<div class="form-group row">
		                        		<div class="col-12">
		                                    <textarea id="js-packages-introduction-text" name="membership_packages_introduction_text">{{ isset($club->clubTextSettings->membership_packages_introduction_text) ? $club->clubTextSettings->membership_packages_introduction_text : '' }}</textarea>
		                                </div>
		                            </div>
		                        </div>
			            	</div>
			            </div>
			        </div>
			    </div>

			    <div class="row">
					<div class="col-xl-12">
						<div class="form-group">
							<button type="submit" class="btn btn-hero btn-noborder btn-primary min-width-125 js-clubpagesetting-save">Save
							</button>
						</div>
					</div>
				</div>
			</div>
		</form>
	</div>
@endsection
