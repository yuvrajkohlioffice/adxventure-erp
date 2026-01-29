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
    <div class="pagetitle">
        <a style="float:right;" class="btn btn-primary" href="<?php echo e(url('user/client/create')); ?>"> Create Client</a>
        <h1>All Clients</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?php echo e(url('/')); ?>">Home</a></li>
                <li class="breadcrumb-item active">Users</li>
            </ol>
        </nav>
    </div>
    <?php echo $__env->make('include.alert', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <form action="" method="GET">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <input type="name" class="form-control" name="name" value="<?php echo e(request()->name ?? ''); ?>"
                                            id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Search by client name...">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <select class="form-control" name="status">
                                                <option value="">SELECT STATUS</option>
                                                <option value="1">ACTIVE</option>
                                                <option value="0">DE-ACTIVE</option> 
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <button class="btn btn-success btn-md" >Filter</button>
                                            &nbsp; &nbsp;
                                            <a href="#" id="resetButton" class="btn btn-danger btn-danger" >Refresh</a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                    </div>
                                </div>
                            </div>
                        </form>
                        <table class="table table-striped table-bordered text-center">
                            <thead>
                                <tr class="bg-light">
                                    <th scope="col">#</th>
                                    <th scope="col">Name</th>
                                    <th scope="col">Contact Details</th>
                                    <th scope="col">Projects</th>
                                    <th scope="col">Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(count($data) > 0): ?>
                                <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $d): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <th scope="row"> <?php echo e($data->firstItem() + $key); ?>. </th>
                                        <td><b><?php echo e(ucfirst($d->name)); ?></b></td>
                                        <td class="text-left" style="font-size:17px !important;">
                                            Email:<a href="mailto:<?php echo e($d->email); ?>"> <?php echo e($d->email); ?></a> <br>Phone No: <a href="tel:<?php echo e($d->phone_no); ?>" ><?php echo e($d->phone_no); ?></a>
                                        </td>
                                        <td><a href="<?php echo e(asset('projects')); ?>?client=<?php echo e($d->id); ?>"> All Projects (<?php echo e($d->project->count()); ?>) </a></td>
                                        <td>
                                            <?php if($d->status == 1): ?>
                                                <span class="badge bg-success" >Active</span>
                                            <?php else: ?>
                                                <span class="badge bg-danger">In-Active</span>
                                            <?php endif; ?>
                                        </td>
                                        <th>
                                            <a href="<?php echo e(route('crm.upsale',$d->id)); ?>" class="btn btn-primary btn-sm" >Upsale</a>
                                             <a href="<?php echo e(url('/user/client/edit/'.$d->id)); ?>" class="btn btn-sm btn-success">
                                            <i class="fa fa-pencil" ></i>Edit</a>
                                            <?php if($d->status != 1): ?>
                                            <a href="<?php echo e(url('/user/update/status/'.$d->id.'/1')); ?>" onClick="return confirm('Are you sure');" class="btn btn-sm btn-success">
                                            <i class="fa fa-pencil" ></i>Active</a>
                                            <?php else: ?>
                                            <a href="<?php echo e(url('/user/update/status/'.$d->id.'/0')); ?>"  onClick="return confirm('Are you sure');" class="btn btn-sm btn-danger">
                                            <i class="fa fa-pencil" ></i>in Active</a>
                                            <?php endif; ?>
                                        </th>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="8">  <center>     NO DATA FOUND</center> </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                        <!-- End Default Table Example -->
                    </div>
                </div>
            </div>
        </div>
    </section>
    <?php echo e($data->links()); ?>

 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $attributes = $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $component = $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?><?php /**PATH /home/bookmziw/adx_tms/laravel/resources/views/admin/client/index.blade.php ENDPATH**/ ?>