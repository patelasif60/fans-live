<nav id="sidebar">
	<!-- Sidebar Scroll Container -->
	<div id="sidebar-scroll">
		<!-- Sidebar Content -->
		<div class="sidebar-content">
			<!-- Side Header -->
			<div class="content-header content-header-fullrow px-15">
				<!-- Mini Mode -->
				<div class="content-header-section sidebar-mini-visible-b">
					<!-- Logo -->
					<span class="content-header-item font-w700 font-size-xl float-left animated fadeIn">
                        <span class="text-dual-primary-dark">f</span><span class="text-primary">l</span>
                    </span>
					<!-- END Logo -->
				</div>
				<!-- END Mini Mode -->

				<!-- Normal Mode -->
				<div class="content-header-section text-center align-parent sidebar-mini-hidden">
					<!-- Close Sidebar, Visible only on mobile screens -->
					<!-- Layout API, functionality initialized in Codebase() -> uiApiLayout() -->
					<button type="button" class="btn btn-circle btn-dual-secondary d-lg-none align-v-r"
							data-toggle="layout" data-action="sidebar_close">
						<i class="fal fa-times text-danger"></i>
					</button>
					<!-- END Close Sidebar -->

					<!-- Logo -->
					<div class="content-header-item">
						<img src="{{ asset('img/backend/fanslive_logo_blue.svg') }}" style="width: 110px"/>
					</div>
					<!-- END Logo -->
				</div>
				<!-- END Normal Mode -->
			</div>
			<!-- END Side Header -->

			<!-- Side Navigation -->
			<div class="content-side content-side-full">
				<ul class="nav-main">
					@if($currentPanel == 'superadmin')
						<li>
							<a class="{{ active('backend.superadmin.dashboard') }}"
							   href="{{ route('backend.superadmin.dashboard') }}"><i
									class="far fa-desktop-alt"></i><span class="sidebar-mini-hide">Dashboard</span></a>
						</li>

						<li class="{{ active(['backend.cms.*', 'backend.staff.*', 'backend.consumer.*', 'backend.role.*'], 'open') }}">
							<a class="nav-submenu" data-toggle="nav-submenu" href="#"><i class="fal fa-user-friends"></i><span
									class="sidebar-mini-hide">Users</span></a>
							<ul>
								<li>
									<a class="{{ active('backend.role.*') }}" href="{{ route('backend.role.index') }}">CMS user
										roles</a>

								</li>
								<li>
									<a class="{{ active('backend.cms.*') }}" href="{{ route('backend.cms.index') }}">CMS
										users</a>

								</li>
								<li>
									<a class="{{ active('backend.consumer.*') }}"
									   href="{{ route('backend.consumer.index') }}">APP users</a>
								</li>
								<li>
									<a class="{{ active('backend.staff.*') }}"
									   href="{{ route('backend.staff.index') }}">Staff APP users</a>
								</li>
							</ul>
						</li>

						<li>
							<a class="{{ active('backend.clubcategory.*') }}"
							   href="{{ route('backend.clubcategory.index') }}"><i class="fal fa-list-alt"></i><span
									class="sidebar-mini-hide">Categories</span></a>
						</li>
						<li>
							<a class="{{ active('backend.club.*') }}" href="{{ route('backend.club.index') }}"><i
									class="fal fa-trophy-alt"></i><span class="sidebar-mini-hide">Clubs</span></a>
						</li>
						<li>
							<a class="{{ active('backend.competition.*') }}"
							   href="{{ route('backend.competition.index') }}"><i class="fal fa-sitemap"></i><span
									class="sidebar-mini-hide">Competitions</span></a>
						</li>
						<li class="{{ active(['backend.transaction.*','backend.setting.*'], 'open') }}">
							<a class="nav-submenu" data-toggle="nav-submenu" href="#"><i
									class="fal fa-credit-card"></i><span class="sidebar-mini-hide">Transactions</span></a>
							<ul>
								<li>
									<a class="{{ active(route('backend.transaction.report',['type' => 'gbp'])) }}"
									   href="{{ route('backend.transaction.report',['type' => 'gbp']) }}">GBP transactions</a>
								</li>
								<li>
									<a class="{{ active(route('backend.transaction.report',['type' => 'eur'])) }}"
									   href="{{ route('backend.transaction.report',['type' => 'eur']) }}">EUR transactions</a>
								</li>
								<li>
									<a class="{{ active('backend.setting.*') }}"
									   href="{{ route('backend.setting.index') }}">Settings</a>
								</li>
							</ul>
						</li>
						@endrole
						@if($currentPanel == 'clubadmin')
						    @if(Auth::user()->hasRole('superadmin') || \Auth::user()->can('access.clubadmin.dashboard.own'))
							<li>
								<a class="{{ active('backend.clubadmin.dashboard') }}"
								   href="{{ route('backend.clubadmin.dashboard', ['club' => app()->request->route('club')]) }}"><i
										class="far fa-desktop-alt"></i><span class="sidebar-mini-hide">Dashboard</span></a>
							</li>
							@endif
							@if(Auth::user()->hasRole('superadmin') || \Auth::user()->can('access.clubadmin.users.own'))
							<li class="{{ active(['backend.cms.*', 'backend.staff.*', 'backend.consumer.*'], 'open') }}">
								<a class="nav-submenu" data-toggle="nav-submenu" href="#"><i class="si si-puzzle"></i><span
										class="sidebar-mini-hide">Users</span></a>
								<ul>
									<li>
										<a class="{{ active('backend.cms.*') }}" href="{{ route('backend.cms.club.index', ['club' => app()->request->route('club')]) }}">CMS
											users</a>

									</li>
									<li>
										<a class="{{ active('backend.consumer.*') }}"
										   href="{{ route('backend.consumer.club.index', ['club' => app()->request->route('club')]) }}">APP users</a>
									</li>
									<li>
										<a class="{{ active('backend.staff.*') }}"
										   href="{{ route('backend.staff.club.index', ['club' => app()->request->route('club')]) }}">Staff APP users</a>
									</li>
								</ul>
							</li>
							@endif
							@if(Auth::user()->hasRole('superadmin') || \Auth::user()->can('manage.app.settings.own'))
							<li>
								<a class="{{ active('backend.clubappsetting.edit') }}"
								   href="{{ route('backend.clubappsetting.edit', ['club' => app()->request->route('club')]) }}"><i
										class="far fa-cogs"></i><span class="sidebar-mini-hide">App settings</span></a>
							</li>
							@endif
							@if(Auth::user()->hasRole('superadmin') || \Auth::user()->can('manage.stadium.own'))
							<li class="{{ active(['backend.stadiumgeneralsettings.*',
                            'backend.stadiumgeneralsettings.edit', 'backend.pricingbands.*',
                            'backend.hospitalitysuite.*', 'backend.stadiumblocks.*', 'backend.stadiumentrance.*'], 'open') }}">

								<a class="nav-submenu" data-toggle="nav-submenu" href="#"><i class="far fa-pennant"></i><span
										class="sidebar-mini-hide">Stadium</span></a>
								<ul>
									<li>
										<a class="{{ active('backend.stadiumgeneralsettings.edit') }}"
										   href="{{ route('backend.stadiumgeneralsettings.edit',['club' => app()->request->route('club')]) }}">General
											settings</a>
									</li>
									@if(isset($clubDetail->stadium->is_using_allocated_seating) && $clubDetail->stadium->is_using_allocated_seating > 0 )
									<li>
										<a class="{{ active('backend.stadiumblocks.*') }}"
										   href="{{ route('backend.stadiumblocks.index',['club' => app()->request->route('club')]) }}">Blocks</a>
									</li>
									@endif
									<li>
										<a class="{{ active('backend.stadiumentrance.*') }}"
										   href="{{ route('backend.stadiumentrance.edit', ['club' => app()->request->route('club')]) }}">Entrances</a>
									</li>
									<li>
										<a class="{{ active('backend.pricingbands.*') }}"
										   href="{{ route('backend.pricingbands.index',['club' => app()->request->route('club')]) }}">Pricing
											bands</a>
									</li>
									<li>
										<a class="{{ active('backend.hospitalitysuite.*') }}"
										   href="{{ route('backend.hospitalitysuite.index', ['club' => app()->request->route('club')]) }}">Hospitality and suites</a>
									</li>
								</ul>
							</li>
							@endif
							@if(Auth::user()->hasRole('superadmin') || \Auth::user()->can('manage.feeds.own'))
							<li class="{{ active(['backend.contentfeed.*', 'backend.feeditem.*'], 'open') }}">
								<a class="nav-submenu" data-toggle="nav-submenu" href="#"><i
										class="far fa-rss"></i><span class="sidebar-mini-hide">Feeds</span></a>
								<ul>
									<li>
										<a class="{{ active('backend.contentfeed.*') }}"
										   href="{{ route('backend.contentfeed.index', ['club' => app()->request->route('club')]) }}">Configure
											feeds</a>
									</li>
									<li>
										<a class="{{ active('backend.feeditem.*') }}"
										   href="{{ route('backend.feeditem.index', ['club' => app()->request->route('club')]) }}">Feed
											items</a>
									</li>
								</ul>
							</li>
							@endif
							@if(Auth::user()->hasRole('superadmin') || \Auth::user()->can('manage.news.own'))
							<li>
								<a class="{{ active('backend.news.*') }}"
								   href="{{ route('backend.news.index', ['club' => app()->request->route('club')]) }}"><i
										class="far fa-newspaper"></i><span class="sidebar-mini-hide">News</span></a>
							</li>
							@endif
							@if(Auth::user()->hasRole('superadmin') || \Auth::user()->can('manage.ctas.own'))
							<li>
								<a class="{{ active('backend.cta.*') }}"
								   href="{{ route('backend.cta.index', ['club' => app()->request->route('club')]) }}"><i
										class="far fa-hand-pointer"></i><span class="sidebar-mini-hide">CTAs</span></a>
							</li>
							@endif
							@if(Auth::user()->hasRole('superadmin') || \Auth::user()->can('manage.polls.own'))
							<li>
								<a class="{{ active('backend.poll.*') }}"
								   href="{{ route('backend.poll.index', ['club' => app()->request->route('club')]) }}"><i
										class="far fa-poll-people"></i><span class="sidebar-mini-hide">Polls</span></a>
							</li>
							@endif
							@if(Auth::user()->hasRole('superadmin') || \Auth::user()->can('manage.pushnotifications.own'))
							<li>
								<a class="{{ active('backend.pushnotification.*') }}" href="{{ route('backend.pushnotification.index', ['club' => app()->request->route('club')]) }}"><i class="far fa-bell-on"></i><span class="sidebar-mini-hide">Push notifications</span></a>
							</li>
							@endif
							@if(Auth::user()->hasRole('superadmin') || \Auth::user()->can('manage.travelinformation.own'))
							<li class="{{ active(['backend.travelwarnings.*', 'backend.travelinformationpages.*', 'backend.traveloffers.*'], 'open') }}">
								<a class="nav-submenu" data-toggle="nav-submenu" href="#"><i
										class="far fa-route"></i><span
										class="sidebar-mini-hide">Travel information</span></a>
								<ul>
									<li>
										<a class="{{ active('backend.travelwarnings.*') }}"
										   href="{{ route('backend.travelwarnings.index', ['club' => app()->request->route('club')]) }}">Travel
											warnings</a>
									</li>
									<li>
										<a class="{{ active('backend.travelinformationpages.*') }}"
										   href="{{ route('backend.travelinformationpages.index', ['club' => app()->request->route('club')]) }}">Travel
											information</a>
									</li>
									<li>
										<a class="{{ active('backend.traveloffers.*') }}"
										   href="{{ route('backend.traveloffers.index', ['club' => app()->request->route('club')]) }}">Travel
											offers</a>
									</li>
								</ul>
							</li>
							@endif
							@if(Auth::user()->hasRole('superadmin') || \Auth::user()->can('manage.membershippackages.own'))
							<li>
								<a class="{{ active('backend.membershippackages.*') }}"
								   href="{{ route('backend.membershippackages.index', ['club' => app()->request->route('club')]) }}"><i
										class="far fa-box-full"></i><span
										class="sidebar-mini-hide">Membership packages</span></a>
							</li>
							@endif
							@if(Auth::user()->hasRole('superadmin') || \Auth::user()->can('manage.events.own'))
							<li>
								<a class="{{ active('backend.event.*') }}"
								   href="{{ route('backend.event.index', ['club' => app()->request->route('club')]) }}"><i
										class="far fa-calendar-star"></i><span
										class="sidebar-mini-hide">Events</span></a>
							</li>
							@endif
							@if(Auth::user()->hasRole('superadmin') || \Auth::user()->can('manage.matches.own'))
							<li>
								<a class="{{ active('backend.matches.*') }}"
								   href="{{ route('backend.matches.index', ['club' => app()->request->route('club')]) }}"><i
										class="far fa-whistle"></i><span class="sidebar-mini-hide">Matches</span></a>
							</li>
							@endif
							@if(Auth::user()->hasRole('superadmin') || \Auth::user()->can('manage.commerce.own'))
							<li class="{{ active(['backend.category.*', 'backend.specialoffer.*' ,'backend.product.*', 'backend.collectionpoint.*', 'backend.loyaltyreward.*'], 'open') }}">
								<a class="nav-submenu" data-toggle="nav-submenu" href="#"><i
										class="far fa-cash-register"></i><span class="sidebar-mini-hide">Commerce</span></a>
								<ul>
									<li>
										<a class="{{ active('backend.collectionpoint.*') }}"
										   href="{{ route('backend.collectionpoint.index', ['club' => app()->request->route('club')]) }}">Collection
											points</a>
									</li>
									<li>
										<a class="{{ active('backend.category.*') }}"
										   href="{{ route('backend.category.index', ['club' => app()->request->route('club')]) }}">Categories</a>

									</li>
									<li>
										<a class="{{ active('backend.product.*') }}"
										   href="{{ route('backend.product.index', ['club' => app()->request->route('club')]) }}">Products</a>
									</li>
									<li>
										<a class="{{ active('backend.specialoffer.*') }}"
										   href="{{ route('backend.specialoffer.index',['club' => app()->request->route('club')]) }}">Special
											offers</a>
									</li>
									<li>
										<a class="{{ active('backend.loyaltyreward.*') }}"
										   href="{{ route('backend.loyaltyreward.index', ['club' => app()->request->route('club')]) }}">Loyalty
											rewards</a>
									</li>
								</ul>
							</li>
							@endif
							@if(Auth::user()->hasRole('superadmin') || \Auth::user()->can('access.clubadmin.transactions.own'))
							<li class="{{ active(['backend.transaction.*'], 'open') }}">
								<a class="nav-submenu" data-toggle="nav-submenu" href="#"><i
										class="fal fa-credit-card"></i><span class="sidebar-mini-hide">Transactions</span></a>
								<ul>
									<li>
										<a class="{{ active('backend.transaction.*') }}"
										   href="{{ route('backend.transaction.index', ['club' => app()->request->route('club')]) }}">{{ $clubDetail->currency }} transactions</a>
									</li>
								</ul>
							</li>
							@endif
							@if(Auth::user()->hasRole('superadmin') || \Auth::user()->can('manage.clubinformation.own'))
							<li>
								<a class="{{ active('backend.clubinformationpages.*') }}" href="{{ route('backend.clubinformationpages.index', ['club' => app()->request->route('club')]) }}"><i
										class="far fa-club"></i><span class="sidebar-mini-hide">My club</span></a>
							</li>
							@endif
							@if(Auth::user()->hasRole('superadmin') || \Auth::user()->can('manage.quizzes.own'))
							<li>
								<a class="{{ active('backend.quizzes.*') }}"
								href="{{ route('backend.quizzes.index', ['club' => app()->request->route('club')]) }}"><i class="far fa-ballot-check"></i><span
										class="sidebar-mini-hide">Quizzes</span></a>
							</li>
							@endif
							@if(Auth::user()->hasRole('superadmin') || \Auth::user()->can('manage.videos.own'))
							<li>
								<a class="{{ active('backend.video.*') }}"
								href="{{ route('backend.video.index', ['club' => app()->request->route('club')]) }}"><i
										class="far fa-video"></i><span class="sidebar-mini-hide">Videos</span></a>
							</li>
							@endif
						@endif
				</ul>
			</div>
			<!-- END Side Navigation -->
		</div>
		<!-- Sidebar Content -->
	</div>
	<!-- END Sidebar Scroll Container -->
</nav>
