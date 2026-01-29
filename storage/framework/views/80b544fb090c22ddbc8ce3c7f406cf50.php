<?php if (isset($component)) { $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54 = $component; } ?>
<?php $component = App\View\Components\AppLayout::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('app-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(App\View\Components\AppLayout::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
    <?php $__env->startSection('title','My Clients'); ?>
    <!-- Datatables css -->
    <link href="<?php echo e(asset('assets/vendor/datatable/css/dataTables.bootstrap5.min.css')); ?>" rel="stylesheet" type="text/css" />
    <link href="<?php echo e(asset('assets/vendor/datatable/css/buttons.bootstrap5.min.css')); ?>" rel="stylesheet" type="text/css" />
    <link href="<?php echo e(asset('assets/vendor/datatable/css/keyTable.bootstrap5.min.css')); ?>" rel="stylesheet" type="text/css" />
    <link href="<?php echo e(asset('assets/vendor/datatable/css/responsive.bootstrap5.min.css')); ?>" rel="stylesheet" type="text/css" />
    <link href="<?php echo e(asset('assets/vendor/datatable/css/select.bootstrap5.min.css')); ?>" rel="stylesheet" type="text/css" />
    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">
    <!-- Select2 Bootstrap 5 Theme CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet">  
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <style>
        .col-3{
            float:right;
        }
        #loading-spinner {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 9999;
            display: none; /* Initially hidden */
        }
        .no-scroll {
            overflow: hidden;
        }
    </style>

    <div class="pagetitle">
        <h1>My Clients</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?php echo e(url('/')); ?>">Home</a></li>
                <li class="breadcrumb-item active">My Clients</li>
            </ol>
        </nav>
    </div>

    <section class="section" id="client-section">
        <div class="row">
            <div class="col-xxl-12 col-xl-12">
                <div class="card">
                    <div class="card-header">
                    </div>
                    <div class="card-body"> 
                        <!-- Datatable  -->
                        <div id="datatable-buttons_wrapper" class="dataTables_wrapper dt-bootstrap5 no-footer mt-3">
                            <div class="row">
                                <div class="col-12">
                                    <table id="client-table" class="table table-striped table-bordered dt-responsive nowrap dataTable no-footer dtr-inline" aria-describedby="datatable-buttons_info">
                                        <thead>
                                            <tr>
                                                <th>Sr No.</th>
                                                <th>Clinet Info</th>
                                                <th>Projects</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Datatables js -->
    <script src="<?php echo e(asset('assets/vendor/datatable/jquery.dataTables.min.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/vendor/datatable/dataTables.bootstrap5.min.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/vendor/datatable/dataTables.buttons.min.js')); ?>"></script>
     <!-- dataTable.responsive -->
    <script src="<?php echo e(asset('assets/vendor/datatable/dataTables.responsive.min.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/vendor/datatable/responsive.bootstrap5.min.js')); ?>"></script>
      <!-- Select2 JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
    <script>
        $(function () {
            // Show Data Table Data
            let table = $('#client-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "",
                },
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                    { data: 'client_info', name: 'client_info'},
                    { data: 'projects', name: 'projects',orderable: false, searchable: false},
                    { data: 'actions', name: 'actions', orderable: false, searchable: false }
                ]
            });

        });
    </script>

 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $component = $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?><?php /**PATH /home/adxventure/lara_tms/resources/views/admin/client/my_client.blade.php ENDPATH**/ ?>