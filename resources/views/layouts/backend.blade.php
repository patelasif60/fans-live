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

        <!-- Stylesheets -->

        <!-- Fonts and Codebase framework -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Muli:300,400,400i,600,700">

        <!-- You can include a specific file from css/themes/ folder to alter the default color theme of the template. eg: -->
        <!-- <link rel="stylesheet" id="css-theme" href="assets/css/themes/flat.min.css"> -->
        <!-- END Stylesheets -->

        {{-- Page specific plugin styles --}}
        <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
        <link rel="stylesheet" href="{{ asset('plugins/sweetalert2/sweetalert2.min.css') }}">
        @yield('plugin-styles')
        <link rel="stylesheet" id="css-main" href="{{ asset(mix('css/backend/main.css')) }}">

        {{-- Page specific custom stylesheets --}}
        @routes
        @yield('page-styles')
    </head>
    <body>

        <!-- Page Container -->
        <!--
            Available classes for #page-container:

        GENERIC

            'enable-cookies'                            Remembers active color theme between pages (when set through color theme helper Codebase() -> uiHandleTheme())

        SIDEBAR & SIDE OVERLAY

            'sidebar-r'                                 Right Sidebar and left Side Overlay (default is left Sidebar and right Side Overlay)
            'sidebar-mini'                              Mini hoverable Sidebar (screen width > 991px)
            'sidebar-o'                                 Visible Sidebar by default (screen width > 991px)
            'sidebar-o-xs'                              Visible Sidebar by default (screen width < 992px)
            'sidebar-inverse'                           Dark themed sidebar

            'side-overlay-hover'                        Hoverable Side Overlay (screen width > 991px)
            'side-overlay-o'                            Visible Side Overlay by default

            'enable-page-overlay'                       Enables a visible clickable Page Overlay (closes Side Overlay on click) when Side Overlay opens

            'side-scroll'                               Enables custom scrolling on Sidebar and Side Overlay instead of native scrolling (screen width > 991px)

        HEADER

            ''                                          Static Header if no class is added
            'page-header-fixed'                         Fixed Header

        HEADER STYLE

            ''                                          Classic Header style if no class is added
            'page-header-modern'                        Modern Header style
            'page-header-inverse'                       Dark themed Header (works only with classic Header style)
            'page-header-glass'                         Light themed Header with transparency by default
                                                        (absolute position, perfect for light images underneath - solid light background on scroll if the Header is also set as fixed)
            'page-header-glass page-header-inverse'     Dark themed Header with transparency by default
                                                        (absolute position, perfect for dark images underneath - solid dark background on scroll if the Header is also set as fixed)

        MAIN CONTENT LAYOUT

            ''                                          Full width Main Content if no class is added
            'main-content-boxed'                        Full width Main Content with a specific maximum width (screen width > 1200px)
            'main-content-narrow'                       Full width Main Content with a percentage width (screen width > 1200px)
        -->
        <div id="page-container" class="sidebar-o enable-page-overlay side-scroll page-header-modern">
            <!-- Side Overlay-->
            @include('partials.backend.overlay')
            <!-- END Side Overlay -->

            <!-- Sidebar -->
            <!--
                Helper classes

                Adding .sidebar-mini-hide to an element will make it invisible (opacity: 0) when the sidebar is in mini mode
                Adding .sidebar-mini-show to an element will make it visible (opacity: 1) when the sidebar is in mini mode
                    If you would like to disable the transition, just add the .sidebar-mini-notrans along with one of the previous 2 classes

                Adding .sidebar-mini-hidden to an element will hide it when the sidebar is in mini mode
                Adding .sidebar-mini-visible to an element will show it only when the sidebar is in mini mode
                    - use .sidebar-mini-visible-b if you would like to be a block when visible (display: block)
            -->
            @include('partials.backend.sidebar')
            <!-- END Sidebar -->

            <!-- Header -->
            @include('partials.backend.header')
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
            @include('partials.backend.footer')
            <!-- END Footer -->
        </div>
        <!-- END Page Container -->

        <!-- Codebase Core JS -->
        <script src="{{ asset('js/backend/core.js') }}"></script>

        <!-- Page JS Plugins -->
        <script src="{{ asset('plugins/jquery-validation/jquery.validate.min.js') }}"></script>
        <script type="text/javascript" src="{{ asset('plugins/jquery-validation/additional-methods.js') }}"></script>

        {{-- Plugin JS --}}
        {{-- <script type="text/javascript" src="https://demo.pixelcave.com/dashmix/assets/js/plugins/select2/js/select2.full.min.js"></script> --}}
        <script src="{{ asset('plugins/sweetalert2/sweetalert2.min.js')}}"></script>
        <script src="{{ asset('plugins/jquery-cookie/jquery.cookie.min.js')}}"></script>
        <script src="{{ asset('plugins/twbs-pagination/jquery.twbsPagination.min.js')}}"></script>
        <script src="{{ asset('plugins/toastr/js/toastr.min.js')}}"></script>
        <script src="{{ asset('plugins/select2/js/select2.full.min.js') }}"></script>
        <script src="{{ asset('plugins/moment/moment-with-locales.min.js') }}"></script>
        <script src="{{ asset('plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js') }}"></script>
        @yield('plugin-scripts')

        <script src="{{ asset('js/backend/common.js') }}"></script>
        {{-- <script type="text/javascript" src="{{ asset('plugins/moment/moment.min.js')}}"></script> --}}
        <script type="text/javascript" src="{{ asset('plugins/moment/moment-timezone.min.js')}}"></script>
        <script type="text/javascript" src="{{ asset('plugins/moment/moment-timezone-with-data.min.js')}}"></script>
        {{-- Page specific custom scripts --}}
        @yield('page-scripts')

        <!-- Page JS Helpers (BS Datepicker + BS Colorpicker + BS Maxlength + Select2 + Masked Input + Range Sliders + Tags Inputs plugins) -->
        <script>jQuery(function(){ Codebase.helpers(['select2']); });</script>
    </body>
</html>
