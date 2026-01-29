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
    <?php $__env->startSection('title','Api Details'); ?>
    <style>
        .custom-switch {
            position: relative;
            display: inline-block;
            width: 46px;
            height: 26px;
        }
        .custom-switch {
            position: relative;
            display: inline-block;
            width: 46px;
            height: 26px;
        }

        .custom-switch input:checked + .custom-slider {
            background-color: #007bff;
        }
        .custom-switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .custom-slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ccc;
            transition: .4s;
            border-radius: 34px;
        }
        .custom-switch input:checked + .custom-slider:before {
            transform: translateX(20px);
        }
        .custom-slider:before {
            position: absolute;
            content: "";
            height: 20px;
            width: 20px;
            left: 3px;
            bottom: 3px;
            background-color: white;
            transition: .4s;
            border-radius: 50%;
        }
    </style>
    <div class="pagetitle">
        <!-- <a style="float:right;" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">Add Apis</a> -->
        <h1>Apis</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?php echo e(url('/')); ?>">Home</a></li>
                <li class="breadcrumb-item active">Api Details </li>
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
                                <tr class="bg-success text-white table-bordered ">
                                    <th scope="col">#</th>
                                    <th scope="col">Name</th>
                                    <th scope="col">Api Url</th>
                                    <th scope="col">Api key</th>
                                    <th scope="col">Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(isset($apis)): ?>
                                <?php $__empty_1 = true; $__currentLoopData = $apis; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $api): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <tr>
                                        <td><?php echo e($index +1); ?></td>
                                       <td><?php echo e($api->name); ?></td>
                                       <td><?php echo e($api->url); ?></td>
                                       <td><?php echo e($api->key); ?></td>
                                       <td>
                                            <label class="custom-switch">
                                                <input type="checkbox" class="status_toggle" checked="" data-url="https://newcrm.dsom.in/company-check-status/1177" onclick="confirmStatusChange(this, 'Deactivate this company?')">
                                                <span class="custom-slider"></span>
                                            </label>
                                       </td>
                                       <td>
                                            <button class="btn btn-sm btn-warning" onclick="EditApi(<?php echo e($api->id); ?>,'<?php echo e($api->name); ?>','<?php echo e($api->url); ?>','<?php echo e($api->key); ?>')"><i class="bi bi-pencil-fill"></i></button>
                                            <button class="btn btn-sm btn-danger"><i class="bi bi-trash-fill"></i></button>
                                       </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <tr>
                                        Api Not Found
                                    </tr>
                                <?php endif; ?>
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
                    <h5 class="modal-title" id="exampleModalLabel">Add Api Key</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="ajax-form" data-action="<?php echo e(route('api-settings.store')); ?>" data-method="POST" enctype="multipart/form-data">
                        <?php echo csrf_field(); ?>
                        <div class="mb-3">
                            <label for="api_name" class="form-label">Api Name</label>
                            <input type="text" class="form-control"  id="api_name" name="api_name" placeholder="Enter Api Name.." value="<?php echo e(old('api_name')); ?>">
                            <small id="error-api_name" class="form-text error text-danger"><?php echo e($errors->first('api_name')); ?></small>
                        </div>
                        <div class="mb-3">
                            <label for="api_url" class="form-label">Api Url</label>
                            <input type="text" class="form-control" id="api_url" name="api_url" placeholder="Enter Api.." value="<?php echo e(old('api_url')); ?>">
                            <small id="error-api_url" class="form-text error text-danger"><?php echo e($errors->first('api_url')); ?></small>
                        </div>
                        <div class="mb-3">
                            <label for="api_key" class="form-label">Api Key </label>
                            <input type="text" class="form-control" name="api_key" id="api_key" placeholder="Enter Api Key.." value="<?php echo e(old('api_key')); ?>" >
                            <small id="error-api_key" class="form-text error text-danger"><?php echo e($errors->first('account_no')); ?></small>
                        </div>
                        <button type="submit" class="btn btn-primary">Add Api</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Model start  -->
    <div class="modal" id="editModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true"
        data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-lg modal-centerd">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">EditApi Api Key</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form class="ajax-form" data-action="<?php echo e(route('api-settings.store')); ?>" data-method="POST" enctype="multipart/form-data">
                        <?php echo csrf_field(); ?>
                        <div class="mb-3">
                            <label for="edit_api_name" class="form-label">Api Name</label>
                            <input type="text" class="form-control"  id="edit_api_name" name="api_name" placeholder="Enter Api Name.." value="<?php echo e(old('api_name')); ?>">
                            <small id="error-edit_api_name" class="form-text error text-danger"><?php echo e($errors->first('api_name')); ?></small>
                        </div>
                        <div class="mb-3">
                            <label for="edit_api_url" class="form-label">Api Url</label>
                            <input type="text" class="form-control" id="edit_api_url" name="api_url" placeholder="Enter Api.." value="<?php echo e(old('api_url')); ?>">
                            <small id="error-edit_api_url" class="form-text error text-danger"><?php echo e($errors->first('api_url')); ?></small>
                        </div>
                        <div class="mb-3">
                            <label for="edit_api_key" class="form-label">Api Key </label>
                            <input type="text" class="form-control" name="api_key" id="edit_api_key" placeholder="Enter Api Key.." value="<?php echo e(old('api_key')); ?>" >
                            <small id="error-edit_api_key" class="form-text error text-danger"><?php echo e($errors->first('account_no')); ?></small>
                        </div>
                        <button type="submit" class="btn btn-primary">Update Api</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

<script>
    function EditApi(id,name,url,key){
        $('#edit_api_name').val(name);
        $('#edit_api_url').val(url);
        $('#edit_api_key').val(key);

        let formAction = `https://tms.adxventure.com/api-settings/${id}`;
        $('.ajax-form').attr('data-action', formAction);
        $('.ajax-form').append('<input type="hidden" name="_method" value="PUT">');
        $('#editModal').show();
    }

    function Verification(image, id) {
        var modal = new bootstrap.Modal(document.getElementById('verification'));
        
        var modalBody = document.getElementById('modal-body-content');
        modalBody.innerHTML = '';
        
        var img = document.createElement('img');
        img.src = image; 
        img.alt = 'scanner';
        img.width = 500;
        
        var btn = document.createElement('a');
        btn.href = '<?php echo e(url('banks/verified')); ?>/' + id; 
        btn.className = "btn btn-success";
        btn.textContent = "Verified"; 
        
        
        modalBody.appendChild(img);
        modalBody.appendChild(btn);
        modal.show();
    }
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
<?php endif; ?><?php /**PATH /home/adxventure/lara_tms/resources/views/admin/settings/api/index.blade.php ENDPATH**/ ?>