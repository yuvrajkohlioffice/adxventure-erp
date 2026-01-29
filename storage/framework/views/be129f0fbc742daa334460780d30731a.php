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
<?php $__env->startSection('title',"Employee's"); ?>
    <div class="pagetitle">
        <a style="float:right;" class="btn btn-primary" href="<?php echo e(route('users.create')); ?>"> Create Employee</a>
        <h1>All Employees</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?php echo e(url('/')); ?>">Home</a></li>
                <li class="breadcrumb-item active">Users</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->
    <?php echo $__env->make('include.alert', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <form action="" method="GET" class="my-3">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-4">
                                        <input type="name" class="form-control" name="name" value="<?php echo e(request()->name ?? ''); ?>"
                                            id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Search by employee name...">
                                    </div>
                                    <div class="col-md-3">
                                        <select class="form-control" name="department">
                                            <option value="">Search by Department</option>
                                            <option value="0">New Regsitered</option>
                                            <?php if(count($departments) > 0): ?>
                                                <?php $__currentLoopData = $departments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dep): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <option value="<?php echo e($dep->id); ?>" <?php if(request()->department == $dep->id): ?> selected <?php endif; ?>><?php echo e($dep->name); ?> (<?php echo e($dep->users->count() ?? '0'); ?>)</option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            <?php endif; ?>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <select class="form-control" name="status">
                                            <option value="">SELECT STATUS</option>
                                            <option value="1" <?php if(request()->status == "1"): ?> selected <?php endif; ?>>ACTIVE</option>
                                            <option value="0" <?php if(request()->status == "0"): ?> selected <?php endif; ?>>DE-ACTIVE</option> 
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <button class="btn btn-success btn-md" >Filter</button>
                                        &nbsp; &nbsp;
                                        <a href="#" id="resetButton" class="btn btn-danger btn-danger" >Refresh</a>
                                    </div>
                                    <div class="col-md-3">
                                    </div>
                                </div>
                            </div>
                        </form>
                        <!-- Default Table -->
                        <table class="table table-striped">
                            <thead>
                                <tr class="bg-light">
                                    <th scope="col">#</th>
                                    <th scope="col">Profile Image </th>
                                    <th scope="col">Name</th>
                                    <th scope="col">Department & Role</th>
                                    <th scope="col">Contact Details</th>
                                    <th scope="col">Joining Date</th>
                                    <th scope="col">Date Of Birth</th>
                                    <th scope="col">Status</th>
                                    <th scope="col">Login</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(count($data) > 0): ?>
                                <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $d): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <th scope="row"> <?php echo e($data->firstItem() + $key); ?>. </th>
                                        <th>  <img  src="<?php echo e($d->image); ?>" style="width:100px !important;height:120px;" class="img-fluid rounded"/></th>
                                        <td><b><?php echo e(ucfirst($d->name)); ?></b></td>  
                                        <td>
                                            <b><?php echo e(ucfirst($d->department->name ?? '')); ?></b>
                                            <br><?php echo e(optional($d->roles->first())->name ?? 'No Approved   '); ?>

                                        </td>
                                        <td style="font-size:17px !important;">Email:<a href="mailto:<?php echo e($d->email); ?>"> <?php echo e($d->email); ?></a> <br>Phone No: <?php echo e($d->phone_no); ?></td>
                                        <td><?php echo e(date("d M, Y",strtotime($d->date_of_joining))); ?></td>
                                        <td><?php echo e(date("d M, Y",strtotime($d->date_of_birth))); ?></td>
                                        <td>
                                            <?php if($d->is_active == 1): ?>
                                                <span class="badge bg-success" >Active</span>
                                            <?php else: ?>
                                                <span class="badge bg-danger">In-Active</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <a href="<?php echo e(route('user.login',$d->id)); ?>" class="btn btn-sm btn-primary">Login</a>
                                        </td>
                                        <td>
                                            <a href="<?php echo e(route('users.edit',$d->id)); ?>" class="btn btn-sm btn-success">
                                            <i class="fa fa-pencil" ></i>Edit</a>
                                            <?php if($d->is_active != 1): ?>
                                            <a href="<?php echo e(url('/user/update/status/'.$d->id.'/1')); ?>" onClick="return confirm('Are you sure');" class="btn btn-sm btn-success">
                                            <i class="fa fa-pencil" ></i>Active</a>
                                            <?php else: ?>
                                            <a href="<?php echo e(url('/user/update/status/'.$d->id.'/0')); ?>"  onClick="return confirm('Are you sure');" class="btn btn-sm btn-danger">
                                            <i class="fa fa-pencil" ></i>in Active</a>
                                            <?php endif; ?>
                                            <br>
                                            <a style="margin-top:10px;" href="<?php echo e(url('/logs/index/'.$d->id)); ?>"  class="btn btn-sm btn-danger">
                                                <i class="fa fa-pencil" ></i>Activity Log
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php else: ?>
                                <tr >
                                    <td colspan="8">  <center>NO DATA FOUND</center> </td>
                                </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                        <div class="row pagination-links">
                            <div class="col-8">
                            <?php if($data->total() > 0): ?>
                                Showing <?php echo e($data->firstItem()); ?> to <?php echo e($data->lastItem()); ?> of <?php echo e($data->total()); ?> entries
                            <?php endif; ?>
                            </div>
                            <div class="col-4 text-end">
                                <?php echo e($data->appends(request()->query())->links()); ?>

                            </div>
                        </div>
                        <!-- End Default Table Example -->
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
<?php endif; ?><?php /**PATH /home/bookmziw/adx_tms/laravel/resources/views/admin/employee/index.blade.php ENDPATH**/ ?>