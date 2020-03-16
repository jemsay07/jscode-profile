<nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm top-nav fixed-top">
    <div class="container-fluid">
        <ul class="nav-header pull-left">
            <li class="hidden-md hidden-lg">
                <button class="navbar-toggler" data-toggle="layout" data-action="sidebar_toggle" type="button">
                    <span class="navbar-toggler-icon"></span>
                </button>
            </li>
            <li class="hidden-xs hidden-sm">
                <button class="btn btn-default" data-toggle="layout" data-action="jsc_sidebar_mini_toggle" type="button"><i class="fa fa-ellipsis-v"></i></button>
            </li>
        </ul>
        {{-- <a class="navbar-brand" href="{{ url('/') }}">
            {{ config('app.name', 'Laravel') }}
        </a> --}}
        <div id="jsc_ddown" class="dropdown">
            <button class="btn btn-secondary dropdown-toggle" type="button" id="jscDropdownBtn" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Admin
            </button>
            <div class="dropdown-menu" aria-labelledby="jscDropdownBtn">
                <div class="jsc-profile-wrap">
                    <div class="jsc-profile-img-wrap">
                        <img src="{{ asset('images/default.png') }}" alt="jsc-default-image" class="jsc-img"> 
                    </div>
                    <p class="jsc-profile-name">Jemson Sayre</p>
                    <p class="jsc-profile-profession">Web Developer</p>
                </div>
                {{-- <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="#">Action</a>
                <a class="dropdown-item" href="#">Another action</a>
                <a class="dropdown-item" href="#">Something else here</a> --}}
                <div class="dropdown-divider"></div>
                <div class="jsc-view-out-wrap d-flex">
                    <div class="col-6 jsc-cols-wrap jsc-view">
                        <a href="{{ route('user.profile-show') }}"><i class="fas fa-eye"></i></a>
                    </div>
                    <div class="col-6 jsc-cols-wrap jsc-out">
                        <a href="{{ route('logout') }}"
                           onclick="event.preventDefault();
                           document.getElementById('logout-form').submit();"><i class="fas fa-sign-out-alt"></i>{{-- {{ __('Logout') }} --}}</a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</nav>

<!--Heading-->
@if (trim($__env->yieldContent('page-heading')))
    <div class="jsc-content bg-wf9 mt-menu">
        <div class="d-flex">
            <div class="col-12 col-md-7">
                <h1 class="page-heading">
                    @yield('page-heading')
                </h1>
            </div>
            {{-- <div class="col-md-3 hidden-xs hidden-sm">Breadcrumb</div> --}}
        </div>
    </div>
@endif