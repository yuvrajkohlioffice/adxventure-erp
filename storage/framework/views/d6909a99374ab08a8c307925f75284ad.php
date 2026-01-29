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

        <h1>Task Report Attachement - <?php echo e($response->task->project->name ?? ''); ?></h1>

        <nav>

            <ol class="breadcrumb">

                <li class="breadcrumb-item"><a href="<?php echo e(url('/')); ?>">Home</a></li>

                <li class="breadcrumb-item active">Task Report Attachement</li>

            </ol>

        </nav>

    </div>

    

    <!-- End Page Title -->



    <section class="section">

        <div class="row">

            <div class="col-lg-12">

                <div class="card">

                    <div class="card-body p-4">

                        <div class="row">    

                            <?php if($response->task->attachment == 1 || $data): ?>

                                <h5>Attachments : </h5>

                                    <?php if(count($data) > 0): ?>

                                        <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $image): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                                        <div class="col-md-6 mb-4">

                                                <img src="<?php echo e(asset('images/'.$image->filename)); ?>" width="550px;" />

                                        </div>

                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                    <?php else: ?>

                                        No Attachement found!

                                    <?php endif; ?>

                            <?php endif; ?>



                            <?php if($response->remark): ?>

                                <h5> Remark : </h5>

                                <p><?php echo e($response->remark); ?></p>

                            <?php endif; ?> 

                            <?php if($response->url): ?>

                                <h5> Url : </h5>

                                <a href="<?php echo e($response->url); ?>" target="_blank"><?php echo e($response->url); ?></a>

                            <?php endif; ?> 

                            

                            

                        </div>

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
<?php endif; ?><?php /**PATH /home/adxventure/lara_tms/resources/views/admin/report/view.blade.php ENDPATH**/ ?>