@extends('layouts.backend')

@section('page-scripts')
    <script src="{{ asset('js/backend/pages/competitions/edit.js') }}"></script>
@endsection

@section('content')
	<div class="block">
		<div class="block-header block-header-default">
            <h3 class="block-title">Edit competition</h3>
            <div class="block-options">
            </div>
        </div>
        <div class="block-content block-content-full">
        	<div class="row">
                <div class="col-xl-12">
		            <form class="edit-competition-form align-items-center" action="{{ route('backend.competition.update', $competition) }}" method="post" enctype="multipart/form-data">
		    			{{ method_field('PUT') }}
                        {{ csrf_field() }}

                        <div class="row">
                            <div class="col-xl-6">
        		    			<div class="form-group{{ $errors->has('name') ? ' is-invalid' : '' }}">
        		                    <label for="name" class="required">Name:</label>
        		                    <input type="text" class="form-control" id="name" name="name" value="{{ $competition->name }}">
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
                                                            <input type="file" class="form-control custom-file-input" id="logo" name="logo" data-toggle="custom-file-input">
                                                            <label class="form-control custom-file-label" for="logo">Choose file</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div id="competition-logo" class="{{ $competition->logo ? '' : 'd-md-none' }}">
                                                <div id="logo_preview_container">
                                                    <div class="logo_preview_container ml-3">
                                                        <img src="{{ $competition->logo }}" alt="Competition logo">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                {{-- <div class="form-group row">
                                    <div class="{{ $competition->logo ? 'col-9' : 'col-12' }} js-manage-logo-width">
                                        <label class="required">Logo:</label>
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="custom-file"> --}}
                                                    <!-- Populating custom file input label with the selected filename (data-toggle="custom-file-input" is initialized in Codebase() -> uiHelperCoreCustomFileInput()) -->
                                                    {{-- <input type="file" class="form-control custom-file-input" id="logo" name="logo" data-toggle="custom-file-input">
                                                    <label class="form-control custom-file-label" for="logo">Choose file</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-3 {{ $competition->logo ? '' : 'd-md-none' }}" id="competition-logo">
                                        <div class="d-flex justify-content-center" id="logo_preview_container">
                                            <img src="{{ $competition->logo }}" class="img-avatar img-avatar-square" alt="Competition logo">
                                        </div>
                                    </div>
                                </div> --}}
                            </div>

                            <div class="col-xl-6">
                                <div class="form-group{{ $errors->has('external_app_id') ? ' is-invalid' : '' }}">
                                    <label for="external_app_id" class="required">External API competition ID:</label>
                                    <input type="text" class="form-control" id="external_app_id" name="external_app_id" value="{{ $competition->external_app_id }}">
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
                                        id="is_primary" {{ $competition->is_primary ? 'checked' : ''}} value="1">
                                        <label class="custom-control-label" for="is_primary">Primary competition</label>
                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-12">
                                <div class="form-group">
                                    <label class="required">Status:</label>
                                    <div>
                                        @foreach($competitionStatus as $key => $status)
                                            <div class="custom-control custom-radio custom-control-inline mb-5">
                                                <input class="custom-control-input" type="radio" name="status" id="status_{{$key}}" value="{{$status}}" {{ $status == $competition->status ? 'checked' : '' }}>
                                                <label class="custom-control-label" for="status_{{$key}}">{{$status}}</label>
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
                                        Update
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
