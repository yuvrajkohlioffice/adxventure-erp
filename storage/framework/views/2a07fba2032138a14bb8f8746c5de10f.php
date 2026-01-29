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
    <?php $__env->startSection('title','Leaves'); ?>
        <!-- Datatables css -->
    <link href="<?php echo e(asset('assets/vendor/datatable/css/dataTables.bootstrap5.min.css')); ?>" rel="stylesheet" type="text/css" />
    <link href="<?php echo e(asset('assets/vendor/datatable/css/buttons.bootstrap5.min.css')); ?>" rel="stylesheet" type="text/css" />
    <link href="<?php echo e(asset('assets/vendor/datatable/css/keyTable.bootstrap5.min.css')); ?>" rel="stylesheet" type="text/css" />
    <link href="<?php echo e(asset('assets/vendor/datatable/css/responsive.bootstrap5.min.css')); ?>" rel="stylesheet" type="text/css" />
    <link href="<?php echo e(asset('assets/vendor/datatable/css/select.bootstrap5.min.css')); ?>" rel="stylesheet" type="text/css" />
    <style>
        .form-group{
            margin-top:10px;
            margin-bottom:10px;
        }
        label{  
            font-weight:600;
        }    
        .custom-tooltip {
        --bs-tooltip-bg: var(--bd-violet-bg);
        --bs-tooltip-color: var(--bs-white);
        }
    </style>

    <!-- Start Page Title -->
    <div class="pagetitle">
       <a style="float:right; margin-left:10px" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#AddModel" ><i class="bi bi-plus-circle"></i> Apply Leave</a>
        <h1>Leaves</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?php echo e(url('/')); ?>">Home</a></li>
                <li class="breadcrumb-item active">Leaves</li>
            </ol>
        </nav>
    </div>
    <!-- End Page Title -->

    <!-- leave report section start -->
    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <div class="col-12">
                            <div class="row">
                                <!-- User leave Filter -->
                                <?php if(Auth::user()->hasRole(['Super-Admin','Admin','Human Resources Executive','Marketing-Manager','Technology Manager','Digital Marketing Manager'])): ?>
                                <div class="col-3">
                                    <select class="form-select custom-select" name="user" id="filter-user">
                                        <option value="">Select User</option>
                                        <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($user->id); ?>" <?php echo e(request('user') == $user->id ? 'selected' : ''); ?>><?php echo e($user->name); ?> (<?php echo e($user->roles->pluck('name')->implode(', ')); ?>)</option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                                <?php endif; ?>

                                <!-- Leave Type Filter -->
                                <div class="col-3">
                                    <select class="form-select custom-select" name="type" id="filter-type">
                                        <option value="">Select leave Type..</option>
                                        <option value="Casual" <?php echo e(request('type') == 'Casual' ? 'selected' : ''); ?>>Casual</option>
                                        <option value="Sick" <?php echo e(request('type') == 'Sick' ? 'selected' : ''); ?>>Sick</option>
                                        <option value="Half-day" <?php echo e(request('type') == 'Half-day' ? 'selected' : ''); ?>>Half Day</option>
                                    </select>
                                </div>
                                <!-- Leave Status Filter -->
                                <div class="col-3">
                                    <select class="form-select custom-select" name="status" id="filter-status">
                                        <option value="" <?php echo e(is_null(request('status')) ? 'selected' : ''); ?>>Select leave status..</option>
                                        <option value="1" <?php echo e(request('status') === '1' ? 'selected' : ''); ?>>Approved</option>
                                        <option value="2" <?php echo e(request('status') === '2' && !is_null(request('status')) ? 'selected' : ''); ?>>Un-Approved</option>
                                        <option value="0" <?php echo e(request('status') === '0' && !is_null(request('status')) ? 'selected' : ''); ?>>Pending</option>
                                    </select>
                                </div>
                            </div>
                        </div>  
                    </div>
                    <div class="card-body mt-2">
                        <table id="leave-table" class="table table-striped ">
                            <thead>
                                <tr class="bg-light">
                                    <th>Sr No.</th>
                                    <th>Employee Name</th>
                                    <th>Apply Date</th>
                                    <th>Leave Dates</th>
                                    <th>Leave Days</th>
                                    <th>Leave Type</th>
                                    <th>Leave Reason</th>
                                    <th>Approve & Un-Aprroved By</th>
                                    <th>Status</th>
                                    <?php if(Auth::user()->hasRole(['Super-Admin','Admin'])): ?>    
                                    <th>Action</th>
                                    <?php endif; ?>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- leave report section End -->

    <!-- leave apply modal start  -->
    <div class="modal" id="AddModel" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-lg">
            <div class="modal-content" style="top:120px">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Apply Leave</h5>
                    <button type="button" class="border-0" style="background: border-box;" data-bs-dismiss="modal" aria-label="Close"><i class="bi bi-x-circle-fill" style="font-size: x-large;"></i></button>
                </div>
                <div class="modal-body">
                    <form autocomplete="off" data-method="POST" data-action="<?php echo e(url('leave/store')); ?>" id="ajax-form" enctype="multipart/form-data">
                            <?php echo csrf_field(); ?>
                            <div class="row">
                                <div class="col-md-12">
                                    <label for="type">Leave Type <span class="text-danger">*</span></label>
                                    <select class="form-select" name="type" id="type">
                                        <option value="">Select Type</option>
                                        <option value="Casual">Casual</option>
                                        <option value="Sick">Sick</option>
                                        <option value="Half-day">Half Day</option>
                                        <option value="Non-Paid-leave">Non Paid leave (NPL)</option>
                                    </select>
                                    <small id="error-type" class="form-text error text-danger"></small>
                                </div>
                                
                                <div class="col-md-6 mt-3">
                                    <label for="from_date"> From Date <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control" id="from_date" name="from_date" min="<?php echo e(date('Y-m-d')); ?>"   placeholder="Enter From Date..">
                                    <small id="error-from_date" class="form-text error text-danger"></small>
                                </div>
                                <div class="col-md-6 mt-3">
                                    <label for="to_date"> To Date <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control"  id="to_date" name="to_date"  min="<?php echo e(date('Y-m-d')); ?>" readonly  placeholder="Enter To Date..">
                                    <small id="error-to_date" class="form-text error text-danger"></small>
                                </div>
                                <div class="col-md-12 mt-3">
                                    <label for="reason"> Leave Reason <span class="text-danger">*</span></label>
                                    <textarea class="form-control" id="reason" name="reason" value="<?php echo e(old('subject')); ?>"  placeholder="Enter subject.."></textarea>
                                    <small id="error-reason" class="form-text error text-danger"></small>
                                </div>
                                <div class="col-md-12 mt-3">
                                    <label for="docs"> Documnent</label>
                                    <input type="file" name="document" id="docs" class="form-control" name="document">
                                    <small id="error-reason" class="form-text error text-danger"></small>
                                </div>
                            </div>
                            <button id="submit-btn"  type="submit" class="btn btn-sm btn-primary mt-3">
                                <span class="loader" id="loader" style="display: none;"></span> 
                                Submit Leave Request
                            </button>
                        </form>
                </div>
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

    <script>
        $(function () {
            // Show Data Table Data
            let table = $('#leave-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "",
                    data: function (d) {
                        d.user = $('#filter-user').val();
                        d.status = $('#filter-status').val();
                        d.type = $('#filter-type').val();
                    }
                },
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                    { data: 'employee', name: 'employee',orderable: false, searchable: false},
                    { data: 'created_at', name: 'created_at',orderable: false, searchable: false},
                    { data: 'leave_dates', name: 'leave_dates',orderable: false, searchable: false},
                    { data: 'leave_days', name: 'leave_days',orderable: false, searchable: false},
                    { data: 'type', name: 'type',orderable: false, searchable: false },
                    { data: 'reason', name: 'reason',orderable: false, searchable: false },
                    { data: 'approved_by', name: 'approved_by',orderable: false, searchable: false },
                    { data: 'approval_status', name: 'approval_status',orderable: false, searchable: false },
                    <?php if(Auth::user()->hasRole(['Super-Admin','Admin'])): ?>    
                    { data: 'action', name: 'action', orderable: false, searchable: false }
                    <?php endif; ?>
                ]
            });

            // drop down filter 
            $('#filter-user,#filter-status,#filter-type').on('change keyup', function () {
                table.draw();
            });
        });
    </script>


    <script>
        function deleteConfirmation(deleteUrl) {
            swal({
                title: "Are you sure?",
                text: "Once deleted, you will not be able to recover this leave record!",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            })
            .then((willDelete) => {
                if (willDelete) {
                    $.ajax({
                        url: deleteUrl,
                        type: "GET",
                        data: {
                            _token: "<?php echo e(csrf_token()); ?>"
                        },
                        success: function(response) {
                            swal("Success!", "Leave record deleted successfully!", "success");
                            $('#leave-table').DataTable().ajax.reload(null, false); 
                        },
                        error: function(xhr) {
                            swal("Error!", "Something went wrong!", "error");
                        }
                    });
                }
            });
        }


        function Stauts(id, status) {
            if (status === 'reject') {
                swal({
                    title: "Reject Reason",
                    text: "Please provide a reason for rejecting this leave:",
                    content: "input",
                    buttons: true,
                    dangerMode: true,
                }).then((reason) => {
                    if (!reason) {
                        swal("Error!", "Rejection reason is required!", "error");
                        return;
                    }

                    sendStatusUpdate(id, status, reason);
                });

            } else {
                swal({
                    title: "Are you sure?",
                    text: "You are about to approve this leave request.",
                    icon: "warning",
                    buttons: true,
                    dangerMode: false,
                }).then((willApprove) => {
                    if (willApprove) {
                        sendStatusUpdate(id, status);
                    }
                });
            }
        }

        function sendStatusUpdate(id, status, reason = '') {
            $.ajax({
                url: `/leave/status/${id}/${status}`,
                type: "GET",
                data: {
                    _token: "<?php echo e(csrf_token()); ?>",
                    reason: reason
                },
                success: function(response) {
                    swal("Success!", "Leave status updated successfully!", "success");
                    $('#leave-table').DataTable().ajax.reload(null, false); 
                },
                error: function(xhr) {
                    swal("Error!", "Something went wrong!", "error");
                }
            });
        }

    </script>


     <script>
        document.addEventListener('DOMContentLoaded', function () {
            const selectBoxes = document.querySelectorAll('select');

            selectBoxes.forEach(selectBox => {
                selectBox.addEventListener('change', function () {
                    if (this.value !== '') {
                        selectBoxes.forEach(otherSelectBox => {
                            if (otherSelectBox !== this && otherSelectBox.value === '') {
                                otherSelectBox.selectedIndex = 0; // Reset to default
                            }
                        });
                    }
                    if (this.id === 'leave_day') {
                        const fromDateContainer = document.getElementById('from_date_container');
                        const toDateContainer = document.getElementById('to_date_container');
                        if (this.value === 'custome') {
                            fromDateContainer.style.display = 'block';
                            toDateContainer.style.display = 'block';
                        } else {
                            fromDateContainer.style.display = 'none';
                            toDateContainer.style.display = 'none';
                        }
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
<?php endif; ?>











<?php /**PATH /home/bookmziw/lara_tms/resources/views/admin/leaves/create.blade.php ENDPATH**/ ?>