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
    <?php $__env->startSection('title','Service'); ?>
    <style>
        .col-3{
            float:right;
        }
    </style>
    <div class="pagetitle">
        <button style="float:right;" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#AddModel" > Create Service</button>
        <h1>Service</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?php echo e(url('/')); ?>">Home</a></li>
                <li class="breadcrumb-item active">Service</li> 
            </ol>
        </nav>
    </div>
    <?php echo $__env->make('include.alert', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="col-3">
                            <input type="text" id="searchInput" placeholder="Search for categories..." class="form-control mt-3 mb-3">
                        </div>
                        <table class="table table-striped table-bordered text-center mt-3">
                            <thead>
                                <tr class="bg-light">
                                    <th scope="col">#</th>
                                    <th scope="col">Service</th>
                                    <th scope="col">Assigned Project</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i=1; ?>
                                <?php if(count($projectCategories) > 0): ?>
                                <?php $__currentLoopData = $projectCategories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $d): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <th scope="row"><?php echo e($i++); ?></th>
                                        <td><b><?php echo e($d->name); ?></b></td>
                                        <td><b><?php echo e($d->project_count); ?></b></td>
                                        <th>
                                            <button  class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#EditModel<?php echo e($d->id); ?>" >Edit</button>
                                            <!-- <a href="<?php echo e(route('project.category.delete', ['id' => $d->id])); ?>" class="btn btn-sm btn-danger delete-btn"
                                                onclick="event.preventDefault(); deleteConfirmation('<?php echo e(route('project.category.delete', ['id' => $d->id])); ?>');">
                                                Delete
                                            </a> -->
                                        </th>
                                    </tr>


                                     <!-- Project Category Edit Model  -->
                                    <div class="modal" id="EditModel<?php echo e($d->id); ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLabel">Edit Service</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <form  class="ajax-form" data-action="<?php echo e(route('project.category.update')); ?>"  data-method="POST">
                                                        <?php echo csrf_field(); ?>
                                                        <div class="mb-3">
                                                        <label for="exampleInputcategory1" class="form-label">Service Name</label>
                                                        <input type="hidden" name="id" value="<?php echo e($d->id); ?>">
                                                        <input type="text" class="form-control" name="category" id="addcategory" id="exampleInputcategory1"  value="<?php echo e($d->name); ?>" placeholder="Enter Service Name" required>
                                                        </div>
                                                        <button type="submit" class="btn btn-primary">Update</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php else: ?>
                                <tr>
                                    <td colspan="8">  <center>     NO DATA FOUND</center> </td>
                                </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                        <!-- End Default Table Example -->
                    </div>
                </div>
            </div>
        </div>
    </section>

   <!-- Project Category Add Model  -->
    <div class="modal" id="AddModel" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add Service</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form  class="ajax-form" data-action="<?php echo e(route('project.category.create')); ?>"  data-method="POST">
                        <?php echo csrf_field(); ?>
                        <div class="mb-3">
                        <label for="exampleInputcategory1" class="form-label">Service Name</label>
                        <input type="text" class="form-control" name="category" id="addcategory" id="exampleInputcategory1" placeholder="Enter Service Name" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Create</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <?php echo e($projectCategories->links()); ?> 


    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script>
    function deleteConfirmation(deleteUrl) {
        swal({
            title: "Are you sure?",
            text: "Once deleted, you will not be able to recover this category!",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        })
        .then((willDelete) => {
            if (willDelete) {
                // If user confirms, proceed with the deletion by visiting the delete URL
                window.location.href = deleteUrl;
            } else {
                // If user cancels, do nothing
                swal("Your category is safe!", {
                    icon: "info",
                });
            }
        });
    }
</script>
<script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('searchInput');
            searchInput.addEventListener('keyup', function() {
                const filter = searchInput.value.toLowerCase();
                const rows = document.querySelectorAll('.table tbody tr');

                rows.forEach(row => {
                    const category = row.querySelector('td:nth-child(2)').textContent.toLowerCase();
                    if (category.includes(filter)) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            });
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
<?php endif; ?><?php /**PATH /home/adxventure/lara_tms/resources/views/admin/projectcategory/index.blade.php ENDPATH**/ ?>