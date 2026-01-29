<?php if (isset($component)) { $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54 = $component; } ?>
<?php $component = App\View\Components\AppLayout::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('app-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(App\View\Components\AppLayout::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
    <style>
        .form-group{
            margin-top:10px;
            margin-bottom:10px;
        }
        label{
        font-weight:600;
        }
               
    </style>
   <div class="pagetitle">
        <h1>Users</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?php echo e(url('/')); ?>">Client</a></li>
                <li class="breadcrumb-item active">Create Client</li>
            </ol>
        </nav>
    </div>
    <section class="section">
        <div class="row">
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-body p-4">
                        <form autocomplete="off" data-method="POST" data-action="<?php echo e(route('user.client.store')); ?>" id="ajax-form" enctype="mu">
                            <?php echo csrf_field(); ?>
                            <?php if(isset($lead)): ?>
                            <input type="hidden" name="lead_id" value="<?php echo e($lead->id); ?>">
                            <?php endif; ?>
                            <div class="row">
                                <div class="col-12 mt-3">
                                    <label for="exampleInputEmail1">Name</label>
                                    <input type="text" class="form-control" id="exampleInputEmail1" name="name" 
                                        value="<?php echo e($lead->name ?? old('name')); ?>" 
                                      placeholder="Enter name..">
                                    <small id="error-name" class="form-text error text-danger"></small>
                                </div>
                                <div class="col-12 mt-3">
                                    <label for="exampleInputEmail2">Email address</label>
                                    <input type="email" class="form-control" id="exampleInputEmail2" name="email" placeholder="Enter email.." value="<?php echo e($lead->email ?? old('email')); ?>">
                                    <small id="error-email" class="form-text error text-danger"></small>
                                </div>
                                <div class="col-12 mt-3">
                                    <label for="exampleInputEmail2">Phone No.</label>
                                    <input type="number" class="form-control" id="exampleInputEmail2" name="phone_no" placeholder="Enter phone no..." value="<?php echo e($lead->phone ?? old('phone_no')); ?>">
                                    <small id="error-phone_no" class="form-text error text-danger"></small>
                                </div>
                                <div class="col-12 mt-3">
                                    <label for="exampleInputEmail2">Client Category</label>
                                    <select class="form-control" id="exampleInputEmail2" name="client_category">
                                        <option value="">Select Category</option>
                                        <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($category->category_id); ?>"><?php echo e($category->name); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                    <small id="error-client_category" class="form-text error text-danger"></small>
                                </div>
                                <div class="col-12 mt-3">
                                    <label for="exampleInputAddress1">Address</label>
                                    <input type="text" class="form-control" name="address" id="exampleInputAddress1" placeholder="Address" value="<?php echo e(old('address')); ?>" >
                                    <small id="error-address" class="form-text error text-danger"></small>
                                </div>
                                <div class="col-12 mt-3">
                                    <label for="exampleInputcity1">City</label>
                                    <input type="text" class="form-control" name="city" id="exampleInputcity1" placeholder="City" value="<?php echo e($lead->city ?? old('city')); ?>" >
                                    <small id="error-city" class="form-text error  text-danger"></small>
                                </div>
                                <div class="col-12 mt-3">
                                    <label for="exampleInputPassword1">Password</label>
                                    <input type="password" class="form-control" name="password" id="exampleInputPassword1" placeholder="Password">
                                    <small id="error-password" class="form-text error  text-danger"></small>
                                </div>
                                <div class="col-3 mt-3">
                                    <button id="submit-btn"  type="submit" class="btn btn-primary">
                                    <span class="loader" id="loader" style="display: none;"></span> 
                                    Create Client
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
<?php endif; ?><?php /**PATH /home/adxventure/lara_tms/resources/views/admin/client/create.blade.php ENDPATH**/ ?>