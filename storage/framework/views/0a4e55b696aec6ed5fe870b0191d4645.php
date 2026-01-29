<?php if (isset($component)) { $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54 = $component; } ?>
<?php $component = App\View\Components\AppLayout::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('app-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(App\View\Components\AppLayout::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
    <div class="pagetitle"> 
        <h1>Profile Verification</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?php echo e(url('/')); ?>">Home</a></li>
                <li class="breadcrumb-item active">Verification</li>
            </ol>
        </nav>
    </div>
    <?php echo $__env->make('include.alert', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <br>
                        <form action="" methos="GET">
                            <div class="row">
                                <div class="col-md-6">
                                      <div class="form-group">
                                        <input type="text" class="form-control" name="name" placeholder="Enter task name..." value="<?php echo e(request()->name); ?>" />
                                     </div>
                                </div>
                                  <div class="col-md-4">
                                      <div class="form-group">
                                          <select  class="form-control"  name="status">
                                              <option value="">SELECT</option>
                                              <option value="0"  <?php if(request()->status == 0): ?> selected <?php endif; ?>>Working</option>
                                              <option value="1" <?php if(request()->status == 1): ?> selected <?php endif; ?>>Hold</option>
                                          </select>
                                     </div>
                                </div>
                                <div style="padding-top:10px;" class="col-md-2">
                                     
                                    <button  class="btn btn-success">Filter</button>
                                    <a href="<?php echo e(url('project/task/'.($project->id ?? 0))); ?>" class="btn btn-danger">Reset</a>
                                </div>
                            </div>
                        </form>
                        <br>
                        <!-- Default Table -->
                        <table class="table">
                            <thead>
                                <tr class="bg-light">
                                    <th scope="col">#</th>
                                    <th scope="col">Employee</th>
                                    <th scope="col">Date of Birth</th>
                                    <th scope="col">Date of joining</th>
                                    <th scope="col">Aadhar Card</th>
                                    <th scope="col">Pan Card</th>
                                    <th scope="col">Passbook</th>
                                    <th scope="col">Account Details</th>
                                    <th scope="col">Documents</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <th scope="row"><?php echo e($loop->iteration); ?></th>
                                    <td><?php echo e($user->name); ?> <small>(<?php echo e($user->roles->pluck('name')->implode(', ')); ?>)</small><br>
                                        <small><?php echo e($user->email); ?><br><?php echo e($user->phone_no); ?></small>    
                                    </td>
                                    <td><?php echo e($user->date_of_birth); ?></td>
                                    <td><?php echo e($user->date_of_joining); ?></td>
                                    <td><?php echo e($user->aadhar_no); ?></td>
                                    <td><?php echo e($user->pan_no); ?></td>
                                    <td><?php echo e($user->account->account_no ?? 0); ?></td>
                                    <td>
                                        <small>
                                            <?php echo e($user->account->account_holder_name ?? 0); ?><br>    
                                            <?php echo e($user->account->bank_name ?? 0); ?><br>
                                            <?php echo e($user->account->ifsc ?? 0); ?>

                                        </small>
                                    </td>
                                    <td><button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#docs">View Docs</button></td>
                                    <td>
                                        <?php if($user->verificatio != 1): ?>
                                        <a href="<?php echo e(route('profile.verify',['id'=>$user->id,'status'=>2])); ?>" class="btn btn-sm btn-primary"
                                        onclick="return confirm('Are you sure you want to verify this user?')">Verify</a
                                        <?php else: ?>
                                        <a href="" class="btn btn-sm"
                                        onclick="return confirm('Are you sure you want to unverify this user?')">Unverify
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                        <?php echo e($users->links()); ?>

                        <!-- End Default Table Example -->
                    </div>
                </div>
            </div>
        </div>
    </section>
     <!-- View Docs -->
     <div class="modal" id="docs" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content" style="top:50px;right:150px;width:1200px;background:#f2f2f2;height: 110vh;">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Docments</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="font-size:20px"></button>
                </div>
                <div class="modal-body" style="overflow: scroll;">
                    <?php
                        $aadharBackImage = $user->document->aadhar_back_img ?? 'default-placeholder.png';
                        $aadharFrontImage = $user->document->aadhar_front_img ?? 'default-placeholder.png';
                        $panImage = $user->document->pan_img ?? 'default-placeholder.png';
                        $accountImage = $user->document->account_img ?? 'default-placeholder.png';
                    ?>
                    <div class="row">
                        <div class="col-md-6">
                            <h5>Aadhar Card Front Image</h5>
                            <div style="border: 1px solid;border-radius: 10px;"  class="text-center">
                                <a href="<?php echo e(asset('aadhar_front_image/' . $aadharFrontImage)); ?>"  >
                                    <img src="<?php echo e(asset('aadhar_front_image/' . $aadharFrontImage)); ?>" alt="Front Image" width="100%" style="padding: 3px;border-radius: 10px;">
                                </a>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h5>Aadhar Card Back Image</h5>
                            <div style="border: 1px solid;border-radius: 10px;"  class="text-center">
                                <a href="<?php echo e(asset('aadhar_back_image/' . $aadharBackImage)); ?>">
                                    <img src="<?php echo e(asset('aadhar_back_image/' . $aadharBackImage)); ?>" alt="Back Image" width="100%" style="padding: 3px;border-radius: 10px;">
                                </a>
                            </div>
                        </div>
                        <div class="col-md-6 mt-3">
                            <h5>Pan Image</h5>
                            <div style="border: 1px solid;border-radius: 10px;"   class="text-center">
                                <a href="<?php echo e(asset('pan_image/' . $panImage)); ?>">
                                    <img src="<?php echo e(asset('pan_image/' . $panImage)); ?>" alt="Pan Image" width="100%" style="padding: 3px;border-radius: 10px;">
                                </a>
                            </div>
                        </div>
                        <div class="col-md-6 mt-3">
                            <h5>Passbook Image</h5>
                            <div style="border: 1px solid;border-radius: 10px;"  class="text-center">
                                <a href="<?php echo e(asset('passbook_image/' . $accountImage)); ?>">
                                    <img src="<?php echo e(asset('passbook_image/' . $accountImage)); ?>" alt="Account Image" width="100%" style="padding: 3px;border-radius: 10px;">
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $component = $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>

<?php /**PATH /home/adxventure/lara_tms/resources/views/admin/user/profile.blade.php ENDPATH**/ ?>