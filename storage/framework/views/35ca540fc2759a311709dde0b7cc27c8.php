<?php if (isset($component)) { $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54 = $component; } ?>
<?php $component = App\View\Components\AppLayout::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('app-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(App\View\Components\AppLayout::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
    <div class="pagetitle"> 
        <a style="float:right;" class="btn btn-md btn-primary" href="<?php echo e(route('task.create.custom',$project->id)); ?>">Create Task</a>
        <h1>All Tasks - Project : <?php echo e($project->name ?? ''); ?> </h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?php echo e(url('/')); ?>">Home</a></li>
                <li class="breadcrumb-item active">Task</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->
    <?php echo $__env->make('include.alert', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <br>
                        <!-- Form to handle filters -->
                        <form action="" method="GET">
                            <div class="row">
                                <div class="col">
                                    <input type="text" class="form-control" name="name" placeholder="Enter task name..." value="<?php echo e(request()->name); ?>" />
                                </div>
                                <?php if(Auth::user()->roles()->whereIn('id', [1, 2, 11, 12, 21])->exists()): ?>
                                    <div class="col">
                                        <select class="form-select" name="member">
                                            <option selected disabled>Select Project Member</option>
                                            <?php $__currentLoopData = $projectmembers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $member): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($member->user_id); ?>" <?php echo e(request()->member == $member->user_id ? 'selected' : ''); ?>>
                                                    <?php echo e($member->users->name ?? ''); ?>

                                                </option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                    </div>
                                <?php endif; ?>

                                <div class="col">
                                    <select class="form-control" name="status">
                                        <option value="" <?php if(request()->status === ''): ?> selected <?php endif; ?>>Select Task Type</option>
                                        <option value="1" <?php if(request()->status == 1): ?> selected <?php endif; ?>>Working</option>
                                        <option value="0" <?php if(request()->status == 0): ?> selected <?php endif; ?>>Hold</option>
                                    </select>
                                </div>
                                
                                <!-- Date Range Picker -->
                                <div id="reportrange" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%" class="col">
                                    <i class="bi bi-funnel-fill"></i> &nbsp;
                                    <span></span> <i class="fa fa-caret-down"></i>
                                    <input type="hidden" name="start_date" id="start_date" value="<?php echo e(request()->start_date); ?>">
                                    <input type="hidden" name="end_date" id="end_date" value="<?php echo e(request()->end_date); ?>">
                                </div>

                                <div class="col">
                                    <button  class="btn btn-outline-success mx-2">Filter</button>
                                    <a href="<?php echo e(url('project/task/'.($project->id ?? 0))); ?>" class="btn btn-outline-danger">Reset</a>
                                </div>
                            </div>
                        </form>
                        <br>
                        <!-- Default Table -->
                        <table class="table">
                            <thead>
                                <tr class="bg-light">
                                    <th scope="col">#</th>
                                    <th scope="col">Task</th>
                                    <th scope="col">Assign To</th>
                                    <th scope="col">Estimate Time</th>
                                    <th scope="col">Type</th>
                                    <th scope="col">Created Date</th>
                                    <?php if(Auth::user()->hasRole(['Super-Admin','Admin','Project-Manager'])): ?>
                                    <th scope="col">Status Action</th>
                                    <th>Action</th>
                                    <?php endif; ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(count($data) > 0): ?>
                                <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $d): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <th scope="row"> <?php echo e($data->firstItem() + $key); ?>. </th>
                                        <td><?php echo e($d->name); ?> <br>
                                            <i class="bi bi-arrow-return-right" style="color: #201afb;"></i> 
                                            Task given by <strong><?php echo e($d->organiser->name ?? 'N/A'); ?></strong> - 
                                            <span data-bs-toggle="tooltip" title="<?php echo e($d->organiser->getRoleNames()->first() ?? 'No Role'); ?>">
                                                <?php echo e($d->organiser->getRoleNames()->first() ?? 'No Role'); ?>

                                            </span>
                                        </td>
                                        <td style="width:20%;font-size:15px;">
                                        <?php if($d->users->isNotEmpty()): ?>
                                            <?php $__currentLoopData = $d->users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <div>
                                                    <Strong><?php echo e($user->name); ?></Strong> (<?php echo e($user->getRoleNames()->implode(', ') ?? 'No Role'); ?>)
                                                </div>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        <?php else: ?>
                                            NULL
                                        <?php endif; ?>
                                            <br>
                                        </td>
                                        <td>
                                            <span  data-toggle="tooltip" data-placement="top" title="<?php echo e($d->dates ?? ''); ?>" class="badge bg-success text-white" >
                                                <?php if($d->type == 1): ?>
                                                    Daily
                                                <?php elseif($d->type == 2): ?>
                                                    Weekly
                                                <?php elseif($d->type == 3): ?>
                                                    Monthly
                                                <?php else: ?>
                                                     Specific Dates
                                                <?php endif; ?>
                                            </span>
                                        </td>
                                        <?php if($d->status == 0): ?>
                                            <td><span class="btn btn-sm btn-info " >On Hold </span> </td>
                                        <?php else: ?>
                                            <td><span class="btn btn-sm btn-success" > Working </span> </td>
                                        <?php endif; ?>
                                        <td>
                                            <?php echo e(date("d M,Y",strtotime($d->created_at))); ?> <br>
                                            <span class="badge text-white bg-danger "> Estimate Time : <?php echo e($d->estimated_time ?? 'N/A'); ?> min</span>
                                        </td>
                                        <?php if(Auth::user()->hasRole(['Super-Admin','Admin','Project-Manager'])): ?>
                                        <?php if($d->status == 1): ?>
                                        <td>
                                            <a class="btn btn-sm btn-info text-white" href="#" onclick="confirmHoldTask(<?php echo e($d->id); ?>,'hold'); return false;">Hold Task</a>
                                        </td>
                                        <?php else: ?>
                                            <td><a class="btn btn-sm btn-success" onclick="confirmHoldTask(<?php echo e($d->id); ?>,'resume'); return false;"> Resume Task  </a> </td>
                                        <?php endif; ?>
                                        <th>
                                            <a href="<?php echo e(route('tasks.edit',$d->id)); ?>" class="btn btn-md btn-success">
                                                <i class="fa fa-pencil" ></i>Edit
                                            </a>
                                            <a href="<?php echo e(route('task.delete',$d->id)); ?>" onclick="return confirm('Are you sure ?')" class="btn btn-md btn-danger">
                                                <i class="fa fa-pencil" ></i>Delete
                                            </a>
                                        </th>
                                        <?php endif; ?>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php else: ?>
                                <tr>
                                    <td colspan="8"><center>NO DATA FOUND</center></td>
                                </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                        <!-- End Default Table Example -->
                        <div class="row">
                            <div class="col-9">
                                <?php if($data->total() > 0): ?>
                                    Showing <?php echo e($data->firstItem()); ?> to <?php echo e($data->lastItem()); ?> of <?php echo e($data->total()); ?> entries
                                <?php endif; ?>
                            </div>
                            <div class="col-3">
                                <?php echo e($data->appends(request()->query())->links()); ?>

                            </div>
                        </div>  
                    </div>
                </div>
            </div>
        </div>
    </section>
    <div class="modal" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Task Hold </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form  method="POST" enctype="multipart/form-data" class="hold-form"> 
                        <?php echo csrf_field(); ?>
                        <div class="row">
                            <div class="col-12">
                                <label for="holdTask">Hold Task</label><br>
                                <div class="taskUser"></div>
                                <small id="error-executive" class="form-text error text-muted"></small>
                            </div>
    
                            <!-- Task Members will be dynamically populated here -->
                            
                            <div class="col-12 my-3">
                                <label for="remark">Remark</label>
                                <textarea name="remark" cols="4" class="form-control"></textarea>
                            </div>
                            <input type="hidden" id="type" value="hold" name="type">
                            <div class="col-3">
                                <button type="submit" class="btn btn-primary">Hold Task</button>
                            </div>
                        </div>  
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="modal" id="resumeModel" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Task Resume </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form  method="POST" enctype="multipart/form-data" class="hold-form"> 
                        <?php echo csrf_field(); ?>
                        <div class="row">
                            <div class="col-12">
                                <label for="holdTask">Resume Task</label><br>
                                <div class="taskUser"></div>
                                <small id="error-executive" class="form-text error text-muted"></small>
                            </div>
    
                            <!-- Task Members will be dynamically populated here -->
                            
                            <div class="col-12 my-3">
                                <label for="remark">Remark</label>
                                <textarea name="remark" cols="4" class="form-control"></textarea>
                            </div>
                            <input type="hidden" id="type" value="resume" name="type">
                            <div class="col-3">
                                <button type="submit" class="btn btn-primary">Resume Task</button>
                            </div>
                        </div>  
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <script>
      function confirmHoldTask(id, type) {
    let actionText = (type === 'hold') ? 'hold' : 'resume';
    let actionTitle = (type === 'hold') ? 'Hold this task?' : 'Resume this task?';

    Swal.fire({
        title: 'Are you sure?',
        text: 'Do you really want to ' + actionText + ' this task?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, ' + actionText + ' it!',
        cancelButtonText: 'No, cancel!'
    }).then((result) => {
        if (result.isConfirmed) {
            HoldTask(id, type); // Make the AJAX request to fetch task users
        }
    });
}

function HoldTask(id, type) {
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url: '<?php echo e(route('task.user')); ?>',
        type: 'POST', // Use POST or GET based on your preference
        data: { id: id, type: type },
        success: function (response) {
            if (response.success) {
                // Clear existing content in the modal to prevent duplication
                $('#exampleModal .taskUser .task-members, #resumeModel .taskUser .task-members').remove();
                
                // Create new content with task users
                let taskMembersHtml = '<div class="task-members">';
                response.taskmembers.forEach(function(member) {
                    taskMembersHtml += '<input type="checkbox" name="user[]" value="' + member.user_id + '"> ' +
                        (member.users ? member.users.name : '') + '<br>';
                });
                taskMembersHtml += '</div>';

                // Append the new content to the respective modal
                if (type === "hold") {
                    $('.hold-form').attr('action', '/user/tasks/status/' + id + '/0');
                    $('#exampleModal .taskUser').prepend(taskMembersHtml);
                    $('#exampleModal').modal('show');
                } else {
                    $('.hold-form').attr('action', '/user/tasks/status/' + id + '/1');
                    $('#resumeModel .taskUser').prepend(taskMembersHtml);
                    $('#resumeModel').modal('show');
                }
            } else {
                toastr.error('Unable to fetch task users.');
            }
        },
        error: function () {
            toastr.error('An error occurred.');
        }
    });
}

    </script>
    
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $component = $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>

<?php /**PATH /home/adxventure/lara_tms/resources/views/admin/tasks/index.blade.php ENDPATH**/ ?>