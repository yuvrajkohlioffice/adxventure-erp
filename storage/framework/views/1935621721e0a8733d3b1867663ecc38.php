<?php
    $date = date('d-m-Y');
?>
<?php $__empty_1 = true; $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $d): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
<tr id="row<?php echo e($key); ?>"
    <?php if(isset($d->taskDate)): ?> 
        data-given-time="<?php echo e($d->taskDate['start_date']); ?>" 
    <?php endif; ?>>
    <th scope="row"> <?php echo e($data->firstItem() + $key); ?>.</th>
    <td style="width:20%;"><?php echo e($d->name); ?> <br> 
        <a  href="javacscript:void(0)" data-id="<?php echo e($d->id); ?>" class="viewDetails"  >View Task Details </a> <br>
        <span class="badge bg-success"> Task given by <?php echo e($d->organiser->name ?? ''); ?> </span>
    </td>
    <td>
        <?php if($d->category == 1): ?>
        <span class="badge bg-info text-white"> NORMAL</span>
        <?php elseif($d->category == 2): ?>
        <span class="badge bg-warning text-white"> MEDIUM </span>
        <?php elseif($d->category == 3): ?>
        <span class="badge bg-danger text-white"> HIGH </span>
        <?php elseif($d->category == 4): ?>
        <span class="badge bg-danger text-white"> URGENT </span>
        <?php endif; ?>
    </td>
    <td>
        <?php if($d->type == 1): ?>
            <span class="badge bg-success text-white"> DAILY </span>
        <?php elseif($d->type == 2): ?>
            <span class="badge bg-success text-white"> WEEKLY </span>
        <?php elseif($d->type == 3): ?>
            <span class="badge bg-success text-white"> MONTHLY </span>
        <?php elseif($d->type == 4): ?>
            <span class="badge bg-success text-white"> ONCE </span>
        <?php endif; ?>
    </td>
    <td>
    <?php echo e($d->deadline ? \Carbon\Carbon::parse($d->deadline)->format('F j, Y') : "N/A"); ?>


    </td>
    <td>
        <?php if($d->report): ?>
            <span class="badge bg-primary text-white"> Done </span>
        <?php else: ?>
        <span class="badge bg-danger text-white"> Pending   </span>
            <?php if(isset($d->taskDate) && $d->taskDate['start_date'] ): ?>
            <span class="badge bg-danger text-white"> On process..   </span>
            <?php endif; ?> 
        <?php endif; ?>
    </td>
    <?php if(Auth::user()->roles()->whereIn('id', [1, 2, 11, 12, 21])->exists()): ?>
    <td><?php echo e($d->estimated_time ?? 'N/A'); ?> min</td>
    <td><?php echo e($d->task_timing ?? 'N/A'); ?> min</td>
    <?php endif; ?>
    <td>
        <?php if(Auth::user()->hasRole(['Digital Marketing Executive','Technology Executive','Technology Tech Lead','Digital Marketing Intern','Project-Manager','Digital Marketing Manager','Graphic Designing Intern'])): ?>
        <?php if(!isset($d->taskDate)): ?>

            <!-- Start Task Button -->
            <button data-taskid="<?php echo e($d->id); ?>" data-dateid="<?php echo e($d->taskdatestiming->id ?? '0'); ?>" data-da="startTask" class="startTask btn btn-primary btn-sm">
                <i class="fa fa-clock-o"></i> Start Task
            </button>
        <?php elseif(isset($d->taskDate) && !isset($d->taskDate['end_date'])): ?>
            <!-- Task in Progress -->
            <?php if(!isset($d->taskDate['paused_time'])): ?>
                <!-- Pause and End Task Buttons -->
                <button data-taskid="<?php echo e($d->id); ?>" data-dateid="<?php echo e($d->taskdatestiming->id ?? '0'); ?>" data-da="pausedTask" class="startTask btn btn-warning btn-sm">
                    <i class="bi bi-pause"></i> Pause
                </button>
                
                <?php if(empty($d->report)): ?>
                        <?php if(auth()->user()->role_id != 1 ): ?>
                        <a data-id="<?php echo e($d->id); ?>" data-date="<?php echo e($date ?? ''); ?>" data-attach="<?php echo e($d->attachment); ?>" data-remarkable="<?php echo e($d->remark_needed); ?>" data-url="<?php echo e($d->url); ?>"
                        href="javascript:void(0)" data-href="<?php echo e(route('report.user.create',$d->id)); ?>"
                        class="btn btn-sm btn-success submitReport">
                            <i class="fa fa-pencil"></i>End Task
                        </a>
                        <?php endif; ?>
                <?php endif; ?>
        
            <?php elseif(isset($d->taskDate['paused_time']) && !isset($d->taskDate['restart_time'])): ?>
                <!-- Restart Button (if task is paused) -->
                <button data-taskid="<?php echo e($d->id); ?>" data-dateid="<?php echo e($d->taskdatestiming->id ?? '0'); ?>" data-da="resumeTask" class="startTask btn btn-info btn-sm">
                    <i class="bi bi-pause"></i> Restart
                </button>
        
            <?php else: ?>
                <!-- End Task Button (if task is active or resumed) -->
                
                <?php if(empty($d->report)): ?>
                        <?php if(auth()->user()->role_id != 1 ): ?>
                        <a data-id="<?php echo e($d->id); ?>" data-date="<?php echo e($date ?? ''); ?>" data-attach="<?php echo e($d->attachment); ?>" data-remarkable="<?php echo e($d->remark_needed); ?>" data-url="<?php echo e($d->url); ?>"
                        href="javascript:void(0)" data-href="<?php echo e(route('report.user.create',$d->id)); ?>"
                        class="btn btn-sm btn-success submitReport">
                            <i class="fa fa-pencil"></i>End Task
                        </a>
                        <?php endif; ?>
                <?php endif; ?>
            <?php endif; ?>
    
        <?php elseif(!isset($d->taskDate['end_date'])): ?>
            <!-- End Task Button if no end_date -->
            
            <?php if(empty($d->report)): ?>
                <?php if(auth()->user()->role_id != 1 ): ?>
                <a data-id="<?php echo e($d->id); ?>" data-date="<?php echo e($date ?? ''); ?>" data-attach="<?php echo e($d->attachment); ?>" data-remarkable="<?php echo e($d->remark_needed); ?>" data-url="<?php echo e($d->url); ?>"
                href="javascript:void(0)" data-href="<?php echo e(route('report.user.create',$d->id)); ?>"
                class="btn btn-sm btn-success submitReport">
                    <i class="fa fa-pencil"></i> End Task
                </a>
                <?php endif; ?>
            <?php endif; ?>
        <?php else: ?>
        
            
            <?php if(empty($d->report)): ?>
                <?php if(auth()->user()->role_id != 1 ): ?>
                <a data-id="<?php echo e($d->id); ?>" data-date="<?php echo e($date ?? ''); ?>" data-attach="<?php echo e($d->attachment); ?>" data-remarkable="<?php echo e($d->remark_needed); ?>" data-url="<?php echo e($d->url); ?>"
                href="javascript:void(0)" data-href="<?php echo e(route('report.user.create',$d->id)); ?>"
                class="btn btn-sm btn-success submitReport">
                    <i class="fa fa-pencil"></i>End Task
                </a>
                <?php endif; ?>
                <?php elseif($d->report): ?>
                    <?php if($d->type == 4): ?>
                     
                    <button class="btn btn-danger btn-sm" onclick="MarkAsComplete(<?php echo e($d->id); ?>)">Mark as Complete</button>
                        
                    <?php endif; ?>
                    <?php if($d->report->status != 1): ?>
                    <a href="<?php echo e(route('report.attachments',$d->id)); ?>" class="btn btn-sm btn-success">
                        <i class="fa fa-pencil"></i>View Doc
                    </a>
                    <?php if(Auth::user()->hasRole(['Project-Manager'])): ?>
                        <a href="#" onClick="RejectReport(<?php echo e($d->report->id); ?>)" class="btn btn-md btn-danger btn-sm" >Reject Report</a>
                    <?php endif; ?>
                    <?php else: ?>
                        <?php if(auth()->user()->role_id != 1 ): ?>
                        <a data-id="<?php echo e($d->id); ?>" data-date="<?php echo e($date ?? ''); ?>" data-attach="<?php echo e($d->attachment); ?>" data-remarkable="<?php echo e($d->remark_needed); ?>" data-url="<?php echo e($d->url); ?>"
                        href="javascript:void(0)"   data-href="<?php echo e(url('/report/' . $d->report->id . '/1')); ?>"
                        class="btn btn-sm btn-primary submitReport">
                            <i class="fa fa-pencil"></i>Submit Again
                        </a>
                        <?php endif; ?>
                        <span class="badge bg-danger" onclick="RejectView('<?php echo e($d->report->reject_remark); ?>')" style="cursor:pointer"> Report Reject</span><br>
                    <?php endif; ?>
                <?php endif; ?>  
                <?php endif; ?>
            <?php elseif($d->report): ?>
                <?php if($d->report->status != 1): ?>
                    <a href="<?php echo e(route('report.attachments',$d->id)); ?>" class="btn btn-sm btn-success">
                        <i class="fa fa-pencil"></i>View Doc
                    </a>
                    <?php if(Auth::user()->roles()->whereIn('id', [1, 2, 11, 12, 21])->exists()): ?>
                        <a href="#" onClick="RejectReport(<?php echo e($d->report->id); ?>)" class="btn btn-md btn-danger" >Reject Report</a>
                    <?php endif; ?>
                    <?php else: ?>
                        <span class="badge bg-danger" onclick="RejectView('<?php echo e($d->report->reject_remark); ?>')" style="cursor:pointer"> Report Reject</span><br>
                    <?php endif; ?>
                <?php else: ?>
                    N/A       
                <?php endif; ?>
    </td>
</tr>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
<tr>
    <td colspan="8">
        <center>There is no Task for today , Enjoy !! </center>
    </td>
</tr>
<?php endif; ?>                            <?php /**PATH /home/adxventure/lara_tms/resources/views/admin/tasks/tabledata.blade.php ENDPATH**/ ?>