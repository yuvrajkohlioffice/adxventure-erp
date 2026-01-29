<?php if (isset($component)) { $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54 = $component; } ?>
<?php $component = App\View\Components\AppLayout::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('app-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(App\View\Components\AppLayout::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
    <?php $__env->startSection('title','Create-Project'); ?>
    <style>
        .col-6 mt-3{
            margin-top:10px;
            margin-bottom:10px;
        }
        label{
            font-weight:600;
        }        
    </style>
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>" />
    <link rel="stylesheet" type="text/css" href="https://unpkg.com/trix@2.0.8/dist/trix.css">
    <script type="text/javascript" src="https://unpkg.com/trix@2.0.8/dist/trix.umd.min.js"></script>

   <div class="pagetitle">
        <h1>Create Projects</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?php echo e(url('/')); ?>">Home</a></li>
                <li class="breadcrumb-item active">Create Project</li>
            </ol>
        </nav>
    </div>
    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body p-4">
                        <h6>
                            <strong>
                                <?php echo e(strtoupper($invoice->lead->name ?? $invoice->client->name)); ?> 
                                (<?php echo e($invoice->lead->email ?? $invoice->client->email ?? 'Email not available'); ?>)
                            </strong>
                        </h6>
                        <small>
                            <strong>
                                Service: 
                                <?php if($invoice->services->isNotEmpty()): ?>
                                    <?php $__currentLoopData = $invoice->services; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $service): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php echo e($service->work_name); ?>,
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php endif; ?>  
                            </strong>
                        </small>
                        <form autocomplete="off" data-method="POST" data-action="<?php echo e(route('project.store')); ?>" id="ajax-form" enctype="multipart/form-data">
                            <?php echo csrf_field(); ?>
                            <input type="hidden" name="invoice_id" value="<?php echo e($invoice->id); ?>">
                            <div class="row">
                                <div class="col-6 mt-3">
                                    <label for="exampleInputEmail1">Project Logo</label>
                                    <input type="file" class="form-control" id="exampleInputEmail1" name="logo"  >
                                    <small id="error-logo" class="form-text error text-danger"></small>
                                </div>
                                <div class="col-6 mt-3">
                                    <label for="exampleInputEmail1">Company/Project Name <span class="text-danger">*<span></label>
                                    <input type="text" class="form-control" id="exampleInputEmail1" name="name" value="<?php echo e(old('name')); ?>"  placeholder="Enter company/Project name..">
                                    <small id="error-name" class="form-text error text-danger"></small>
                                </div>
                                <div class="col-6 mt-3">
                                    <label for="exampleInputcontactPersonName1">Contact Person Name <span class="text-danger">*<span></label>
                                    <input type="text" class="form-control" id="exampleInputcontactPersonName1" name="contact_person_name" value="<?php echo e(old('contact_person_name')); ?>"  placeholder="Enter Contact Person name..">
                                    <small id="error-contactPersonName" class="form-text error text-danger"></small>
                                </div>
                                <div class="col-6 mt-3">
                                    <label for="exampleInputEmail1">Contact Person Mobile <span class="text-danger">*<span></label>
                                    <input type="number" class="form-control" id="exampleInputEmail1" name="contact_person_mobile" value="<?php echo e(old('contact_person_mobile')); ?>"  placeholder="Enter Contact Person Mobile..">
                                    <small id="error-companyname" class="form-text error text-danger"></small>
                                </div>
                                
                                
                                 
                                
                                 
                                
                                
                                <div class="col-6 mt-3">
                                    <label for="exampleInputEmail2">Website</label>
                                    <input type="text" class="form-control" id="exampleInputEmail2" name="Website" placeholder="Enter Website Url...">
                                    <small id="error-website" class="form-text error text-danger"></small>
                                </div>
                                <div class="col-6 mt-3">
                                    <label for="exampleInputsocialmedia2">Social Media</label>
                                    <input type="text" class="form-control" id="exampleInputsocialmedia2" name="social_media" placeholder="Enter Social Media Url...">
                                    <small id="error-socialMedia" class="form-text error text-danger"></small>
                                </div>
                                <div class="col-6 mt-3">
                                    <label for="exampleInputsocialmedia2">Assign Project(Project Manager)<span class="text-danger">*<span></label>
                                    <select name="project_manager" class="form-control" >
                                        <option selected disabled>Select Project Manager</option>
                                        <?php if(isset($projectManagers)): ?>
                                            <?php $__currentLoopData = $projectManagers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $client): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($client->id); ?>" ><?php echo e($client->name); ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        <?php endif; ?>
                                    </select>
                                    <small id="error-project_manager" class="form-text error text-danger"></small>
                                </div>
                                    <!-- <div class="col-6 mt-3">
                                        <label for="">Billing Date<span class="text-danger">*<span></label>
                                        <input type="date" class="form-control" name="billing_date" placeholder="Select Billing Date">
                                        <small id="error-billing_date" class="form-text error text-danger"></small>
                                    </div> -->
                                <div class="col-12 mt-3">
                                    <label for="exampleInputPassword1">Job Description<span class="text-danger">*<span></label>
                                    <input id="x" type="hidden" name="description">
                                    <trix-editor input="x" cols="4"></trix-editor>
                                    <!--<textarea class="form-control" rows="7" name="description" ></textarea>-->
                                    <small id="error-description" class="form-text error text-danger"></small>
                                </div>
                                <div class="col-3 mt-3" style="float:right">
                                    <button id="submit-btn"  type="submit" class="btn btn-primary">
                                    <span class="loader" id="loader" style="display: none;"></span> 
                                    Add Project </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <script>
        $(document).ready(function(){
            $( '.select-2-multiple' ).select2( {
                theme: 'bootstrap-5'
            } );
        });
    </script>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $component = $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?><?php /**PATH /home/adxventure/lara_tms/resources/views/admin/projects/create.blade.php ENDPATH**/ ?>