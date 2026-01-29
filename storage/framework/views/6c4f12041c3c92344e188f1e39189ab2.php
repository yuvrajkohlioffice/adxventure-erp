<?php if (isset($component)) { $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54 = $component; } ?>
<?php $component = App\View\Components\AppLayout::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('app-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(App\View\Components\AppLayout::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
    <?php $__env->startSection('title','Bank Details'); ?>
    <div class="pagetitle">
        <a style="float:right;" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">Add Bank
            Details</a>
        <h1>Bank Details</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?php echo e(url('/')); ?>">Home</a></li>
                <li class="breadcrumb-item active">Bank Details </li>
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
                        <table class="table table-striped">
                            <thead>
                                <tr class="bg-success text-white table-bordered text-center">
                                    <th scope="col">#</th>
                                    <th scope="col">Bank Name</th>
                                    <th scope="col">Account Holder Name</th>
                                    <th scope="col">Account No.</th>
                                    <th scope="col">Ifsce Code</th>
                                    <th scope="col">Gst</th>
                                    <th scope="col">Scanner Image</th>
                                    <th scope="col">Verify</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(count($banks) > 0): ?>
                                <?php $i=1 ?>
                                <?php $__currentLoopData = $banks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $bank): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr class="text-center" style="font-size:14px">
                                    <td scope="row"> <?php echo e($i++); ?>. </td>
                                    <td scope="row"><?php echo e($bank->bank_name); ?> </td>
                                    <td scope="row"><?php echo e($bank->holder_name); ?> </td>
                                    <td scope="row"><?php echo e($bank->account_no); ?> </td>
                                    <td scope="row"><?php echo e($bank->ifsc); ?> </td>
                                    <td scope="row">
                                        <?php if($bank->gst == 1): ?>
                                        yes
                                        <?php else: ?>
                                        No
                                        <?php endif; ?>
                                    </td>
                                    <td scope="row"><img src="<?php echo e($bank->scanner); ?>" alt="scanner" width="50px"></td>
                                    <td>
                                        <?php if($bank->verify == 1): ?>
                                        <button class="btn btn-sm btn-success">verified</button>
                                        <?php else: ?>
                                        <button class="btn btn-sm btn-warning" onclick="Verification('<?php echo e($bank->scanner); ?>', <?php echo e($bank->id); ?>)">Verify</button>
                                        <?php endif; ?>
                                    </td>
                                   
                                    <td scope="row">
                                        <button class="btn btn-sm btn-primary" data-bs-toggle="modal"
                                            data-bs-target="#EditModel<?php echo e($bank->id); ?>">
                                            Edit
                                        </button>
                                        <?php if($bank->status ==0): ?>
                                        <a href="<?php echo e(route('bank.status', ['id' => $bank->id,'status' => 1])); ?>"
                                            class="btn btn-sm btn-success delete-btn">
                                            Active
                                        </a>
                                        <?php else: ?>
                                        <a href="<?php echo e(route('bank.status', ['id' => $bank->id,'status' => 0])); ?>"
                                            class="btn btn-sm btn-danger delete-btn">
                                            In-Active
                                        </a>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <!-- Edit Model Start  -->
                                <div class="modal" id="EditModel<?php echo e($bank->id); ?>" tabindex="-1"
                                    aria-labelledby="exampleModalLabel" aria-hidden="true" data-bs-backdrop="static"
                                    data-bs-keyboard="false">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">Add Bank Details</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <form action="<?php echo e(route('banks.update',['bank'=>$bank->id])); ?>"
                                                    method="POST"  enctype="multipart/form-data">
                                                    <?php echo csrf_field(); ?>
                                                    <?php echo method_field('PUT'); ?>
                                                    <div class="mb-3">
                                                        <label for="exampleInputEmail1" class="form-label">Bank
                                                            Name</label>
                                                        <input type="text" class="form-control" name="bank_name"
                                                            placeholder="Enter Bank Name.."
                                                            value="<?php echo e(old('bank_name',$bank->bank_name)); ?>" required>
                                                        <small id="error-bank_name"
                                                            class="form-text error text-danger"><?php echo e($errors->first('bank_name')); ?></small>

                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="exampleInputEmail1" class="form-label">Account
                                                            Holder Name</label>
                                                        <input type="text" class="form-control"
                                                            name="account_holder_name"
                                                            placeholder="Enter Account Holder Name.."
                                                            value="<?php echo e(old('account_holder_name',$bank->holder_name)); ?>"
                                                            required>
                                                        <small id="error-account_holder_name"
                                                            class="form-text error text-danger"><?php echo e($errors->first('account_holder_name')); ?></small>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="exampleInputPassword1" class="form-label">Account
                                                            No.</label>
                                                        <input type="number" class="form-control" name="account_no"
                                                            id="exampleInputPassword1" placeholder="Enter Account No.."
                                                            value="<?php echo e(old('account_no',$bank->account_no)); ?>" required>
                                                        <small id="error-account_no"
                                                            class="form-text error text-danger"><?php echo e($errors->first('account_no')); ?></small>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="exampleInputPassword1" class="form-label">Ifsc
                                                            Code</label>
                                                        <input type="text" class="form-control" name="ifsc" id=""
                                                            placeholder="Enter Account Ifsc Code.."
                                                            value="<?php echo e(old('ifsc',$bank->ifsc)); ?>" required>
                                                        <small id="error-ifsc"
                                                            class="form-text error text-danger"><?php echo e($errors->first('ifsc')); ?></small>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="exampleInputPassword1" class="form-label">Scanner Image</label>
                                                        <input type="file" class="form-control" name="scanner" id=""
                                                            placeholder="Enter Account Branch Name.." value="<?php echo e(old('scanner')); ?>">
                                                        <small id="error-scanner"
                                                            class="form-text error text-danger"><?php echo e($errors->first('scanner')); ?></small>
                                                    </div>
                                                    <div class="mb-3">
                                                        <input type="radio" name="gst" value="1" <?php if($bank->gst == 1): ?>
                                                        checked <?php endif; ?> required>
                                                        <label for="exampleInputPassword1" class="form-label">With
                                                            Gst</label> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                        <input type="radio" name="gst" id="" value="0" <?php if($bank->gst ==
                                                        0): ?> checked <?php endif; ?> required>
                                                        <label for="exampleInputPassword1" class="form-label">Without
                                                            Gst</label>

                                                    </div>
                                                    <button type="submit" class="btn btn-primary">Update</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Edit Model End  -->
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php else: ?>
                                <tr>
                                    <td colspan="7" class="text-center">No Bank Details available</td>
                                </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Add bank Detail Model start  -->
    <div class="modal" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true"
        data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add Bank Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="<?php echo e(route('banks.store')); ?>" method="POST" enctype="multipart/form-data">
                        <?php echo csrf_field(); ?>
                        <div class="mb-3">
                            <label for="exampleInputEmail1" class="form-label">Bank Name</label>
                            <input type="text" class="form-control" name="bank_name" placeholder="Enter Bank Name.."
                                value="<?php echo e(old('bank_name')); ?>" required>
                            <small id="error-bank_name"
                                class="form-text error text-danger"><?php echo e($errors->first('bank_name')); ?></small>
                        </div>
                        <div class="mb-3">
                            <label for="exampleInputEmail1" class="form-label">Account Holder Name</label>
                            <input type="text" class="form-control" name="account_holder_name"
                                placeholder="Enter Account Holder Name.." value="<?php echo e(old('account_holder_name')); ?>"
                                required>
                            <small id="error-account_holder_name"
                                class="form-text error text-danger"><?php echo e($errors->first('account_holder_name')); ?></small>
                        </div>
                        <div class="mb-3">
                            <label for="exampleInputPassword1" class="form-label">Account No.</label>
                            <input type="number" class="form-control" name="account_no" id="exampleInputPassword1"
                                placeholder="Enter Account No.." value="<?php echo e(old('account_no')); ?>" required>
                            <small id="error-account_no"
                                class="form-text error text-danger"><?php echo e($errors->first('account_no')); ?></small>
                        </div>
                        <div class="mb-3">
                            <label for="exampleInputPassword1" class="form-label">Ifsc Code</label>
                            <input type="text" class="form-control" name="ifsc" id=""
                                placeholder="Enter Account Ifsc Code.." value="<?php echo e(old('ifsc')); ?>" required>
                            <small id="error-ifsc"
                                class="form-text error text-danger"><?php echo e($errors->first('ifsc')); ?></small>
                        </div>
                        <div class="mb-3">
                            <label for="exampleInputPassword1" class="form-label">Scanner Image</label>
                            <input type="file" class="form-control" name="scanner" id=""
                                placeholder="Enter Account Branch Name.." value="<?php echo e(old('scanner')); ?>" required>
                            <small id="error-scanner"
                                class="form-text error text-danger"><?php echo e($errors->first('scanner')); ?></small>
                        </div>
                        <div class="mb-3">
                            <input type="radio" name="gst" value="1">
                            <label for="exampleInputPassword1" class="form-label">With Gst</label>
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            <input type="radio" name="gst" id="" value="0">
                            <label for="exampleInputPassword1" class="form-label">Without Gst</label>
                        </div>
                        <button type="submit" class="btn btn-primary">Create</button>
                    </form>
                </div>
            </div>
        </div>
    </div>


<!-- Add Bank Detail Modal Start -->
<div class="modal" id="verification" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add Bank Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="modal-body-content" style="display: flex;flex-direction: column;gap: 30px;align-items: center;">
             
            </div>
        </div>
    </div>
</div>
<script>
    function Verification(image, id) {
        // Initialize the modal
        var modal = new bootstrap.Modal(document.getElementById('verification'));
        
        // Update the modal body with the image
        var modalBody = document.getElementById('modal-body-content');
        modalBody.innerHTML = ''; // Clear existing content
        
        // Create and set up the image
        var img = document.createElement('img');
        img.src = image; // Set the image source
        img.alt = 'scanner';
        img.width = 500; // Set the width
        
        // Create and set up the verification button
        var btn = document.createElement('a');
        btn.href = '<?php echo e(url('banks/verified')); ?>/' + id; // Concatenate the ID to the URL
        btn.className = "btn btn-success"; // Set the class
        btn.textContent = "Verified"; // Set the button text
        
        
        // Append the elements to the modal body
        modalBody.appendChild(img); // Append the image to the modal body
        modalBody.appendChild(btn); // Append the button to the modal body
        
        // Show the modal
        modal.show();
    }
</script>


    <!-- Add bank Detail Model End  -->
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $component = $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?><?php /**PATH /home/adxventure/lara_tms/resources/views/admin/bank/index.blade.php ENDPATH**/ ?>