<?php if (isset($component)) { $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54 = $component; } ?>
<?php $component = App\View\Components\AppLayout::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('app-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(App\View\Components\AppLayout::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
    <?php $__env->startSection('title','Profile'); ?>
    <style>
    .form-group {
        margin-top: 10px;
        margin-bottom: 10px;
    }

    label {
        font-weight: 600;
    }

    .main-body {
        padding: 15px;
    }

    .card {
        box-shadow: 0 1px 3px 0 rgba(0, 0, 0, .1), 0 1px 2px 0 rgba(0, 0, 0, .06);
    }

    .card {
        position: relative;
        display: flex;
        flex-direction: column;
        min-width: 0;
        word-wrap: break-word;
        background-color: #fff;
        background-clip: border-box;
        border: 0 solid rgba(0, 0, 0, .125);
        border-radius: .25rem;
    }

    .card-body {
        flex: 1 1 auto;
        min-height: 1px;
        padding: 1rem;
    }

    .gutters-sm {
        margin-right: -8px;
        margin-left: -8px;
    }

    .gutters-sm>.col,
    .gutters-sm>[class*=col-] {
        padding-right: 8px;
        padding-left: 8px;
    }

    .mb-3,
    .my-3 {
        margin-bottom: 1rem !important;
    }

    .bg-gray-300 {
        background-color: #e2e8f0;
    }

    .h-100 {
        height: 100% !important;
    }

    .shadow-none {
        box-shadow: none !important;
    }
    </style>
    <div class="pagetitle">
        <h1>Profile</h1>

        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?php echo e(url('/')); ?>">Profile</a></li>
                <li class="breadcrumb-item active">Edit Profile</li>
            </ol>
        </nav>
    </div>

    <?php if($user->verification == 0): ?>
    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body p-4">
                        <form autocomplete="off" data-method="POST" data-action="<?php echo e(url('profiles')); ?>" id="ajax-form">
                            <?php echo csrf_field(); ?>
                            <div class="row">
                                <div class="col-4">
                                    <label for="exampleInputEmail1">Profile Image : </label>
                                    <input type="file" class="form-control" id="exampleInputEmail1" name="profile_image"
                                        value="<?php echo e($user->image); ?>" enctype="multipart/form-data">
                                    <small id="error-profile_image" class="form-text error text-danger"></small>
                                </div>
                                <div class="col-4">
                                    <label for="exampleInputEmail1">Name</label>
                                    <input type="text" class="form-control" id="exampleInputEmail1" name="name"
                                        value="<?php echo e($user->name); ?>" readonly>
                                    <small id="error-name" class="form-text error text-danger"></small>
                                </div>
                                <div class="col-4">
                                    <label for="exampleInputEmail2">Email address</label>
                                    <input type="email" class="form-control" id="exampleInputEmail2" name="email"
                                        value="<?php echo e($user->email); ?>" readonly>
                                    <small id="error-email" class="form-text error text-danger"></small>
                                </div>
                                <div class="col-4 mt-3">
                                    <label for="exampleInputEmail2">Phone No.</label>
                                    <input type="number" class="form-control" id="exampleInputEmail2" name="phone_no"
                                        value="<?php echo e($user->phone_no); ?>" readonly>
                                    <small id="error-phone_no" class="form-text error text-danger"></small>
                                </div>
                                <div class="col-4 mt-3">
                                    <label for="exampleInputEmail1">Date of Joining</label>
                                    <input type="date" class="form-control" id="exampleInputEmail1"
                                        name="date_of_joining" value="<?php echo e($user->date_of_joining); ?>" readonly>
                                    <small id="error-date-of-joining" class="form-text error text-danger"></small>
                                </div>
                                <div class="col-4 mt-3">
                                    <label for="exampleInputEmail1">Date of Birth</label>
                                    <input type="date" class="form-control" id="exampleInputEmail1" name="date_of_birth"
                                        value="<?php echo e($user->date_of_birth); ?>" readonly>
                                    <small id="error-date-of-birth" class="form-text error text-danger"></small>
                                </div>
                                <div class="col-4 mt-3">
                                    <label for="exampleInputEmail2">Skills</label>
                                    <select name="skills[]" id="skills" class="form-select custome-select">
                                        <option value="">Select Skills</option>
                                        <?php $__currentLoopData = $skills; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $skill): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($skill->id); ?>"><?php echo e($skill->name); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                    </select>
                                    <small id="error-skills" class="form-text error text-danger"></small>
                                </div>
                                <div class="col-4 mt-3">
                                    <label for="exampleInputEmail1">City</label>
                                    <input type="text" class="form-control" id="exampleInputEmail1" name="city"
                                        value="<?php echo e(old('city')); ?>">
                                    <small id="error-city" class="form-text error text-danger"></small>
                                </div>
                                <div class="col-4 mt-3">
                                    <label for="exampleInputEmail1">Address</label>
                                    <input type="address" class="form-control" id="exampleInputEmail1" name="address"
                                        value="<?php echo e(old('address')); ?>">
                                    <small id="error-address" class="form-text error text-danger"></small>
                                </div>
                                <div class="col-4 mt-3">
                                    <label for="exampleInputEmail1">Aadhar No.</label>
                                    <input type="number" class="form-control" id="exampleInputEmail1" name="aadhar_no"
                                        value="<?php echo e(old('aadhar_no')); ?>">
                                    <small id="error-aadhar_no" class="form-text error text-danger"></small>
                                </div>
                                <div class="col-4 mt-3">
                                    <label for="exampleInputEmail1">Pan No.</label>
                                    <input type="text" class="form-control" id="exampleInputEmail1" name="pan_no"
                                        value="<?php echo e(old('pan_no')); ?>">
                                    <small id="error-pan_no" class="form-text error text-danger"></small>
                                </div>
                                <div class="col-4 mt-3">
                                    <label for="exampleInputEmail1">Account No.</label>
                                    <input type="number" class="form-control" id="exampleInputEmail1" name="account_no"
                                        value="<?php echo e(old('account_no')); ?>">
                                    <small id="error-account_no" class="form-text error text-danger"></small>
                                </div>
                                <div class="col-4 mt-3">
                                    <label for="exampleInputEmail1">Account Holder Name.</label>
                                    <input type="text" class="form-control" id="exampleInputEmail1"
                                        name="account_holder_name" value="<?php echo e(old('account_holder_name')); ?>">
                                    <small id="error-account_holder_name" class="form-text error text-danger"></small>
                                </div>
                                <div class="col-4 mt-3">
                                    <label for="exampleInputEmail1">Bank Name .</label>
                                    <input type="text" class="form-control" id="exampleInputEmail1" name="bank_name"
                                        value="<?php echo e(old('bank_name')); ?>">
                                    <small id="error-bank_name" class="form-text error text-danger"></small>
                                </div>
                                <div class="col-4 mt-3">
                                    <label for="exampleInputEmail1">Ifsc Code.</label>
                                    <input type="text" class="form-control" id="exampleInputEmail1" name="ifsc"
                                        value="<?php echo e(old('ifsc')); ?>">
                                    <small id="error-ifsc" class="form-text error text-danger"></small>
                                </div>
                                <div class="col-6 mt-3">
                                    <label for="exampleInputEmail1">Aadhar Front Image : </label>
                                    <input type="file" class="form-control" id="exampleInputEmail1"
                                        name="aadhar_front_image" enctype="multipart/form-data">
                                    <small id="error-aadhar_front_image" class="form-text error text-danger"></small>
                                </div>
                                <div class="col-6 mt-3">
                                    <label for="exampleInputEmail1">Aadhar Back Image : </label>
                                    <input type="file" class="form-control" id="exampleInputEmail1"
                                        name="aadhar_back_image" enctype="multipart/form-data">
                                    <small id="error-aadhar_back_image" class="form-text error text-danger"></small>
                                </div>
                                <div class="col-6 mt-3">
                                    <label for="exampleInputEmail1">Pan Card Image : </label>
                                    <input type="file" class="form-control" id="exampleInputEmail1" name="pan_image"
                                        enctype="multipart/form-data">
                                    <small id="error-pan_image" class="form-text error text-danger"></small>
                                </div>
                                <div class="col-6 mt-3">
                                    <label for="exampleInputEmail1">Passbook Image: </label>
                                    <input type="file" class="form-control" id="exampleInputEmail1"
                                        name="passbook_image" enctype="multipart/form-data">
                                    <small id="error-passbook_image" class="form-text error text-danger"></small>
                                </div>
                                <div class="col-3 mt-3">
                                    <button id="submit-btn" type="submit" class="btn btn-primary">
                                        <span class="loader" id="loader" style="display: none;"></span>
                                        Create User
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <?php elseif($user->verification == 1): ?>
    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body p-4 text-center">
                    <h4>Hi <?php echo e($user->name); ?>, your profile is under the approval process. Please wait for the HR Department to verify your profile.</h4>

                    </div>
                </div>
            </div>
        </div>
    </section>
    <?php elseif($user->verification == 2): ?>
    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body p-4">
                        <div class="row gutters-sm">
                            <div class="col-md-4 mb-3">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="d-flex flex-column align-items-center text-center">
                                            <img src="<?php echo e(asset($user->image)); ?>" alt="Admin" class="rounded-circle"
                                                width="150">
                                            <div class="mt-3">
                                                <h4><?php echo e($user->name); ?></h4>
                                                <p class="text-secondary mb-1">
                                                    <?php echo e($user->roles->pluck('name')->implode(', ')); ?></p>
                                                <p class="text-muted font-size-sm"><?php echo e($user->department->name); ?></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="card mb-3">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-6">
                                                <div class="row">
                                                    <div class="col-sm-3">
                                                        <h6 class="mb-0">Full Name</h6>
                                                    </div>
                                                    <div class="col-sm-9 text-secondary">
                                                        <?php echo e($user->name); ?>

                                                    </div>
                                                </div>
                                                <hr>
                                               
                                                <div class="row">
                                                    <div class="col-sm-3">
                                                        <h6 class="mb-0">Phone</h6>
                                                    </div>
                                                    <div class="col-sm-9 text-secondary">
                                                        <?php echo e($user->phone_no); ?>

                                                    </div>
                                                </div>
                                                <hr>
                                                <div class="row">
                                                    <div class="col-sm-3">
                                                        <h6 class="mb-0">City</h6>
                                                    </div>
                                                    <div class="col-sm-9 text-secondary">
                                                        <?php echo e($user->city); ?>

                                                    </div>
                                                </div>
                                                <hr>
                                                <div class="row">
                                                    <div class="col-sm-3">
                                                        <h6 class="mb-0">Address</h6>
                                                    </div>
                                                    <div class="col-sm-9 text-secondary">
                                                        <?php echo e($user->address); ?>

                                                    </div>
                                                </div>
                                                <hr>
                                            </div>



                                            <div class="col-6">
                                            <div class="row">
                                                    <div class="col-sm-6">
                                                        <h6 class="mb-0">Email</h6>
                                                    </div>
                                                    <div class="col-sm-6 text-secondary">
                                                        <?php echo e($user->email); ?>

                                                    </div>
                                                </div>
                                                <hr>
                                                <div class="row">
                                                    <div class="col-sm-6">
                                                        <h6 class="mb-0">Account Holder Name</h6>
                                                    </div>
                                                    <div class="col-sm-6 text-secondary">
                                                        <?php echo e($user->account->account_holder_name); ?>

                                                    </div>
                                                </div>
                                                <hr>
                                                <div class="row">
                                                    <div class="col-sm-6">
                                                        <h6 class="mb-0">Bank Name</h6>
                                                    </div>
                                                    <div class="col-sm-6 text-secondary">
                                                        <?php echo e($user->account->bank_name); ?>

                                                    </div>
                                                </div>
                                                <hr>
                                                <div class="row">
                                                    <div class="col-sm-6">
                                                        <h6 class="mb-0">Ifsc Code</h6>
                                                    </div>
                                                    <div class="col-sm-6 text-secondary">
                                                        <?php echo e($user->account->ifsc); ?>

                                                    </div>
                                                </div>
                                                <hr>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <?php
                                $aadharBackImage = $user->document->aadhar_back_img ?? 'default-placeholder.png';
                                $aadharFrontImage = $user->document->aadhar_front_img ?? 'default-placeholder.png';
                                $panImage = $user->document->pan_img ?? 'default-placeholder.png';
                                $accountImage = $user->document->account_img ?? 'default-placeholder.png';
                            ?>
                            <div class="row gutters-sm">
                                <div class="col-sm-3 mb-3">
                                    <div class="card h-100">
                                        <div class="card-body">
                                            <div class="d-flex flex-column align-items-center text-center">
                                                <a href="<?php echo e(asset('aadhar_front_image/' . $aadharFrontImage)); ?>">
                                                    <img src="<?php echo e(asset('aadhar_front_image/' . $aadharFrontImage)); ?>" alt="Front Image" width="250px">
                                                </a>
                                                <div class="mt-3">
                                                    <h5>Aadhar No.: <?php echo e($user->aadhar_no); ?></h5>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-3 mb-3">
                                    <div class="card h-100">
                                        <div class="card-body">
                                            <div class="d-flex flex-column align-items-center text-center">
                                                <a href="<?php echo e(asset('aadhar_back_image/' . $aadharBackImage)); ?>">
                                                    <img src="<?php echo e(asset('aadhar_back_image/' . $aadharBackImage)); ?>" alt="Back Image" width="250px">
                                                </a>
                                                <div class="mt-3">
                                                    <h5>Aadhar No.: <?php echo e($user->aadhar_no); ?></h5>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-3 mb-3">
                                    <div class="card h-100">
                                        <div class="card-body">
                                            <div class="d-flex flex-column align-items-center text-center">
                                                <a href="<?php echo e(asset('pan_image/' . $panImage)); ?>">
                                                    <img src="<?php echo e(asset('pan_image/' . $panImage)); ?>" alt="Pan Image
                                                    " width="250px">
                                                </a>
                                                <div class="mt-3">
                                                    <h5>Pan No.: <?php echo e($user->pan_no); ?></h5>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-3 mb-3">
                                    <div class="card h-100">
                                        <div class="card-body">
                                            <div class="d-flex flex-column align-items-center text-center">
                                                <a href="<?php echo e(asset('passbook_image/' . $accountImage)); ?>">
                                                    <img src="<?php echo e(asset('passbook_image/' . $accountImage)); ?>" alt="Account Image
                                                    " width="250px">
                                                </a>
                                                <div class="mt-3">
                                                    <h5>Account No.: <?php echo e($user->account->account_no); ?></h5>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <?php endif; ?>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $component = $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?><?php /**PATH /home/adxventure/lara_tms/resources/views/admin/user/profile-edit.blade.php ENDPATH**/ ?>