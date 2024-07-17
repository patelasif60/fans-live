@extends('layouts.backend')

@section('plugin-styles')
	<link rel="stylesheet" href="{{ asset('plugins/summernote/summernote-bs4.css')}}">
@endsection

@section('plugin-scripts')
	<script src="{{ asset('plugins/ckeditor/ckeditor.js') }}"></script>
@endsection

@section('page-scripts')
	<script src="{{ asset('js/backend/pages/events/edit.js') }}"></script>
@endsection

@section('content')
	<div class="block">
		<div class="block-header block-header-default">
			<h3 class="block-title">Edit event</h3>
			<label class="align-items-right">Available ticket : {{$event->number_of_tickets - $event->getEventTickets($event) }} </label>
			<div class="block-options">
			</div>
		</div>
		<div class="block-content block-content-full">
			<div class="row">
				<div class="col-xl-12">
					<form class="edit-event-form"
						  action="{{ route('backend.event.update', ['club' => app()->request->route('club'), 'event' => $event]) }}"
						  method="post" enctype="multipart/form-data">
						{{ method_field('PUT') }}
						{{ csrf_field() }}
						<div class="row">
							<div class="col-xl-6">
								<div class="form-group{{ $errors->has('title') ? ' is-invalid' : '' }}">
									<label for="title" class="required">Title:</label>
									<input type="text" class="form-control" id="title" name="title"
										   value="{{ $event->title }}">
									@if ($errors->has('title'))
										<div class="invalid-feedback animated fadeInDown">
											<strong>{{ $errors->first('title') }}</strong>
										</div>
									@endif
								</div>
							</div>

							<div class="col-xl-6">
								<div class="form-group{{ $errors->has('location') ? ' is-invalid' : '' }}">
									<label for="location" class="required">Location:</label>

									<input type="text" class="form-control" id="location" name="location"
										   value="{{ $event->location }}">

									@if ($errors->has('location'))
										<div class="invalid-feedback animated fadeInDown">
											<strong>{{ $errors->first('location') }}</strong>
										</div>
									@endif
								</div>
							</div>

							<div class="col-xl-6">
								<div class="form-group {{ $errors->has('dateandtime') ? ' is-invalid' : '' }}">
									<label for="dateandtime" >Date and time:</label>
									<div class='input-group date js-datetimepicker' data-target-input="nearest"
										 id="dateandtime">
										<input type="text" class="form-control datetimepicker-input" name="dateandtime"
											   value="{{ convertDateTimezone($event->date_time, null, $clubDetail->time_zone, config('fanslive.DATE_TIME_CMS_FORMAT.php')) }}" data-target="#dateandtime" readonly
											   data-toggle="datetimepicker"/>
										<div class="input-group-append" data-target="#dateandtime"
											 data-toggle="datetimepicker">
											<div class="input-group-text"><i class="fal fa-calendar-alt"></i></div>
										</div>
									</div>

									@if ($errors->has('dateandtime'))
										<div class="invalid-feedback animated fadeInDown">
											<strong>{{ $errors->first('dateandtime') }}</strong>
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
                                                            <input type="file" class="form-control custom-file-input" id="logo" name="logo" data-toggle="custom-file-input" accept="image/*">
                                                            <label id="lbl_logo" name="lbl_logo" class="form-control custom-file-label" for="logo">Choose file</label>
                                                        </div>
                                                    </div>
                                                </div>  
                                            </div>
                                            <div id="logo_preview_container" class="{{ $event->image ? '' : 'd-md-none' }}">
                                                <div class="logo_preview_container ml-3">
                                                    <img src="{{ $event->image }}" id="logo_preview" alt="Event logo">
                                                </div>
                                            </div>
                                        </div>
                                        <label class="helper m-0">Image dimensions: 840px X 525px (png only)</label>
                                    </div>
                                </div>

								{{-- <div class="form-group row">
									<div
										class="{{ $event->image ? 'col-9' : 'col-12' }} js-manage-logo-width">
										<label  class="required">Image:</label>
										<div class="row">
											<div class="col-12">
												<div class="custom-file">
													<input type="file" class="custom-file-input" id="logo" name="logo"
														   data-toggle="custom-file-input">
													<label id="lbl_logo" name="lbl_logo" class="custom-file-label"
														   for="logo">Choose file</label>
												</div>
												<label class="helper mt-5">Image dimensions: 840px X 525px ( png only
																		)</label>
											</div>
										</div>
									</div>
									<div class="col-3 {{ $event->image ? '' : 'd-md-none' }}"
										 id="logo_preview_container">
										<div class="logo_preview_container m-auto">
											<img src="{{ $event->image }}" id="logo_preview" alt="Event logo">
										</div>
									</div>
								</div> --}}
							</div>

							<div class="col-xl-6">
								<div class="form-group{{ $errors->has('price') ? ' is-invalid' : '' }}">
									<label for="price" class="required">Price:</label>

									<div class="input-group">
										<div class="input-group-text">
											<i class="font-size-sm font-w600 text-uppercase text-muted">{{ $currencySymbol[$club->currency] }}</i>
										</div>
										<input type="text" class="form-control" name="price" value="{{ $event->price }}"
											   min="0">
									</div>
									@if ($errors->has('price'))
										<div class="invalid-feedback animated fadeInDown">
											<strong>{{ $errors->first('price') }}</strong>
										</div>
									@endif
								</div>
							</div>

							<div class="col-xl-6">
								<div class="form-group{{ $errors->has('vat_rate') ? ' is-invalid' : '' }}">
									<label for="vat_rate" class="required">VAT (%):</label>
									<input type="text" class="form-control" name="vat_rate"
										   value="{{ $event->vat_rate }}" min="0">
									@if ($errors->has('vat_rate'))
										<div class="invalid-feedback animated fadeInDown">
											<strong>{{ $errors->first('vat_rate') }}</strong>
										</div>
									@endif
								</div>
							</div>

							<div class="col-xl-6">
								<div class="form-group">
									<label for="rewards_percentage_override">Rewards percentage override:</label>
									<input type="text" class="form-control" name="rewards_percentage_override"
										   id="rewards_percentage_override"
										   value="{{ $event->rewards_percentage_override }}">
								</div>
							</div>

							<div class="col-xl-6">
								<div
									class="form-group{{ $errors->has('number_of_tickets ') ? ' is-invalid' : '' }}">
									<label for="vat_rate" class="required">Number of tickets:</label>
									<input type="number" class="form-control" name="number_of_tickets"
										   id="number_of_tickets"
										   value="{{ $event->number_of_tickets }}" min="0">
									@if ($errors->has('number_of_tickets'))
										<div class="invalid-feedback animated fadeInDown">
											<strong>{{ $errors->first('number_of_tickets') }}</strong>
										</div>
									@endif
								</div>
							</div>

							<div class="col-xl-6">
								<div class="form-group {{ $errors->has('description') ? ' is-invalid' : '' }}">
									<label for="description" class="required">Description:</label>
									<div class="row">
										<div class="col-12">
											<textarea id="js-ckeditor"
													  name="description">{{$event->description}}</textarea>
										</div>
										@if ($errors->has('description'))
											<div class="invalid-feedback animated fadeInDown">
												<strong>{{ $errors->first('description') }}</strong>
											</div>
										@endif
									</div>
								</div>
							</div>

							<div class="col-xl-6">
								<div class="form-group">
									<label class="required">Status:</label>
									<div>
										@foreach($eventStatus as $statusKey => $status)
											<div class="custom-control custom-radio custom-control-inline mb-5">
												<input class="custom-control-input" type="radio" name="status"
													   id="status_{{$statusKey}}"
													   value="{{$status}}" {{ $event->status == $status ? 'checked' : '' }}>
												<label class="custom-control-label"
													   for="status_{{$statusKey}}">{{$status}}</label>
											</div>
										@endforeach()
									</div>
								</div>
								<div class="form-group">
									<label class="required">Availability:</label>
									<div>
										@if(in_array(config('fanslive.ALL_FANS_MEMBERSHIP_PACKAGE_ID'), $eventPackage))
											@foreach($membershipPackageList as $packageListKey => $packageList)
												<div class="col-xl-4 custom-control custom-checkbox custom-control-inline mb-5">
													<input class="form-group custom-control-input @if($packageListKey == config('fanslive.ALL_FANS_MEMBERSHIP_PACKAGE_ID')) default-package @else premium-package @endif" type="checkbox"
														   name="packageList[]" id="packageList_{{$packageListKey}}"
														   @if(in_array($packageListKey, $eventPackage) && ($packageListKey == config('fanslive.ALL_FANS_MEMBERSHIP_PACKAGE_ID'))) checked @else checked disabled 
														   @endif value="{{$packageListKey}}">
													<label class="custom-control-label"
														   for="packageList_{{$packageListKey}}">{{$packageList}}</label>
												</div>
											@endforeach()
										@else
											@foreach($membershipPackageList as $packageListKey => $packageList)
												<div class="col-xl-4 custom-control custom-checkbox custom-control-inline mb-5">
													<input class="form-group custom-control-input @if($packageListKey == config('fanslive.ALL_FANS_MEMBERSHIP_PACKAGE_ID')) default-package @else premium-package @endif" type="checkbox"
														   name="packageList[]" id="packageList_{{$packageListKey}}"
														   @if(in_array($packageListKey, $eventPackage)) checked
														   @endif value="{{$packageListKey}}">
													<label class="custom-control-label"
														   for="packageList_{{$packageListKey}}">{{$packageList}}</label>
												</div>
											@endforeach()
										@endif
									</div>
								</div>
							</div>

							<div class="col-xl-12">
								<div class="form-group">
									<button type="submit" class="btn btn-hero btn-noborder btn-primary min-width-125 js-event-update">
										Update
									</button>
									<a href="{{ route('backend.event.index', ['club' => app()->request->route('club')]) }}"
									   class="btn btn-hero btn-noborder btn-alt-secondary">
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
