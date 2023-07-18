<div class="navbar-inner">
    <!-- Collapse -->
    <div class="collapse navbar-collapse" id="sidenav-collapse-main">
        <!-- Nav items -->
        <ul class="navbar-nav">
            {{-- <li class="nav-item">
                <a class="nav-link" href="{{ route('home') }}">
                    <i class="ni ni-tv-2 text-primary"></i> {{ __('Dashboard') }}
                </a>
            </li> --}}
            @if (Auth::user()->isAdmin())
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('web_users_index') }}">
                        <i class="ni ni-single-02 text-primary"></i>
                        <span class="nav-link-text">Users list</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('web_get_All_Requests_index') }}">
                        <i class="ni ni-books text-primary"></i>
                        <span class="nav-link-text">Users requests</span>
                    </a>
                </li>
                {{-- <li class="nav-item">
                    <a class="nav-link" href="{{ route('web_create_admin_index') }}">
                        <i class="ni ni-fat-add text-primary"></i>
                        <span class="nav-link-text">Create user</span>
                    </a>
                </li> --}}
            @endif
            <li class="nav-item">
                <a class="nav-link" href="{{ route('logout') }}"
                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="ni ni-circle-08 text-pink"></i> Logout
                </a>
            </li>
        </ul>
    </div>
</div>
