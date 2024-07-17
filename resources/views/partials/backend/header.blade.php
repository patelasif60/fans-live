<header id="page-header">
    <!-- Header Content -->
    <div class="content-header d-block">
        <div class="row align-items-center justify-content-between">
            <div class="col-2 col-md-2 col-lg-2">
                <!-- Left Section -->
                <div class="content-header-section">
                    <!-- Toggle Sidebar -->
                    <!-- Layout API, functionality initialized in Codebase() -> uiApiLayout() -->
                    <button type="button" class="btn btn-circle btn-dual-secondary" data-toggle="layout" data-action="sidebar_toggle">
                        <i class="fal fa-bars"></i>
                    </button>
                    <!-- END Toggle Sidebar -->
                </div>
                <!-- END Left Section -->
            </div>
            <div class="col-10 col-md-6 col-lg-5 col-xl-4">
                <!-- Right Section -->
                <div class="content-header-section">
                    <div class="row">
                        <div class="col-9 col-md-8">
                            @role('superadmin')
                                <!-- Switch context -->
                                <select class="js-select2 form-control js-context-switch" name="context_switch">
                                    <option value="{{ route('backend.superadmin.dashboard') }}">Admin</option>
                                    @foreach($clubs as $club)
                                        <option {{ request()->route()->parameter('club') == $club->slug ? "selected='selected'" : '' }} value="{{ route('backend.clubadmin.dashboard', ['club' => $club->slug]) }}">{{ $club->name }}</option>
                                    @endforeach
                                </select>
                                <!-- Switch context -->
                            @endrole
                        </div>

                        <div class="col-3 col-md-4">
                            <!-- User Dropdown -->
                            <div class="btn-group pull-right" role="group">
                                <button type="button" class="btn btn-rounded btn-dual-secondary" id="page-header-user-dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fal fa-user d-sm-none"></i>
                                    <span class="d-none d-sm-inline-block">{{ auth()->user()->first_name }}</span>
                                    <i class="fal fa-angle-down ml-5"></i>
                                </button>
                                <div class="dropdown-menu dropdown-menu-right min-width-200" aria-labelledby="page-header-user-dropdown">
                                    <a class="dropdown-item" href="{{ route('backend.changepassword') }}">
                                        <i class="fal fa-fw fa-key mr-5"></i> Change password
                                    </a>
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                        onclick="event.preventDefault();
                                                 document.getElementById('logout-form').submit();">
                                        <i class="fal fa-fw fa-sign-out mr-5"></i> Sign out
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        {{ csrf_field() }}
                                    </form>
                                </div>
                            </div>
                            <!-- END User Dropdown -->
                        </div>
                    </div>
                </div>
                <!-- END Right Section -->
            </div>
        </div>
    </div>
    <!-- END Header Content -->

    <!-- Header Loader -->
    <!-- Please check out the Activity page under Elements category to see examples of showing/hiding it -->
    <div id="page-header-loader" class="overlay-header bg-primary">
        <div class="content-header content-header-fullrow text-center">
            <div class="content-header-item">
                <i class="fal fa-sun fa-spin text-white"></i>
            </div>
        </div>
    </div>
    <!-- END Header Loader -->
</header>
