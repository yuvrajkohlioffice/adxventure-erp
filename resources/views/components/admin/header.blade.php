<header id=header class="header fixed-top">
    <div class="row align-items-center">
        <div class="col-2">
            <a href="{{ url('/dashboard') }}" class="logo d-flex align-items-center">
                <img src="{{ asset('logo.png') }}" alt="logo">
            </a>
        </div>
        <div class="col-2">
            <div class=search-bar>
                <h6 style=font-weight:600;font-size:small;>
                   <i class="bi bi-calendar-check-fill"></i> {{ date('d/m/Y') }} 
                    <spna id=live-time></spna>
                </h6>
            </div>
        </div>
        <div class="col-5 d-flex gap-5">
            @if(auth()->check()) 
            @php $userId = auth()->user()->id; $times = \App\Helpers\LogHelper::getLoginLogoutTimes($userId); @endphp
            <div class=search-bar>
                <h6 style=font-weight:600;font-size:small;><i class="bi bi-alarm"></i> Login Time: {{  $times['login_time'] ?? 'Not Available' }}</h6>
            </div>
            <div class=search-bar>
                <h6 style=font-weight:600;font-size:small;><i class="bi bi-alarm-fill"></i> Logout Time: {{ $times['logout_time'] ?? 'Not Available' }}</h6>
            </div>
            @endif
        </div>
        <div class="col-1"></div>
        <div class="col-2">
            <nav class="header-nav ms-auto">
                <ul class="d-flex align-items-center justify-content-end">
                    <li class="nav-item d-block d-lg-none">
                        <a class="nav-link nav-icon search-bar-toggle" href=#>
                        <i class="bi bi-search"></i>
                        </a>
                    </li>
                    @if(false)
                    <li class="nav-item dropdown">
                        <a class="nav-link nav-icon" href=# data-bs-toggle=dropdown>
                        <i class="bi bi-bell"></i>
                        <span class="badge bg-primary badge-number">4</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow notifications">
                        <li class=dropdown-header>
                            You have 4 new notifications
                            <a href=#><span class="badge rounded-pill bg-primary p-2 ms-2">View all</span></a>
                        </li>
                        <li>
                            <hr class=dropdown-divider>
                        </li>
                        <li class=notification-item>
                            <i class="bi bi-exclamation-circle text-warning"></i>
                            <div>
                                <h4>Lorem Ipsum</h4>
                                <p>Quae dolorem earum veritatis oditseno</p>
                                <p>30 min. ago</p>
                            </div>
                        </li>
                        <li>
                            <hr class=dropdown-divider>
                        </li>
                        <li class=notification-item>
                            <i class="bi bi-x-circle text-danger"></i>
                            <div>
                                <h4>Atque rerum nesciunt</h4>
                                <p>Quae dolorem earum veritatis oditseno</p>
                                <p>1 hr. ago</p>
                            </div>
                        </li>
                        <li>
                            <hr class=dropdown-divider>
                        </li>
                        <li class=notification-item>
                            <i class="bi bi-check-circle text-success"></i>
                            <div>
                                <h4>Sit rerum fuga</h4>
                                <p>Quae dolorem earum veritatis oditseno</p>
                                <p>2 hrs. ago</p>
                            </div>
                        </li>
                        <li>
                            <hr class=dropdown-divider>
                        </li>
                        <li class=notification-item>
                            <i class="bi bi-info-circle text-primary"></i>
                            <div>
                                <h4>Dicta reprehenderit</h4>
                                <p>Quae dolorem earum veritatis oditseno</p>
                                <p>4 hrs. ago</p>
                            </div>
                        </li>
                        <li>
                            <hr class=dropdown-divider>
                        </li>
                        <li class=dropdown-footer>
                            <a href=#>Show all notifications</a>
                        </li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link nav-icon" href=# data-bs-toggle=dropdown>
                        <i class="bi bi-chat-left-text"></i>
                        <span class="badge bg-success badge-number">3</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow messages">
                        <li class=dropdown-header>
                            You have 3 new messages
                            <a href=#><span class="badge rounded-pill bg-primary p-2 ms-2">View all</span></a>
                        </li>
                        <li>
                            <hr class=dropdown-divider>
                        </li>
                        <li class=message-item>
                            <a href=#>
                                <img src=assets/img/messages-1.jpg alt="" class=rounded-circle>
                                <div>
                                    <h4>Maria Hudson</h4>
                                    <p>Velit asperiores et ducimus soluta repudiandae labore officia est ut...</p>
                                    <p>4 hrs. ago</p>
                                </div>
                            </a>
                        </li>
                        <li>
                            <hr class=dropdown-divider>
                        </li>
                        <li class=message-item>
                            <a href=#>
                                <img src=assets/img/messages-2.jpg alt="" class=rounded-circle>
                                <div>
                                    <h4>Anna Nelson</h4>
                                    <p>Velit asperiores et ducimus soluta repudiandae labore officia est ut...</p>
                                    <p>6 hrs. ago</p>
                                </div>
                            </a>
                        </li>
                        <li>
                            <hr class=dropdown-divider>
                        </li>
                        <li class=message-item>
                            <a href=#>
                                <img src=assets/img/messages-3.jpg alt="" class=rounded-circle>
                                <div>
                                    <h4>David Muldon</h4>
                                    <p>Velit asperiores et ducimus soluta repudiandae labore officia est ut...</p>
                                    <p>8 hrs. ago</p>
                                </div>
                            </a>
                        </li>
                        <li>
                            <hr class=dropdown-divider>
                        </li>
                        <li class=dropdown-footer>
                            <a href=#>Show all messages</a>
                        </li>
                        </ul>
                    </li>
                    @endif
                    <li class="dropdown notification-list topbar-dropdown ">
                        <a class="nav-link dropdown-toggle nav-user me-0" data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
                            @if(!auth()->user()->image)
                                <img src="{{ asset('/user1.png') }}" alt="user-image" class="rounded-circle" style="height: 32px;width: 32px;">
                            @else
                                <img src="{{asset(auth()->user()->image)}}" alt="user-image" class="rounded-circle" style="height: 32px;width: 32px;">
                            @endif
                            <span style=font-weight:600;font-size:small;>{{ auth()->user()->name }} ({{ auth()->user()->roles()->first()->name }}) <i class="mdi mdi-chevron-down"></i></span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end profile-dropdown p-2">
                            <!-- item-->
                            <div class="dropdown-header noti-title">
                                <h6 class="text-overflow m-0">Welcome !</h6>
                            </div>

                            <!-- item-->
                            <a class="dropdown-item notify-item" href="{{ url('profile')}}">
                                   <i class="bi bi-person-circle"></i>
                                    <span>My Profile</span>
                                </a>

                            <div class="dropdown-divider"></div>
                            <!-- item-->
                            <form method="POST" action="{{ route('logout') }}" id="logout-form">
                                @csrf
                                <input type="hidden" name="type" value="0" id="logout-type">
                                <button class="btn btn-danger w-100 mt-2" type="button" id="logout-button"><i class="bi bi-box-arrow-left"></i> Log out</button>
                            </form>
                        </div>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
    {{-- <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">

            <!-- Brand -->
            <a class="navbar-brand" href="{{ route('dashboard') }}">
                <i class="bi bi-grid"></i> Dashboard
            </a>

            <!-- Mobile Toggle -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#topbarNav" aria-controls="topbarNav"
                    aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Menu -->
            <div class="collapse navbar-collapse" id="topbarNav">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0">

                    <!-- Profile -->
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('profiles') ? 'active' : '' }}"
                        href="{{ url('profiles') }}">
                            <i class="bi bi-person-fill"></i> Profile
                        </a>
                    </li>

                    @can('role_permissions')
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('role') ? 'active' : '' }}"
                        href="{{ route('role') }}">
                            Role & Permission
                        </a>
                    </li>
                    @endcan

                    @can('expenses')
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('expenses') ? 'active' : '' }}"
                        href="{{ url('expenses') }}">
                            Expenses
                        </a>
                    </li>
                    @endcan

                    <!-- CRM Dropdown -->
                    @can('crm')
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle {{ request()->routeIs('crm.*') ? 'active' : '' }}"
                        href="#" role="button" data-bs-toggle="dropdown">
                            CRM
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                                <a class="dropdown-item {{ request()->routeIs('crm.create') ? 'active' : '' }}"
                                href="{{ route('crm.create') }}">Add Leads</a>
                            </li>
                            <li>
                                <a class="dropdown-item {{ request()->routeIs('crm.index') ? 'active' : '' }}"
                                href="{{ route('crm.index') }}">All Leads</a>
                            </li>
                            <li>
                                <a class="dropdown-item {{ request()->routeIs('crm.api') ? 'active' : '' }}"
                                href="{{ route('crm.api') }}">API</a>
                            </li>
                        </ul>
                    </li>
                    @endcan

                    <!-- Attendance -->
                    @can('attendance')
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('employee.late.report') ? 'active' : '' }}"
                        href="{{ route('employee.late.report') }}">
                            Team Attendance
                        </a>
                    </li>
                    @else
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('employee.user.late.report') ? 'active' : '' }}"
                        href="{{ route('employee.user.late.report',['id'=>Auth::id()]) }}">
                            Attendance
                        </a>
                    </li>
                    @endcan

                    <!-- User Menu -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                            <i class="bi bi-person-circle"></i> {{ Auth::user()->name }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                                <a class="dropdown-item" href="{{ route('logout') }}"
                                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    Logout
                                </a>
                            </li>
                        </ul>
                    </li>

                </ul>
            </div>
        </div>
    </nav> --}}

{{-- <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
    @csrf
</form> --}}
</header>

{{-- <style>
    /* Icon top, text bottom */
.nav-icon-text {
    display: flex;
    flex-direction: column;
    align-items: center;
    font-size: 12px;
    padding: 8px 14px;
}

.nav-icon-text i {
    font-size: 20px;
    margin-bottom: 4px;
}

/* Active link */
.navbar-dark .nav-link.active {
    color: #0d6efd;
}

/* Hover effect */
.nav-icon-text:hover {
    background: rgba(255,255,255,0.08);
    border-radius: 6px;
}

/* Desktop spacing */
@media (min-width: 992px) {
    .navbar-nav .nav-item {
        margin: 0 6px;
    }
}

</style> --}}
 {{-- <header id="header" class="header fixed-top bg-white shadow-sm">

    <!-- ===== TOP INFO BAR ===== -->
    <div class="container-fluid py-2">
        <div class="row align-items-center">

            <!-- Logo -->
            <div class="col-2">
                <a href="{{ url('/dashboard') }}" class="logo d-flex align-items-center">
                    <img src="{{ asset('logo.png') }}" alt="logo" height="40">
                </a>
            </div>

            <!-- Date & Time -->
            <div class="col-3">
                <h6 class="mb-0 fw-semibold small">
                    <i class="bi bi-calendar-check-fill"></i>
                    {{ date('d/m/Y') }}
                    <span id="live-time"></span>
                </h6>
            </div>

            <!-- Login / Logout Time -->
            <div class="col-5 d-flex gap-4">
                @if(auth()->check())
                    @php
                        $userId = auth()->user()->id;
                        $times = \App\Helpers\LogHelper::getLoginLogoutTimes($userId);
                    @endphp
                    <h6 class="mb-0 fw-semibold small">
                        <i class="bi bi-alarm"></i>
                        Login: {{ $times['login_time'] ?? 'N/A' }}
                    </h6>
                    <h6 class="mb-0 fw-semibold small">
                        <i class="bi bi-alarm-fill"></i>
                        Logout: {{ $times['logout_time'] ?? 'N/A' }}
                    </h6>
                @endif
            </div>

            <!-- User Profile -->
            <div class="col-2 text-end">
                <div class="dropdown">
                    <a class="d-flex align-items-center gap-2 text-decoration-none dropdown-toggle"
                       data-bs-toggle="dropdown" href="#">
                        <img src="{{ auth()->user()->image ? asset(auth()->user()->image) : asset('user1.png') }}"
                             class="rounded-circle" width="32" height="32">
                        <span class="fw-semibold small">
                            {{ auth()->user()->name }}
                        </span>
                    </a>

                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <a class="dropdown-item" href="{{ url('profile') }}">
                                <i class="bi bi-person-circle"></i> My Profile
                            </a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button class="dropdown-item text-danger">
                                    <i class="bi bi-box-arrow-left"></i> Logout
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>

        </div>
    </div>

    <!-- ===== MAIN NAVBAR ===== -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">

            <!-- Mobile Toggle -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#topbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Menu -->
            <div class="collapse navbar-collapse justify-content-center" id="topbarNav">
                <ul class="navbar-nav">

                    <!-- Dashboard -->
                    <li class="nav-item text-center">
                        <a class="nav-link nav-icon-text {{ request()->routeIs('dashboard') ? 'active' : '' }}"
                           href="{{ route('dashboard') }}">
                            <i class="bi bi-grid"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>

                    <!-- Profile -->
                    <li class="nav-item text-center">
                        <a class="nav-link nav-icon-text {{ request()->is('profiles') ? 'active' : '' }}"
                           href="{{ url('profiles') }}">
                            <i class="bi bi-person-fill"></i>
                            <span>Profile</span>
                        </a>
                    </li>

                    @can('expenses')
                    <li class="nav-item text-center">
                        <a class="nav-link nav-icon-text {{ request()->is('expenses') ? 'active' : '' }}"
                           href="{{ url('expenses') }}">
                            <i class="bi bi-currency-rupee"></i>
                            <span>Expenses</span>
                        </a>
                    </li>
                    @endcan

                    @can('attendance')
                    <li class="nav-item text-center">
                        <a class="nav-link nav-icon-text {{ request()->routeIs('employee.late.report') ? 'active' : '' }}"
                           href="{{ route('employee.late.report') }}">
                            <i class="bi bi-calendar-check-fill"></i>
                            <span>Attendance</span>
                        </a>
                    </li>
                    @endcan

                    <!-- CRM -->
                    @can('crm')
                    <li class="nav-item dropdown text-center">
                        <a class="nav-link dropdown-toggle nav-icon-text {{ request()->routeIs('crm.*') ? 'active' : '' }}"
                           href="#" data-bs-toggle="dropdown">
                            <i class="bi bi-menu-button-wide-fill"></i>
                            <span>CRM</span>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="{{ route('crm.create') }}">Add Leads</a></li>
                            <li><a class="dropdown-item" href="{{ route('crm.index') }}">All Leads</a></li>
                            <li><a class="dropdown-item" href="{{ route('crm.api') }}">API</a></li>
                        </ul>
                    </li>
                    @endcan

                </ul>
            </div>
        </div>
    </nav> 

</header> --}}





<!-- Modal HTML -->
<div class="modal fade" id="lateModal" tabindex="-1" aria-labelledby="lateModalLabel" aria-hidden="true">
   <div class="modal-dialog modal-lg">
      <div class="modal-content">
         <div class="modal-header">
              <h5 class="modal-title" id="lateModalLabel">Good Morning 👋🏼,<b>{{ucfirst(auth()->user()->name)}}</b></h5>
         </div>
         <div class="modal-body">
            <div class="row align-items-center">
                <div class="col-6">
                    <div>
                        <img src="{{asset('late-image.jpg')}}" alt="late-image" width="100%" style="border-radius: 8px;">
                    </div>
                </div>
                <div class="col-6">
                   <h3 class="text-center my-2 fs-2" style="color:#FE6600;"> 
                        <span style="font-size:80px;">🕔</span><br>
                        <span style="font-weight:600">Every day is a new chance to do better!</span>
                    </h3>
                    <form id="lateReasonForm">
                        @csrf
                        <div class="mb-3">
                            <label for="reason" class="form-label">Please provide a reason for being late:</label>
                            <textarea class="form-control" id="reason" name="reason" required></textarea>
                            <span class="text-danger" id="reason-error"></span>
                        </div>
                        <button type="submit" class="btn btn-primary w-100" id="late-reason-submit-btn">Submit your valid reason</button>
                    </form>
                </div>
            </div>
        
         </div>
      </div>
   </div>
</div>
