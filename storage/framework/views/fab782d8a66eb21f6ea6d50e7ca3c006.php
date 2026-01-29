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
    <?php $__env->startSection('title','Campaigns'); ?>
    <link rel="stylesheet" type="text/css" href="https://unpkg.com/trix@2.0.8/dist/trix.css">
    <script type="text/javascript" src="https://unpkg.com/trix@2.0.8/dist/trix.umd.min.js"></script>
        <!-- Datatables css -->
    <link href="<?php echo e(asset('assets/vendor/datatable/css/dataTables.bootstrap5.min.css')); ?>" rel="stylesheet" type="text/css" />
    <link href="<?php echo e(asset('assets/vendor/datatable/css/buttons.bootstrap5.min.css')); ?>" rel="stylesheet" type="text/css" />
    <link href="<?php echo e(asset('assets/vendor/datatable/css/keyTable.bootstrap5.min.css')); ?>" rel="stylesheet" type="text/css" />
    <link href="<?php echo e(asset('assets/vendor/datatable/css/responsive.bootstrap5.min.css')); ?>" rel="stylesheet" type="text/css" />
    <link href="<?php echo e(asset('assets/vendor/datatable/css/select.bootstrap5.min.css')); ?>" rel="stylesheet" type="text/css" />
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
        <button class="btn btn-sm btn-primary" style="float:right;"  data-bs-toggle="modal" data-bs-target="#importModal">+ Import Leads</button>
        <button type="button" class="btn btn-sm btn-success me-2" style="float:right;"  id="startCampaignBtn" data-campaign-id="<?php echo e($campaign->id); ?>"> <i class="fa fa-play"></i> Start Campaign</button>
        <h1>Campaigns</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?php echo e(url('/')); ?>">Home</a></li>
                <li class="breadcrumb-item active">Campaigns</li>
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
                        <table class="table table-striped" id="recipientTable">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Recipient</th>
                                    <th>Channel</th>
                                    <th>Status</th>
                                    <th>Failed Reason</th>
                                    <th>Created</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Add Campaign Detail Model start  -->
    <div class="modal" id="importModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true"
        data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Import Leads</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="ajax-form" data-action="<?php echo e(route('campaigns.import', $campaign->id)); ?>" data-method="POST" enctype="multipart/form-data">
                    <div class="modal-body">
                        <?php echo csrf_field(); ?>
                        <select name="channel" class="form-select mb-3">
                            <option value="email">Email</option>
                            <option value="whatsapp">WhatsApp</option>
                        </select>

                        <input type="file" name="file" class="form-control" required>
                    </div>
                    <div class="modal-footer">
                        <button  type="submit" class="btn btn-primary">Upload</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
      <!-- Datatables js -->
    <script src="<?php echo e(asset('assets/vendor/datatable/jquery.dataTables.min.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/vendor/datatable/dataTables.bootstrap5.min.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/vendor/datatable/dataTables.buttons.min.js')); ?>"></script>
     <!-- dataTable.responsive -->
    <script src="<?php echo e(asset('assets/vendor/datatable/dataTables.responsive.min.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/vendor/datatable/responsive.bootstrap5.min.js')); ?>"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(function () {

            let table = $('#recipientTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "<?php echo e(route('campaigns.show', $campaign->id)); ?>",
                    data: function (d) {
                        d.status = $('#statusFilter').val();
                    }
                },
                columns: [
                    { data: 'DT_RowIndex', orderable: false },
                    { data: 'recipient', name: 'email' },
                    { data: 'channel' },
                    { data: 'status', orderable: false },
                    { data: 'failed_reason', orderable: false },
                    { data: 'created_at' }
                ]
            });

            $('#statusFilter').change(function () {
                table.ajax.reload();
            });

        });
        </script>
        <script>
            document.getElementById('startCampaignBtn').addEventListener('click', function () {
                let campaignId = this.getAttribute('data-campaign-id');

                Swal.fire({
                    title: 'Start Campaign?',
                    text: "This will send messages to all pending recipients.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#28a745',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, start it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                         // Show loading alert
                        Swal.fire({
                            title: 'Starting...',
                            html: 'Messages are being queued',
                            allowOutsideClick: false,
                            didOpen: () => { Swal.showLoading() }
                        });
                        
                        // AJAX POST request
                        $.ajax({
                            url: `/campaigns/${campaignId}/start`,
                            type: 'POST',
                            data: {
                                _token: "<?php echo e(csrf_token()); ?>"
                            },
                            success: function(res){

                                console.error(res);
                                Swal.fire(
                                    'Started!',
                                    'Campaign has been started successfully.',
                                    'success'
                                );
                                // Optional: reload page or table
                                location.reload();
                            },
                            error: function(err){
                                Swal.fire(
                                    'Error!',
                                    'Something went wrong. Please try again.',
                                    'error'
                                );
                            }
                        });

                    }
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
<?php endif; ?><?php /**PATH /home/adxventure/lara_tms/resources/views/admin/campaign/show.blade.php ENDPATH**/ ?>