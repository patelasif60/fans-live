@extends('layouts.backend')

@section('page-scripts')
	<script src="{{ asset('js/backend/pages/users/role/create.js') }}"></script>
@endsection

@section('content')
	<div class="block">
		<div class="block-header block-header-default">
			<h3 class="block-title">Add role</h3>
			<div class="block-options">
			</div>
		</div>
		<div class="block-content block-content-full">
			<div class="row">
				<div class="col-xl-12">
					<form class="create-role-form" action="{{ route('backend.role.store') }}" method="post">
						@csrf
						<div class="form-group{{ $errors->has('display_name') ? ' is-invalid' : '' }} row">
							<label for="display_name" class="col-12 required">Name:</label>
							<div class="col-md-6">
								<input type="text" class="form-control" id="display_name" name="display_name" value="{{ old('display_name') }}">
								@if ($errors->has('display_name'))
									<div class="invalid-feedback animated fadeInDown">
										<strong>{{ $errors->first('display_name') }}</strong>
									</div>
								@endif
							</div>
						</div>
						<div class="form-group row">
							<label for="display_name" class="col-12 required">Permissions:</label>
							 @foreach($permission_own as $key => $permission_own)
							 <div class="col-xl-4 mb-5">
								<div class="custom-control custom-checkbox">
		                            <input class="custom-control-input" type="checkbox" value="{{ $key }}" name="permission[]" id="<?=strtolower(str_replace(' ', '_', $permission_own))?>">
		                            <label class="custom-control-label" for="<?=strtolower(str_replace(' ', '_', $permission_own))?>">{{ $permission_own }}</label>
	                        	</div>
							</div>
                            @endforeach
                             @if ($errors->has('permission'))
                             <div class="form-group col-xl-12 is-invalid mb-10">
                             	<div class="invalid-feedback animated fadeInDown">
									<strong>{{ $errors->first('permission') }}</strong>
								</div>
							</div>	
							@endif
						</div>
						<div class="form-group row">
							<div class="col-12">
								<button type="submit" class="btn btn-hero btn-noborder btn-primary min-width-125">
									Create
								</button>
								<a href="{{ route('backend.role.index') }}" class="btn btn-hero btn-noborder btn-alt-secondary">
									Cancel
								</a>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
@endsection
