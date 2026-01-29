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
    <?php $__env->startSection('title','Role-Edit'); ?>

    <div class="pagetitle">
    <a style="float:right;margin-left:10px" class="btn btn-primary"  href="<?php echo e(route('role')); ?>">Roles</a>
        <h1>Edit Role</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?php echo e(url('/')); ?>">Home</a></li>
                <li class="breadcrumb-item active">Edit Role</li>
            </ol>
        </nav>
    </div>
    <!-- End Page Title -->
    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body p-4">
                    <form autocomplete="off" data-method="POST" data-action="<?php echo e(route('permissions.assign')); ?>" id="ajax-form" enctype="multipart/form-data">
                        <?php echo csrf_field(); ?>
                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <label for="designation">Designation <span class="text-danger">*</span></label>
                                <select name="designation" class="form-control mt-2" required>
                                    <option selected value="<?php echo e($role->id); ?>"><?php echo e($role->name); ?></option>
                                </select>
                                <small id="error-designation" class="form-text error text-danger"></small>
                            </div>

                            <div class="col-md-12">
                                <label for="permissions">Permissions <span class="text-danger">*</span></label>
                                <?php if(isset($permissions)): ?>
                                    <?php $__currentLoopData = $permissions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $permission): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <div class="form-check">
                                            <input type="checkbox" name="permission[]" value="<?php echo e($permission->name); ?>" class="form-check-input mt-2" id="permission-<?php echo e($permission->id); ?>"  <?php if($role->permissions->contains('id', $permission->id)): ?> checked <?php endif; ?>>
                                            <label class="form-check-label" for="permission-<?php echo e($permission->name); ?>"><?php echo e($permission->name); ?></label>
                                        </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php endif; ?>
                            </div>

                            <div class="col-md-12 mt-3">
                                <button id="submit-btn" type="submit" class="btn btn-sm btn-primary">
                                    <span class="loader" id="loader" style="display: none;"></span>
                                  Edit Role
                                </button>
                            </div>
                        </div>
                    </form>

                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Upload CSV Model  -->
    <div class="modal" id="AddModel" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-bs-backdrop="static"
    data-bs-keyboard="false">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Upload CSV</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form  class="ajax-form" data-action="<?php echo e(route('crm.csv')); ?>"  data-method="POST" enctype="multipart/form-data">
                        <?php echo csrf_field(); ?>
                        <div class="mb-3">
                            <label for="exampleInputcategory1" class="form-label">Select CSV file:</label>
                            <input type="file" class="form-control" id="csv_file" name="csv_file"  required id="addcategory">
                            <small id="error-csv_file" class="form-text error text-danger"></small>
                        </div>
                        <button type="submit" class="btn btn-primary">Upload</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function(){
            $( '.select-2-multiple' ).select2( {
                theme: 'bootstrap-5',
            } );
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
<?php endif; ?><?php /**PATH /home/bookmziw/lara_tms/resources/views/admin/permission/role-edit.blade.php ENDPATH**/ ?>