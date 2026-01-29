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
<?php $__env->startSection('title','Projects'); ?>
    <style>
        .hold_reason_textarea {
            display: none;
        }
    </style>
    
    <div class="pagetitle">
        <h1>All Projects</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?php echo e(url('/')); ?>">Home</a></li>
                <li class="breadcrumb-item active">Project</li>
            </ol>
        </nav>
    </div>
    <?php echo $__env->make('include.alert', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <form action="" method="GET">
                            <div class="col-md-12"> 
                                <div class="row m-4">
                                    <div class="col-md-4">
                                            <input type="name" class="form-control" name="name"
                                                value="<?php echo e(request()->name ?? ''); ?>" id="exampleInputEmail1"
                                                aria-describedby="emailHelp" placeholder="Search by project name...">
                                    </div>
                                    <div class="col-md-4">
                                        <select class="form-control" name="projectstatus">
                                            <option selected disabled>Search Here..</option>
                                            <option value="hold">Hold Project</option>
                                            <option value="1">All Project</option>
                                            <option value="2">Complete Project</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                            <button class="btn btn-success btn-md">Filter</button>
                                            &nbsp; &nbsp;
                                            <a href="<?php echo e(url('project')); ?>"
                                                class="btn btn-success btn-danger">Refresh</a>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                </div>
                            </div>
                        </form>
                        <!-- Default Table -->
                        <table class="table table-striped">
                            <thead>
                                <tr class="bg-light">
                                    <th scope="col">#</th>
                                    <th scope="col">Logo</th>
                                    <th scope="col">Project Name</th>
                                    <th scope="col">Client Contact Details</th>
                                    <th scope="col">Project Date</th>
                                    <th scope="col">Deadline Date</th>
                                    <th scope="col">Memeber</th>
                                    <th scope="col">Tasks</th>
                                    <?php if(Auth::user()->hasRole(['Super-Admin', 'Admin', 'Marketing-Manager','Project-Manager'])): ?>
                                    <th scope="col">Action</th>
                                    <?php endif; ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(isset($data)): ?>
                                <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $d): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <th scope="row"> <?php echo e($data->firstItem() + $key); ?>. </th>
                                    <td><a href="<?php echo e(route('projects.details',['project_id'=> $d->id])); ?>"><img width="70px" height="60px" src="<?php echo e($d->logo); ?>" /></a></td>
                                    <td style="width:20%;">
                                        <a href="<?php echo e(route('projects.details',['project_id'=> $d->id])); ?>"><b><?php echo e(ucfirst($d->name)); ?> </b></a><br>
                                        <small>client name : <?php echo e($d->client->name ?? ''); ?></small><br>
                                        <?php if($d->status == 0): ?>
                                        <span class="badge bg-danger">
                                            OnHold
                                        </span> 
                                        <?php elseif($d->status == 2): ?>
                                        <span class="badge bg-success text-white">
                                            Complete
                                        </span>
                                        <?php elseif($d->status == 3): ?>
                                            <span class="badge bg-dark text-white">
                                                Not Assigned (New)
                                            </span>
                                        <?php else: ?>
                                        <span class="badge bg-primary text-white">
                                            Ongoing
                                        </span>
                                        <?php endif; ?>
                                        <a class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#followup<?php echo e($d->id); ?>">Follow Up
                                            <?php if($d->followup->count()>=1): ?>
                                            (<?php echo e($d->followup->count()); ?>)
                                            <?php endif; ?>
                                        </a>
                                        <?php
                                            $latestFollowup = $d->Followup->sortByDesc('created_at')->first();
                                            $lastFollow = null;
                                            if ($latestFollowup) {
                                                $lastFollow = \Carbon\Carbon::parse($latestFollowup->created_at)->diffForHumans();
                                            }
                                        ?>
                                        <br>
                                        <?php if($lastFollow !== null): ?>
                                        <small> (Last Follow-up: <?php echo e($lastFollow); ?>)</small>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <small>
                                            Email: <a href="mailto:<?php echo e($d->client->email ?? ''); ?>"> <?php echo e($d->client->email ?? ''); ?></a><br>
                                            Phone :<a href="tel:<?php echo e($d->client->phone_no ?? ''); ?>">
                                                <?php echo e($d->client->phone_no ?? ''); ?></a><br>
                                            Website: <a target="_blank" href="<?php echo e($d->website); ?>"><?php echo e($d->website); ?> </a>
                                        </small>
                                    </td>
                                    <td><?php echo e(date('d M, Y',strtotime($d->created_at))); ?></td>
                                    <td><?php echo e(date('d M, Y',strtotime($d->created_at))); ?></td>
                                    <td>
                                        <small>
                                            <?php $__currentLoopData = $d->users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <?php echo e($user->name); ?>  (<?php echo e($user->roles->pluck('name')->join(', ')); ?>)<br>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </small>
                                    </td>
                                    <td>
                                        <?php if(Auth::user()->hasRole(['Super-Admin', 'Admin', 'Marketing-Manager','Project-Manager'])): ?>
                                            <a class="btn btn-sm btn-warning" href="<?php echo e(route('project.task.index',['id'=> $d->id])); ?>">
                                                Task(<?php echo e($tasks->where('project_id', $d->id)->count()); ?>)
                                            </a> 
                                        <?php else: ?>
                                            <a class="btn btn-sm btn-primary" href="<?php echo e(route('task.create.custom',['id'=> $d->id])); ?>">
                                              Create Task
                                            </a> 
                                        <?php endif; ?>       
                                    </td>
                                    <?php if(Auth::user()->hasRole(['Super-Admin', 'Admin', 'Marketing-Manager','Project-Manager'])): ?>
                                    <td>
                                        <div class="dropdown">
                                            <i class="bi bi-three-dots-vertical " type="button" id="dropdownMenuButton2"
                                                data-bs-toggle="dropdown" aria-expanded="false"></i>
                                            <ul class="dropdown-menu dropdown-menu-light"
                                                aria-labelledby="dropdownMenuButton2">
                                                <?php if($d->status != 0): ?>
                                                <li>
                                                    <a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#AssignPRoject<?php echo e($d->id); ?>">
                                                        Assign Project
                                                    </a>
                                                </li>
                                                <?php endif; ?>
                                                <li > 
                                                    <?php if($d->status == 0 || $d->status == 2): ?>
                                                    <a  class="dropdown-item " onClick="return confirm('Are you sure?');"
                                                        href="<?php echo e(url('/project/status/'.$d->id."/1")); ?>">Ongoing</a>
                                                    <?php else: ?>
                                                    <a  class="dropdown-item" data-toggle="tooltip" data-placement="top"
                                                        title="Its means you are stopping all the services & process."
                                                        data-bs-toggle="modal" data-bs-target="#exampleModal<?php echo e($d->id); ?>">
                                                        Hold</a>
                                                    <?php endif; ?>
                                                </li>
                                                <li>
                                                    <a class="dropdown-item" onClick="return confirm('Are you sure?');"
                                                        href="<?php echo e(url('/project/status/'.$d->id."/2")); ?>">Complete</a>
                                                </li>
                                                <li>
                                                    <a class="dropdown-item" onClick="return confirm('Are you sure?');"
                                                    href="<?php echo e(route('project.edit',['project'=>$d->id])); ?>">Edit</a>
                                                    </li>
                                                </li>
                                                <li>
                                                <a class="dropdown-item" onclick="Credintoal(<?php echo e($d->id); ?>)">Add Credintoal</a>

                                                    </li>
                                                </li>
                                            </ul>
                                        </div>
                                    </td>
                                    <?php endif; ?>
                                </tr>
                                <!-- Reason Model  -->
                                <div class="modal" id="exampleModal<?php echo e($d->id); ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">Reason</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <form action="<?php echo e(url('/project/status/'.$d->id.'/0')); ?>" method="POST" id="HoldForm">
                                                    <?php echo csrf_field(); ?>
                                                    <div class="mb-3">
                                                        <input type="radio" name="hold_reason" value="Payment Not Completed">
                                                        <label class="form-label">Payment Not Completed</label><br>
                                                        <input type="radio" name="hold_reason" value="Client not responded">
                                                        <label class="form-label">Client not responded</label><br>
                                                        <input type="radio" name="hold_reason" value="Other">
                                                        <label class="form-label">Other</label><br>
                                                    </div>
                                                    <div class="hold_reason_textarea" style="display: none;">
                                                        <label class="form-label">Enter Your Reason</label>
                                                        <textarea name="reason" class="form-control"></textarea>
                                                        <input type="hidden" name="project_id" value="<?php echo e($d->id); ?>">
                                                    </div>
                                                    <div class="mt-3">
                                                        <button type="button" class="btn btn-primary" onclick="confirmAndSubmit()">Hold Project</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!--Follow Up  Model Start -->
                                <div class="modal" id="followup<?php echo e($d->id); ?>" tabindex="-1"
                                    aria-labelledby="exampleModalLabel" aria-hidden="true" data-bs-backdrop="static"
                                    data-bs-keyboard="false">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">Follow Up</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <form class="ajax-form" data-action="<?php echo e(route('invoice.followup')); ?>"
                                                    data-method="POST">
                                                    <?php echo csrf_field(); ?>
                                                    <input type="hidden" name="project_id" value="<?php echo e($d->id); ?>">
                                                    <div class="form-group">
                                                        <label>Remark</label>
                                                        <textarea class="form-control" name="remark"></textarea>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Next Follow Up date </label>
                                                        <input type="date" class="form-control" name="next_date">
                                                    </div>
                                                    <button type="submit" class="btn btn-primary">Submit</button>
                                                </form>
                                                <div class="container">
                                                    <h3 class="card-title text-center">Follow Up data</h3> 
                                                    <?php
                                                    $j=1;
                                                    ?>
                                                    <?php $__currentLoopData = $d->Followup->sortByDesc('id'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $follow): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <?php
                                                        $user = App\Models\User::find($follow->user_id);
                                                    ?>
                                                    <p style="border-bottom : 1px solid #ccc">
                                                        <?php echo e($j++); ?>.<strong>User:<?php echo e(strtoupper($user->name)); ?>| </strong><strong>Remark: </strong><?php echo e($follow->remark); ?> |
                                                        (<?php echo e(\Carbon\Carbon::parse($follow->created_at)->format('d-m-Y / H:i:s')); ?>)
                                                        | <strong>Next Follow Up</strong>: <?php echo e($follow->next_date); ?> <br>
                                                    </p>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                  <!-- Modal for Assigned Project  -->
                                  <div class="modal fade" id="AssignPRoject<?php echo e($d->id); ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">Assign Project</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <form  action="<?php echo e(route('projects.assign')); ?>" method="POST">
                                                    <?php echo csrf_field(); ?>
                                                    <div class="form-group">
                                                        <label class="form-label">Select Employee</label>
                                                        <input type="hidden" name="project_id" value="<?php echo e($d->id); ?>">
                                                        <select name="assignd_user[]" class="form-control select-2-multiple" multiple>
                                                            <option value="">Select Employee..</option>
                                                            <?php if(isset($users)): ?>
                                                                <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                    <?php if($user->roles->isNotEmpty()): ?>
                                                                        <option value="<?php echo e($user->id); ?>"><?php echo e($user->name); ?> (<?php echo e($user->roles->first()->name); ?>)</option>
                                                                    <?php endif; ?>
                                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                            <?php endif; ?>
                                                        </select>
                                                    </div>
                                                    <button type="submit" class="btn btn-primary mt-3">Submit</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php else: ?>
                                <tr>
                                    <td colspan="8">
                                        <center>NO DATA FOUND </center>
                                    </td>
                                </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                        <div class="row pagination-links">
                <div class="col-8">
                <?php if($data->total() > 0): ?>
                    Showing <?php echo e($data->firstItem()); ?> to <?php echo e($data->lastItem()); ?> of <?php echo e($data->total()); ?> entries
                <?php endif; ?>
                </div>
                <div class="col-4 text-end">
                    <?php echo e($data->appends(request()->query())->links()); ?>

                </div>
            </div>
                    <!-- End Default Table Example -->
                </div>
            </div>
           
        </div>
    </section>
    <div class="modal fade" id="credintoal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" style="width:200%;right: 17rem;top: 20vh;">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add Credintoal</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="ajax-form" data-action="<?php echo e(route('projects.credintoal')); ?>" data-method="POST">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="project_id" value="">
                    <div id="credintoal-container">
                        <div class="row mb-3">
                            <div class="col">
                                <label class="form-label">Name</label>
                                <input type="text" name="name[]" class="form-control" placeholder="Cpanel">
                            </div>
                            <div class="col">
                                <label class="form-label">Url</label>
                                <input type="url" name="url[]" class="form-control" placeholder="https://tms.adxventure.com/">
                            </div>
                            <div class="col">
                                <label class="form-label">UserName/Email</label>
                                <input type="text" name="username[]" class="form-control" placeholder="demo@gmail.com">
                            </div>
                            <div class="col">
                                <label class="form-label">Password</label>
                                <input type="password" name="password[]" class="form-control">
                            </div>
                            <div class="col">
                                <label class="form-label">Permission By Role</label>
                                <select name="role[]" class="form-select">
                                    <option selected disabled>Select Roles</option>
                                    <?php $__currentLoopData = $roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($role->id); ?>"><?php echo e($role->name); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                            <div class="col-1 mt-4">
                                <button type="button" class="btn btn-danger remove-row mt-2" >-</button>
                            </div>
                        </div>
                    </div>
                    <button type="button" class="btn btn-primary" id="add-row">Add Row</button>
                    <button type="submit" class="btn btn-primary mt-3" style="float:right">Submit</button>
                </form>
            </div>
        </div>
    </div>
</div>

    <!-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> -->
     <script>
document.getElementById('add-row').addEventListener('click', function() {
    const container = document.getElementById('credintoal-container');
    const newRow = document.createElement('div');
    newRow.classList.add('row', 'mb-3');
    newRow.innerHTML = `
        <div class="col">
            <label class="form-label">Name</label>
            <input type="text" name="name[]" class="form-control" placeholder="Facebook">
        </div>
        <div class="col">
            <label class="form-label">Url</label>
            <input type="url" name="url[]" class="form-control" placeholder="https://tms.adxventure.com/">
        </div>
        <div class="col">
            <label class="form-label">UserName/Email</label>
            <input type="text" name="username[]" class="form-control" placeholder="demo01@gmail.com">
        </div>
        <div class="col">
            <label class="form-label">Password</label>
            <input type="password" name="password[]" class="form-control">
        </div>
          <div class="col">
                <label class="form-label">Permission By Role</label>
                <select name="role[]" class="form-select">
                    <option selected disabled>Select Roles</option>
                    <?php $__currentLoopData = $roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($role->id); ?>"><?php echo e($role->name); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
        <div class="col-1 mt-4">
            <button type="button" class="btn btn-danger remove-row">-</button>
        </div>
          
    `;
    
    container.appendChild(newRow);
});

document.getElementById('credintoal-container').addEventListener('click', function(e) {
    if (e.target.classList.contains('remove-row')) {
        const rows = document.querySelectorAll('#credintoal-container .row');

        console.log(`Number of rows: ${rows.length}`); // Debugging line

        if (rows.length > 1) {
            e.target.closest('.row').remove();
        } else {
            alert("At least one row must remain.");
        }
    }
});

     </script>
    <script>
        $(document).ready(function() {
            console.log('Document is ready');

            $('input[name="hold_reason"]').change(function() {
                console.log('Radio button changed');
                if ($(this).val() === 'Other') {
                    console.log('Other selected');
                    $('.hold_reason_textarea').show();
                    $('textarea[name="reason"]').attr('required', 'required');
                } else {
                    console.log('Other not selected');
                    $('.hold_reason_textarea').hide();
                    $('textarea[name="reason"]').removeAttr('required');
                }
            });
        });

        function confirmAndSubmit() {
            swal({
                title: "Are you sure?",
                text: "Once Done, This Project on Hold!",
                icon: "warning",
                buttons: {
                    cancel: {
                        text: "Cancel",
                        value: null,
                        visible: true,
                        className: "",
                        closeModal: true,
                    },
                    confirm: {
                        text: "OK",
                        value: true,
                        visible: true,
                        className: "",
                        closeModal: false,
                    }
                },
                dangerMode: true,
                closeOnClickOutside: false,
            }).then((willPay) => {
                if (willPay) {
                    document.getElementById("HoldForm").submit(); 
            } else {
                swal.close(); // Close the SweetAlert dialog if canceled
            }
            });
            }
            function Credintoal(id) {
                document.querySelector('input[name="project_id"]').value = id;
                // Show the modal
                var modal = new bootstrap.Modal(document.getElementById('credintoal'));
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
<?php endif; ?><?php /**PATH /home/bookmziw/lara_tms/resources/views/admin/projects/index.blade.php ENDPATH**/ ?>