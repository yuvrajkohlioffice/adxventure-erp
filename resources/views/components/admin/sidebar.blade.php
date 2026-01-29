<aside id="sidebar" class="sidebar">
    <ul class="sidebar-nav" id="sidebar-nav">
        <li class="nav-item"><a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}"><i class="bi bi-grid"></i>Dashboard</a></li>
        <li class="nav-item"><a class="nav-link {{ request()->is('profiles') ? 'active' : '' }}" href="{{ url('profiles') }}"><i class="bi bi-person-fill"></i>Profile</a></li>
        @can('role_permissions')
            <li class="nav-item"><a class="nav-link {{ request()->routeIs('role') ? 'active' : '' }}" href="{{ route('role') }}"><i class="bi bi-grid"></i>Role & Permission</a></li>
        @endcan
        @can('expenses')
            <li class="nav-item"><a class="nav-link {{ request()->is('expenses') ? 'active' : '' }}" href="{{ url('expenses') }}"><i class="bi bi-grid"></i>Expenses</a></li>
        @endcan
        @can('departments')
            <li class="nav-item"><a class="nav-link {{ request()->routeIs('departments') ? 'active' : '' }}" href="{{ route('departments') }}"><i class="bi bi-grid"></i>Departments</a></li>
        @endcan
        @can('candidates')
            <li class="nav-item"><a class="nav-link {{ request()->routeIs('candidates.index') ? 'active' : '' }}" href="{{ route('candidates.index') }}"><i class="bi bi-grid"></i>Candidates</a></li>
        @endcan
        @can('campaigns')
            <li class="nav-item"><a class="nav-link {{ request()->routeIs('campaigns.index') ? 'active' : '' }}" href="{{ route('campaigns.index') }}"><i class="bi bi-grid"></i>Campaigns</a></li>
        @endcan
        <li class="nav-item"><a class="nav-link {{ request()->routeIs('leave.create') ? 'active' : '' }}" href="{{ route('leave.create') }}"><i class="bi bi-escape"></i>Leave's</a></li>
        @can('attendance')
            <li class="nav-item"><a class="nav-link {{ request()->routeIs('employee.late.report') ? 'active' : '' }}" href="{{ route('employee.late.report') }}"><i class="bi bi-calendar-check-fill"></i>Team Attendance</a></li>
        @else
           <li class="nav-item"><a class="nav-link {{ request()->routeIs('employee.user.late.report') ? 'active' : '' }}" href="{{ route('employee.user.late.report',['id' =>Auth::user()->id ]) }}"><i class="bi bi-calendar-check-fill"></i>Attendance</a></li>
        @endcan
        @can('reports')
            <li class="nav-item"><a class="nav-link {{ request()->is('task/taskReport') ? 'active' : '' }}" href="{{ url('task/taskReport') }}"><i class="bi bi-grid"></i>Reports</a></li>
        @endcan 
        @can('crm')
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('crm.*') || request()->routeIs('user.bde.report') ? '' : 'collapsed' }}" data-bs-target="#components-nav4" data-bs-toggle="collapse" href="#">
                    <i class="bi bi-menu-button-wide-fill"></i><span>CRM </span><i class="bi bi-chevron-down ms-auto"></i>
                </a>
                <ul id="components-nav4" class="nav-content collapse {{ request()->routeIs('crm.*') || request()->routeIs('user.bde.report') ? 'show' : '' }}">
                    <li><a href="{{ route('crm.create') }}" class="{{ request()->routeIs('crm.create') ? 'active' : '' }}"><i class="bi bi-circle"></i>Add Leads</a></li>
                    <li><a href="{{ route('crm.index') }}" class="{{ request()->routeIs('crm.index') ? 'active' : '' }}"><i class="bi bi-circle"></i>All Leads</a></li>
                    <li><a href="{{ route('crm.api') }}" class="{{ request()->routeIs('crm.api') ? 'active' : '' }}"><i class="bi bi-circle"></i>Api</a></li>
                </ul>
            </li>
        @endcan
        @can('my_client')
            <li class="nav-item"><a class="nav-link {{ request()->routeIs('crm.my_client') ? 'active' : '' }}" href="{{route('crm.my_client')}}"><i class="bi bi-people-fill"></i><span>My Clients</span></a></li>
        @endcan
        @can('users')
            <li class="nav-item">
                <a class="nav-link {{ request()->is('users*') || request()->is('user/client/index') ? '' : 'collapsed' }}" data-bs-target="#components-nav25" data-bs-toggle="collapse" href="#">
                    <i class="bi bi-menu-button-wide"></i><span>All Users</span><i class="bi bi-chevron-down ms-auto"></i>
                </a>
                <ul id="components-nav25" class="nav-content collapse {{ request()->is('users*') || request()->is('user/client/index') ? 'show' : '' }}">
                    <li><a href="{{ route('users.index') }}" class="{{ request()->routeIs('users.index') ? 'active' : '' }}"><i class="bi bi-circle"></i>Employees</a></li>
                    <li><a href="{{ url('/user/client/index') }}" class="{{ request()->is('user/client/index') ? 'active' : '' }}"><i class="bi bi-circle"></i>Clients</a></li>
                </ul>
            </li>
        @endcan
        @can('manage_project')
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('project.index') || request()->routeIs('category.index') || request()->routeIs('project.category.index') ? '' : 'collapsed' }}" data-bs-target="#components-nav2" data-bs-toggle="collapse" href="#">
                    <i class="bi bi-menu-button-wide"></i><span>Manage Projects</span><i class="bi bi-chevron-down ms-auto"></i>
                </a>
                <ul id="components-nav2" class="nav-content collapse {{ request()->routeIs('project.index') || request()->routeIs('category.index') || request()->routeIs('project.category.index') ? 'show' : '' }}">
                    <li><a href="{{ route('project.index') }}" class="{{ request()->routeIs('project.index') ? 'active' : '' }}"><i class="bi bi-circle"></i>All Projects</a></li>
                    <li><a href="{{ route('category.index') }}" class="{{ request()->routeIs('category.index') ? 'active' : '' }}"><i class="bi bi-circle"></i>Client Category</a></li>
                    <li><a href="{{ route('project.category.index') }}" class="{{ request()->routeIs('project.category.index') ? 'active' : '' }}"><i class="bi bi-circle"></i>Service</a></li>
                </ul>
            </li>
        @endcan
        @can('client_category')
        <li class="nav-item"><a class="nav-link {{ request()->routeIs('category.index') ? 'active' : '' }}" href="{{route('category.index')}}"><i class="bi bi-people-fill"></i><span>Client Category</span></a></li>
        @endcan
        @can('manage_billing')
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('invoice.index') ? '' : 'collapsed' }}" data-bs-target="#components-nav21" data-bs-toggle="collapse" href="#">
                    <i class="bi bi-menu-button-wide"></i><span>Manage Billing</span><i class="bi bi-chevron-down ms-auto"></i>
                </a>
                <ul id="components-nav21" class="nav-content collapse {{ request()->routeIs('invoice.index') ? 'show' : '' }}">
                    <li><a href="{{ route('invoice.index') }}" class="{{ request()->routeIs('invoice.index') ? 'active' : '' }}"><i class="bi bi-circle"></i>Invoice</a></li>
                </ul>
            </li>
        @endcan
        @can('settings')
            <li class="nav-item">
                <a class="nav-link {{ request()->is('banks') || request()->routeIs('templet.index') || request()->is('office') ? '' : 'collapsed' }}" data-bs-target="#components-nav22" data-bs-toggle="collapse" href="#">
                    <i class="bi bi-menu-button-wide"></i><span>Settings</span><i class="bi bi-chevron-down ms-auto"></i>
                </a>
                <ul id="components-nav22" class="nav-content collapse {{ request()->is('banks') || request()->routeIs('templet.index') || request()->is('office') ? 'show' : '' }}">
                    <li><a href="{{ url('/banks') }}" class="{{ request()->is('banks') ? 'active' : '' }}"><i class="bi bi-circle"></i>Bank Details</a></li>
                    <li><a href="{{ route('templet.index') }}" class="{{ request()->routeIs('templet.index') ? 'active' : '' }}"><i class="bi bi-circle"></i>Templates</a></li>
                    <li><a href="{{ url('office') }}" class="{{ request()->is('office') ? 'active' : '' }}"><i class="bi bi-circle"></i>Office</a></li>
                    <li><a href="{{ route('api-settings.index') }}" class="{{ request()->routeIs('api-settings.index') ? 'active' : '' }}"><i class="bi bi-circle"></i>Apis </a></li>
                </ul>
            </li>
        @endcan

        @can('my_project')
        <li class="nav-item"><a class="nav-link {{ request()->routeIs('project.index') ? 'active' : '' }}" href="{{ route('project.index') }}"><i class="bi bi-grid"></i>My Projects</a></li>
        @endcan
        @can('my_task')
        <li class="nav-item"><a class="nav-link {{ request()->is('user/project/tasks') ? 'active' : '' }}" href="{{ url('/user/project/tasks') }}"><i class="bi bi-grid"></i>My Task</a></li>
        @endcan
    {{--@if(auth()->user()->roles()->first()->hasPermissionTo('crm'))--}}
    </ul>
</aside>
