@extends('layouts.backend')

@section('content')
    <div class="block">
        <div class="block-header block-header-default">
            <h3 class="block-title">Change password</h3>
            <div class="block-options">
            </div>
        </div>
        <div class="block-content block-content-full">
            <div class="row justify-content-center">
                <div class="col-xl-6">
                    <form class="change-password-form" action="{{ route('backend.storechangepassword') }}" method="post">
                        @csrf
                        <input type="hidden" name="user_id" value="{{ Auth::user()->id }}">
                        <div class="clearfix"></div>
                        <div class="form-group{{ $errors->has('current_password') ? ' is-invalid' : '' }}">
                            <label for="current_password" class="required">Current password:</label>
                            <input type="password" class="form-control" id="current_password" name="current_password" value="{{ old('current_password') }}">
                            @if ($errors->has('current_password'))
                                <div class="invalid-feedback animated fadeInDown">
                                    <strong>{{ $errors->first('current_password') }}</strong>
                                </div>
                            @endif
                        </div>
                        
                        <div class="form-group{{ $errors->has('password') ? ' is-invalid' : '' }}">
                            <label for="password" class="required">Password:</label>
                            <input type="password" class="form-control" id="change-password" name="password" value="{{ old('password') }}">
                            @if ($errors->has('password'))
                                <div class="invalid-feedback animated fadeInDown">
                                    <strong>{{ $errors->first('password') }}</strong>
                                </div>
                            @endif
                        </div>

                        <div class="form-group{{ $errors->has('password_confirmation') ? ' is-invalid' : '' }}">
                            <label for="password_confirmation" class="required">Confirm password:</label>
                            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" value="{{ old('password_confirmation') }}">
                            @if ($errors->has('password_confirmation'))
                                <div class="invalid-feedback animated fadeInDown">
                                    <strong>{{ $errors->first('password_confirmation') }}</strong>
                                </div>
                            @endif
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-hero btn-noborder btn-primary min-width-125">
                                Save password
                            </button>
                            <a href="{{ route('backend.superadmin.dashboard') }}" class="btn btn-hero btn-noborder btn-alt-secondary">
                                Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('page-scripts')
    <script src="{{ asset('js/backend/pages/users/change_password.js') }}"></script>
@endsection