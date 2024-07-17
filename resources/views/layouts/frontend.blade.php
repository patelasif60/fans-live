<!doctype html>
<!--[if lte IE 9]>     <html lang="en" class="no-focus lt-ie10 lt-ie10-msg"> <![endif]-->
<!--[if gt IE 9]><!--> <html lang="en" class="no-focus"> <!--<![endif]-->
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">

        <title>{{ config('app.name', 'Fanslive') }}</title>

        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="description" content="{{ config('app.name', 'Admin') }} created by Aecor Digital Limited">
        <meta name="author" content="aecor">
        <meta name="robots" content="noindex, nofollow">

        <!-- Open Graph Meta -->
        <meta property="og:title" content="{{ config('app.name', 'Admin') }}">
        <meta property="og:site_name" content="Fanslive">
        <meta property="og:description" content="{{ config('app.name', 'Admin') }} created by Aecor Digital Limited">
        <meta property="og:type" content="website">
        <meta property="og:url" content="">
        <meta property="og:image" content="">

        <!-- Icons -->
        <!-- The following icons can be replaced with your own, they are used by desktop and mobile browsers -->
        <link rel="apple-touch-icon" sizes="57x57" href="{{ asset('favicons/apple-icon-57x57.png')}}">
        <link rel="apple-touch-icon" sizes="60x60" href="{{ asset('favicons/apple-icon-60x60.png')}}">
        <link rel="apple-touch-icon" sizes="72x72" href="{{ asset('favicons/apple-icon-72x72.png')}}">
        <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('favicons/apple-icon-76x76.png')}}">
        <link rel="apple-touch-icon" sizes="114x114" href="{{ asset('favicons/apple-icon-114x114.png')}}
        ">
        <link rel="apple-touch-icon" sizes="120x120" href="{{ asset('favicons/apple-icon-120x120.png')}}
        ">
        <link rel="apple-touch-icon" sizes="144x144" href="{{ asset('favicons/apple-icon-144x144.png')}}
        ">
        <link rel="apple-touch-icon" sizes="152x152" href="{{ asset('favicons/apple-icon-152x152.png')}}
        ">
        <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('favicons/apple-icon-180x180.png')}}
        ">
        <link rel="icon" type="image/png" sizes="192x192"  href="{{ asset('favicons/android-icon-192x192.png')}}">
        <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicons/favicon-32x32.png')}}">
        <link rel="icon" type="image/png" sizes="96x96" href="{{ asset('favicons/favicon-96x96.png')}}">
        <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicons/favicon-16x16.png')}}">
        <link rel="manifest" href="{{ asset('favicons/manifest.json')}}">
        <meta name="msapplication-TileColor" content="#ffffff">
        <meta name="msapplication-TileImage" content="/ms-icon-144x144.png">
        <meta name="theme-color" content="#ffffff">
        <!-- END Icons -->

        @yield('plugin-styles')
        <link rel="stylesheet" href="{{ asset(mix('css/frontend/main.css')) }}">
		@routes

        @yield('page-styles')
    </head>
    <body>
        <div id="page-container">
            <!-- Header -->
            @include('partials.frontend.header')
            <!-- END Header -->

            <!-- Main Container -->
            <main id="main-container">

                <!-- Page Content -->
                <div class="content">
                    @include('flash::message')
                    @yield('content')
                </div>
                <!-- END Page Content -->

            </main>
            <!-- END Main Container -->

            <!-- Footer -->
            @include('partials.frontend.footer')
            <!-- END Footer -->
        </div>
        <!-- END Page Container -->

        <script src="{{ asset('js/frontend/common.js') }}"></script>

        <!-- Page JS Plugins -->
        <!-- <script src="{{ asset('plugins/jquery-validation/jquery.validate.min.js') }}"></script>
        <script type="text/javascript" src="{{ asset('plugins/jquery-validation/additional-methods.js') }}"></script> -->

        {{-- Plugin JS --}}
        <!-- <script src="{{ asset('plugins/select2/js/select2.full.min.js') }}"></script>
        <script src="{{ asset('plugins/sweetalert2/sweetalert2.min.js')}}"></script>
        <script src="{{ asset('plugins/jquery-cookie/jquery.cookie.min.js')}}"></script>
        <script src="{{ asset('plugins/twbs-pagination/jquery.twbsPagination.min.js')}}"></script>
        <script src="{{ asset('plugins/toastr/js/toastr.min.js')}}"></script> -->
        <script type="text/javascript" src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
        @yield('plugin-scripts')

        @yield('page-scripts')

        <!-- Page JS Helpers (BS Datepicker + BS Colorpicker + BS Maxlength + Select2 + Masked Input + Range Sliders + Tags Inputs plugins) -->
        <!-- <script>jQuery(function(){ Codebase.helpers(['select2']); });</script> -->
    </body>
</html>
