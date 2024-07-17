@extends('layouts.auth')

@section('page-scripts')
    <script src="{{ asset('js/backend/pages/users/cms/set_password.js') }}"></script>
@endsection

@section('content')
<div class="content content-full">
    <div class="px-30 py-10">
        <img src="{{ asset('img/backend/fanslive_logo_blue.svg') }}" alt="Fanslive Logo" style="height: 30px;">
        <h1 class="h3 font-w700 mt-30 mb-10">Set password</h1>
    </div>
    <form class="js-validation-signin admin-set-password-link-form px-30" method="POST" action="{{ route('backend.cmsuser.password') }}">
        <input type="hidden" name="token" id="token" value="{{ $token }}">
        {{  csrf_field() }}
        
        <div class="form-group row">
            <div class="col-12">
                <div class="form-material floating">
                    <input id="email" type="email" class="form-control" name="email" value="{{ $email }}" autofocus readonly="readonly">
                    <label for="email">Email address</label>
                </div>
            </div>
        </div>

        <div class="form-group row{{ $errors->has('password') ? ' is-invalid' : '' }}">
            <div class="col-12">
                <div class="form-material floating">
                    <input id="password" type="password" class="form-control" name="password" value="{{ old('password') }}" required autofocus>
                    <label for="password">Password</label>
                </div>
                @if ($errors->has('password'))
                    <div class="invalid-feedback animated fadeInDown">
                        <strong>{{ $errors->first('password') }}</strong>
                    </div>
                @endif
            </div>
        </div>

        <div class="form-group row">
            <div class="col-12">
                <div class="form-material floating">
                    <input id="password_confirmation" type="password" class="form-control" name="password_confirmation" required>
                    <label for="password_confirmation">Confirm password</label>
                </div>
            </div>
        </div>

        <div class="form-group row">
            <div class="col-12 my-10">
                <button type="submit" class="btn btn-block btn-hero btn-noborder btn-lg btn-primary">
                    Set password
                </button>
            </div>
        </div>

    </form>
</div>
@endsection
