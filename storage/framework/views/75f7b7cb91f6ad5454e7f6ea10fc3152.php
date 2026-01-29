<?php if (isset($component)) { $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54 = $component; } ?>
<?php $component = App\View\Components\AppLayout::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('app-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(App\View\Components\AppLayout::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
    <?php $__env->startSection('title','Role-Create'); ?>

    <div class="pagetitle">
        <a style="float:right;margin-left:10px" class="btn btn-primary"  href="<?php echo e(route('role')); ?>">Roles</a>
        <h1>Add Role</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?php echo e(url('/')); ?>">Home</a></li>
                <li class="breadcrumb-item active">Add Role</li>
            </ol>
        </nav>  
    </div>
    <!-- End Page Title -->
    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body p-4">
                    <form autocomplete="off" data-method="POST" data-action="<?php echo e(route('role.store')); ?>" id="ajax-form" enctype="multipart/form-data">
                        <?php echo csrf_field(); ?>
                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <label for="designation">Designation <span class="text-danger">*</span></label>
                                <input class="form-control mt-2" id="designation" type="text" name="designation" placeholder="Enter Designation" required>
                                <small id="error-designation" class="form-text error text-danger"></small>
                            </div>

                            <div class="col-md-12 mb-2">
                                <label for="permissions">Permissions <span class="text-danger">*</span></label>
                                <?php if(isset($permissions)): ?>
                                    <?php $__currentLoopData = $permissions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $permission): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <div class="form-check">
                                            <input type="checkbox" name="permission[]" value="<?php echo e($permission->name); ?>" class="form-check-input" id="permission-<?php echo e($permission->id); ?>">
                                            <label class="form-check-label" for="permission-<?php echo e($permission->name); ?>"><?php echo e($permission->name); ?></label>
                                        </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php endif; ?>
                            </div>

                            <div class="col-md-12 mt-3">
                                <button id="submit-btn" type="submit" class="btn btn-primary">
                                    <span class="loader" id="loader" style="display: none;"></span>
                                  Create Role
                                </button>
                            </div>
                        </div>
                    </form>

                    </div>
                </div>
            </div>
        </div>
    </section>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $component = $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?><?php /**PATH /home/adxventure/lara_tms/resources/views/admin/permission/role-create.blade.php ENDPATH**/ ?>