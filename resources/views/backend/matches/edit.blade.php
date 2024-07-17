@extends('layouts.backend')

@section('plugin-scripts')
	<script src="{{ asset('plugins/jquery-repeater/jquery.repeater.min.js') }}"></script>
@endsection

@section('page-scripts')
	<script src="{{ asset('js/backend/pages/matches/edit.js') }}"></script>
@endsection

@section('content')
	<div class="block">
		<form class="edit-match-form repeater"
			  action="{{ route('backend.matches.update', ['club' => app()->request->route('club'), 'match' => $match]) }}"
			  method="post" enctype="multipart/form-data">
			{{ method_field('PUT') }}
			{{ csrf_field() }}


			<div class="block-header block-header-default">
				<h3 class="block-title">Edit match</h3>
				<div class="float-right">Booked seats: {{$bookedSeats}}</div>
				<div class="block-options d-inline-flex align-items-center">
					<div class="custom-control custom-checkbox">
						<input class="custom-control-input" type="checkbox" name="is_published" id="is_published"
							   {{ $match->is_published ? 'checked' : ''}} value="1">
						<label class="custom-control-label" for="is_published">Is published?</label>
					</div>
				</div>
			</div>

			<div class="block-content">
				<div class="block block-bordered">
					<div class="block-content">
						<ul class="nav nav-tabs nav-tabs-alt nav-tabs-block nav-justified" data-toggle="tabs" role="tablist">
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
											<select class="js-select2 form-control" id="home" name="home" {{ $match->is_match_imported ? 'disabled' : '' }}>
												<option value="">Please select</option>
												@foreach($clubs as $club)
													<option
														value="{{ $club->id }}" {{ $club->id == $match->homeTeam->id ? 'selected' : '' }}>{{ $club->name }}</option>
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
											<select class="js-select2 form-control" id="away" name="away" {{ $match->is_match_imported ? 'disabled' : '' }}>
												<option value="">Please select</option>
												@foreach($clubs as $club)
													<option
														value="{{ $club->id }}" {{ $club->id == $match->awayTeam->id ? 'selected' : '' }}>{{ $club->name }}</option>
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
											{{-- <input type="text" class="form-control datetimepicker-input" name="kickoff_time"
												   data-target="#kickoff_time" value="{{ convertDateTimezone($match->kickoff_time, null, $clubDetail->time_zone, config('fanslive.DATE_TIME_CMS_FORMAT.php')) }}" readonly
												   data-toggle="datetimepicker" {{ $match->is_match_imported ? 'disabled' : '' }}/> --}}
											<input type="text" class="form-control datetimepicker-input" name="kickoff_time"
												   data-target="#kickoff_time" value="{{ convertDateTimezone($match->kickoff_time, null, $clubDetail->time_zone, config('fanslive.DATE_TIME_CMS_FORMAT.php')) }}"
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
										<label class="col-lg-12">Result:</label>
										<div class="col-lg-6 form-group">
											<div class="input-group">
												<div class="input-group-prepend">
		                                            <span class="input-group-text">
		                                                Home
		                                            </span>
												</div>
												<input type="text" class="form-control" id="result_home" name="result_home"
													   placeholder="" value="{{ $match->full_time_home_team_score }}">
											</div>
										</div>
										<div class="col-lg-6 form-group">
											<div class="input-group">
												<input type="text" class="form-control" id="result_away" name="result_away"
													   placeholder="" value="{{ $match->full_time_away_team_score }}">
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
													   placeholder="" value="{{ $match->extra_time_home_team_score }}">
											</div>
										</div>
										<div class="col-lg-6 form-group">
											<div class="input-group">
												<input type="text" class="form-control" id="aet_away" name="aet_away"
													   placeholder="" value="{{ $match->extra_time_away_team_score }}">
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
													   name="penalties_home" placeholder=""
													   value="{{ $match->penalties_home_team_score }}">
											</div>
										</div>
										<div class="col-lg-6 form-group">
											<div class="input-group">
												<input type="text" class="form-control" id="penalties_away"
													   name="penalties_away" placeholder=""
													   value="{{ $match->penalties_away_team_score }}">
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
											<input {{$currentClub != $match->homeTeam->id ? 'disabled' :''}} class="custom-control-input manage-hide-show" type="checkbox"
												   name="is_enable_ticket" id="is_enable_ticket"
												   value="1" {{ $match->is_ticket_sale_enabled == 1 ? $currentClub == $match->homeTeam->id ? 'checked' :'': '' }}>
											<label class="custom-control-label" for="is_enable_ticket">Enable ticket sales for
												this match</label>
										</div>
									</div>
								</div>
							</div>
							<div class="manage-hide-show-div {{ $match->is_ticket_sale_enabled == 1 ?  $currentClub == $match->homeTeam->id ? '' : 'd-none' : 'd-none' }}">
								<div class="row">
									<div class="col-xl-6">
										<div
											class="form-group {{ $errors->has('maximum_number_of_ticket_per_user') ? ' is-invalid' : '' }}">
											<label for="maximum_number_of_ticket_per_user" class="required">Maximum number of
												ticket per user:</label>
											<input type="number" class="form-control" id="maximum_number_of_ticket_per_user"
												   name="maximum_number_of_ticket_per_user" min="0"
												   value="{{ Arr::get($match->ticketing, 'maximum_ticket_per_user') }}">
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
										@php $date=''; $pkgId=''; $convertedPublicationDate='';@endphp
										@foreach($match->ticketingMembership as $packagedata)
											@if($package->id == $packagedata->membership_package_id)
												@php $date=$packagedata->date;
		            							     $pkgId=$packagedata->id;
												@endphp
											@endif
										@endforeach
										<div class="col-xl-6">
											<div class="form-group">
												<label for="{{ $package->title }}">{{ $package->title }}:</label>
												<div class='input-group date js-datetimepicker' data-target-input="nearest"
													 id="{{ $package->id }}">
													<input type="text" class="form-control datetimepicker-input ticketing-package-date-required" id="package[{{$pkgId}}][{{ $package->id }}]"
														   value="{{ convertDateTimezone($date, null, $clubDetail->time_zone, config('fanslive.DATE_TIME_CMS_FORMAT.php')) }}" name="package[{{ $package->id }}][{{$pkgId}}]"
														   data-target="#{{ $package->id }}" readonly
														   data-toggle="datetimepicker"/>
													<div class="input-group-append" data-target="#{{ $package->id }}"
														 data-toggle="datetimepicker">
														<div class="input-group-text"><i class="fal fa-calendar-alt"></i></div>
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
										<div class="form-group {{ $errors->has('available_blocks') ? ' is-invalid' : '' }}">
											<label for="available_blocks" class="required">Available blocks:</label>
											<div>
												<select class="form-control" id="available_blocks" name="available_blocks[]"
														size="7" multiple>
													@foreach($availableBlocks as $block)
														@if(Arr::get($match->ticketing, 'availableBlocks'))
															<option
																value="{{ $block->id }}" {{ in_array($block->id,$match->ticketing->availableBlocks->pluck('block_id')->toArray())? 'selected' : '' }}>{{ $block->name }}</option>
														@else
															<option value="{{ $block->id }}">{{ $block->name }}</option>
														@endif
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
			                                                        <label id="lbl_unavailable_seats" name="lbl_unavailable_seats" class="form-control custom-file-label text-truncate pr-100" for="unavailable_seats">{{ Arr::get($match->ticketing, 'unavailable_seats_file_name') ? Arr::get($match->ticketing, 'unavailable_seats_file_name') : 'Choose file'}}</label>
			                                                    </div>
			                                                </div>
			                                            </div>
			                                        </div>
			                                        <div id="unavailable_seats_image" class="mx-3 {{ Arr::get($match->ticketing, 'unavailable_seats') ? '' : 'd-md-none' }}">
			                                        	<div id="unavailable_seats_preview_container">
															<a download href="{{ Arr::get($match->ticketing, 'unavailable_seats') }}" v-if='{{ Arr::get($match->ticketing, 'unavailable_seats') }}'>
																Download
															</a>
														</div>
														<a id="unavailable_seats_preview_remove" name="unavailable_seats_preview_remove" class="close-preview" data-toggle="tooltip" title="Delete">
															<i class="far fa-trash-alt text-muted text-danger"></i>
														</a>
			                                        </div>
			                                    </div>
			                                </div>
			                            </div>
										{{-- <div class="form-group {{ $errors->has('unavailable_seats') ? ' is-invalid' : '' }}">
											<label>Unavailable seats:</label>
											<div class="row align-items-center">
												<div
													class="{{ Arr::get($match->ticketing, 'unavailable_seats') ? 'col-9' : 'col-12' }} js-manage-unavailable-seats-width">
													<div class="row">
														<div class="col-12">
															<div class="custom-file">
																<input type="file" class="custom-file-input"
																	   id="unavailable_seats" name="unavailable_seats"
																	   data-toggle="custom-file-input">
																<label class="custom-file-label" id="lbl_unavailable_seats"
																	   for="unavailable_seats">{{ Arr::get($match->ticketing, 'unavailable_seats_file_name') ? Arr::get($match->ticketing, 'unavailable_seats_file_name') : 'Choose file'}}</label>
															</div>
														</div>
													</div>
												</div>
												<div
													class="col-3 {{ Arr::get($match->ticketing, 'unavailable_seats') ? '' : 'd-md-none' }}"
													id="unavailable_seats_image">
													<div class="d-flex justify-content-center"
														 id="unavailable_seats_preview_container">
														<a download
														   href="{{ Arr::get($match->ticketing, 'unavailable_seats') }}"
														   v-if='{{ Arr::get($match->ticketing, 'unavailable_seats') }}'>Download</a>
													</div>
													<a id="unavailable_seats_preview_remove"
													   name="unavailable_seats_preview_remove" class="close-preview"
													   data-toggle="tooltip" title="Delete">
														<i class="far fa-trash-alt text-muted text-danger"></i>
													</a>
												</div>
											</div>
										</div> --}}
									</div>
									@endif
									<div class="col-xl-6">
										<div class="form-group {{ $errors->has('pricing_bands') ? ' is-invalid' : '' }}">
											<label for="pricing_bands" class="required">Pricing bands:</label>
											<div>
												<select class="form-control" id="pricing_bands" name="pricing_bands[]" size="7"
														multiple>
													@foreach($pricingBands as $band)
														@if(Arr::get($match->ticketing, 'pricingBrand'))
															<option
																value="{{ $band->id }}" {{in_array($band->id,$match->ticketing->pricingBrand->pluck('pricing_band_id')->toArray())? 'selected' : '' }}>{{ $band->display_name }}</option>
														@else
															<option value="{{ $band->id }}">{{ $band->display_name }}</option>
														@endif

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
											<label for="rewards_percentage_override">Rewards percentage override:</label>
											<input type="text" class="form-control" id="rewards_percentage_override"
												   name="rewards_percentage_override" min="0"
												   value="{{ Arr::get($match->ticketing, 'rewards_percentage_override') }}">
										</div>
									</div>
									<div class="col-xl-12">
										<div class="form-group row">
											<div class="col-xl-6">
												<div class="row align-items-center">
													<div class="col-8">
														<h5 class="mb-0">Sponsors:</h5>
													</div>
													<div class="col-4 text-right">
														<button type="button" class="btn btn-primary btn-noborder js-remove-img"
																data-repeater-create>Add
														</button>
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="col-xl-6 js-sponsors" data-repeater-list="sponsors">
										@if(Arr::get($match->ticketing, 'sponsor') && $match->ticketing->sponsor->isNotEmpty())
											@foreach($match->ticketing->sponsor as $sponsor)
												<div
													class="js-sponsors-fields-wrapper {{ $errors->has('sponsors.*.sponsor') ? ' is-invalid' : '' }}"
													data-repeater-item>
													<div
														class="logo-fields-wrapper js-manage-sponsor-logo-width">
														<div class="d-flex align-items-end mb-3">
															<div class="logo-input flex-grow-1">
																<label for="sponsors">Logo:</label>
																<div class="input-group">
																	<div class="custom-file">
																		<input type='hidden' name="sponserId"
																			   value="{{ $sponsor->id }}"/>
																		<input type="file" class="custom-file-input uploadimage"
																			   name="sponsor" data-toggle="custom-file-input"
																			   accept="image/*">
																		<label class="custom-file-label" for="sponsor">Choose
																			file</label>
																	</div>
																</div>
															</div>
															<div class="d-flex align-items-end">
																<div name="preview_container"
																 	class="{{ Arr::get($match->ticketing, 'sponsor') ? '' : 'd-md-none' }} js-remove-thumb">

																	<div class="logo_preview_container ml-3">
																		<img name="preview" src="{{ $sponsor->logo }}"
																			class="" alt="Sponsor logo">
																	</div>
																</div>
																<div class="logo-delete ml-3">
																	<button class="btn btn-danger"><i class="fal fa-trash"></i></button>
																</div>
															</div>
														</div>
													</div>
												</div>
														@if ($errors->has('sponsors.'. '*' . '.sponsor'))
																	<div class="invalid-feedback animated fadeInDown">
																		<strong>{{ $errors->first('sponsors.'. '*' . '.sponsor') }}</strong>
																	</div>
																@endif

											@endforeach
										@else
											<div
												class="js-sponsors-fields-wrapper {{ $errors->has('sponsors.*.sponsor') ? ' is-invalid' : '' }}"
												data-repeater-item>
												<div class="logo-fields-wrapper js-manage-sponsor-logo-width">
													<div class="d-flex align-items-end mb-3">
														<div class="logo-input flex-grow-1">
															<label for="sponsors">Logo:</label>
															<div class="input-group">
																<div class="custom-file">
																	<input type='hidden' name="sponserId" value=""/>
																	<input type="file" class="custom-file-input uploadimage"
																		   name="sponsor" data-toggle="custom-file-input"
																		   accept="image/*">
																	<label class="custom-file-label" for="sponsor">Choose
																		file</label>
																</div>
															</div>
														</div>
														@if ($errors->has('sponsors.'. '*' . '.sponsor'))
															<div class="invalid-feedback animated fadeInDown">
																<strong>{{ $errors->first('sponsors.'. '*' . '.sponsor') }}</strong>
															</div>
														@endif
														<div class="d-flex align-items-end">
															<div name="preview_container"
																class="{{ Arr::get($match->ticketing, 'sponsor') && Arr::has($match->ticketing->sponsor,'id') ? '' : 'd-md-none' }} js-remove-thumb">
																	<div class="logo_preview_container ml-3">
																		<img name="preview" src=""
																			alt="Sponsor logo">
																	</div>
															</div>
															<div class="logo-delete ml-3">
																<button class="btn btn-danger"><i
																	class="fal fa-trash"></i></button>
															</div>

														</div>
													</div>
												</div>
											</div>
										@endif
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
													   type="checkbox"
													   name="allow_ticket_returns_resales" id="allow_ticket_returns_resales"
													   value="1" {{ Arr::get($match->ticketing, 'allow_ticket_returns_resales') == 1 ? 'checked' : '' }}>
												<label class="custom-control-label" for="allow_ticket_returns_resales">Allow
													ticket returns/resales?</label>
											</div>
										</div>
									</div>
									<div class="col-xl-12 manage-ticket-type-amount-container  {{ Arr::get($match->ticketing, 'allow_ticket_returns_resales') == 1 ? '' : 'd-none' }}">
										<div class="row">
											<div class="col-xl-6">
												<div class="form-group" {{ $errors->has('ticket_resale_fee_type') ? ' is-invalid' : '' }}
												">
													<label class="required">Ticket resale fee type:</label>
													<div>
														@foreach($ticketResaleFeeType as $key => $type)
															<div class="custom-control custom-radio custom-control-inline mb-5">
																<input class="custom-control-input" type="radio"
																	   name="ticket_resale_fee_type"
																	   id="ticket_resale_fee_type_{{$key}}"
																	   value="{{$key}}" {{ Arr::get($match->ticketing, 'ticket_resale_fee_type', 'fixed_fee') == $key ? 'checked' : '' }}>
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
														   name="ticket_resale_fee_amount" min="0"
														   value="{{ Arr::get($match->ticketing, 'ticket_resale_fee_amount') }}">
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
											<input {{ $currentClub != $match->homeTeam->id ? 'disabled': ''}} class="custom-control-input manage-hide-show" type="checkbox"
												   name="is_enable_hospitality" id="is_enable_hospitality"
												   value="1" {{ $match->is_hospitality_ticket_sale_enabled == 1 ?  $currentClub == $match->homeTeam->id ? 'checked':'' : '' }}>
											<label class="custom-control-label" for="is_enable_hospitality">Enable hospitality
												ticket sales
												for this match</label>
										</div>
									</div>
								</div>
							</div>
							<div class="manage-hide-show-div {{ $match->is_hospitality_ticket_sale_enabled == 1 ? $currentClub == $match->homeTeam->id ?'' : 'd-none' : 'd-none' }}">
								<div class="row mt-30 mb-30">
									<div class="col-xl-12">
										<h5 class="mb-2"><label class="required">On sale dates</label></h5>
									</div>
									@foreach($membershipPackage as $package)
										@php $date=''; $hospkgId=0;@endphp
										@foreach($match->hospitalityMembership as $packagedata)
											@if($package->id == $packagedata->membership_package_id)
												@php $date=$packagedata->date;
			            							     $hospkgId=$packagedata->id;
												@endphp
											@endif
										@endforeach
										<div class="col-xl-6">
											<div class="form-group">
												<label for="hospitality_{{ $package->title }}">{{ $package->title }}:</label>
												<div class='input-group date js-hospitality-datepicker' data-target-input="nearest"
													 id="hospitality_{{ $package->id }}">
													<input type="text" class="form-control datetimepicker-input hospitality-package-date-required" value="{{ convertDateTimezone($date, null, $clubDetail->time_zone, config('fanslive.DATE_TIME_CMS_FORMAT.php')) }}" id="hospitality_package[{{$hospkgId}}][{{ $package->id }}]"
														   name="hospitality_package[{{ $package->id }}][{{$hospkgId}}]"
														   data-target="#hospitality_{{ $package->id }}" readonly
														   data-toggle="datetimepicker"/>
													<div class="input-group-append" data-target="#hospitality_{{ $package->id }}"
														 data-toggle="datetimepicker">
														<div class="input-group-text"><i class="fal fa-calendar-alt"></i></div>
													</div>
												</div>
											</div>
										</div>
									@endforeach
								</div>
								<div class="row">
									<div class="col-xl-6">
										<div class="form-group {{ $errors->has('hospitality_suites') ? ' is-invalid' : '' }}">
											<label for="hospitality_suites" class="required">Available suites:</label>
											<div>
												<select class="form-control" id="hospitality_suites" name="hospitality_suites[]"
														size="7" multiple>
													@foreach($hospitalitySuites as $suite)
														@if(Arr::get($match->hospitality, 'hospitalitySuites'))
															<option
																value="{{ $suite->id }}" {{ in_array($suite->id, $match->hospitality->hospitalitySuites->pluck('id')->toArray())? 'selected' : '' }}>{{ $suite->title }}</option>
														@else
															<option value="{{ $suite->id }}">{{ $suite->title }}</option>
														@endif
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
											<input type="text" class="form-control" id="hospitality_rewards_percentage_override"
												   name="hospitality_rewards_percentage_override" min="0"
												   value="{{ Arr::get($match->hospitality, 'rewards_percentage_override') }}">
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="tab-pane" id="btabs-line-ups" roel="tabpanel">
							{{-- <div class="row">
								<div class="col-xl-4">
									<button type="button" class="d-none btn btn-block btn-noborder btn-primary" data-toggle="modal" data-target="#add_player">Add player</button>
								</div>
							</div> --}}

							@if($homeFlag)
								<div class="block block-bordered block-default">
									<div class="block-header block-header-default">
										Home
									</div>
									<div class="block-content">
										<div class="row" id="edit_home_team_player">
											@foreach($match->player as $key => $matchHome)
												@if($matchHome->club_id == $match->home_team_id)
													<div class="col-xl-12 edit-home-team" id="{{ $key }}">
														<div class="block block-bordered block-default">
															{{-- <div class="block-header block-header-default">
																<div>
																</div>
																<div class="block-options"> --}}
																	{{-- <button type="button" class="btn-block-option js-edit-home-team-delete text-danger"
																			title=""
																			data-original-title="Delete">
																		<i class="fal fa-trash"></i>
																	</button> --}}
																{{-- </div>
															</div> --}}
															<div class="block-content block-content-full block-content-sm">
																<div class="row">
																	<div class="col-xl-4">
																		<div class="form-group mb-0">
																			<label class="m-0" for="line_ups_home_number">No:</label>
																			<label class="m-0">{{ $matchHome->shirt_number }}</label>
																			{{-- <input type="number" class="form-control line-ups-home-number"
																				   id="line_ups_home_number{{$key}}" min="0"
																				   name="line_ups_home_number[{{ $matchHome->id}}]"
																				   value="{{ $matchHome->shirt_number }}"> --}}
																			<input type="hidden" class="js-line-ups-away"
																				   name="line_ups_home_number_edit[{{ $matchHome->id }}]"
																				   value="1">
																		</div>
																	</div>
																	<div class="col-xl-4">
																		<div class="form-group mb-0">
																			<label class="m-0" for="line_ups_home_name">Name:</label>
																			<label class="m-0">{{ $matchHome->player->name }}</label>
																			{{-- <div>
																				<select class="js-select2 form-control line-ups-home-name"
																						id="line_ups_home_name{{$key}}"
																						name="line_ups_home_name[{{ $matchHome->id }}]"
																						style="width: 100%;">
																					<option value="">Please select</option>
																					@foreach($players as $player)
																						<option
																							value="{{ $player->id }}" {{ $matchHome->player_id == $player->id ? 'selected' : '' }}>{{ $player->name }}
																						</option>
																					@endforeach
																				</select>
																			</div> --}}
																			{{-- <input type="text" class="form-control line-ups-home-name"
																			id="line_ups_home_name{{$key}}" name="line_ups_home_name[{{ $matchHome->id }}]" value="{{ $matchHome->player->name }}"> --}}
																		</div>
																	</div>
																	<div class="col-xl-4">
																		<div class="form-group mb-0">
																			<label class="m-0">Substitute:</label>
																			<label class="m-0">{{ $matchHome->is_substitute ? "Yes" : "No" }}</label>
																			{{-- <div class="col-12">
																				<div class="custom-control custom-checkbox mb-5">
																					<input class="custom-control-input sub-home" type="checkbox"
																						   name="sub_home[{{ $matchHome->id }}]"
																						   id="sub_home{{$key}}"
																						   value="1" {{ $matchHome->is_substitute ? " checked" : "" }}>
																					<label class="custom-control-label"
																						   for="sub_home{{ $key }}">
																					</label>
																				</div>
																			</div> --}}
																		</div>
																	</div>
																</div>
															</div>
														</div>
													</div>
												@endif
											@endforeach
										</div>
									</div>
								</div>
							@endif

							{{-- <div class="row">
								<div class="col-xl-4">
									<div class="form-group">
										<button type="button" class="d-none btn btn-block btn-noborder btn-primary js-edit-home-team-player">Add home</button>
									</div>
								</div>
							</div> --}}

							@if($awayFlag)
								<div class="block block-bordered block-default">
									<div class="block-header block-header-default">
										Away
									</div>
									<div class="block-content">
										<div class="row" id="edit_away_team_player">
											@foreach($match->player as $key => $matchAway)
												@if($matchAway->club_id == $match->away_team_id)
													<div class="col-xl-12 edit-away-team" id="{{ $key }}">
														<div class="block block-bordered block-default">
															{{-- <div class="block-header block-header-default">
																<div class="d-none block-options">
																	<button type="button" class="btn-block-option js-edit-home-team-delete text-danger"
																			title=""
																			data-original-title="Delete">
																		<i class="fal fa-trash"></i>
																	</button>
																</div>
															</div> --}}
															<div class="block-content block-content-full block-content-sm">
																<div class="row">
																	<div class="col-xl-4">
																		<div class="form-group mb-0">
																			<label class="m-0" for="line_ups_away_number">No:</label>
																			<label class="m-0">{{ $matchAway->shirt_number }}
																			</label>
																			{{-- <input type="number" class="form-control line-ups-away-number"
																				   id="line_ups_away_number{{ $key }}" min="0"
																				   name="line_ups_away_number[{{ $matchAway->id }}]"
																				   value="{{ $matchAway->shirt_number}}"> --}}
																			<input type="hidden" class="js-line-ups-away"
																				   name="line_ups_away_number_edit[{{ $matchAway->id}}]"
																				   value="1">
																		</div>
																	</div>
																	<div class="col-xl-4">
																		<div class="form-group mb-0">
																			<label class="m-0" for="line_ups_away_name">Name:</label>
																			<label class="m-0">{{ $matchAway->player->name}}</label>
																			{{-- <div>
																				<select class="js-select2 form-control line-ups-away-name"
																						id="line_ups_away_name{{$key}}"
																						name="line_ups_away_name[{{ $matchHome->id }}]"
																						style="width: 100%;">
																					<option value="">Please select</option>
																					@foreach($players as $player)
																						<option
																							value="{{ $player->id }}" {{ $matchAway->player_id == $player->id ? 'selected' : '' }}>{{ $player->name }}</option>
																					@endforeach
																				</select>
																			</div> --}}
																			{{-- <input type="text" class="form-control line-ups-away-name" id="line_ups_away_name{{ $key }}" name="line_ups_away_name[{{ $matchAway->id }}]" value="{{ $matchAway->player->name}}"> --}}
																		</div>
																	</div>
																	<div class="col-xl-4">
																		<div class="form-group mb-0">
																			<label class="m-0"> Substitute:</label>
																			<label class="m-0">
																				{{ $matchAway->is_substitute ? "Yes" : "No" }}
																			</label>
																			{{-- <div class="col-12">
																				<div class="custom-control custom-checkbox mb-5">
																					<input class="custom-control-input sub-away" type="checkbox"
																						   name="sub_away[{{ $matchAway->id }}]"
																						   id="sub_away{{ $key }}"
																						   value="1" {{ $matchAway->is_substitute ? " checked" : "" }}>
																					<label class="custom-control-label"
																						   for="sub_away{{ $key }}"></label>
																				</div>
																			</div> --}}
																		</div>
																	</div>
																</div>
															</div>
														</div>
													</div>
												@endif
											@endforeach
										</div>
									</div>
								</div>
							@endif

							@if(!$homeFlag && !$awayFlag)
								<div class="row">
									<div class="col-xl-12">
										<div class="form-group text-center">
											No information available.
										</div>
									</div>
								</div>
							@endif
							{{-- <div class="row">
								<div class="col-xl-4">
									<div class="form-group">
										<button type="button" class="d-none btn btn-block btn-noborder btn-primary js-edit-away-team-player">Add away</button>
									</div>
								</div>
							</div> --}}
						</div>
						<div class="tab-pane" id="btabs-match-events" role="tabpanel">
							<div class="row" id="edit_match_event">
								@foreach($match->event as $key => $matchEvent)
									<div class="col-xl-12 edit-match-event" id='{{ $key }}'>
										<div class="block block-bordered block-default">
											<div class="block-header block-header-default">
												<div></div>
												<div class="block-options">
													<button type="button" class="btn-block-option js-match-event-delete text-danger" title=""
															data-original-title="Delete"><i class="fal fa-trash"></i></button>
												</div>
											</div>
											<input type="hidden" name="event_edit_ids[{{ $key }}]" value="{{ $matchEvent->id }}">
											@if ($matchEvent->action_replay_video)
												<input type="hidden" name="event_edit_video_name[{{ $key }}]"
													   value="{{ $matchEvent->action_replay_video_file_name }}">
											@endif
											<div class="block-content block-content-full">
												<div class="row">
													<div class="col-xl-4">
														<div class="form-group">
															<label class="required" for="match_event{{ $key }}">Team:</label>
															<select class="js-select2 form-control match-event-team"
																	id="match_event{{ $key }}" name="match_event[{{ $key }}]"
																	style="width:100%">
																<option value="">Please select</option>
																<option value="{{ $match->homeTeam->id }}"
																		data-type="home" {{ $matchEvent->club_id == $match->homeTeam->id ? 'selected' : '' }}>{{ $match->homeTeam->name }}</option>
																<option value="{{ $match->awayTeam->id }}"
																		data-type="away" {{ $matchEvent->club_id == $match->awayTeam->id ? 'selected' : '' }}>{{ $match->awayTeam->name }}</option>
															</select>
														</div>
													</div>
													<div class="col-xl-4">
														<div class="form-group">
															<label class="required" for="match_event_player{{ $key }}">Player:</label>
															<select class="js-select2 form-control match-event-player"
																	id="match_event_player{{ $key }}"
																	name="match_event_player[{{ $key }}]"
																	style="width:100%">
																<option value="">Please select</option>
																@if($matchEvent->club_id == $match->home_team_id)
																	@foreach ($homeLineupPlayer as $player )
																		<option
																			value="{{ $player->player->id }}" {{ $matchEvent->player_id == $player->player->id ? 'selected' : '' }}>{{ $player->player->name }}</option>
																	@endforeach
																@endif
																@if($matchEvent->club_id == $match->away_team_id)
																	@foreach ($awayLineupPlayer as $aPlayer )
																		<option
																			value="{{ $aPlayer->player->id }}" {{ $matchEvent->player_id == $aPlayer->player->id ? 'selected' : '' }}>{{ $aPlayer->player->name }}</option>
																	@endforeach
																@endif
															</select>
														</div>
													</div>
													<div class="col-xl-4">
														<div class="form-group">
															<label class="required" for="match_events_time{{ $key }}">Time (mins):</label>
															<input type="number" min="0"
																   class="form-control match_events_time"
																   id="match_events_time{{ $key }}"
																   name="match_events_time[{{ $key }}]"
																   value="{{ $matchEvent->minute }}">
														</div>
													</div>
													<div class="col-xl-4">
														<div class="form-group mb-0">
															<label>Action replay video:</label>
															<div class="custom-file">
																<input type="file"
																	   class="custom-file-input action_replay_video"
																	   id="action_replay_video{{ $key }}"
																	   name="action_replay_video[{{ $key }}]"
																	   data-toggle="custom-file-input" accept="video/*">
																<label class="custom-file-label text-truncate pr-100"
																	   for="action_replay_video{{ $key }}">Choose
																	file</label>
															</div>
														</div>
													</div>

													@if ($matchEvent->action_replay_video)
														<div class="col-xl-4">
															<div class="form-group">
																<label class="invisible">Download video</label>
																<div class="custom-file">
																	<a class="btn btn-alt-primary"
																	   href="{{ $matchEvent->action_replay_video }}">Download
																		video</a>
																</div>
															</div>
														</div>
													@endif

													<div class="col-xl-8">
														{{-- <div class="row"> --}}
															{{-- <div class="col-xl-6">
																<div class="form-group">
																	<label class="required" for="match_event_player{{ $key }}">Player:</label>
																	<select class="js-select2 form-control match-event-player"
																			id="match_event_player{{ $key }}"
																			name="match_event_player[{{ $key }}]"
																			style="width:100%">
																		<option value="">Please select</option>
																		@if($matchEvent->club_id == $match->home_team_id)
																			@foreach ($homeLineupPlayer as $player )
																				<option
																					value="{{ $player->player->id }}" {{ $matchEvent->player_id == $player->player->id ? 'selected' : '' }}>{{ $player->player->name }}</option>
																			@endforeach
																		@endif
																		@if($matchEvent->club_id == $match->away_team_id)
																			@foreach ($awayLineupPlayer as $aPlayer )
																				<option
																					value="{{ $aPlayer->player->id }}" {{ $matchEvent->player_id == $aPlayer->player->id ? 'selected' : '' }}>{{ $aPlayer->player->name }}</option>
																			@endforeach
																		@endif
																	</select>
																</div>
															</div>
															<div class="col-xl-6">
																<div class="form-group">
																	<label class="required" for="match_events_time{{ $key }}">Time (mins):</label>
																	<input type="number" min="0"
																		   class="form-control match_events_time"
																		   id="match_events_time{{ $key }}"
																		   name="match_events_time[{{ $key }}]"
																		   value="{{ $matchEvent->minute }}">
																</div>
															</div> --}}
															{{-- <div class="col-xl-12"> --}}
																<div class="row type-of-event-main-div">
																	<div class="col-xl-6">
																		<div class="form-group mb-0" data-select2-id="21">
																			<label class="required" for="match_type_of_event{{ $key }}">Type of
																				event:</label>
																			<select
																				class="form-control select2-match-event-type match-type-of-event js-select2"
																				id="match_type_of_event{{ $key }}"
																				name="match_type_of_event[{{ $key }}]"
																				style="width:100%">
																				<option value="">Please select</option>
																				@foreach ($matchEventtype as $k1 => $v1 )
																					<option
																						value="{{ $k1 }}" {{ $k1 == $matchEvent->event_type ? ' selected' : ''  }}>{{ $v1 }}</option>
																				@endforeach
																			</select>
																		</div>
																	</div>

																	@if($matchEvent->substitute_player_id)
																	<div class="col-xl-6 substitution-player">
																		<div class="form-group mb-0" data-select2-id="21">
																			<label for="match_type_of_event{{ $key }}">Subbed
																				for:</label>
																			<select
																				class="form-control js-select2 select2-substitution-player"
																				id="substitution-player{{ $key }}"
																				name="substitution_player[{{ $key }}]"
																				style="width:100%">
																				<option value="">Please select</option>
																				@if($matchEvent->club_id == $match->home_team_id)
																					@foreach ($homeBenchPlayer as $player )
																						<option
																							value="{{ $player->player->id }}" {{ $matchEvent->substitute_player_id == $player->player->id ? 'selected' : '' }}>{{ $player->player->name }}</option>
																					@endforeach
																				@endif
																				@if($matchEvent->club_id == $match->away_team_id)
																					@foreach ($awayBenchPlayer as $aPlayer )
																						<option
																							value="{{ $aPlayer->player->id }}" {{ $matchEvent->substitute_player_id == $aPlayer->player->id ? 'selected' : '' }}>{{ $aPlayer->player->name }}</option>
																					@endforeach
																				@endif
																			</select>
																		</div>
																	</div>
																	@endif
																</div>
															{{-- </div> --}}

														{{-- </div> --}}
													</div>
												</div>
											</div>
										</div>
									</div>
								@endforeach
							</div>
							<div class="row">
								<div class="col-xl-4">
									<div class="form-group">
										<button type="button"
												class="btn btn-block btn-primary btn-noborder js-edit-match-event">Add
											event
										</button>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-xl-12">
						<div class="form-group">
							<button type="submit"
									class="btn btn-hero btn-noborder btn-primary min-width-125 match-submit-btn">
								Update
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
								<button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
									<i class="si si-close"></i>
								</button>
							</div>
						</div>
						<form class="block-content" id="add_player_form">
							<div class="form-group">
								<label class="required" for="player_name">Name:</label>
								<input type="text" class="form-control" id="player_name" name="player_name"
									   value="{{ old('player_name') }}">
							</div>
						</form>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-hero btn-noborder btn-alt-secondary" data-dismiss="modal">
							Close
						</button>
						<button type="button" class="btn btn-hero btn-noborder btn-primary js-add-player-save">Save
						</button>
					</div>
				</div>
			</div>
		</div>
		<!-- END Fade In Modal -->
	</div>
@endsection
