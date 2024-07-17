@extends('layouts.auth')

@section('content')
    <div class="content content-full">
        @if(session('status'))
            <div class="px-30 py-10">
                <div class="alert alert-info">
                    {{ session('status') }}
                </div>
            </div>
        @endif

        <!-- Header -->
        <div class="px-30 py-10">
            <img src="{{ asset('img/backend/fanslive_logo_blue.svg') }}" alt="Fanslive Logo" style="height: 30px;">
            <h1 class="h3 font-w700 mt-30 mb-10">Please sign in</h1>
        </div>
        <!-- END Header -->

        <!-- Sign In Form -->
        <!-- jQuery Validation (.js-validation-signin class is initialized in js/pages/op_auth_signin.js) -->
        <!-- For more examples you can check out https://github.com/jzaefferer/jquery-validation -->
        <form class="js-validation-signin px-30 auth-login-form" method="POST" action="{{ route('login') }}">
            @csrf
            <div class="form-group row">
                <div class="col-12">
                    <div class="form-material floating">
                        <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" autofocus>
                        <label for="email">{{ __('Email Address') }}</label>
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
                    <label class="css-control css-control-primary css-checkbox">
                        <input class="css-control-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                        <span class="css-control-indicator"></span> {{ __('Remember me') }}
                    </label>
                </div>
            </div>


            <div class="form-group row">
                <div class="col-12 mb-10">
                    <button type="submit" class="btn btn-block btn-hero btn-noborder btn-lg btn-primary">
                        {{ __('Login') }}
                    </button>
                </div>
                <div class="col-sm-6 mb-5">
                    <a class="btn btn-block btn-noborder btn-alt-secondary" href="javascript:void(0);">
                        {{ __('Create account') }}
                    </a>
                </div>
                <div class="col-sm-6 mb-5">
                    <a class="btn btn-block btn-noborder btn-alt-secondary" href="{{ route('password.request') }}">
                        {{ __('Forgot your password?') }}
                    </a>
                </div>
            </div>
        </form>
        <!-- END Sign In Form -->
    </div>
@endsection

@section('page-scripts')
    <script src="{{ asset('js/backend/auth/login.js') }}"></script>
@endsection
