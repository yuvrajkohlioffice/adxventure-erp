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

                <li class="breadcrumb-item"><a href="<?php echo e(url('/')); ?>">Users</a></li>

                <li class="breadcrumb-item active">Update User</li>

            </ol>

        </nav>

    </div><!-- End Page Title -->



    <section class="section">

        <div class="row">

            <div class="col-lg-12">

                <div class="card">

                    <div class="card-body p-4">



                        <form autocomplete="off" data-method="POST" data-action="<?php echo e(route('user.update',$data->id)); ?>" id="ajax-form" enctype="multipart/form-data">



                        <?php echo csrf_field(); ?>

                        

                            <div class="form-group">

                                <label for="exampleInputEmail1">Profile Image : </label><br>

                                <img src="<?php echo e($data->image); ?>" width="250px" ><br>

                                <input type="file" class="form-control" id="exampleInputEmail1" name="profile_image"  >

                                <small id="error-profile_image" class="form-text error text-muted"></small>

                            </div>

                        



                            <div class="form-group">

                                <label for="exampleInputEmail1">Name</label>

                                <input type="text" class="form-control" id="exampleInputEmail1" name="name" value="<?php echo e($data->name); ?>"  placeholder="Enter name..">

                                <small id="error-name" class="form-text error text-muted"></small>

                            </div>



                            <div class="form-group">

                                <label for="exampleInputEmail1">Date of Joining</label>

                                <input type="date" class="form-control" id="exampleInputEmail1" name="date_of_joining" value="<?php echo e($data->date_of_joining); ?>"  placeholder="Enter date of joining..">

                                <small id="error-date-of-joining" class="form-text error text-muted"></small>

                            </div>



                            <div class="form-group">

                                <label for="exampleInputEmail1">Role</label>

                                <select class="form-control" name="designation" >

                                    <option>SELECT</option>

                                    <?php if(count($designation) > 0): ?>

                                        <?php $__currentLoopData = $designation; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $des): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                                            <option value="<?php echo e($des->id); ?>" <?php if($data->role_id == $des->id): ?> selected <?php endif; ?> > <?php echo e($des->name); ?></option>

                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                    <?php endif; ?>

                                </select>

                                <small id="error-designation" class="form-text error text-muted"></small>

                            </div>



                            <div class="form-group">

                                <label for="exampleInputEmail2">Email address</label>

                                <input type="email" class="form-control" id="exampleInputEmail2" name="email" value="<?php echo e($data->email); ?>" placeholder="Enter email..">

                                <small id="error-email" class="form-text error text-muted"></small>

                            </div>



                            <div class="form-group">

                                <label for="exampleInputEmail2">Phone No.</label>

                                <input type="number" class="form-control" id="exampleInputEmail2" name="phone_no" value="<?php echo e($data->phone_no); ?>" placeholder="Enter phone no...">

                                <small id="error-phone_no" class="form-text error text-muted"></small>

                            </div>



                            <div class="form-group">

                                <label for="exampleInputEmail2">Skills</label>

                                <input type="text" class="form-control" id="exampleInputEmail2" name="skills" value="<?php echo e($data->skills); ?>" placeholder="Enter skills with comma eg. (PHP,CSS, etc)">

                                <small id="error-skills" class="form-text error text-muted"></small>

                            </div>



                            <!-- <div class="form-group">

                                <label for="exampleInputEmail2">Salary (Note: Monthly)</label>

                                <input type="number" class="form-control" id="exampleInputEmail2" name="salary" value="<?php echo e($data->salary); ?>" placeholder="Enter salary...">

                                <small id="error-salary" class="form-text error text-muted"></small>

                            </div> -->



                            <div class="form-group">

                                <label for="exampleInputPassword1">Password</label>

                                <input type="password" class="form-control" name="password" id="exampleInputPassword1" placeholder="Password">

                                <small id="error-password" class="form-text error text-muted"></small>



                            </div>

                

                            <button id="submit-btn"  type="submit" class="btn btn-primary">

                            <span class="loader" id="loader" style="display: none;"></span> 

                            Update User</button>

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
<?php endif; ?><?php /**PATH /home/adxventure/lara_tms/resources/views/admin/employee/edit.blade.php ENDPATH**/ ?>