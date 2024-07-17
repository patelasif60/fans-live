@extends('layouts.auth')

@section('content')
    <div class="content content-full">
        <div class="px-30 py-10">
            <a href="{{ route('login') }}">
                <img src="{{ asset('img/backend/fanslive_logo_blue.svg') }}" alt="Fanslive Logo" style="height: 30px;">
            </a>
            <h1 class="h3 font-w700 mt-30 mb-10">{{ __('Set Password') }}</h1>
        </div>
        <form class="js-validation-signin px-30 auth-set-password-form" method="POST" action="{{ route('password.update') }}">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">
            <div class="form-group row">
                <div class="col-12">
                    <div class="form-material floating">
                        <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ $email }}" autofocus readonly="readonly">
                        <label for="email">{{ __('Email address') }}</label>
                        @if ($errors->has('email'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('email') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-12">
                    <div class="form-material floating">
                        <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password">
                        <label for="password">{{ __('Password') }}</label>
                        @if ($errors->has('password'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('password') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-12">
                    <div class="form-material floating">
                        <input id="password_confirm" type="password" class="form-control{{ $errors->has('password-confirm') ? ' is-invalid' : '' }}" name="password_confirmation">
                        <label for="password_confirm">{{ __('Confirm Password') }}</label>
                        @if ($errors->has('password-confirm'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('password-confirm') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-12 mb-10">
                    <button type="submit" class="btn btn-block btn-hero btn-noborder btn-lg btn-primary">
                        {{-- <i class="fal fa-envelope mr-10"></i> --}}
                        {{ __('Set Password') }}
                    </button>
                </div>
            </div>
        </form>
    </div>    
@endsection
@section('page-scripts')
    <script src="{{ asset('js/backend/auth/reset_password.js') }}"></script>
@endsection