<aside id="sidebar" class="sidebar">
    <ul class="sidebar-nav" id="sidebar-nav">
        <li class="nav-item"><a class="nav-link <?php echo e(request()->routeIs('dashboard') ? 'active' : ''); ?>" href="<?php echo e(route('dashboard')); ?>"><i class="bi bi-grid"></i>Dashboard</a></li>
        <li class="nav-item"><a class="nav-link <?php echo e(request()->is('profiles') ? 'active' : ''); ?>" href="<?php echo e(url('profiles')); ?>"><i class="bi bi-person-fill"></i>Profile</a></li>
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('role_permissions')): ?>
            <li class="nav-item"><a class="nav-link <?php echo e(request()->routeIs('role') ? 'active' : ''); ?>" href="<?php echo e(route('role')); ?>"><i class="bi bi-grid"></i>Role & Permission</a></li>
        <?php endif; ?>
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('expenses')): ?>
            <li class="nav-item"><a class="nav-link <?php echo e(request()->is('expenses') ? 'active' : ''); ?>" href="<?php echo e(url('expenses')); ?>"><i class="bi bi-grid"></i>Expenses</a></li>
        <?php endif; ?>
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('departments')): ?>
            <li class="nav-item"><a class="nav-link <?php echo e(request()->routeIs('departments') ? 'active' : ''); ?>" href="<?php echo e(route('departments')); ?>"><i class="bi bi-grid"></i>Departments</a></li>
        <?php endif; ?>
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('candidates')): ?>
            <li class="nav-item"><a class="nav-link <?php echo e(request()->routeIs('candidates.index') ? 'active' : ''); ?>" href="<?php echo e(route('candidates.index')); ?>"><i class="bi bi-grid"></i>Candidates</a></li>
        <?php endif; ?>
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('campaigns')): ?>
            <li class="nav-item"><a class="nav-link <?php echo e(request()->routeIs('campaigns.index') ? 'active' : ''); ?>" href="<?php echo e(route('campaigns.index')); ?>"><i class="bi bi-grid"></i>Campaigns</a></li>
        <?php endif; ?>
        <li class="nav-item"><a class="nav-link <?php echo e(request()->routeIs('leave.create') ? 'active' : ''); ?>" href="<?php echo e(route('leave.create')); ?>"><i class="bi bi-escape"></i>Leave's</a></li>
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('attendance')): ?>
            <li class="nav-item"><a class="nav-link <?php echo e(request()->routeIs('employee.late.report') ? 'active' : ''); ?>" href="<?php echo e(route('employee.late.report')); ?>"><i class="bi bi-calendar-check-fill"></i>Team Attendance</a></li>
        <?php else: ?>
           <li class="nav-item"><a class="nav-link <?php echo e(request()->routeIs('employee.user.late.report') ? 'active' : ''); ?>" href="<?php echo e(route('employee.user.late.report',['id' =>Auth::user()->id ])); ?>"><i class="bi bi-calendar-check-fill"></i>Attendance</a></li>
        <?php endif; ?>
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('reports')): ?>
            <li class="nav-item"><a class="nav-link <?php echo e(request()->is('task/taskReport') ? 'active' : ''); ?>" href="<?php echo e(url('task/taskReport')); ?>"><i class="bi bi-grid"></i>Reports</a></li>
        <?php endif; ?> 
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('crm')): ?>
            <li class="nav-item">
                <a class="nav-link <?php echo e(request()->routeIs('crm.*') || request()->routeIs('user.bde.report') ? '' : 'collapsed'); ?>" data-bs-target="#components-nav4" data-bs-toggle="collapse" href="#">
                    <i class="bi bi-menu-button-wide-fill"></i><span>CRM </span><i class="bi bi-chevron-down ms-auto"></i>
                </a>
                <ul id="components-nav4" class="nav-content collapse <?php echo e(request()->routeIs('crm.*') || request()->routeIs('user.bde.report') ? 'show' : ''); ?>">
                    <li><a href="<?php echo e(route('crm.create')); ?>" class="<?php echo e(request()->routeIs('crm.create') ? 'active' : ''); ?>"><i class="bi bi-circle"></i>Add Leads</a></li>
                    <li><a href="<?php echo e(route('crm.index')); ?>" class="<?php echo e(request()->routeIs('crm.index') ? 'active' : ''); ?>"><i class="bi bi-circle"></i>All Leads</a></li>
                    <li><a href="<?php echo e(route('crm.api')); ?>" class="<?php echo e(request()->routeIs('crm.api') ? 'active' : ''); ?>"><i class="bi bi-circle"></i>Api</a></li>
                </ul>
            </li>
        <?php endif; ?>
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('my_client')): ?>
            <li class="nav-item"><a class="nav-link <?php echo e(request()->routeIs('crm.my_client') ? 'active' : ''); ?>" href="<?php echo e(route('crm.my_client')); ?>"><i class="bi bi-people-fill"></i><span>My Clients</span></a></li>
        <?php endif; ?>
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('users')): ?>
            <li class="nav-item">
                <a class="nav-link <?php echo e(request()->is('users*') || request()->is('user/client/index') ? '' : 'collapsed'); ?>" data-bs-target="#components-nav25" data-bs-toggle="collapse" href="#">
                    <i class="bi bi-menu-button-wide"></i><span>All Users</span><i class="bi bi-chevron-down ms-auto"></i>
                </a>
                <ul id="components-nav25" class="nav-content collapse <?php echo e(request()->is('users*') || request()->is('user/client/index') ? 'show' : ''); ?>">
                    <li><a href="<?php echo e(route('users.index')); ?>" class="<?php echo e(request()->routeIs('users.index') ? 'active' : ''); ?>"><i class="bi bi-circle"></i>Employees</a></li>
                    <li><a href="<?php echo e(url('/user/client/index')); ?>" class="<?php echo e(request()->is('user/client/index') ? 'active' : ''); ?>"><i class="bi bi-circle"></i>Clients</a></li>
                </ul>
            </li>
        <?php endif; ?>
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage_project')): ?>
            <li class="nav-item">
                <a class="nav-link <?php echo e(request()->routeIs('project.index') || request()->routeIs('category.index') || request()->routeIs('project.category.index') ? '' : 'collapsed'); ?>" data-bs-target="#components-nav2" data-bs-toggle="collapse" href="#">
                    <i class="bi bi-menu-button-wide"></i><span>Manage Projects</span><i class="bi bi-chevron-down ms-auto"></i>
                </a>
                <ul id="components-nav2" class="nav-content collapse <?php echo e(request()->routeIs('project.index') || request()->routeIs('category.index') || request()->routeIs('project.category.index') ? 'show' : ''); ?>">
                    <li><a href="<?php echo e(route('project.index')); ?>" class="<?php echo e(request()->routeIs('project.index') ? 'active' : ''); ?>"><i class="bi bi-circle"></i>All Projects</a></li>
                    <li><a href="<?php echo e(route('category.index')); ?>" class="<?php echo e(request()->routeIs('category.index') ? 'active' : ''); ?>"><i class="bi bi-circle"></i>Client Category</a></li>
                    <li><a href="<?php echo e(route('project.category.index')); ?>" class="<?php echo e(request()->routeIs('project.category.index') ? 'active' : ''); ?>"><i class="bi bi-circle"></i>Service</a></li>
                </ul>
            </li>
        <?php endif; ?>
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('client_category')): ?>
        <li class="nav-item"><a class="nav-link <?php echo e(request()->routeIs('category.index') ? 'active' : ''); ?>" href="<?php echo e(route('category.index')); ?>"><i class="bi bi-people-fill"></i><span>Client Category</span></a></li>
        <?php endif; ?>
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage_billing')): ?>
            <li class="nav-item">
                <a class="nav-link <?php echo e(request()->routeIs('invoice.index') ? '' : 'collapsed'); ?>" data-bs-target="#components-nav21" data-bs-toggle="collapse" href="#">
                    <i class="bi bi-menu-button-wide"></i><span>Manage Billing</span><i class="bi bi-chevron-down ms-auto"></i>
                </a>
                <ul id="components-nav21" class="nav-content collapse <?php echo e(request()->routeIs('invoice.index') ? 'show' : ''); ?>">
                    <li><a href="<?php echo e(route('invoice.index')); ?>" class="<?php echo e(request()->routeIs('invoice.index') ? 'active' : ''); ?>"><i class="bi bi-circle"></i>Invoice</a></li>
                </ul>
            </li>
        <?php endif; ?>
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('settings')): ?>
            <li class="nav-item">
                <a class="nav-link <?php echo e(request()->is('banks') || request()->routeIs('templet.index') || request()->is('office') ? '' : 'collapsed'); ?>" data-bs-target="#components-nav22" data-bs-toggle="collapse" href="#">
                    <i class="bi bi-menu-button-wide"></i><span>Settings</span><i class="bi bi-chevron-down ms-auto"></i>
                </a>
                <ul id="components-nav22" class="nav-content collapse <?php echo e(request()->is('banks') || request()->routeIs('templet.index') || request()->is('office') ? 'show' : ''); ?>">
                    <li><a href="<?php echo e(url('/banks')); ?>" class="<?php echo e(request()->is('banks') ? 'active' : ''); ?>"><i class="bi bi-circle"></i>Bank Details</a></li>
                    <li><a href="<?php echo e(route('templet.index')); ?>" class="<?php echo e(request()->routeIs('templet.index') ? 'active' : ''); ?>"><i class="bi bi-circle"></i>Templates</a></li>
                    <li><a href="<?php echo e(url('office')); ?>" class="<?php echo e(request()->is('office') ? 'active' : ''); ?>"><i class="bi bi-circle"></i>Office</a></li>
                    <li><a href="<?php echo e(route('api-settings.index')); ?>" class="<?php echo e(request()->routeIs('api-settings.index') ? 'active' : ''); ?>"><i class="bi bi-circle"></i>Apis </a></li>
                </ul>
            </li>
        <?php endif; ?>

        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('my_project')): ?>
        <li class="nav-item"><a class="nav-link <?php echo e(request()->routeIs('project.index') ? 'active' : ''); ?>" href="<?php echo e(route('project.index')); ?>"><i class="bi bi-grid"></i>My Projects</a></li>
        <?php endif; ?>
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('my_task')): ?>
        <li class="nav-item"><a class="nav-link <?php echo e(request()->is('user/project/tasks') ? 'active' : ''); ?>" href="<?php echo e(url('/user/project/tasks')); ?>"><i class="bi bi-grid"></i>My Task</a></li>
        <?php endif; ?>
    
    </ul>
</aside>
<?php /**PATH /home/bookmziw/lara_tms/laravel/resources/views/components/admin/sidebar.blade.php ENDPATH**/ ?>