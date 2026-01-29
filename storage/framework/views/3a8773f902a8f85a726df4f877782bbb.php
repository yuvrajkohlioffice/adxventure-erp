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
        
        <h1>All Logs</h1>
        
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?php echo e(url('/')); ?>">Home</a></li>
                <li class="breadcrumb-item ">Users</li>
                 <li class="breadcrumb-item active">Logs</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->



    <section class="section">
        
        
        <div class="row">
            
            
            <div class="col-lg-12">

                <div class="card">
                    <div class="card-body">
                        
                        <br>


                        <!-- Default Table -->
                        <table class="table table-striped">
                            <thead>
                                <tr class="bg-light">
                                    <th scope="col">#</th>
                                    <th scope="col">Name</th>
                                    <th scope="col">Content</th>
                                    <th scope="col">Created Date</th>
                                    <!--<th>Action</th>-->
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(count($data) > 0): ?>
                                <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $d): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <th scope="row"> <?php echo e($data->firstItem() + $key); ?>. </th>
                                        <td><?php echo e(ucfirst($d->users->name ?? '')); ?></td>
                                         <td><?php echo e($d->content); ?> at <?php echo e(date('d M,Y h:i A',strtotime($d->time))); ?> </td>
                                        <td><?php echo e(date("d M, Y",strtotime($d->created_at))); ?></td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php else: ?>
                                <tr >
                                       <td colspan="8">  <center>     NO DATA FOUND</center> </td>
                            
                                </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                        <!-- End Default Table Example -->
                    </div>
                </div>
                
                    <?php echo e($data->links()); ?>

            </div>
        </div>
    </section>



 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $component = $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?><?php /**PATH /home/adxventure/lara_tms/resources/views/admin/logs/index.blade.php ENDPATH**/ ?>