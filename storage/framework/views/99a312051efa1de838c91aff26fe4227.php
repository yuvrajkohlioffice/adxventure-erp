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
        <a style="float:right;" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">Create Template</a>
        <h1> Templates </h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?php echo e(url('/')); ?>">Home</a></li>
                <li class="breadcrumb-item active">Templates</li>
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
                            <thead class="text-dark  ">
                                <tr>
                                    <th style="width:60px;">S.No</th>
                                    <th>Title</th>
                                    <th style="width:70vw;">Message</th>
                                    <th>Category</th>
                                    <th>Type</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(count($templets) > 0): ?>
                                <?php $__currentLoopData = $templets; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k => $templet): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <th><?php echo e(++$k); ?>.</th>
                                        <td><?php echo e($templet->title); ?></td>
                                        <td class="text-left"><?php echo $templet->message; ?></td>
                                        <td>
                                            <?php if($templet->category === 'project'): ?>
                                                <span class="badge bg-primary">Project</span>
                                            <?php elseif($templet->category === 'invoice'): ?>
                                                <span class="badge bg-warning">Invoice</span>
                                            <?php elseif($templet->category === 'lead'): ?>
                                                <span class="badge bg-info">Lead</span>
                                            <?php else: ?>
                                                <span class="badge bg-success">Common</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if($templet->type == 1): ?>
                                                <span class="badge bg-success">Email</span>
                                            <?php elseif($templet->type == 2): ?>
                                                <span class="badge bg-primary">SMS</span>
                                            <?php elseif($templet->type == 3): ?>
                                                <span class="badge bg-warning">WhatsApp</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <button class="btn btn-outline-success btn-sm" data-bs-toggle="modal" data-bs-target="#edit<?php echo e($templet->id); ?>"><i class="bi bi-pencil-square"></i></button>
                                            <a href="<?php echo e(route('templet.delete', ['id' => $templet->id])); ?>" class="btn btn-outline-danger btn-sm delete-btn"
                                                onclick="event.preventDefault(); deleteConfirmation('<?php echo e(route('templet.delete', ['id' => $templet->id])); ?>');">
                                                <i class="bi bi-trash"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    <!-- Edit Templet Modal -->
                                    <div class="modal" id="edit<?php echo e($templet->id); ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static" data-bs-keyboard="false">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content" style="top:100px">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLabel">Edit Template</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <form class="ajax-form" data-method="POST" data-action="<?php echo e(route('templet.update',['id'=>$templet->id])); ?>">
                                                        <?php echo csrf_field(); ?>
                                                        <div class="mb-3">
                                                            <label for="exampleInputEmail1" class="form-label">Select Category</label>
                                                            <select class="form-control" name="category" onchange="Category(this.value)">
                                                                <option disabled <?php echo e($templet->category === null ? 'selected' : ''); ?>>Choose Category..</option>
                                                                <option value="project" <?php echo e($templet->category === 'project' ? 'selected' : ''); ?>>Project</option>
                                                                <option value="common" <?php echo e($templet->category === 'common' ? 'selected' : ''); ?>>Common</option>
                                                            </select>
                                                            <small id="error-category" class="form-text error text-danger"></small>
                                                        </div>
                                                        <div class="category-container">
                                                            <?php if($templet->category === 'project'): ?>
                                                                <div class="mb-3">
                                                                    <label for="categorySelect" class="form-label">Select Project</label>
                                                                    <select class="form-control" name="project" id="categorySelect">
                                                                        <option value="" selected>Select Project</option>
                                                                        <?php $__currentLoopData = $projects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $project): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                            <option value="<?php echo e($project->id); ?>" <?php echo e($templet->project_id == $project->id ? 'selected' : ''); ?>><?php echo e($project->name); ?></option>
                                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                                    </select>
                                                                    <small id="error-project" class="form-text error text-danger"></small>
                                                                </div>
                                                            <?php endif; ?>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="exampleInputEmail1" class="form-label">Title</label>
                                                            <input type="text" name="title" class="form-control" placeholder="Enter Title.." value="<?php echo e($templet->title); ?>">   
                                                            <small id="error-title" class="form-text error text-danger"></small>                             
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="exampleInputEmail1" class="form-label">Select Type</label>
                                                            <select class="form-control" name="type">
                                                                <option disabled <?php echo e($templet->type === null ? 'selected' : ''); ?>>Choose Type..</option>
                                                                <option value="1" <?php echo e($templet->type == 1 ? 'selected' : ''); ?>>Email</option>
                                                                <option value="2" <?php echo e($templet->type == 2 ? 'selected' : ''); ?>>SMS</option>
                                                                <option value="3" <?php echo e($templet->type == 3 ? 'selected' : ''); ?>>WhatsApp</option>
                                                            </select>
                                                            <small id="error-type" class="form-text error text-danger"></small>
                                                        </div>
                                                        <div class="mt-3">
                                                            <label for="exampleInputPassword1">Message<span class="text-danger">*</span></label>
                                                            <textarea class="form-control" rows="7" name="description" ><?php echo $templet->message; ?></textarea>
                                                            <small id="error-description" class="form-text error text-danger"></small>
                                                        </div>
                                                        <div class="mt-3">
                                                            <button type="submit" class="btn btn-primary">Update</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php else: ?>
                                <tr>
                                    <th class="text-center" colspan="6">Not Data Found</th>
                                </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Add Model  -->
    <div class="modal" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-lg">
            <div class="modal-content" style="top:100px">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Create Template</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="ajax-form" data-method="POST" data-action="<?php echo e(route('templet.store')); ?>">
                        <?php echo csrf_field(); ?>
                        <div class="mb-3">
                            <label for="exampleInputEmail1" class="form-label">Select Category</label>
                            <select class="form-control" name="category" onchange="Category(this.value)">
                                <option selected disabled>Choose Category..</option>
                                <option value="common">Common</option>
                                <option value="invoice">Invoice</option>
                                <option value="lead">Lead</option>
                                <option value="project">Project</option>
                            </select>
                            <small id="error-category" class="form-text error text-danger"></small>
                        </div>
                        <div class="category-container"></div>
                        <div class="mb-3">
                            <label for="exampleInputEmail1" class="form-label">Select Type</label>
                            <select class="form-control" name="type">
                                <option selected disabled>Choose Type..</option>
                                <option value="1">Email</option>
                                <option value="2">sms</option>
                                <option value="3">Whatshapp</option>
                            </select>
                            <small id="error-type" class="form-text error text-danger"></small>
                        </div>
                        <div class="mb-3">
                            <label for="exampleInputEmail1" class="form-label">Title</label>
                            <input type="text" name="title" id=""  class="form-control" placeholder="Enter Title..">   
                            <small id="error-title" class="form-text error text-danger"></small>                             
                        </div>
                        <div class="mt-3">
                            <label for="exampleInputPassword1">Message<span class="text-danger">*<span></label>
                            <input id="x" type="hidden" name="description">
                            <trix-editor input="x" cols="4"></trix-editor>
                            <small id="error-description" class="form-text error text-danger"></small>
                        </div>
                        <div class="mt-3">
                            <button type="submit" class="btn btn-primary">Create</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script>
        var token = $('meta[name="csrf-token"]').attr('content');
        function Category(value) {
            if (value === 'project') {
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': token
                    },
                    url: "<?php echo e(route('templet.category')); ?>",
                    type: 'POST',
                    data: { category: value },
                    success: function(response) {
                        console.log(response);
                        
                        // Clear previous content
                        $('.category-container').html('');

                        // Generate category selection options
                        var selectHtml = `
                            <div class="mb-3">
                                <label for="categorySelect" class="form-label">Select Category</label>
                                <select class="form-control" name="project" id="categorySelect">
                                    <option value="" selected>Select Project</option>
                                    ${response.projects.map(project => 
                                        `<option value="${project.id}" ${(response.templetProjectId && response.templetProjectId == project.id) ? 'selected' : ''}>${project.name}</option>`
                                    ).join('')}
                                </select>
                                <small id="error-project" class="form-text error text-danger"></small>
                            </div>
                        `;

                        // Insert the HTML into the container and display it
                        $('.category-container').html(selectHtml).show();
                    },
                    error: function(err) {
                        console.error('Error:', err);
                    }
                });
            } else {
                $('.category-container').hide();
            }
        }
    </script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script>
        function deleteConfirmation(deleteUrl) {
            swal({
                title: "Are you sure?",
                text: "Once deleted, you will not be able to recover this Template!",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            })
            .then((willDelete) => {
                if (willDelete) {
                    window.location.href = deleteUrl;
                } else {
                    swal("Your Template is safe!", {
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
<?php endif; ?><?php /**PATH /home/adxventure/lara_tms/resources/views/admin/templets/index.blade.php ENDPATH**/ ?>