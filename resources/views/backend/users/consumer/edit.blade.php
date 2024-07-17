@extends('layouts.backend')

@section('plugin-styles')
@endsection

@section('plugin-scripts')
@endsection

@section('page-scripts')
    <script src="{{ asset('js/backend/pages/users/consumer/edit.js') }}"></script>
@endsection
@section('content')
    <div class="block">
        <div class="block-header block-header-default">
            <h3 class="block-title">Edit APP user</h3>
			<div class="block-options">
				<h3 class="block-title">Loyalty points: {{isset($userLoyaltyRewardPointBalance) ? $userLoyaltyRewardPointBalance : 0}}</h3>
			</div>
        </div>
        <div class="block-content block-content-full">
            <div class="row">
                <div class="col-xl-12">
                    <form class="edit-consumer-form" action="{{ isset($clubData) ? route('backend.consumer.club.update',['club' => app()->request->route('club'),'user' => $user]) : route('backend.consumer.update', $user) }}" method="post">
                        {{ method_field('PUT') }}
                        {{ csrf_field() }}

                        <div class="row">

                            <div class="col-xl-6">
                                <div class="form-group {{ $errors->has('first_name') ? ' is-invalid' : '' }}">
                                    <label for="first_name" class="required">First name:</label>
                                    <input type="text" class="form-control" id="first_name" name="first_name" value="{{ $user->first_name }}">
                                    @if ($errors->has('first_name'))
                                        <div class="invalid-feedback animated fadeInDown">
                                            <strong>{{ $errors->first('first_name') }}</strong>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div class="col-xl-6">
                                <div class="form-group {{ $errors->has('last_name') ? ' is-invalid' : '' }}">
                                    <label for="last_name" class="required">Last name:</label>
                                    <input type="text" class="form-control" id="last_name" name="last_name" value="{{ $user->last_name }}">
                                    @if ($errors->has('last_name'))
                                        <div class="invalid-feedback animated fadeInDown">
                                            <strong>{{ $errors->first('last_name') }}</strong>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div class="col-xl-6">
                                <div class="form-group {{ $errors->has('email') ? ' is-invalid' : '' }}">
                                    <label for="email" class="required">Email:</label>
                                    <input type="text" class="form-control" id="email" name="email" value="{{ $user->email }}">
                                    @if ($errors->has('email'))
                                        <div class="invalid-feedback animated fadeInDown">
                                            <strong>{{ $errors->first('email') }}</strong>
                                        </div>
                                    @endif
                                </div>
                            </div>
							
                            <div class="col-md-6">
                                <div class="form-group mb-0  {{ $errors->has('dob') ? ' is-invalid' : '' }}">
                                    <label class="required" for="from_date">Date of birth:</label>
                                    <div class='input-group date js-datepicker' data-target-input="nearest" id="dob">
                                        <input  placeholder="dd-mm-yyyy"  type="text" class="form-control datetimepicker-input" name="dob" data-target="#dob" readonly value="{{ convertDateFormat($user->date_of_birth, null, config('fanslive.DATE_CMS_FORMAT.php')) }}" data-toggle="datetimepicker"/>
                                        <div class="input-group-append" data-target="#dob" data-toggle="datetimepicker">
                                            <div class="input-group-text"><i class="fal fa-calendar-alt"></i></div>
                                        </div>
                                    </div>
                                     @if ($errors->has('dob'))
                                            <div class="invalid-feedback animated fadeInDown col-12">
                                                <strong>{{ $errors->first('dob') }}</strong>
                                            </div>
                                        @endif
                                </div>
                            </div>
                           <div class="col-xl-6">
                                <div class="form-group {{ $errors->has('club') ? ' is-invalid' : '' }}">
                                    <label for="club" class="required">Club:</label>
                                    <div>
                                        <select class="form-control js-select2" id="club" name="club">
                                            @if(isset($clubData))
                                                <option value='{{$clubData->id}}'>{{$clubData->name}}</option>
                                            @else
                                                <option value="">Please select</option>
                                                @foreach($clubs as $club)
                                                    <option value="{{ $club->id }}" {{ $club->id == $user->club_id ? 'selected' : '' }}>{{ $club->category ? $club->name . ' (' . $club->category->name . ')' : $club->name }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                        @if ($errors->has('club'))
                                            <div class="invalid-feedback animated fadeInDown">
                                                <strong>{{ $errors->first('club') }}</strong>
                                            </div>
                                        @endif
                                    </div>
                                    @if(isset($clubData))
                                        <input type="hidden" name="club" value="{{$clubData->id}}" >
                                    @endif
                                </div>
                            </div>

							<div class="col-xl-6">
								<div class="form-group">
									<label>Time zone:</label>
									<div>
										{!! $timeZone !!}
									</div>
								</div>
							</div>
                            <div class="col-xl-6">
                                <div class="form-group">
                                    <label class="required">Status:</label>
                                    <div>
                                        @foreach($status as $key => $status)
                                            <div class="custom-control custom-radio custom-control-inline mb-5">
                                                <input class="custom-control-input" type="radio" name="status" id="status_{{$key}}" value="{{$status}}" {{ $status == $user->status ? 'checked' : '' }}>
                                                <label class="custom-control-label" for="status_{{$key}}">{{$status}}</label>
                                            </div>
                                        @endforeach()
                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-12">
                                <div class="form-group">
                                    <button type="submit" class="btn btn-hero btn-noborder btn-primary min-width-125">Update</button>
                                    <a href="{{ isset($clubData) ? route('backend.consumer.club.index', ['club' => app()->request->route('club')]) : route('backend.consumer.index') }}" class="btn btn-hero btn-noborder btn-alt-secondary">Cancel</a>
                                </div>
                            </div>

                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection