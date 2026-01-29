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
    <?php $__env->startSection('title','Late Report'); ?>
    <?php echo $__env->make('include.alert', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <!-- FullCalendar CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.2.0/fullcalendar.min.css" rel="stylesheet">
    <!-- jQuery (FullCalendar requires jQuery) -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <!-- FullCalendar JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.2.0/fullcalendar.min.js"></script>
    <style>
        table tr:hover {
            background-color: #fff !important;
        }
    </style>

    <section class="section">
        <div class="row">
            <div class="col-3 my-2" style="float:right">
                <a href="<?php echo e(URL::previous()); ?>" class="btn btn-secondary" >Back</a>  
            </div>
            <div class="col-12">
                <div class="row">
                    <div class="col-xxl-3 col-md-2">
                        <div class="card info-card sales-card">
                            <div class="card-body">
                                <h5 class="card-title">   <?php echo e($user->name); ?> (<?php echo e($user->roles->pluck('name')->first() ?? 'N/A'); ?>) </h5>
                                <h6><a href="tel:+<?php echo e($user->phone_no); ?>"><?php echo e($user->phone_no); ?></a></h6>
                            </div>
                        </div>
                    </div>
                    <div class="col-xxl-2 col-md-2">
                        <a href="#">
                            <div class="card info-card sales-card">
                                <div class="card-body">
                                    <h5 class="card-title">This Month Late</h5>
                                    <h6><?php echo e($count['this_month_late']?? 0); ?> / <?php echo e($count['this_month']?? 0); ?></h6> 
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-xxl-2 col-md-2">
                        <div class="card info-card sales-card">
                            <a href="#">
                                <div class="card-body">
                                    <h5 class="card-title">Total Late </h5>
                                    <h6><?php echo e($count['total_late'] ?? 0); ?> / <?php echo e($count['total']?? 0); ?> </h6>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-12"> 
                <div class="card">
                    <div class="card-body">
                        <br>
                        <div id="calendar"></div>

                        <!-- <table class="table table-striped">
                            <thead>
                                <tr class="bg-success text-white table-bordered ">
                                    <th scope="col">#</th>
                                    <th scope="col">Date</th>
                                    <th scope="col">Employee</th>
                                    <th scope="col">Login time</th>
                                    <th scope="col">Logout time</th>
                                    <th scope="col">Working Hrs</th>
                                    <th scope="col">Late Reason</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php if(isset($data)): ?>
                                <?php $i = 1; ?>
                                <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $d): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr <?php if($d->status == 1): ?> style="background: #ffcfcf;" <?php else: ?> style="background: #0b700b54;" <?php endif; ?> >
                                        <td><?php echo e($i++); ?></td>
                                        <td><?php echo e($d->created_at->format('d M Y')); ?></td>
                                        <td><strong><?php echo e($d->user->name); ?></strong><br>
                                            <small><?php echo e($d->user->roles->pluck('name')->first() ?? 'N/A'); ?></small>
                                        </td>
                                        <td>
                                        <?php if($d->status == 1): ?>
                                            <span class="badge bg-danger"> <?php echo e($d->login_time); ?> </span>
                                            <?php else: ?>
                                            <span class="badge bg-success">  <?php echo e($d->login_time); ?></span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php echo e($d->logout_time); ?> 
                                        </td>
                                        <td>
                                            <?php echo e($d->working_hrs); ?> 
                                        </td>
                                        <td><?php echo e($d->reason ?? 'N/A'); ?></td>
                                        <td>N/A</td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="9" class="text-center">No data available</td>
                                </tr>
                            <?php endif; ?>
                            </tbody>
                        </table> -->
                        <div class="row pagination-links">
                        <div class="col-8"></div>
                        <div class="col-4 text-end">

                        </div>
                    </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <script>
        $(document).ready(function() {
            $('#calendar').fullCalendar({
                header: {
                    left:'',
                    right: 'prev,next today',
                    center: 'title',
                },
                events: [
                    <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $d): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        {
                            title: 'Login - <?php echo e($d->user->name); ?>',
                            start: "<?php echo e($d->created_at->format('Y-m-d')); ?>T<?php echo e(\Carbon\Carbon::parse($d->login_time)->format('H:i:s')); ?>",
                            end: "<?php echo e($d->created_at->format('Y-m-d')); ?>T<?php echo e(\Carbon\Carbon::parse($d->logout_time)->format('H:i:s')); ?>",
                            extendedProps: {
                                login_time: '<?php echo e($d->login_time); ?>',
                                logout_time: '<?php echo e($d->logout_time ?? 'N/A'); ?>',
                                working_hrs: '<?php echo e($d->working_hrs ?? 'N/A'); ?>',
                                late_reason: <?php echo json_encode($d->reason ?? 'N/A', 15, 512) ?>, 
                                employee: '<?php echo e($d->user->name); ?>',
                                status: '<?php echo e($d->status); ?>',
                            }
                        }
                        <?php if(!$loop->last): ?>,<?php endif; ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                ],
                dayClick: function(date, jsEvent, view) {
                    var clickedDate = date.format(); 
                    var eventsForClickedDate = $('#calendar').fullCalendar('clientEvents', function(event) {
                        return event.start.format('YYYY-MM-DD') === clickedDate;
                    });
                    var event = eventsForClickedDate[0];
                    console.log(event);
                    if (event) {
                        var content = `
                            Employee: ${event.extendedProps.employee}   
                            Login Time: ${event.extendedProps.login_time}
                            Logout Time:${event.extendedProps.logout_time}
                            Working Hours: ${event.extendedProps.working_hrs}
                            Late Reason: ${event.extendedProps.late_reason}
                        `;
                        console.log(content);
                        swal({
                            title: "Event Details",
                            text: content,
                            buttons: true
                        });
                    } else {
                        swal({
                            title: "No Login Record",
                            text: "You are not logged in on this date.",
                            icon: "warning",
                            buttons: true
                        });
                    }
                },
                eventRender: function(event, element) {
                    var customContent = `
                    
                        <strong>Login Time:</strong> ${event.extendedProps.login_time}<br>
                        <strong>Logout Time:</strong> ${event.extendedProps.logout_time}<br>
                        <strong>Working Hours:</strong> ${event.extendedProps.working_hrs}<br>
                        <strong>Late Reason:</strong> ${event.extendedProps.late_reason}
                    `;

                    var isSunday = event.start.day() === 0;
                    var isFourthSaturday = event.start.day() === 6 && event.start.date() >= 22 && event.start.date() <= 28;
                    if (isSunday || isFourthSaturday) {
                        customContent += "<br><strong>Holiday Enjoy!</strong>";
                    }
                    
                    element.find('.fc-title').html(customContent);
                    if (event.extendedProps && event.extendedProps.status == 1) {
                        element.css('background-color', '#ffcfcf');
                    } else {
                        element.css('background-color', 'rgb(222, 255, 222)');
                    }
                    element.css('color', '#000');
                    element.css('padding', '5px');
                }
            });
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
<?php endif; ?><?php /**PATH /home/bookmziw/lara_tms/resources/views/admin/user/user-late-report.blade.php ENDPATH**/ ?>