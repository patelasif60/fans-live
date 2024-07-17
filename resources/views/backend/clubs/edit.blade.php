@extends('layouts.backend')

@section('plugin-styles')
    <link rel="stylesheet" href="{{ asset('plugins/bootstrap-colorpicker/css/bootstrap-colorpicker.min.css') }}">
@endsection

@section('plugin-scripts')
    <script src="{{ asset('plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.min.js') }}"></script>
@endsection

@section('page-scripts')
    <script src="{{ asset('js/backend/pages/clubs/edit.js') }}"></script>
@endsection

@section('content')
	<div class="block">
		<div class="block-header block-header-default">
            <h3 class="block-title">Edit club</h3>
            <div class="block-options">
            </div>
        </div>
        <div class="block-content block-content-full">
        	<div class="row">
                <div class="col-xl-12">
		            <form class="edit-club-form" action="{{ route('backend.club.update', $club) }}" method="post" enctype="multipart/form-data">
		    			{{ method_field('PUT') }}
                        {{ csrf_field() }}
                        <div class="row">
                            <div class="col-xl-6">
                                <div class="form-group{{ $errors->has('name') ? ' is-invalid' : '' }}">
                                    <label for="name" class="required">Name:</label>
                                    <input type="text" class="form-control" id="name" name="name" value="{{ $club->name }}">
                                    @if ($errors->has('name'))
                                        <div class="invalid-feedback animated fadeInDown">
                                            <strong>{{ $errors->first('name') }}</strong>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div class="col-xl-6">
                                <div class="form-group{{ $errors->has('category') ? ' is-invalid' : '' }}">
                                    <label for="category" class="required">Category:</label>
                                    <div>
                                        <select class="js-select2 form-control" id="category" name="category">
                                            <option value="">Please select</option>
                                            @foreach($categories as $category)
                                                <option value="{{ $category->id }}" {{ $category->id == $club->club_category_id ? 'selected' : '' }}>{{ $category->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @if ($errors->has('category'))
                                        <div class="invalid-feedback animated fadeInDown">
                                            <strong>{{ $errors->first('category') }}</strong>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div class="col-xl-6">
                                <div class="form-group{{ $errors->has('external_api_team_id') ? ' is-invalid' : '' }}">
                                    <label for="external_api_team_id" class="required">External API Team ID:</label>
                                    <input type="text" class="form-control" id="external_api_team_id" name="external_api_team_id" value="{{ $club->external_app_id }}">
                                    @if ($errors->has('external_api_team_id'))
                                        <div class="invalid-feedback animated fadeInDown">
                                            <strong>{{ $errors->first('external_api_team_id') }}</strong>
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
                                                            <input type="hidden" value="{{ isset($club->logo_file_name) ? $club->logo_file_name : '' }}" id="logo_file_name" name="logo_file_name">
                                                            <input type="file" class="form-control custom-file-input" id="logo" name="logo" data-toggle="custom-file-input" accept="image/png">
                                                            <label class="form-control custom-file-label text-truncate pr-100" for="logo">{{ isset($club->logo_file_name) ? $club->logo_file_name : 'Choose file' }}</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div id="club-logo" class="{{ $club->logo ? '' : 'd-md-none' }}">
                                                <div id="logo_preview_container">
                                                    <div class="logo_preview_container ml-3">
                                                        <img id="logo_preview" src="{{ $club->logo }}" alt="Club logo">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <label class="helper m-0">Logo dimensions: 150px X 150px ( png only )</label>
                                    </div>
                                </div>
                                {{-- <div class="form-group row">
                                    <div class="{{ $club->logo ? 'col-9' : 'col-12' }} js-manage-logo-width">
                                        <label class="required">Logo:</label>
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="custom-file"> --}}
                                                    <!-- Populating custom file input label with the selected filename (data-toggle="custom-file-input" is initialized in Codebase() -> uiHelperCoreCustomFileInput()) -->
													{{-- <input type="hidden" value="{{ isset($club->logo_file_name) ? $club->logo_file_name : '' }}" id="logo_file_name" name="logo_file_name">
													<input type="file" class="custom-file-input" id="logo" name="logo" data-toggle="custom-file-input" accept="image/png">
                                                    <label class="custom-file-label text-truncate pr-100" for="logo">{{ isset($club->logo_file_name) ? $club->logo_file_name : 'Choose file' }}</label>
                                                </div>
												<label class="helper mt-5">Logo dimensions: 150px X 150px ( png only )</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-3 {{ $club->logo ? '' : 'd-md-none' }}" id="club-logo">
                                        <div class="d-flex justify-content-center" id="logo_preview_container">
                                            <img src="{{ $club->logo }}" id="logo_preview" class="img-avatar img-avatar-square" alt="Club logo">
                                        </div>
                                    </div>
                                </div> --}}
                            </div>

                            <div class="col-xl-6">
                                <div class="form-group">
                                    <label for="primary_colour">Primary colour:</label>
                                    <div>
                                        <div class="js-colorpicker input-group" data-format="hex">
                                            <input type="text" class="form-control" id="primary_colour" name="primary_colour" value="{{ $club->primary_colour }}">
                                            <div class="input-group-append input-group-addon">
                                                <div class="input-group-text">
                                                    <i></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-6">
                                <div class="form-group">
                                    <label for="secondary_colour">Secondary colour:</label>
                                    <div>
                                        <div class="js-colorpicker input-group" data-format="hex">
                                            <input type="text" class="form-control" id="secondary_colour" name="secondary_colour" value="{{ $club->secondary_colour }}">
                                            <div class="input-group-append input-group-addon">
                                                <div class="input-group-text">
                                                    <i></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
							<div class="col-xl-6">
								<div class="form-group">
									<label class="required">Time zone:</label>
									<div>
										{!! $timeZone !!}
									</div>
								</div>
							</div>
                            <div class="col-xl-12">
                                <div class="form-group">
                                    <label class="required">Status:</label>
                                    <div>
                                        @foreach($clubStatus as $key => $status)
                                            <div class="custom-control custom-radio custom-control-inline mb-5">
                                                <input class="custom-control-input" type="radio" name="status" id="status_{{$key}}" value="{{$status}}" {{ $status == $club->status ? 'checked' : '' }}>
                                                <label class="custom-control-label" for="status_{{$key}}">{{$status}}</label>
                                            </div>
                                        @endforeach()
                                    </div>
                                </div>
                            </div>

							<div class="col-xl-12 mt-4">
								<h3 class="block-title mb-20">Currency section</h3>
							</div>
							<div class="col-xl-12">
								<div class="form-group">
									<label class="required">Currency:</label>
									<div>
										@foreach($currencyList as $currencyKey => $currency)
											<div class="custom-control custom-radio custom-control-inline mb-5">
												<input class="custom-control-input currency_type_{{$currencyKey}}" type="radio" name="currency"
													   id="currency_{{$currencyKey}}"
													   value="{{$currencyKey}}" {{ $currencyKey == $club->currency ? 'checked': '' }}>
												<label class="custom-control-label"
													   for="currency_{{$currencyKey}}">{{$currency}}</label>
											</div>
										@endforeach()
									</div>
								</div>
							</div>

							<div class="col-xl-12">
								<h3 class="block-title mb-20">Bank details</h3>
							</div>

							<div class="col-xl-6">
								<div
									class="form-group{{ $errors->has('bank_name') ? ' is-invalid' : '' }}">
									<label for="bank_name" class="required">Bank name:</label>
									<input type="text" class="form-control" id="bank_name" name="bank_name"
										   value="{{ isset($clubBankDetail->bank_name) ? $clubBankDetail->bank_name : '' }}">
									@if ($errors->has('bank_name'))
										<div class="invalid-feedback animated fadeInDown">
											<strong>{{ $errors->first('bank_name') }}</strong>
										</div>
									@endif
								</div>
							</div>
							<div class="col-xl-6">
								<div
									class="form-group{{ $errors->has('account_name') ? ' is-invalid' : '' }}">
									<label for="name" class="required">Account name:</label>
									<input type="text" class="form-control" id="account_name" name="account_name"
										   value="{{ isset($clubBankDetail->account_name) ? $clubBankDetail->account_name :'' }}">
									@if ($errors->has('account_name'))
										<div class="invalid-feedback animated fadeInDown">
											<strong>{{ $errors->first('account_name') }}</strong>
										</div>
									@endif
								</div>
							</div>
							<div class="col-xl-6">
								<div
									class="form-group{{ $errors->has('account_number') ? ' is-invalid' : '' }}">
									<label for="name" class="required">Account number:</label>
									<input type="text" class="form-control" id="account_number" name="account_number"
										   value="{{ isset($clubBankDetail->account_number) ? $clubBankDetail->account_number : '' }}">
									@if ($errors->has('account_number'))
										<div class="invalid-feedback animated fadeInDown">
											<strong>{{ $errors->first('account_number') }}</strong>
										</div>
									@endif
								</div>
							</div>
                            <div class="col-xl-6">
								<div
									class="form-group{{ $errors->has('sort_code') ? ' is-invalid' : '' }}">
									<label for="name" class="required">Sort code:</label>
									<input type="text" class="form-control" id="sort_code" name="sort_code"
										   value="{{ isset($clubBankDetail->sort_code) ? $clubBankDetail->sort_code :'' }}">
									@if ($errors->has('sort_code'))
										<div class="invalid-feedback animated fadeInDown">
											<strong>{{ $errors->first('sort_code') }}</strong>
										</div>
									@endif
								</div>
							</div>
							<div class="col-xl-6 radio_EUR" style="display: none;">
								<div
									class="form-group{{ $errors->has('bic') ? ' is-invalid' : '' }}">
									<label for="name" class="required">BIC:</label>
									<input type="text" class="form-control" id="bic" name="bic"
										   value="{{ isset($clubBankDetail->bic) ? $clubBankDetail->bic : '' }}">
									@if ($errors->has('bic'))
										<div class="invalid-feedback animated fadeInDown">
											<strong>{{ $errors->first('bic') }}</strong>
										</div>
									@endif
								</div>
							</div>
							<div class="col-xl-6 radio_EUR" style="display: none;">
								<div
									class="form-group{{ $errors->has('iban') ? ' is-invalid' : '' }}">
									<label for="name" class="required">IBAN:</label>
									<input type="text" class="form-control" id="iban" name="iban"
										   value="{{ isset($clubBankDetail->iban) ? $clubBankDetail->iban : ''}}">
									@if ($errors->has('iban'))
										<div class="invalid-feedback animated fadeInDown">
											<strong>{{ $errors->first('iban') }}</strong>
										</div>
									@endif
								</div>
							</div>


                            <div class="col-xl-12 mt-4">
                                <h3 class="block-title mb-20">Manage competitions</h3>
                            </div>
                            <div class="col-xl-6 js-club-competitions" v-cloak>
                                <div class="form-group">
                                    <label for="club">Competition:</label>
                                    <div class="d-flex">
                                        <div class="flex-grow-1">
                                            <select2 class="form-control" :options="competitionOptions" v-model="selectedCompetition"></select2>
                                        </div>
                                        <div class="ml-3">
                                            <button type="button" class="btn btn-primary btn-noborder px-4" @click="addCompetition()">Add</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-12" v-for="clubCompetition in clubCompetitions">
                                        <div class="block block-rounded">
                                            <div class="block-header block-header-default">
                                                <h3 class="block-title">@{{ clubCompetition.name }}</h3>
                                                <div class="block-options">
                                                    <button type="button" class="btn-block-option text-danger" data-toggle="tooltip" title="Remove" data-original-title="Remove" @click="removeCompetition(clubCompetition.id)">
                                                        <i class="fal fa-trash"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <input type="hidden" name="club_competitions" id="club_competitions" value="" />
                            </div>

                            <div class="col-xl-12">
                                <div class="form-group">
                                    <button type="submit" class="btn btn-hero btn-noborder btn-primary min-width-125">
                                        Update
                                    </button>
                                    <a href="{{ route('backend.club.index') }}" class="btn btn-hero btn-noborder btn-alt-secondary">
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
