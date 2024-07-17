@extends('layouts.backend')

@section('plugin-scripts')
	<script src="{{ asset('plugins/jquery-repeater/jquery.repeater.min.js') }}"></script>
@endsection

@section('page-scripts')
	<script src="{{ asset('js/backend/pages/matches/create.js') }}"></script>
@endsection

@section('content')
	<div class="block">
		<form class="create-match-form repeater" id="add_match_form"
			  action="{{ route('backend.matches.store', ['club' => app()->request->route('club')]) }}" method="post"
			  enctype="multipart/form-data">
			@csrf
			<div class="block-header block-header-default">
				<h3 class="block-title">Add match</h3>
				<div class="block-options d-inline-flex align-items-center">
					<div class="custom-control custom-checkbox">
						<input class="custom-control-input" type="checkbox" value="1" name="is_published"
							   id="is_published" checked>
						<label class="custom-control-label" for="is_published">Is published?</label>
					</div>
				</div>
			</div>

			<div class="block-content">
				<div class="block block-bordered">
					<div class="block-content">
						<ul class="nav nav-tabs nav-tabs-alt nav-tabs-block nav-justified" data-toggle="tabs"
							role="tablist">
							<li class="nav-item">
								<a class="nav-link active" href="#btabs-settings">Settings</a>
							</li>
							<li class="nav-item">
								<a class="nav-link" href="#btabs-ticketing">Ticketing</a>
							</li>
							<li class="nav-item">
								<a class="nav-link" href="#btabs-hospitality">Hospitality</a>
							</li>
							<li class="nav-item">
								<a class="nav-link" href="#btabs-line-ups">Line ups</a>
							</li>
							<li class="nav-item">
								<a class="nav-link" href="#btabs-match-events">Match events</a>
							</li>
						</ul>
					</div>

					<div class="block-content tab-content">
						<div class="tab-pane active" id="btabs-settings" role="tabpanel">
							<div class="row">
								<div class="col-xl-6">
									<div class="form-group{{ $errors->has('home') ? ' is-invalid' : '' }}">
										<label for="home" class="required">Home:</label>
										<div>
											<select class="js-select2 form-control" id="home" name="home">
												<option value="">Please select</option>
												@foreach($clubs as $club)
													<option value="{{ $club->id }}">{{ $club->name }}</option>
												@endforeach
											</select>
										</div>
										@if ($errors->has('home'))
											<div class="invalid-feedback animated fadeInDown">
												<strong>{{ $errors->first('home') }}</strong>
											</div>
										@endif
									</div>
								</div>

								<div class="col-xl-6">
									<div class="form-group{{ $errors->has('away') ? ' is-invalid' : '' }}">
										<label for="away" class="required">Away:</label>
										<div>
											<select class="js-select2 form-control" id="away" name="away">
												<option value="">Please select</option>
												@foreach($clubs as $club)
													<option value="{{ $club->id }}">{{ $club->name }}</option>
												@endforeach
											</select>
										</div>
										@if ($errors->has('away'))
											<div class="invalid-feedback animated fadeInDown">
												<strong>{{ $errors->first('away') }}</strong>
											</div>
										@endif
									</div>
								</div>


								<div class="col-xl-6">
									<div class="form-group {{ $errors->has('kickoff_time') ? ' is-invalid' : '' }}">
										<label for="kickoff_time" class="required">Kick off date/time:</label>
										<div class='input-group date js-datetimepicker' data-target-input="nearest"
											 id="kickoff_time">
											<input type="text" class="form-control datetimepicker-input"
												   name="kickoff_time" data-target="#kickoff_time" readonly
												   data-toggle="datetimepicker"/>
											<div class="input-group-append" data-target="#kickoff_time"
												 data-toggle="datetimepicker">
												<div class="input-group-text"><i class="fal fa-calendar-alt"></i></div>
											</div>
										</div>
										@if ($errors->has('kickoff_time'))
											<div class="invalid-feedback animated fadeInDown">
												<strong>{{ $errors->first('kickoff_time') }}</strong>
											</div>
										@endif
									</div>
								</div>

								<div class="col-xl-6">
									<div class="row">
										<label for="result_home" class="col-lg-12">Result:</label>
										<div class="col-lg-6 form-group">
											<div class="input-group">
												<div class="input-group-prepend">
			                                        <span class="input-group-text">
			                                            Home
			                                        </span>
												</div>
												<input type="text" class="form-control" id="result_home"
													   name="result_home" placeholder="">
											</div>
										</div>
										<div class="col-lg-6 form-group">
											<div class="input-group">
												<input type="text" class="form-control" id="result_away"
													   name="result_away" placeholder="">
												<div class="input-group-append">
			                                        <span class="input-group-text">
			                                            Away
			                                        </span>
												</div>
											</div>
										</div>
									</div>
								</div>

								<div class="col-xl-6">
									<div class="row">
										<label for="aet_home" class="col-lg-12">AET:</label>
										<div class="col-lg-6 form-group">
											<div class="input-group">
												<div class="input-group-prepend">
			                                        <span class="input-group-text">
			                                            Home
			                                        </span>
												</div>
												<input type="text" class="form-control" id="aet_home" name="aet_home"
													   placeholder="">
											</div>
										</div>
										<div class="col-lg-6 form-group">
											<div class="input-group">
												<input type="text" class="form-control" id="aet_away" name="aet_away"
													   placeholder="">
												<div class="input-group-append">
			                                        <span class="input-group-text">
			                                            Away
			                                        </span>
												</div>
											</div>
										</div>
									</div>
								</div>

								<div class="col-xl-6">
									<div class="row">
										<label for="penalties_home" class="col-lg-12">Penalties:</label>
										<div class="col-lg-6 form-group">
											<div class="input-group">
												<div class="input-group-prepend">
			                                        <span class="input-group-text">
			                                            Home
			                                        </span>
												</div>
												<input type="text" class="form-control" id="penalties_home"
													   name="penalties_home" placeholder="">
											</div>
										</div>
										<div class="col-lg-6 form-group">
											<div class="input-group">
												<input type="text" class="form-control" id="penalties_away"
													   name="penalties_away" placeholder="">
												<div class="input-group-append">
			                                        <span class="input-group-text">
			                                            Away
			                                        </span>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="tab-pane" id="btabs-ticketing" role="tabpanel">
							<div class="row">
								<div class="col-xl-6">
									<div class="form-group">
										<div class="custom-control custom-checkbox mb-5">
											<input disabled class="custom-control-input manage-hide-show" type="checkbox"
												   name="is_enable_ticket" id="is_enable_ticket" value="1">
											<label class="custom-control-label" for="is_enable_ticket">Enable tickets
												sales for this match</label>
										</div>
									</div>
								</div>
							</div>
							<div class="manage-hide-show-div d-none">
								<div class="row">
									<div class="col-xl-6">
										<div
											class="form-group {{ $errors->has('maximum_number_of_ticket_per_user') ? ' is-invalid' : '' }}">
											<label for="maximum_number_of_ticket_per_user" class="required">Maximum
												number of ticket per user:</label>
											<input type="number" class="form-control"
												   id="maximum_number_of_ticket_per_user"
												   name="maximum_number_of_ticket_per_user" min="0">
											@if ($errors->has('maximum_number_of_ticket_per_user'))
												<div class="invalid-feedback animated fadeInDown">
													<strong>{{ $errors->first('maximum_number_of_ticket_per_user') }}</strong>
												</div>
											@endif
										</div>
									</div>
								</div>
								<div class="row mt-30 mb-30">
									<div class="col-xl-12">
										<h5 class="mb-2"><label class="required">On sale dates</label></h5>
									</div>
									@foreach($membershipPackage as $package)
										<div class="col-xl-6">
											<div class="form-group">
												<label for="{{ $package->title }}">{{ $package->title }}:</label>
												<div class='input-group date js-datetimepicker' data-target-input="nearest" id="{{ $package->id }}">
													<input type="text" class="form-control datetimepicker-input ticketing-package-date-required" id="package[][{{ $package->id }}]"
														   name="package[][{{ $package->id }}]"
														   data-target="#{{ $package->id }}" readonly data-toggle="datetimepicker" />


													<div class="input-group-append" data-target="#{{ $package->id }}"
														 data-toggle="datetimepicker">
														<div class="input-group-text"><i
																class="fal fa-calendar-alt"></i></div>
													</div>
												</div>
											</div>
										</div>
									@endforeach
								</div>
								<input type="hidden" name="seatValidaton" id="seatValidaton" value='{{isset($clubDetail->stadium->is_using_allocated_seating) && $clubDetail->stadium->is_using_allocated_seating > 0 ? $clubDetail->stadium->is_using_allocated_seating : '0'}}'>
								<div class="row">
									@if(isset($clubDetail->stadium->is_using_allocated_seating) && $clubDetail->stadium->is_using_allocated_seating > 0 )
									<div class="col-xl-6">
										<div
											class="form-group {{ $errors->has('available_blocks') ? ' is-invalid' : '' }}">
											<label for="available_blocks" class="required">Available blocks:</label>
											<div>
												<select class="form-control" id="available_blocks"
														name="available_blocks[]" size="7" multiple style="width:100%;">
													@foreach($availableBlocks as $block)
														<option value="{{ $block->id }}">{{ $block->name }}</option>
													@endforeach()
												</select>
											</div>
											@if ($errors->has('available_blocks'))
												<div class="invalid-feedback animated fadeInDown">
													<strong>{{ $errors->first('available_blocks') }}</strong>
												</div>
											@endif
										</div>
									</div>
									<div class="col-xl-6">
										<div class="form-group {{ $errors->has('unavailable_seats') ? ' is-invalid' : '' }}">
		                                    <div class="logo-fields-wrapper">
		                                        <label>Unavailable seats:</label>
		                                        <div class="d-flex align-items-center">
		                                            <div class="logo-input flex-grow-1">
		                                                <div class="input-group">
		                                                    <div class="custom-file">
		                                                        <div>
		                                                            <input type="file" class="form-control custom-file-input" id="unavailable_seats" name="unavailable_seats" data-toggle="custom-file-input">
		                                                            <label id="lbl_unavailable_seats" name="lbl_unavailable_seats" class="form-control custom-file-label text-truncate pr-100" for="unavailable_seats">Choose file</label>
		                                                        </div>
		                                                    </div>
		                                                </div>
		                                            </div>
		                                            <div id="unavailable_seats_preview_container" class="mx-3 d-md-none">
		                                            </div>
		                                            <a id="unavailable_seats_preview_remove" name="unavailable_seats_preview_remove" class="close-preview d-md-none" data-toggle="tooltip" title="Delete">
														<i class="far fa-trash-alt text-muted text-danger"></i>
													</a>
		                                        </div>
		                                    </div>
		                                </div>
										{{-- <div
											class="form-group {{ $errors->has('unavailable_seats') ? ' is-invalid' : '' }}">
											<label>Unavailable seats:</label>
											<div class="row align-items-center">
												<div class="col-12 js-manage-unavailable-seats-width">
													<div class="row">
														<div class="col-12">
															<div class="custom-file">
																<input type="file" class="custom-file-input"
																	   id="unavailable_seats" name="unavailable_seats"
																	   data-toggle="custom-file-input">
																<label class="custom-file-label"
																	   id="lbl_unavailable_seats"
																	   for="unavailable_seats">Choose file</label>
															</div>
														</div>
													</div>
												</div>
												<div class="col-3">
													<div class="d-flex justify-content-center d-md-none"
														 id="unavailable_seats_preview_container"> --}}
														{{-- <img src="" id="unavailable_seats_preview" class="img-avatar img-avatar-square mb-2 pull-right" alt="Unavailable seats"> --}}
													{{-- </div>
													<a id="unavailable_seats_preview_remove"
													   name="unavailable_seats_preview_remove"
													   class="close-preview d-md-none" data-toggle="tooltip"
													   title="Delete">
														<i class="far fa-trash-alt text-muted text-danger"></i>
													</a>
												</div>
											</div>
										</div> --}}
									</div>
									@endif
									<div class="col-xl-6">
										<div
											class="form-group {{ $errors->has('pricing_bands') ? ' is-invalid' : '' }}">
											<label for="pricing_bands" class="required">Pricing bands:</label>
											<div>
												<select class="form-control" id="pricing_bands" name="pricing_bands[]"
														size="7" multiple style="width:100%;">
													@foreach($pricingBands as $band)
														<option
															value="{{ $band->id }}">{{ $band->display_name }}</option>
													@endforeach()
												</select>
											</div>
											@if ($errors->has('pricing_bands'))
												<div class="invalid-feedback animated fadeInDown">
													<strong>{{ $errors->first('pricing_bands') }}</strong>
												</div>
											@endif
										</div>
									</div>
									<div class="col-xl-6">
										<div class="form-group">
											<label for="rewards_percentage_override">Rewards percentage
												override:</label>
											<input type="text" class="form-control" id="rewards_percentage_override"
												   name="rewards_percentage_override" min="0">
										</div>
									</div>
									<div class="col-xl-12">
											<div class="col-xl-6">
										<div class="form-group row">
												<div class="row align-items-center">
													<div class="col-8">
														<h5 class="mb-0">Sponsors:</h5>
													</div>
													<div class="col-4 text-right">
														<button type="button" class="btn btn-primary btn-noborder"
																data-repeater-create>Add
														</button>
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="col-xl-6 js-sponsors" data-repeater-list="sponsors">
										<div
											class="js-sponsors-fields-wrapper {{ $errors->has('sponsors.*.sponsor') ? ' is-invalid' : '' }}"
											data-repeater-item>

											<div class="logo-fields-wrapper js-manage-sponsor-logo-width">
												<div class="d-flex align-items-end mb-3">
													<div class="logo-input flex-grow-1">
														<label for="sponsors">Logo:</label>
														<div class="input-group">
															<div class="custom-file">
																<input type="file" class="custom-file-input uploadimage"
																	name="sponsor" data-toggle="custom-file-input"
																	accept="image/*">

																<label class="custom-file-label" for="sponsor">Choose
																	file</label>
															</div>
														</div>
													</div>
													<div class="d-flex align-items-end">
														<div name="preview_container" class="d-md-none">
															<div class="logo_preview_container ml-3">
																<img src="" name="preview" alt="Sponsor logo">
															</div>
														</div>
														<div class="logo-delete ml-3" >
															<button class="btn btn-danger"><i class="fal fa-trash"></i></button>
														</div>
													</div>
												</div>
											</div>
										</div>
										@if ($errors->has('sponsors'))
											<div class="invalid-feedback animated fadeInDown">
												<strong>{{ $errors->first('sponsors') }}</strong>
											</div>
										@endif
									</div>
								</div>
								<div class="row">
									<div class="col-xl-12">
										<div class="form-group">
											<div class="custom-control custom-checkbox mb-5">
												<input class="custom-control-input manage-ticket-type-amount"
													   type="checkbox" name="allow_ticket_returns_resales"
													   id="allow_ticket_returns_resales" value="1">
												<label class="custom-control-label" for="allow_ticket_returns_resales">Allow
													ticket returns/resales?</label>
											</div>
										</div>
									</div>
									<div class="col-xl-12 manage-ticket-type-amount-container d-none">
										<div class="row">
											<div class="col-xl-6">
												<div class="form-group" {{ $errors->has('ticket_resale_fee_type') ? ' is-invalid' : '' }}
													">
													<label class="required">Ticket resale fee type:</label>
													<div>
														@foreach($ticketResaleFeeType as $key => $type)
															<div
																class="custom-control custom-radio custom-control-inline mb-5">
																<input class="custom-control-input" type="radio"
																	   name="ticket_resale_fee_type"
																	   id="ticket_resale_fee_type_{{$key}}"
																	   value="{{$key}}" {{ $key == 'fixed_fee' ? 'checked' : '' }}>
																<label class="custom-control-label"
																	   for="ticket_resale_fee_type_{{$key}}">{{$type}}</label>
															</div>
														@endforeach()
													</div>
												</div>
											</div>
											<div class="col-xl-6">
												<div
													class="form-group {{ $errors->has('ticket_resale_fee_amount') ? ' is-invalid' : '' }}">
													<label for="ticket_resale_fee_amount" class="required">Ticket resale fee
														amount:</label>
													<input type="text" class="form-control" id="ticket_resale_fee_amount"
														   name="ticket_resale_fee_amount" min="0">
													@if ($errors->has('ticket_resale_fee_amount'))
														<div class="invalid-feedback animated fadeInDown">
															<strong>{{ $errors->first('ticket_resale_fee_amount') }}</strong>
														</div>
													@endif
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="tab-pane" id="btabs-hospitality" role="tabpanel">
							<div class="row">
								<div class="col-xl-6">
									<div class="form-group">
										<div class="custom-control custom-checkbox mb-5">
											<input disabled class="custom-control-input manage-hide-show" type="checkbox"
												   name="is_enable_hospitality" id="is_enable_hospitality" value="1">
											<label class="custom-control-label" for="is_enable_hospitality">Enable
												hospitality ticket sales for this match</label>
										</div>
									</div>
								</div>
							</div>
							<div class="manage-hide-show-div d-none">

								<div class="row mt-30 mb-30">
									<div class="col-xl-12">
										<h5 class="mb-2"><label class="required">On sale dates</label></h5>
									</div>
									@foreach($membershipPackage as $package)
										<div class="col-xl-6">
											<div class="form-group">
												<label for="hospitality_{{ $package->title }}">{{ $package->title }}:</label>
												<div class='input-group date js-hospitality-datepicker'
													 data-target-input="nearest" id="hospitality_{{ $package->id }}">
													<input type="text" class="form-control datetimepicker-input hospitality-package-date-required" id="hospitality_package[][{{ $package->id }}]"
														   name="hospitality_package[][{{ $package->id }}]"
														   data-target="#hospitality_{{ $package->id }}" readonly
														   data-toggle="datetimepicker"/>
													<div class="input-group-append"
														 data-target="#hospitality_{{ $package->id }}"
														 data-toggle="datetimepicker">
														<div class="input-group-text"><i class="fal fa-calendar-alt"></i>
														</div>
													</div>
												</div>
											</div>
										</div>
									@endforeach
								</div>
								<div class="row">
									<div class="col-xl-6">
										<div
											class="form-group {{ $errors->has('hospitality_suites') ? ' is-invalid' : '' }}">
											<label for="hospitality_suites" class="required">Available suites:</label>
											<div>
												<select class="form-control" id="hospitality_suites"
														name="hospitality_suites[]" size="7" multiple style="width:100%;">
													@foreach($hospitalitySuites as $suite)
														<option value="{{ $suite->id }}">{{ $suite->title }}</option>
													@endforeach()
												</select>
											</div>
											@if ($errors->has('hospitality_suites'))
												<div class="invalid-feedback animated fadeInDown">
													<strong>{{ $errors->first('hospitality_suites') }}</strong>
												</div>
											@endif
										</div>
									</div>
									<div class="col-xl-6">
										<div class="form-group">
											<label for="hospitality_rewards_percentage_override">Rewards percentage
												override:</label>
											<input type="text" class="form-control"
												   id="hospitality_rewards_percentage_override"
												   name="hospitality_rewards_percentage_override" min="0">
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="tab-pane" id="btabs-line-ups" roel="tabpanel">

							<div class="row">
								<div class="col-xl-12">
									<div class="form-group text-center">
										No information available.
									</div>
								</div>
							</div>
							{{-- <div class="row">
								<div class="col-xl-4">
									<button type="button" class="d-none btn btn-block btn-noborder btn-primary" data-toggle="modal" data-target="#add_player">Add player</button>
								</div>
							</div>
							<div class="row">
								<div class="col-xl-12">
									<div class="">
										<div class="d-flex align-items-center justify-content-between">
											<h5 class="mb-0 d-none">Home</h5>
										</div>
									</div>
								</div>
							</div>
							<div class="row" class="js-line-up-home-main-div" id="add_home_team_player">
							</div>
							<div class="row js-line-ups-detail-div">
								<div class="col-xl-4">
									<div class="form-group">
										<input type="hidden" name="line_ups_home">
										<button type="button" class="d-none btn btn-block btn-noborder btn-primary js-added-home js-add-home-team-player">Add
											home</button>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-xl-12">
									<div class="">
										<div class="d-flex align-items-center justify-content-between">
											<h5 class="mb-0 d-none">Away</h5>
										</div>
									</div>
								</div>
							</div>
							<div class="row" id="add_away_team_player">
							</div>
							<div class="row">
								<div class="col-xl-4">
									<div class="form-group">
										<input type="hidden" name="line_ups_away">
										<button type="button" class="d-none btn btn-block btn-noborder btn-primary js-add-away-team-player">Add
											away</button>
									</div>
								</div>
							</div> --}}
						</div>
						<div class="tab-pane" id="btabs-match-events" role="tabpanel">
							{{-- <div class="row" id="add_match_event">
							</div> --}}
							<div class="row">
								<div class="col-xl-12">
									<div class="form-group text-center">
										No information available.
									</div>
								</div>
							</div>
							{{-- <div class="row">
								<div class="col-xl-4">
									<div class="form-group">
										<button type="button" class="d-none btn btn-block btn-primary btn-noborder js-add-match-event">Add event</button>
									</div>
								</div>
							</div> --}}
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-xl-12">
						<div class="form-group">
							<button type="submit" class="btn btn-hero btn-noborder btn-primary min-width-125">Create
							</button>
							<a href="{{ route('backend.matches.index', ['club' => app()->request->route('club')]) }}"
							   class="btn btn-hero btn-noborder btn-alt-secondary">
								Cancel
							</a>
						</div>
					</div>
				</div>
			</div>
		</form>
		<!-- Fade In Modal -->
		<div class="modal fade" id="add_player" role="dialog" aria-labelledby="add_player" aria-hidden="true">
			<div class="modal-dialog modal-lg" role="document">
				<div class="modal-content">
					<div class="block block-themed block-transparent mb-0">
						<div class="block-header bg-primary-dark">
							<h3 class="block-title">Add Player</h3>
							<div class="block-options">
								<button type="button" class="btn-block-option" data-dismiss="modal"
										aria-label="Close">
									<i class="si si-close"></i>
								</button>
							</div>
						</div>
						<form id="add_player_form" class="block-content">
							<div class="form-group">
								<label class="required" for="player_name">Name:</label>
								<input type="text" class="form-control" id="player_name" name="player_name"
									   value="{{ old('player_name') }}">
							</div>
						</form>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-hero btn-noborder btn-alt-secondary"
								data-dismiss="modal">Close
						</button>
						<button type="button" class="btn btn-hero btn-noborder btn-primary js-add-player-save">
							Save
						</button>
					</div>
				</div>
			</div>
		</div>
		<!-- END Fade In Modal -->



	</div>
@endsection
