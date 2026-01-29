<?php if (isset($component)) { $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54 = $component; } ?>
<?php $component = App\View\Components\AppLayout::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('app-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(App\View\Components\AppLayout::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
    <link rel="stylesheet" type="text/css" href="https://unpkg.com/trix@2.0.8/dist/trix.css">
    <script type="text/javascript" src="https://unpkg.com/trix@2.0.8/dist/trix.umd.min.js"></script>
    <div class="pagetitle">
        <a style="float:right;" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">Add Office</a>
        <h1>Office's</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?php echo e(url('/')); ?>">Home</a></li>
                <li class="breadcrumb-item active">Office's</li>
            </ol>
        </nav>
    </div>
    <?php echo $__env->make('include.alert', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="row m-2 p-2">
                    <div class="card-body">
                        <table class="table table-bordered text-center">
                            <thead class="text-dark ">
                                <tr>
                                    <th style="width:60px;">S.No</th>
                                    <th>Office Name</th>
                                    <th>Office Email</th>
                                    <th>Office Phone</th>
                                    <th>Zip code</th>
                                    <th>GST/Tax No.</th>
                                    <th>City</th>
                                    <th>state</th>
                                    <th>Country</th>
                                    <th>Address</th>
                                    <th style="width:90px;">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(count($offices) > 0): ?>
                                <?php $__currentLoopData = $offices; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $office): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <th><?php echo e(++$key); ?>.</th>
                                        <td><?php echo e($office->name); ?></td>
                                        <td><?php echo e($office->email); ?></td>
                                        <td><?php echo e($office->phone); ?></td>
                                        <td><?php echo e($office->zip_code ?? 'N/A'); ?> </td>
                                        <td><?php echo e($office->tax_no  ?? 'N/A'); ?></td>
                                        <td><?php echo e($office->city); ?></td>
                                        <td><?php echo e($office->state  ?? 'N/A'); ?></td>
                                        <td><?php echo e($office->country); ?></td>
                                        <td><?php echo e($office->address); ?></td>
                                        <td>
                                            <button class="btn btn-outline-success btn-sm" data-bs-toggle="modal" data-bs-target="#edit<?php echo e($office->id); ?>"><i class="bi bi-pencil-square"></i></button>
                                            <a href="<?php echo e(route('office.destroy', ['office' => $office->id])); ?>" class="btn btn-outline-danger btn-sm delete-btn"
                                                onclick="event.preventDefault(); deleteConfirmation('<?php echo e(route('office.destroy', ['office' => $office->id])); ?>');">
                                                <i class="bi bi-trash"></i>
                                            </a>
                                        </td>   
                                    </tr>
                                    <!-- Edit Templet Modal -->
                                    <div class="modal" id="edit<?php echo e($office->id); ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static" data-bs-keyboard="false">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content" style="top:100px">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLabel">Edit Template</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <form class="ajax-form" data-method="POST" data-action="<?php echo e(route('office.update',['office'=>$office->id])); ?>">
                                                        <?php echo csrf_field(); ?>
                                                        <?php echo method_field('PUT'); ?>
                                                        <div class="row">
                                                            <div class="col-6">
                                                                <label for="exampleInputEmail1" class="form-label">Office Name<span class="text-danger">*</span></label>
                                                                <input type="text" name="name" id=""  class="form-control" placeholder="Enter Office Name..." value="<?php echo e($office->name); ?>" required>  
                                                                <small id="error-name" class="form-text error text-danger"></small>
                                                            </div>
                                                            <div class="col-6">
                                                                <label for="exampleInputEmail1" class="form-label">Office Email<span class="text-danger">*</span></label>
                                                                <input type="text" name="email" id=""  class="form-control" placeholder="Enter Office Email..." value="<?php echo e($office->email); ?>" required>  
                                                                <small id="error-email" class="form-text error text-danger"></small>
                                                            </div>
                                                            <div class="col-6 mt-2">
                                                                <label for="exampleInputEmail1" class="form-label">Office Phone No.(without Country Code)<span class="text-danger">*</span></label>
                                                                <input type="text" name="phone" id=""  class="form-control" placeholder="Enter Office Email..." value="<?php echo e($office->phone); ?>" required>  
                                                                <small id="error-phone" class="form-text error text-danger"></small>
                                                            </div>
                                                            <div class="col-6 mt-2">
                                                                <label for="exampleInputEmail1" class="form-label">GST/Tax Registration No.</label>
                                                                <input type="text" name="tax_no" id="" class="form-control"  placeholder="Enter Office GST/Tax Registration No..." value="<?php echo e($office->tax_no); ?>">
                                                                <small id="error-tax_no" class="form-text error text-danger"></small> 
                                                            </div>
                                                            <div class="col-6 mt-2">
                                                                <label for="exampleInputEmail1" class="form-label">City<span class="text-danger">*</span></label>
                                                                <input type="text" name="city" id="" class="form-control"  placeholder="Enter City Name..." required value="<?php echo e($office->city); ?>">
                                                                <small id="error-city" class="form-text error text-danger"></small> 
                                                            </div>
                                                            <div class="col-6 mt-2">
                                                                <label for="exampleInputEmail1" class="form-label">Zip Code</label>
                                                                <input type="number" name="zip_code" id="" class="form-control"  placeholder="Enter Zip Code.." value="<?php echo e($office->zip_code); ?>">
                                                                <small id="error-zip_code" class="form-text error text-danger"></small> 
                                                            </div>
                                                            <div class="col-6 mt-2">
                                                                <label for="exampleInputEmail1" class="form-label">State</label>
                                                                <input type="text" name="state" id="" class="form-control"  placeholder="Enter STate Name..." value="<?php echo e($office->state); ?>">
                                                                <small id="error-state" class="form-text error text-danger"></small> 
                                                            </div>
                                                            <div class="col-6 mt-2">
                                                                <label for="exampleInputEmail1" class="form-label">Country<span class="text-danger">*</span></label>
                                                                <input type="text" name="country" id="" class="form-control"  placeholder="Enter Country Name..." required value="<?php echo e($office->country); ?>">
                                                                <small id="error-country" class="form-text error text-danger"></small> 
                                                            </div>
                                                            <div class="col-12 mt-2">
                                                                <label for="exampleInputEmail1" class="form-label">Address<span class="text-danger">*</span></label>
                                                                <input type="text" name="address" id="" class="form-control"  placeholder="Enter Address..." required value="<?php echo e($office->address); ?>">
                                                                <small id="error-address" class="form-text error text-danger"></small> 
                                                            </div>
                                                        </div>
                                                        <div class="mt-3">
                                                            <button type="submit" class="btn btn-primary">Add Office</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php else: ?>
                                <tr>
                                    <th class="text-center" colspan="9">Not Data Found</th>
                                </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Add MOdel  -->
    <div class="modal" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-lg">
            <div class="modal-content" style="top:100px">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add Office</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="ajax-form" data-method="POST" data-action="<?php echo e(url('office')); ?>">
                        <?php echo csrf_field(); ?>
                        <div class="row">
                            <div class="col-6">
                                <label for="exampleInputEmail1" class="form-label">Office Name<span class="text-danger">*</span></label>
                                <input type="text" name="name" id=""  class="form-control" placeholder="Enter Office Name..." required>  
                                <small id="error-name" class="form-text error text-danger"></small>
                            </div>
                            <div class="col-6">
                                <label for="exampleInputEmail1" class="form-label">Office Email<span class="text-danger">*</span></label>
                                <input type="text" name="email" id=""  class="form-control" placeholder="Enter Office Email..." required>  
                                <small id="error-email" class="form-text error text-danger"></small>
                            </div>
                            <div class="col-6 mt-2">
                                <label for="exampleInputEmail1" class="form-label">Office Phone No.(without Country Code)<span class="text-danger">*</span></label>
                                <input type="text" name="phone" id=""  class="form-control" placeholder="Enter Office Email..." required>  
                                <small id="error-phone" class="form-text error text-danger"></small>
                            </div>
                            <div class="col-6 mt-2">
                                <label for="exampleInputEmail1" class="form-label">GST/Tax Registration No.</label>
                                <input type="text" name="tax_no" id="" class="form-control"  placeholder="Enter Office GST/Tax Registration No...">
                                <small id="error-tax_no" class="form-text error text-danger"></small> 
                            </div>
                            <div class="col-6 mt-2">
                                <label for="exampleInputEmail1" class="form-label">City<span class="text-danger">*</span></label>
                                <input type="text" name="city" id="" class="form-control"  placeholder="Enter City Name..." required>
                                <small id="error-city" class="form-text error text-danger"></small> 
                            </div>
                            <div class="col-6 mt-2">
                                <label for="exampleInputEmail1" class="form-label">Zip Code</label>
                                <input type="number" name="zip_code" id="" class="form-control"  placeholder="Enter Zip Code..">
                                <small id="error-zip_code" class="form-text error text-danger"></small> 
                            </div>
                            <div class="col-6 mt-2">
                                <label for="exampleInputEmail1" class="form-label">State</label>
                                <input type="text" name="state" id="" class="form-control"  placeholder="Enter STate Name...">
                                <small id="error-state" class="form-text error text-danger"></small> 
                            </div>
                            <div class="col-6 mt-2">
                                <label for="exampleInputEmail1" class="form-label">Country<span class="text-danger">*</span></label>
                                <input type="text" name="country" id="" class="form-control"  placeholder="Enter Country Name..." required>
                                <small id="error-country" class="form-text error text-danger"></small> 
                            </div>
                            <div class="col-12 mt-2">
                                <label for="exampleInputEmail1" class="form-label">Address<span class="text-danger">*</span></label>
                                <input type="text" name="address" id="" class="form-control"  placeholder="Enter Address..." required>
                                <small id="error-address" class="form-text error text-danger"></small> 
                            </div>
                        </div>
                        <div class="mt-3">
                            <button type="submit" class="btn btn-primary">Add Office</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script>
        function deleteConfirmation(deleteUrl) {
            swal({
                title: "Are you sure?",
                text: "Once deleted, you will not be able to recover this Office!",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            })
            .then((willDelete) => {
                if (willDelete) {
                    window.location.href = deleteUrl;
                } else {
                    swal("Your Office is safe!", {
                        icon: "info",
                    });
                }
            });
        }
    </script>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $component = $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?><?php /**PATH /home/adxventure/lara_tms/resources/views/admin/office/index.blade.php ENDPATH**/ ?>