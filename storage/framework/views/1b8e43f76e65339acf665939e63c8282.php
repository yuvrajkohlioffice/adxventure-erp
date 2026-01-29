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
    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <table class="table table-striped">
                            <thead>
                                <tr class="bg-success text-white table-bordered ">
                                    <th scope="col">#</th>
                                    <th scope="col">Employee</th>
                                    <th scope="col">login time</th>
                                    <th scope="col">Logout time</th>
                                    <th scope="col">Working hrs</th>
                                    <th scope="col">Device</th>
                                    <th scope="col">IP Address</th>
                                    <th scope="col">Late Reason</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(isset($data)): ?>
                                    <?php $i = 1; ?>
                                    <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $d): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                            <td><?php echo e($i++); ?></td> <!-- Increment $i correctly -->
                                            <td class="d-flex align-items-center gap-3  ">
                                                <img src="<?php echo e($d->user->image); ?>" style="width:80px !important;height:80px; object-fit:cover" class="rounded"/>
                                                <div>
                                                    <strong><a href="<?php echo e(route('employee.user.late.report',['id'=>$d->user_id])); ?>"><?php echo e($d->user->name); ?></a></strong><br>
                                                    <small><?php echo e($d->user->roles->pluck('name')->first() ?? 'N/A'); ?></small>
                                                </div>
                                            </td>
                                            <td>
                                                <?php if($d->status == 1): ?>
                                                <span class="badge bg-danger"> <?php echo e($d->login_time); ?> </span>
                                                <?php else: ?>
                                                <span class="badge bg-success">  <?php echo e($d->login_time); ?></span>
                                                <?php endif; ?>
                                            </td>
                                            <td><?php echo e($d->logout_time ?? '-'); ?></td>
                                            <td><?php echo e($d->working_hrs ?? '-'); ?></td>
                                            <td><?php echo e($d->device ?? '-'); ?></td>
                                            <td><?php echo e($d->ip_address ?? '-'); ?></td>
                                            <td><?php echo e($d->reason  ?? 'On Time'); ?></td>
                                            <td>
                                                <?php if($d->status == 1): ?>
                                                    <button class="btn btn-sm btn-warning">Warning Mail </button>
                                                <?php else: ?>
                                                N/A
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="9" class="text-center">No data available</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $attributes = $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $component = $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?><?php /**PATH /home/bookmziw/lara_tms/resources/views/admin/user/late-report.blade.php ENDPATH**/ ?>