<div id="jsc_nav" role="navigation">
	<div id="jsc_admin_menu_bg" class="shadow-sm"></div>
	<div id="jsc_admin_menu_wrap" class="jsc-sidebar">
		<div class="jsc-site-logo">
			{{-- <img src="{{ asset('images/default.png') }}" alt="default-image"> --}}
				<a class="navbar-brand" href="{{ url('/') }}">
						{{ config('app.name', 'Laravel') }}
				</a>
				<div class="hidden-md hidden-lg" id="jsc_close_nav">
					<i class="fas fa-times"></i>
				</div>
		</div>
		<ul class="nav flex-column">
			<li><a href="{{ route('dashboard') }}" class="nav-link @yield('active_dashboard')"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
			{{-- <li><a href="@#" class="nav-link">Post</a></li> --}}
			<li>
				@php
						$collapse = (request()->is('page/') || request()->is('page/*')) ? 'show' : '';
						$aria_expanded = (request()->is('page/') || request()->is('page/*')) ? 'true' : 'false';
				@endphp
				<a href="@#" class="nav-link" data-toggle="collapse" data-target="#navUserPage" aria-expanded="{{ $aria_expanded }}"><i class="fas fa-copy"></i> Page</a>
				<div class="collapse {{ $collapse }}" id="navUserPage">
					<ul class="nav flex-column py-0 submenu">
						<a href="{{ route('page.page_index') }}" class="nav-link @yield('active_page_list')"><i class="fas fa-copy"></i> All Page</a>
						<a href="{{ route('page.page_create') }}" class="nav-link @yield('active_page_new')"><i class="fas fa-file"></i> Add New</a>
					</ul>
				</div>
			</li>
			
			<li>
				@php
						$collapse = (request()->is('media') || request()->is('media/*')) ? 'show' : '';
						$aria_expanded = (request()->is('media') || request()->is('media/*')) ? 'true' : 'false';
				@endphp
				<a href="@#" class="nav-link" data-toggle="collapse" data-target="#navUsersMedia" aria-expanded="{{ $aria_expanded }}"><i class="fas fa-photo-video"></i> Media</a>
				<div class="collapse {{ $collapse }}" id="navUsersMedia">
					<ul class="nav flex-column py-0 submenu">
						<a href="{{ route('media.index') }}" class="nav-link @yield('active_media')"><i class="fas fa-bars"></i> Library</a>
						<a href="{{ route('media.upload') }}" class="nav-link @yield('active_media_upload')"><i class="fas fa-photo-video"></i> Add New</a>
					</ul>
				</div>
			</li>
			<li>
				@php
						$collapse = (request()->is('menu') || request()->is('menu/*')) ? 'show' : '';
						$aria_expanded = (request()->is('menu') || request()->is('menu/*')) ? 'true' : 'false';
				@endphp
				<a href="@#" class="nav-link" data-toggle="collapse" data-target="#navUsersAppearance" aria-expanded="{{ $aria_expanded }}"><i class="fas fa-palette"></i> Appearance</a>
				<div class="collapse {{ $collapse }}" id="navUsersAppearance">
					<ul class="nav flex-column py-0 submenu">
						<li class="nav-item">
							<a href="{{ route('menu.index') }}" class="nav-link @yield('active_menu_list')"><i class="fas fa-bars"></i> Menu</a>
						</li>
					</ul>
				</div>
			</li>
			<li><a href="{{ route('user.profile-show') }}" class="nav-link @yield('active_user')"><i class="fas fa-user"></i> Users</a></li>
			<li><a href="@#" class="nav-link"><i class="fas fa-sliders-h"></i> General Settings</a></li>
		</ul>
	</div>    
</div>