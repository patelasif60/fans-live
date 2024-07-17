@extends('layouts.error')

@section('content')

<div class="py-30 text-center">
    <div class="display-3 text-danger">
        <i class="fal fa-ban"></i> 401
    </div>
    <h1 class="h2 font-w700 mt-30 mb-10">Unauthorized.</h1>
    <h2 class="h3 font-w400 text-muted mb-50">We are sorry but you do not have permission to access this page.</h2>
    <a class="btn btn-hero btn-rounded btn-alt-secondary" href="{{ route('login') }}">
        <i class="fal fa-arrow-left mr-10"></i> Back to login
    </a>
</div>

@endsection
