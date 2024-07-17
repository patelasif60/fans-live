@extends('layouts.auth')

@section('content')
    <div class="content content-full">
        @if (session('status'))
            <div class="px-30 py-10">
                <div class="alert alert-info">
                    {{ session('status') }}
                </div>
            </div>
        @endif
        <div class="px-30 py-10">
            <a href="{{ route('login') }}">
                <img src="{{ asset('img/backend/fanslive_logo_blue.svg') }}" alt="Fanslive Logo" style="height: 30px;">
            </a>
            <h1 class="h3 font-w700 mt-30 mb-10">{{ __('Reset Password') }}</h1>
        </div>
        <form class="js-validation-signin px-30 auth-reset-password-form" method="POST" action="{{ route('password.email') }}">
            @csrf
            <div class="form-group row">
                <div class="col-12">
                    <div class="form-material floating">
                        <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" autofocus>
                        <label for="email">{{ __('E-Mail Address') }}</label>
                        @if ($errors->has('email'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('email') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-12 mb-10">
                    <button type="submit" class="btn btn-block btn-hero btn-noborder btn-lg btn-primary">
                        <i class="fal fa-envelope mr-10"></i> {{ __('Send Password Reset Link') }}
                    </button>
                </div>
                <div class="col-sm-6 mb-5">
                    <a class="btn btn-block btn-noborder btn-alt-secondary" href="{{ route('login') }}">
                        <i class="fal fa-lock mr-5"></i> {{ __('Login') }}
                    </a>
                </div>
                <div class="col-sm-6 mb-5">
                    <a class="btn btn-block btn-noborder btn-alt-secondary" href="javascript:void(0);">
                        <i class="fal fa-plus mr-5"></i> {{ __('Create Account') }}
                    </a>
                </div>
            </div>
        </form>
    </div>    
@endsection
@section('page-scripts')
    <script src="{{ asset('js/backend/auth/reset_password.js') }}"></script>
@endsection