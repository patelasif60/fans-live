@extends('layouts.backend')

@section('page-scripts')
	<script src="{{ asset('js/backend/pages/users/cms/create.js') }}"></script>
@endsection

@section('content')
	<div class="block">
		<div class="block-header block-header-default">
			<h3 class="block-title">Add CMS user</h3>
			<div class="block-options">
			</div>
		</div>
		<div class="block-content block-content-full">
			<div class="row">
				<div class="col-xl-12">
					<form class="create-cms-form " action="{{ isset($clubData) ? route('backend.cms.club.store',['club' => app()->request->route('club')]):route('backend.cms.store') }}" method="post">
						@csrf

						<div class="row">
							<div class="col-xl-6">
								<div class="form-group{{ $errors->has('first_name') ? ' is-invalid' : '' }}">
									<label for="first_name" class="required">First name:</label>
									<input type="text" class="form-control" id="first_name" name="first_name" value="{{ old('first_name') }}">
									@if ($errors->has('first_name'))
										<div class="invalid-feedback animated fadeInDown">
											<strong>{{ $errors->first('first_name') }}</strong>
										</div>
									@endif
								</div>
							</div>

							<div class="col-xl-6">
								<div class="form-group{{ $errors->has('last_name') ? ' is-invalid' : '' }}">
									<label for="last_name" class="required">Last name:</label>
									<input type="text" class="form-control" id="last_name" name="last_name" value="{{ old('last_name') }}">
									@if ($errors->has('last_name'))
										<div class="invalid-feedback animated fadeInDown">
											<strong>{{ $errors->first('last_name') }}</strong>
										</div>
									@endif
								</div>
							</div>

							<div class="col-xl-6">
								<div class="form-group{{ $errors->has('email') ? ' is-invalid' : '' }}">
									<label for="email" class="required">Email address:</label>
									<input type="text" class="form-control" id="email" name="email" value="{{ old('email') }}">
									@if ($errors->has('email'))
									<div class="invalid-feedback animated fadeInDown">
										<strong>{{ $errors->first('email') }}</strong>
									</div>
									@endif
								</div>
							</div>

							<div class="col-xl-6">
								<div class="form-group{{ $errors->has('company') ? ' is-invalid' : '' }}">
									<label for="company" class="required">Company:</label>
									<input type="text" class="form-control" id="company" name="company" value="{{ old('company') }}">
									@if ($errors->has('company'))
										<div class="invalid-feedback animated fadeInDown">
											<strong>{{ $errors->first('company') }}</strong>
										</div>
									@endif
								</div>
							</div>

						</div>

						<div class="row">
							
							<div class="col-xl-6">
								<div class="form-group{{ $errors->has('notes') ? ' is-invalid' : '' }}">
									<label for="example-textarea-input">Notes</label>
									<div>
										<textarea class="form-control" id="notes" name="notes" rows="6"></textarea>
										@if ($errors->has('notes'))
											<div class="invalid-feedback animated fadeInDown">
												<strong>{{ $errors->first('notes') }}</strong>
											</div>
										@endif
									</div>
								</div>
							</div>

							<div class="col-xl-12">
								<div class="form-group">
									<label class="required">Status:</label>
									<div>
										@foreach($status as $key => $status)
											<div class="custom-control custom-radio custom-control-inline mb-5">
												<input class="custom-control-input" type="radio" name="status" id="status_{{$key}}" value="{{$status}}" {{ $key == 'active' ? 'checked' : '' }}>
												<label class="custom-control-label" for="status_{{$key}}">{{$status}}</label>
											</div>
										@endforeach()
									</div>
								</div>
							</div>

							<div class="col-xl-12">
								<div class="row">
									<div class="col-xl-12">
                                        <div class="content-heading">
                                            <h5 class="mb-0">Role section</h5>
                                        </div>
                                    </div>
								</div>
							</div>

							<div class="col-xl-12">
								<div class="form-group{{ $errors->has('role') ? ' is-invalid' : '' }}">
									<label  class="required">Role:</label>
									<div>
										@if(!isset($clubData))
										<div class="custom-control custom-radio custom-control-inline mb-5">
											<input class="custom-control-input" type="radio" name="role" id="role_superadmin" value="superadmin">
											<label class="custom-control-label" for="role_superadmin">Super admin</label>
										</div>
										@endif
										<div class="custom-control custom-radio custom-control-inline mb-5">
											<input {{ isset($clubData)?'checked':''}} class="custom-control-input" type="radio" name="role" id="role_clubadmin" value="clubadmin">
											<label class="custom-control-label" for="role_clubadmin">Club user</label>
										</div>
										{{-- @if(isset($clubData))
											{{isset($clubData)?'disabled checked ':'' }}
											<input type="hidden" name="role" value="clubadmin">
										@endif --}}
										@if ($errors->has('role'))
	                                        <div class="invalid-feedback animated fadeInDown">
	                                            <strong>{{ $errors->first('role') }}</strong>
	                                        </div>
	                                    @endif
									</div>
								</div>
							</div>

							<div class="col-xl-12 d-none" id="club_user">
								<div class="row">
									<div class="col-xl-6">
										<div class="row">
											<div class="col-xl-12">
												<div class="form-group">
		                                            <div>
		                                                <select {{isset($clubData)?'disabled':''}}  class="js-select2 form-control js-select2-allow-clear" id="club" name="club" style="width: 100%;">
		                                                	@if(isset($clubData))
		                                                		<option value='{{$clubData->id}}'>{{$clubData->name}}</option>
                                    						@else
		                                                    	<option value="">Please select</option>
				                                            	@foreach($clubs as $club)
				                                                	<option value="{{ $club->id }}">{{ $club->name }}</option>
				                                            	@endforeach
				                                            @endif
		                                                </select>
		                                            </div>
		                                        </div>
		                                    </div>
	                                        @if(isset($clubData))
	                                        	<input type="hidden" name="club" value="{{$clubData->id}}" >
											@endif
											<div class="col-xl-12">
												<div class="form-group row ">
		                                    		@foreach($roles as $role)
														<div class="col-xl-6">
															<div class="custom-control custom-checkbox mb-3">
																<input class="custom-control-input" type="checkbox" name="club_admin_roles[]" id="club_role_{{ $role->name }}" value="{{ $role->name }}">
																<label class="custom-control-label" for="club_role_{{ $role->name }}">{{ $role->name }}</label>
															</div>
														</div>	
														<div class="col-xl-6 mb-3">
									
															<div class="custom-control custom-checkbox text-right">
																<button type="button" ref-id="{{$role->id}}" class="js-showmodelpopup btn btn-sm btn-primary btn-noborder ml-auto d-block" data-toggle="modal" data-target="#permission">View permission</button>
															</div>
														</div>														 
													@endforeach
													<div class="col-xl-12">
														<div class="error-display">
														</div>
													</div>
						                    	</div>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="col-xl-12">
								<div class="form-group">
									<button type="submit" class="btn btn-hero btn-noborder btn-primary min-width-125">
										Create
									</button>
									<a href="{{ isset($clubData) ? route('backend.cms.club.index',['club' => app()->request->route('club')]) : route('backend.cms.index') }}" class="btn btn-hero btn-noborder btn-alt-secondary">
										Cancel
									</a>
								</div>
							</div>

						</div>
					</form>
					<div class="modal fade" id="permission" role="dialog"
                         aria-labelledby="permission_info_content" aria-hidden="true">
                        <div class="modal-dialog modal-lg" role="document">
                            <div class="modal-content">
                                <div class="block block-themed block-transparent mb-0">
                                    <div class="block-header bg-primary-dark">
                                        <h3 class="block-title">Permissions</h3>
                                        <div class="block-options">
                                            <button type="button" class="btn-block-option" data-dismiss="modal"
                                                    aria-label="Close">
                                                <i class="si si-close"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="block-content block-content-full">
	                                    <div class="row">
											<div class="col-xl-12" id="permissionData">
											</div>
	                                    </div>
                                	</div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-hero btn-noborder btn-alt-secondary"
                                            data-dismiss="modal">Close
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
				</div>
			</div>
		</div>
	</div>
@endsection