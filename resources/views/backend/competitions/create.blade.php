@extends('layouts.backend')

@section('page-scripts')
	<script src="{{ asset('js/backend/pages/competitions/create.js') }}"></script>
@endsection

@section('content')
	<div class="block">
		<div class="block-header block-header-default">
			<h3 class="block-title">Add competition</h3>
			<div class="block-options">
			</div>
		</div>
		<div class="block-content block-content-full">
			<div class="row">
				<div class="col-xl-12">
					<form class="create-competition-form" action="{{ route('backend.competition.store') }}" method="post" enctype="multipart/form-data">
						@csrf
						<div class="row">
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

							<div class="col-xl-6">
								<div class="form-group">
                                    <div class="logo-fields-wrapper">
                                        <div class="d-flex">
                                            <div class="logo-input flex-grow-1">
                                                <label class="required">Logo:</label>
                                                <div class="input-group">
                                                    <div class="custom-file">
                                                        <div>
                                                            <input type="file" class="form-control custom-file-input" id="logo" name="logo" data-toggle="custom-file-input" accept="image/*">
                                                            <label class="form-control custom-file-label" for="logo">Choose file</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div id="logo_preview_container" class="d-md-none">
                                                <div class="logo_preview_container ml-3">
                                                    <img src="" id="logo_preview" alt="Competition logo">
                                                </div>
                                            </div>
                                        </div>
                                        @if ($errors->has('logo'))
		                                    <div class="invalid-feedback animated fadeInDown">
		                                        <strong>{{ $errors->first('logo') }}</strong>
		                                    </div>
		                                @endif
                                    </div>
                                </div>
	                    	</div>

	                    	<div class="col-xl-6">
		                        <div class="form-group{{ $errors->has('external_app_id') ? ' is-invalid' : '' }}">
									<label for="external_app_id" class="required">External API Competition ID:</label>
									<input type="text" class="form-control" id="external_app_id" name="external_app_id" value="{{ old('external_app_id') }}">
									@if ($errors->has('external_app_id'))
                                        <div class="invalid-feedback animated fadeInDown">
                                            <strong>{{ $errors->first('external_app_id') }}</strong>
                                        </div>
                                    @endif
								</div>
							</div>

							<div class="col-xl-6">
								<div class="form-group">
									<label></label>
		                        	<div class="custom-control custom-checkbox mb-5">
			                            <input class="custom-control-input" type="checkbox" name="is_primary"
			                            id="is_primary" value="1">
			                            <label class="custom-control-label" for="is_primary">Primary competition</label>
		                        	</div>
		                        </div>
							</div>

							<div class="col-xl-12">
								<div class="form-group">
		                            <label class="required">Status:</label>
		                            <div>
		                            	@foreach($competitionStatus as $key => $competition)
			                                <div class="custom-control custom-radio custom-control-inline mb-5">
			                                    <input class="custom-control-input" type="radio" name="status" id="status_{{$key}}" value="{{$competition}}" {{ $key == 'published' ? 'checked' : '' }}>
			                                    <label class="custom-control-label" for="status_{{$key}}">{{$competition}}</label>
			                                </div>
		                                @endforeach()
		                            </div>
		                        </div>
			                </div>

			                <div class="col-xl-12 mt-4">
			                	<h3 class="block-title mb-20">Manage clubs</h3>
			                </div>
			                <div class="col-xl-6 js-competition-clubs" v-cloak>
			                	<div class="form-group">
									<label for="club">Clubs:</label>
									<div class="d-flex">
										<div class="flex-grow-1">
											<select2 class="form-control" :options="clubOptions" v-model="selectedClub"></select2>
										</div>
										<div class="ml-3">
							                <button type="button" class="btn btn-primary btn-block btn-noborder px-4" @click="addClub()">Add</button>
							            </div>
									</div>
								</div>

		                        <div class="form-group row">
                            		<div class="col-12" v-for="competitionClub in competitionClubs">
                            			<div class="block block-rounded">
                                            <div class="block-header block-header-default">
                                                <h3 class="block-title">@{{ competitionClub.name }}</h3>
                                                <div class="block-options">
                                                    <button type="button" class="btn-block-option text-danger" data-toggle="tooltip" title="Remove" data-original-title="Remove" @click="removeClub(competitionClub.id)">
                                                        <i class="fal fa-trash"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                            		</div>
                            	</div>
                            	<input type="hidden" name="competition_clubs" id="competition_clubs" value="" />
							</div>
			                <div class="col-xl-12">
								<div class="form-group">
									<button type="submit" class="btn btn-hero btn-noborder btn-primary min-width-125">
										Create
									</button>
									<a href="{{ route('backend.competition.index') }}" class="btn btn-hero btn-noborder btn-alt-secondary">
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
