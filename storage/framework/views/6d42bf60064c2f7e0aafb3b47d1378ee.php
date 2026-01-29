<?php if (isset($component)) { $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54 = $component; } ?>
<?php $component = App\View\Components\AppLayout::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('app-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(App\View\Components\AppLayout::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    <div class="pagetitle">
        <h1>Task</h1>
        <button class="btn btn-outline-primary" style="float:right" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasRight" aria-controls="offcanvasRight">Weekly Report</button>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?php echo e(url('/')); ?>">Home</a></li>
                <li class="breadcrumb-item active">Reports</li>
            </ol>
        </nav>
    </div>
    <!-- End Page Title -->
    <form action="" method="GET" id="filter-form" >
        <div class="row"style="margin-top:10px;margin-bottom:10px;" >
            <!--<div class="col-md-2">-->
            <!--    <input type="text" class="form-control" name="name" value="<?php echo e(request()->name ?? ''); ?>" placeholder="Employee Name..." />-->
            <!--</div>-->
            <div class="col-md-2">
                <select class="form-control" name="project">
                    <option value="">SELECT  PROJECT</option>
                    <?php if(count($projects) > 0): ?>
                        <?php $__currentLoopData = $projects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pro): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($pro->id); ?>" <?php if(request()->project == $pro->id): ?> selected <?php endif; ?>><?php echo e($pro->name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php endif; ?>
                </select>
            </div>
            <div class="col-md-2">
                  <select class="form-control" name="department">
                    <option value="">SELECT TYPE</option>
                    <?php if(count($departments) > 0): ?>
                        <?php $__currentLoopData = $departments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dep): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($dep->id); ?>" <?php if(request()->department == $dep->id): ?> selected <?php endif; ?>><?php echo e($dep->name); ?> (<?php echo e($dep->user_count ?? '0'); ?>)</option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php endif; ?>
                </select>
            </div>
            <div class="col-md-3">
            <div id="reportrange" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%">
                        <i class="bi bi-funnel-fill"></i> &nbsp;
                        <span></span> <i class="fa fa-caret-down"></i>
                    </div>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary btn-md">Filter</button>
                <a href="#" id="resetButton" class="btn btn-danger btn-md">Reset</a>
            </div>
        </div>
    </form>
    <!--<div class="row"style="margin-bottom:10px;" >-->
    <!--        <div class="col-md-12">-->
    <!--           <a type="submit" class="btn btn btn-success"  href="?status=1"  > Active Users : <?php echo e($data->where('status','1')->count()); ?></a> -->
    <!--           <a type="submit" class="btn btn btn-danger"  href="?status=0"   > InActive Users : <?php echo e($data->where('status','0')->count()); ?></a>-->
    <!--        </div>-->   
    <!--</div>-->
    <div class="row">
        <?php if(count($data) > 0): ?>
            <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="col-md-3" style="margin-top:20px;border-radius:20px;">
                    <div class="card">
                        <div style="padding:10px;" class="card-body">
                            <?php $userId =  $user->id; $times = \App\Helpers\LogHelper::getLoginLogoutTimes($userId); ?>
                            <div class="d-flex justify-content-between">
                                <p class="m-0" data-bs-toggle="tooltip" title="<?php echo e($user->LateReason->filter(function($reason) {
    return $reason->created_at->isToday();
})->first()->reason ?? 'N/A'); ?>" style="cursor:pointer">
                                    Login: <?php echo e($times['login_time'] ?? 'Not Available'); ?> <?php if($user->LateReason->count() >= 1): ?><span class="badge bg-danger"><?php echo e(optional($user->LateReason)->count() ?? 0); ?></span><?php endif; ?>
                                </p>
                                <p class="m-0">   Logout: <?php echo e($times['logout_time'] ?? 'Not Available'); ?></p>
                                <?php
                                $loginTime = \Carbon\Carbon::parse($times['login_time']);
                                $comparisonTime = \Carbon\Carbon::createFromTimeString('09:00:00 AM');

                                $isLate = $loginTime->gt($comparisonTime);
                                $diffInMinutes = $loginTime->diffInMinutes($comparisonTime);
                                $hours = floor($diffInMinutes / 60);
                                $minutes = $diffInMinutes % 60;
                            ?>

                           
                                <?php if($isLate): ?>
                                    <p class="badge bg-danger">
                                        Late: <?php echo e($hours < 1 ? '' : $hours . ' hr(s) and '); ?><?php echo e($minutes); ?> min(s)
                                    </p>
                                <?php else: ?>
                                    <p class="badge bg-success">On Time</p>
                                <?php endif; ?>
                            </div>
                            <center>
                                <?php if($user->image): ?>
                                    <img src="<?php echo e($user->image); ?>" style="width:130px;height:150px;margin-top:10px;"  />
                                <?php else: ?>
                                    <img src="<?php echo e(asset('user1.png')); ?>" style="width:100%;height:150px;margin-top:10px;"  />
                                <?php endif; ?>
                                <h5 class="card-title">
                                    <b><?php echo e(substr($user->name, 0, '25')); ?></b>      
                                    <?php $__currentLoopData = $user->leave; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $leave): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php if(\Carbon\Carbon::today()->between($leave->from_date, $leave->to_date) && $leave->status == 1): ?>
                                            <span class="badge bg-danger text-light"> on leave</span>
                                        <?php endif; ?>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><br>     
                                    <small>
                                    <?php $__currentLoopData = $user->roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php echo e($role->name); ?>

                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </small>
                                  
                                </h5>
                            </center    >
                            <span><b>Today Project Report :</b> &nbsp;<?php echo e($user->dailyReport->where('created_at', '>=', \Carbon\Carbon::today())->count()); ?>/<?php echo e($user->projects->count()); ?></span><br>
                            <span><b>Phone :</b> &nbsp; <?php echo e($user->phone_no); ?></span><br>
                            <!-- <span><b>Email :</b> &nbsp; <?php echo e($user->email); ?></span><br>
                            <span><b>DOJ   :</b> &nbsp; <?php echo e($user->date_of_joining); ?></span><br> -->
                            <span><b>Projects   :</b> &nbsp; <?php echo e($user->projects->count()); ?></span><br>   
                            <span><b>Complete Task   :</b> &nbsp; <?php echo e($user->totalCompletedTasks); ?></span><br>   
                            <span><b>Total Task   :</b> &nbsp; <?php echo e($user->totalAssignedTasks); ?></span><br>
                            <a href="<?php echo e(route('areport.task',$user->id)); ?><?php if(request()->project): ?>?project=<?php echo e(request()->project); ?><?php endif; ?>" style="width:100%;" class="btn btn-<?php echo e($user->color); ?> text-white"> 
                                <?php echo e($user->taskCompletionStatus); ?>  
                            </a>
                        </div>
                    </div>   
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <?php else: ?>
            <div class="col-md-12 bg-white p-5" style="border-radius:20px;text-align:center;">
                <h2>No task assigned to any team member.</h2>
            </div>
        <?php endif; ?>
    </div>  
    </section>

    <div class="offcanvas offcanvas-end offcanvas-large " tabindex="-1" id="offcanvasRight" aria-labelledby="offcanvasRightLabel" style="width: 35vw;">
    <div class="offcanvas-header">
        <h5 id="offcanvasRightLabel">Today BDE Report</h5>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
        <table class="table table-striped">
            <thead>
                <tr class="bg-light">
                    <th scope="col">#</th>
                    <th scope="col">BDE Name</th>
                    <th scope="col">Assigned Lead</th>
                    <th scope="col">Followup</th>
                    <th scope="col">Calls</th>
                    <th scope="col">Proposal</th>
                    <th scope="col">Converted</th>
                </tr>
            </thead>
            <tbody>
             
            </tbody>
        </table>
    </div>        
</div>
<script>
    $(function() {
    var start = moment().subtract(29, 'days');
    var end = moment();

    function cb(start, end) {
        $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
    }
    
    $('#reportrange').daterangepicker({
        startDate: start,
        endDate: end,
        ranges: {
            'Today': [moment(), moment()],
            'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
            'Last 7 Days': [moment().subtract(6, 'days'), moment()],
            'Last 30 Days': [moment().subtract(29, 'days'), moment()],
            'This Month': [moment().startOf('month'), moment().endOf('month')],
            'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        }
    }, function(start, end) {
        cb(start, end);

        let startDate = start.format('YYYY-MM-DD');
        let endDate = end.format('YYYY-MM-DD');

        // Show the loader before the AJAX request
        $('#today-report').html(`
            <tr id="loader-row">
                <td colspan="2" class="text-center">
                    <div id="loader">Loading...</div>
                </td>
            </tr>
        `);
    });

    cb(start, end);
});

</script>
<script>
    // Initialize Bootstrap tooltips
var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
  return new bootstrap.Tooltip(tooltipTriggerEl)
})

</script>
    
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $component = $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?><?php /**PATH /home/adxventure/lara_tms/resources/views/admin/reports/index.blade.php ENDPATH**/ ?>