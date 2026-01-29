<?php if (isset($component)) { $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54 = $attributes; } ?>
<?php $component = App\View\Components\AppLayout::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('app-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(App\View\Components\AppLayout::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
     <!-- Show Counts  -->
    <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    <style>
        .chartjs.card {
            height: 335px;
        }
    </style>

    <div class="pagetitle">
        <div id="reportrange" class="form-control" style="cursor: pointer;width: 100%; max-width:340px;float:right; border-radius:6px;padding:3.5px 6px;font-weight: 600;">
            <small>Sort By</small>&nbsp;
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-calendar"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
            <span></span>
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-down me-0"><polyline points="6 9 12 15 18 9"></polyline></svg>
        </div>
        <h1>Dashboard</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?php echo e(route('dashboard')); ?>">Home</a></li>
                <li class="breadcrumb-item active">Dashboard</li>
            </ol>
        </nav>
    </div>

    <?php if (\Illuminate\Support\Facades\Blade::check('role', 'Super-Admin')): ?>
        <section class="section dashboard">
            <div class="row">
                <div class="col">
                    <div class="card info-card sales-card">
                        <div class="card-body pt-4 d-flex align-items-center gap-3">
                            <i class="bi bi-people-fill" style="font-size: xx-large;background: blue;border-radius: 50%;padding: 2px 10px;color:white;"></i>
                            <div class="div">
                                <h5 class="card-title m-0 p-0" style="font-weight:600;">Leads</h5>
                                <h6 class="mb-0 fs-22 text-dark mt-2" id="leads_count"><?php echo e($count['leads'] ?? 0); ?></h6>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card info-card sales-card">
                        <div class="card-body pt-4 d-flex align-items-center gap-3">
                                <i class="bi bi-person-check-fill" style="font-size: xx-large;background: green;border-radius: 50%;padding: 2px 10px;color: white;"></i>
                            <div class="div">
                                <h5 class="card-title m-0 p-0" style="font-weight:600;">Followups</h5>
                                <h6 class="mb-0 fs-22 text-dark mt-2" id="followups_count"><?php echo e($count['followups'] ?? 0); ?></h6> 
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card info-card sales-card">
                        <div class="card-body pt-4 d-flex align-items-center gap-3">
                            <i class="bi bi-file-text-fill" style="font-size: xx-large;background: chocolate;border-radius: 50%;padding: 2px 10px;color: white;"></i>
                            <div class="div">
                                <h5 class="card-title m-0 p-0" style="font-weight:600;">Proposals</h5>
                                <h6 class="mb-0 fs-22 text-dark mt-2" id="proposals_count"><?php echo e($count['proposal'] ?? 0); ?></h6>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card info-card sales-card">
                            <div class="card-body pt-4 d-flex align-items-center gap-3">
                            <i class="bi bi-file-earmark-arrow-up-fill" style="font-size: xx-large;background: darkslateblue;border-radius: 50%;padding: 2px 10px;color: white;"></i>
                            <div class="div">
                                <h5 class="card-title m-0 p-0" style="font-weight:600;">Quotation</h5>
                                <h6 class="mb-0 fs-22 text-dark mt-2" id="quotation_count"><?php echo e($count['quotation'] ?? 0); ?></h6>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card info-card sales-card">
                            <div class="card-body pt-4 d-flex align-items-center gap-3">
                            <i class="bi bi-currency-rupee" style="font-size: xx-large;background: maroon;border-radius: 50%;padding: 2px 10px;color: white;"></i>
                            <div class="div">
                                <h5 class="card-title m-0 p-0" style="font-weight:600;">Revenue</h5>
                                <h6 class="mb-0 fs-22 text-dark mt-2" id="revenue_count">₹ <?php echo e($count['revenue'] ?? 0); ?></h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <div class="card info-card sales-card">
                            <div class="card-body pt-4 d-flex align-items-center gap-3">
                            <i class="bi bi-person-circle"  style="font-size: xx-large;background: cadetblue;border-radius: 50%;padding: 2px 10px;color: white;"></i>
                            <div class="div">
                                <h5 class="card-title m-0 p-0" style="font-weight:600;">Total Employee</h5>
                                <h6 class="mb-0 fs-22 text-dark mt-2" id="revenue_count"><?php echo e($count['employee'] ?? 0); ?></h6>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card info-card sales-card">
                            <div class="card-body pt-4 d-flex align-items-center gap-3">
                            <i class="bi bi-person-check-fill" style="font-size: xx-large;background: blueviolet;border-radius: 50%;padding: 2px 10px;color: white;"></i>
                            <div class="div">
                                <h5 class="card-title m-0 p-0" style="font-weight:600;">Total Clients</h5>
                                <h6 class="mb-0 fs-22 text-dark mt-2" id="revenue_count"><?php echo e($count['client'] ?? 0); ?></h6>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card info-card sales-card">
                            <div class="card-body pt-4 d-flex align-items-center gap-3">
                        <i class="bi bi-box-fill" style="font-size: xx-large;background: black;border-radius: 50%;padding: 2px 10px;color: white;"></i>
                            <div class="div">
                                <h5 class="card-title m-0 p-0" style="font-weight:600;">Total Projects</h5>
                                <h6 class="mb-0 fs-22 text-dark mt-2" id="revenue_count"><?php echo e($count['project'] ?? 0); ?></h6>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card info-card sales-card">
                            <div class="card-body pt-4 d-flex align-items-center gap-3">
                        <i class="bi bi-list-check" style="font-size: xx-large;background: crimson;border-radius: 50%;padding: 2px 10px;color: white;"></i>
                            <div class="div">
                                <h5 class="card-title m-0 p-0" style="font-weight:600;">Total Tasks</h5>
                                <h6 class="mb-0 fs-22 text-dark mt-2" id="revenue_count"><?php echo e($count['task'] ?? 0); ?></h6>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card info-card sales-card">
                            <div class="card-body pt-4 d-flex align-items-center gap-3">
                        <i class="bi bi-calendar-check-fill"  style="font-size: xx-large;background: darkslategrey;border-radius: 50%;padding: 2px 10px;color: white;"></i>
                            <div class="div">
                                <h5 class="card-title m-0 p-0" style="font-weight:600;">Attandance</h5>
                                <h6 class="mb-0 fs-22 text-dark mt-2" id="revenue_count"><?php echo e($count['attandance'] ?? 0); ?></h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 col-xl-7">
                    <div class="card overflow-hidden">
                        <div class="card-header">
                            <div class="d-flex align-items-center justify-content-between">
                                <h5 class="card-title mb-0">Today leaves</h5>
                                <a href="<?php echo e(url('leave')); ?>" class="btn  btn-outline-dark">Leaves <i class="bi bi-arrow-up-right-circle-fill"></i></a>
                            </div>
                        </div>
                        <div class="card-body mt-0">
                            <div class="table-responsive table-card mt-0">
                                <table class="table table-borderless table-centered align-middle table-nowrap mb-0">
                                    <thead class="text-muted table-light">
                                        <tr>
                                            <th scope="col" class="cursor-pointer">Employee</th>
                                            <th scope="col" class="cursor-pointer">Department</th>
                                            <th scope="col" class="cursor-pointer">Type</th>
                                            <th scope="col" class="cursor-pointer">Duration</th>
                                            <th scope="col" class="cursor-pointer">Reason</th>
                                            <th scope="col" class="cursor-pointer">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>  
                                        <?php if(isset($leaves)): ?>
                                        <?php $__empty_1 = true; $__currentLoopData = $leaves; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $leave): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                        <tr>
                                            <td>  
                                                <div class="d-flex align-items-center gap-1">
                                                    <?php if(!$leave->users->image): ?>
                                                        <img src="<?php echo e(asset('/user1.png')); ?>" alt="user-image" class="rounded-circle" style="height: 45px;width: 45px;">
                                                    <?php else: ?>
                                                        <img src="<?php echo e(asset($leave->users->image)); ?>" alt="user-image" class="rounded-circle" style="height: 45px;width: 45px;">
                                                    <?php endif; ?>

                                                    <div>
                                                        <h6 class="m-0"><?php echo e(ucfirst($leave->users->name) ?? ''); ?></h6>
                                                        
                                                    </div>
                                                </div>
                                            </td>
                                            
                                            <td><?php echo e($leave->type); ?></td>
                                            <td>
                                                <span class="badge text-bg-dark"><?php echo e($leave->days); ?> Days</span>
                                            </td>
                                            <td>
                                                <?php if($leave->document): ?>
                                                    <a href="<?php echo e(asset('leaves/' . $leave->document)); ?> " target='_blank'><i class='bi bi-file-earmark-pdf-fill'></i></a>
                                                <?php endif; ?>
                                                <small style='cursor:pointer' data-bs-toggle='tooltip' data-bs-placement='top' title="<?php echo e($leave->request); ?>"><?php echo e(substr($leave->request,0,20)); ?>..</small> 
                                            </td>
                                            <td>
                                                <?php if($leave->status == 1): ?>
                                                    <span class="badge text-bg-success">Approved</span>
                                                <?php elseif($leave->status == 2): ?>
                                                    <span class="badge text-bg-danger">Un-Approved</span>
                                                <?php else: ?> 
                                                    <span class="badge text-bg-warning">Pending</span>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                        <tr>
                                            <td>Employee not on leave. </td>
                                        </tr>
                                        <?php endif; ?>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    <?php endif; ?>

    <script>
        //Date-range picker 
        $(document).ready(function() {
            var start = moment();
            var end = moment();
            function cb(start, end) {
                $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
            }
            $('#reportrange').daterangepicker({
                startDate: start,
                endDate: end,
                ranges: {
                    'All': [moment().subtract(10, 'years'), moment().add(10, 'years')],
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
                // Perform the AJAX request to fetch the filtered data
                busy(1);
                $.ajax({
                    url: '<?php echo e(route("crm.counts")); ?>',
                    method: 'GET',
                    data: {
                        start_date: startDate,  
                        end_date: endDate,
                    },  
                    success: function(response) {
                        busy(0);
                        $('#leads_count').text(response.leads);
                        $('#followups_count').text(response.followups);
                        $('#proposals_count').text(response.proposals);
                        $('#quotation_count').text(response.quotation);
                        $('#revenue_count').text(response.revenue);
                        $('#delay_count').text(response.delay);
                        $('#reject_count').text(response.reject);
                    },
                    error: function() {
                    }
                });
            });
            cb(start, end);
        });
    </script>
   
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $attributes = $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $component = $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?><?php /**PATH /home/bookmziw/adx_tms/laravel/resources/views/dashboard.blade.php ENDPATH**/ ?>