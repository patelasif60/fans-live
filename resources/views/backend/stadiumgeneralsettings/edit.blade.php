@extends('layouts.backend')

@section('page-scripts')
	<script src="{{ asset('js/backend/pages/stadiumgeneralsettings/edit.js') }}"></script>
@endsection

@section('content')
	<div class="block">
		<div class="block-header block-header-default">
			<h3 class="block-title">Edit stadium general settings</h3>
			<div class="block-options">
			</div>
		</div>
		<div class="block-content block-content-full">
			<div class="row">
				<div class="col-xl-12">
					<form class="edit-stadium-settings-form align-items-center"
						  action="{{ route('backend.stadiumgeneralsettings.update', ['club' => app()->request->route('club')]) }}"
						  method="post" enctype="multipart/form-data">
						{{ method_field('PUT') }}
						{{ csrf_field() }}

						<div class="row">
							<div class="col-xl-6">
								<div class="form-group{{ $errors->has('name') ? ' is-invalid' : '' }}">
									<label for="name" class="required">Name:</label>
									<input type="text" class="form-control" name="name"
										   value="{{isset($stadiumGeneralSetting->name) ? $stadiumGeneralSetting->name : '' }}">
									@if ($errors->has('name'))
										<div class="invalid-feedback animated fadeInDown">
											<strong>{{ $errors->first('name') }}</strong>
										</div>
									@endif
								</div>
							</div>

							<div class="col-xl-6">
								<div class="form-group{{ $errors->has('address') ? ' is-invalid' : '' }}">
									<label for="address" class="required">Stadium address:</label>
									<input type="text" class="form-control" name="address" value=
									"{{isset($stadiumGeneralSetting->address) ? $stadiumGeneralSetting->address : '' }}">
									@if ($errors->has('address'))
										<div class="invalid-feedback animated fadeInDown">
											<strong>{{ $errors->first('address') }}</strong>
										</div>
									@endif
								</div>
							</div>

							<div class="col-xl-6">
								<div class="form-group">
									<label for="address_2">Stadium address 2:</label>
									<input type="text" class="form-control" name="address_2"
										   value="{{isset($stadiumGeneralSetting->address_2) ? $stadiumGeneralSetting->address_2 : '' }}">
								</div>
							</div>

							<div class="col-xl-6">
								<div class="form-group{{ $errors->has('town') ? ' is-invalid' : '' }}">
									<label for="town" class="required">Stadium address - town or city:</label>
									<input type="text" class="form-control" name="town"
										   value="{{isset($stadiumGeneralSetting->town) ? $stadiumGeneralSetting->town : '' }}">
									@if ($errors->has('town'))
										<div class="invalid-feedback animated fadeInDown">
											<strong>{{ $errors->first('town') }}</strong>
										</div>
									@endif
								</div>
							</div>

							<div class="col-xl-6">
								<div class="form-group{{ $errors->has('postcode') ? ' is-invalid' : '' }}">
									<label for="postcode" class="required">Stadium address - postcode:</label>
									<input type="text" class="form-control" name="postcode"
										   value="{{isset($stadiumGeneralSetting->postcode) ? $stadiumGeneralSetting->postcode : '' }}">
									@if ($errors->has('postcode'))
										<div class="invalid-feedback animated fadeInDown">
											<strong>{{ $errors->first('postcode') }}</strong>
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
                                                            <label class="form-control custom-file-label text-truncate pr-100" for="image">{{ isset($stadiumGeneralSetting->image_file_name) ? $stadiumGeneralSetting->image_file_name : 'Choose file' }}</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div id="image_preview_container" class="{{ isset($stadiumGeneralSetting->image) ? '' : 'd-md-none' }}">
                                                <div id="image_preview_container">
                                                    <div class="logo_preview_container ml-3">
                                                        <img id="image_preview" name="image_preview" src="{{isset($stadiumGeneralSetting->image)? $stadiumGeneralSetting->image : ''}}" alt="image">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <label class="helper m-0">Image dimensions: 840px X 525px (png only)</label>
                                    </div>
                                </div>
							</div>

							<div class="col-xl-6">
								<div class="form-group">
									<div class="custom-control custom-checkbox mb-5">
										<input class="custom-control-input" type="checkbox"
											   name="is_using_allocated_seating" id="is_using_allocated_seating"
											   {{ isset($stadiumGeneralSetting->is_using_allocated_seating) && $stadiumGeneralSetting->is_using_allocated_seating == 1 ? 'checked' : ''}} {{(isset($clubDetail->stadiumBlocks) && $clubDetail->stadiumBlocks->count() > 0) ||(isset ($clubDetail->ticketTransaction) && $clubDetail->ticketTransaction->count() > 0) ? 'disabled': ''}} value="1">
										@if((isset($clubDetail->stadiumBlocks) && $clubDetail->stadiumBlocks->count() > 0) || (isset ($clubDetail->ticketTransaction) && $clubDetail->ticketTransaction->count() > 0))
											<input type="hidden"
											   name="is_using_allocated_seating" value="{{ isset($stadiumGeneralSetting) ? $stadiumGeneralSetting->is_using_allocated_seating : '' }}" />
										@endif
										<label class="custom-control-label" for="is_using_allocated_seating">Stadium
											uses allocated seating (Enable blocks)</label>
									</div>
								</div>
							</div>

							<div class="col-xl-6 js-aerial-view-ticketing-graphic {{ !(isset($stadiumGeneralSetting->is_using_allocated_seating) && $stadiumGeneralSetting->is_using_allocated_seating == 1) ? 'd-none' : ''}}">
								<div class="logo-fields-wrapper">
                                    <div class="d-flex">
                                        <div class="logo-input flex-grow-1">
                                            <label class="required">Aerial view ticketing graphic:</label>
                                            <div class="input-group">
                                                <div class="custom-file">
                                                    <div>
                                                    	<input type="hidden" value="{{ isset($stadiumGeneralSetting->aerial_view_ticketing_graphic_file_name) ? $stadiumGeneralSetting->aerial_view_ticketing_graphic_file_name : '' }}" id="aerial_view_ticketing_graphic_file_name" name="aerial_view_ticketing_graphic_file_name">
                                                        <input type="file" class="form-control custom-file-input uploadarialimage" id="aerial_view_ticketing_graphic" name="aerial_view_ticketing_graphic" data-toggle="custom-file-input" accept="image/*">
                                                        <label class="form-control custom-file-label text-truncate pr-100" id="lbl_aerial_view_ticketing_graphic"
													   name="lbl_aerial_view_ticketing_graphic"
													   for="aerial_view_ticketing_graphic">{{ isset($stadiumGeneralSetting->aerial_view_ticketing_graphic_file_name) ? $stadiumGeneralSetting->aerial_view_ticketing_graphic_file_name : 'Choose file' }}</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="{{ isset($stadiumGeneralSetting->aerial_view_ticketing_graphic) ? '' : 'd-md-none' }}">
                                            <div id="logo_preview_container">
                                                <div class="logo_preview_container ml-3">
                                                    <img id="logo_preview" name="logo_preview" src="{{ isset($stadiumGeneralSetting->aerial_view_ticketing_graphic) ? $stadiumGeneralSetting->aerial_view_ticketing_graphic : ''}}" alt="Image">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
							</div>

							<div class="col-xl-12 js-number-of-seats {{isset($stadiumGeneralSetting->is_using_allocated_seating) && $stadiumGeneralSetting->is_using_allocated_seating == 1 ? 'd-none' : ''}}" >
								<div class="row">
									<div class="col-xl-6">
										<div class="form-group {{ $errors->has('number_of_seats') ? ' is-invalid' : '' }}">
											<label class="required" for="number_of_seats">Number of seats:</label>
											<input type="number" min="1"class="form-control" name="number_of_seats" id="number_of_seats" value="{{ isset($stadiumGeneralSetting->number_of_seats) ? $stadiumGeneralSetting->number_of_seats : '' }}">
											@if ($errors->has('number_of_seats'))
												<div class="invalid-feedback animated fadeInDown">
													<strong>{{ $errors->first('number_of_seats') }}</strong>
												</div>
											@endif
										</div>
									</div>
								</div>
							</div>
							<div class="col-xl-12">
								<div class="form-group">
									<button type="submit" class="btn btn-hero btn-noborder btn-primary min-width-125">
										Update
									</button>
								</div>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
@endsection
