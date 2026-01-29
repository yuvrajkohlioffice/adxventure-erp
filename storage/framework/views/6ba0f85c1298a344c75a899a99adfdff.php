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
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.14.0/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="/resources/demos/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <style>
        .ui-datepicker {
        width: 19em !important;
        }
        .highlighted-date {
            background-color: red !important;
            color: white !important;
        }
   
        .event a {
            background-color: black !important;
            color: red !important;
            border: 5px solid red !important;
        }
        .btn{
            margin:5px !important;
        }
        .highlighted-red a {
            background-color: red !important;
            color: white !important;
            border:red !important;
        }

        /* Highlighted green for completion rates of 100 */
        .highlighted-green a {
            background-color: green !important;
            color: white !important;
            border:green !important;
        }

        .nav-tabs .nav-item.show .nav-link, .nav-tabs .nav-link.active {
            color: #fff !important;
            background-color: blue !important;
            border-color: blue !important;
        }
   
        .fixed.busy {
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            z-index: 99999;
            display: flex;
            position: fixed;
            background: rgba(0, 0, 0, 0.25);
            align-items: center;
            justify-content: center;
        }
       
    </style>

    <div class="pagetitle">
        <a style="float:right; margin-left:10px" class="btn btn-md btn-primary" data-bs-toggle="modal" data-bs-target="#OtherReportModal">Other Report</a>
        <h1>All Assign Tasks <?php if(request()->userData): ?> || User Task : <?php echo e(request()->userData->name); ?>  <?php endif; ?> </h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?php echo e(url('/')); ?>">Home</a></li>
                <li class="breadcrumb-item active">Task</li>
            </ol>
        </nav>
    </div>
   
    <?php if($errors->any()): ?>
        <div class="alert alert-danger">
            <ul>
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li><?php echo e($error); ?></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </div>
    <?php endif; ?>

    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row mt-5">
                            <div class="col-md-9">
                               <form id="ajaxFormData" style="margin-bottom:20px;">
                                    <input type="hidden" id="projectID" name="project" value="" />
                                    <div class="row">
                                        <div class="col-md-5"> 
                                            <input type="date" format="d-m-Y" class="form-control start_date" id="start_date" name="start_date" placeholder="Select date" value="<?php echo e(request()->start_date ?? ''); ?>" />
                                            <input type="hidden" class="form-control" id="dateSet" value="" />
                                        </div>
                                        <div class="col-md-4">
                                            <select class="form-control" name="status">
                                                <option value="">SELECT STATUS</option>
                                                <option value="0">Pending</option>
                                                <option value="4">Done</option>
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <button class="btn btn-md btn-success pull-right">
                                                <i class="fa fa-search"></i> Filter
                                            </button>
                                            <a href="" class="btn btn-danger"> Refresh </a>
                                        </div>
                                    </div>
                                    <!-- <a  href="<?php echo e(route('task.generateReport',($_GET['id'] ?? ''))); ?>">Generate Report</a> -->
                                </form>

                                <div class="row">
                                    <div class="col-xxl-4 col-md-4">
                                        <div class="card info-card bg-primary">
                                            <div style="padding:0px 20px !important;" class="card-body">
                                            <h5 class=" text-white card-title"> Total Task <span class="text-white"></span>    </h5>
                                            <p style="font-size:30px;font-weight:700;"  id="totalTaskShow" class="text-white" ><?php echo e(request()->totalTask ?? '0'); ?></p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xxl-4 col-md-4">
                                        <div class="card info-card bg-danger">
                                            <div style="padding:0px 20px !important;"  class="card-body">
                                                <h5 class=" text-white card-title"> Pending Task <span class="text-white"></span>    </h5>
                                                <p style="font-size:30px;font-weight:700;" id="totalPendingShow" class="text-white" ><?php echo e(request()->pendingTask ?? '0'); ?></p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xxl-4 col-md-4">
                                        <div class="card info-card bg-success">
                                            <div style="padding:0px 20px !important;"  class="card-body">
                                            <h5 class=" text-white card-title"> Completed Task <span class="text-white"></span>    </h5>
                                            <p style="font-size:30px;font-weight:700;"  id="totalComplatingShow" class="text-white" ><?php echo e(request()->doneTask ?? '0'); ?></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div id="datepicker1" style="width:100% !important;"> 
                            </div>
                        </div>
                        <?php if(isset($projectsTasksCount) && count($projectsTasksCount) > 0): ?>
                            <ul class="nav nav-tabs" id="myTab" role="tablist">
                                <!-- Project tabs loop -->
                                <?php $__currentLoopData = $projectsTasksCount; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $project): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <li class="nav-item">
                                        <button class="projectButton nav-link" id="<?php echo e($project['project_id']); ?>-tab" 
                                            data-tab="<?php echo e($project['project_id']); ?>" 
                                            data-filter="project" 
                                            data-project="<?php echo e($project['project_id']); ?>"
                                            type="button" role="tab" aria-selected="false"> 
                                            <?php echo e(ucfirst($project['name'])); ?> (<?php echo e($project['task_count']); ?>)<!-- Corrected 'nmae' to 'name' -->
                                        </button>
                                    </li>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </ul>
                        <?php else: ?>
                            <p>No projects available.</p>
                        <?php endif; ?>
                        <div id="projectData" class="pt-2">
                            
                        </div>
                    
                    <div class="col-md-12 mt-3">
                        <?php if(isset($data) && $data->count() > 0 ): ?>
                            <table id="myTable" class="table table-striped">
                                <thead>
                                    <tr class="bg-light">
                                        <th scope="col">#</th>
                                        <th scope="col">Task</th>
                                        <th scope="col">Priority</th>
                                        <th scope="col">Type</th>
                                        <th scope="col">Deadline</th>
                                        <th scope="col">Status</th>
                                        <?php if(Auth::user()->roles()->whereIn('id', [1, 2, 11, 12, 21])->exists()): ?>
                                        <th scope="col">Estimate time</th>
                                        <th scope="col">Task time</th>
                                        <?php endif; ?>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                   
                                    <?php echo $__env->make('admin.tasks.tabledata', ['data' => $data], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                </tbody>
                            </table>
                        <?php else: ?>
                            <h4 class="py-5">
                            <center>There is no Task for today , Enjoy !! </center>
                            </h4>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
          
            <!-- <div class="col-md-3 col-lg-3">
                <div  id="projectFDetails" class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <h5 style="font-weight:800;margin:12px 5px;">PROJECT DETAILS</h5>
                            </div>
                            <div class="col-md-12">
                                <table style="margin-top:15px !important;" class=" table table-stripped">
                                    <thead>
                                        <tr class="bg-primary text-white">
                                            <th colspan="2">Project</th>
                                        </tr>
                                    </thead>
                                    <tr>
                                        <th> Project Name </th>
                                        <th id="projectName"></th>
                                    </tr>
                                    <tr>
                                        <th> Project Website </th>
                                        <th ><a id="projectWebsite" target="_blank" href="">  </a></th>
                                    </tr>
                                    <tr>
                                        <th> Manager Name</th>
                                        <th id="projectManager"></th>
                                    </tr>
                                    <tr>
                                        <th> Manager Contact No.</th>
                                        <th id="projectManagerContact"></th>
                                    </tr>
                                    <tr>
                                        <th> Team Leader Name </th>
                                        <th id="projectTeamleader"></th>
                                    </tr>
                                    <tr>
                                        <th> Team Contact No. </th>
                                        <th id="projectTeamleaderContact"></th>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div> -->
        </div>
    </section>


    <form autocomplete="off" data-method="POST" data-action="<?php echo e(route('report.user.store')); ?>" id="send-ajax-form-report" enctype="multipart/form-data">
        <?php echo csrf_field(); ?>
        <div class="modal submitReportModal" id="" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">
                            Submit Report of this Task
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="tasdkIdReport" name="id" value="" />
                        <input type="hidden" name="type" value="endTaskReport" />
                        <!-- <div class="form-group">
                            <label for="exampleInputEmail2">Submit Date</label>
                            
                            <small id="error-submit_date" class="form-text error text-muted"></small>
                        </div> -->
                        <input type="hidden" class="form-control modalInput" id="submit_date" name="submit_date" placeholder="Enter submit_date"  readonly />
                        <div class="form-group">
                            <label for="exampleInputEmail2">Task Status</label>
                            <select class="form-select" name="task_status">
                                <option selected disabled> Select task status</option>
                                <option value="in_progress">In a Progress</option>
                                <option value="Completed">Completed</option>
                            </select>
                            <small id="error-task_status" class="form-text error text-danger"></small>
                        </div>
                        <div id="attach" class="form-group attachment">
                            <label for="exampleInputEmail1">Attachement:</label><br />
                            <input type="file" id="exampleInputEmail2" class="form-control modalInput" name="attachment[]" multiple />
                            <small id="error-attachment" class="form-text error text-muted"></small>
                        </div>
                        <div id="remarkable" class="form-group">
                            <label for="exampleInputPassword1">Remark</label>
                            <textarea class="form-control modalInput" rows="7" name="remark"></textarea>
                            <small id="error-remark" class="form-text error text-muted"></small>
                        </div>
                        <div id="url" class="form-group">
                            <label for="exampleInputPassword1">Url</label>
                            <input type="url" class="form-control modalInput" name="url">
                            <small id="error-url" class="form-text error text-danger"></small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            Close
                        </button>
                        <button id="submit-btn" type="submit" class="btn btn-primary">
                            <span class="loader" id="loader" style="display: none"></span>
                            Submit
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <!-- Modal -->
    <div class="modal" id="genrateReportModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <form autocomplete="off" data-method="POST" data-action="" id="sendGenrateReport" enctype="multipart/form-data">
            <?php echo csrf_field(); ?>
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">
                        Send Report Email
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <input type="hidden" name="totalTask" id="totalTask" >
                <input type="hidden" name="completeTask" id="completeTask">
                <input type="hidden" name="PendingTask" id="PendingTask">
                <div class="modal-body">
                    <input type="hidden" id="ReportDate" name="date" value="" />
                    <div class="form-group">
                        <label for="exampleInputPassword1">Send Report Email Remark</label>
                        <textarea id="emailRemark" class="form-control modalInput" rows="7" name="remark"></textarea>
                        <small id="error-remark" class="form-text error text-muted"></small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        Close
                    </button>
                    <button id="submit-btn" type="submit" class="btn btn-primary">
                        <span class="loader" id="loader" style="display: none"></span>
                        Submit
                    </button>
                </div>
            </div>
        </form>
        </div>
    </div>

    <div class="modal" id="ViewDetailsModal" tabindex="-1" aria-labelledby="ViewDetailsModal" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-light">
                    <h5 style="font-weight:550;" class="modal-title">
                        Task description:
                    </h5>
                    <button type="button" class="btn-close cutom-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <b> <div id="taskDetailsDiv"></div></b>
                </div> 
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary cutom-close" data-bs-dismiss="modal">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal" id="rejectModel" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Task Resume </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form  method="POST" enctype="multipart/form-data" id="reject-form"> 
                        <?php echo csrf_field(); ?>
                            <div class="col-12 my-3">
                                <label for="remark">Remark</label>
                                <textarea name="remark" cols="4" class="form-control"></textarea>
                            </div>
                            <div class="col-3">
                                <button type="submit" class="btn btn-primary" >Reject</button>
                            </div>
                        </div>  
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal" id="report" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Reject Reason</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                
                </div>
            </div>
        </div>
    </div>

    <div class="modal" id="MarkAsComplete" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel"> Mark as Complete</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="<?php echo e(route('task.complete')); ?>">
                        <?php echo csrf_field(); ?>
                        <input type="hidden" name="task_id"  id="task_id" value="">
                        <div class="form-group">
                            <label for="remark">Remark</label>
                            <textarea name="remark" cols="4" class="form-control"></textarea>
                        </div>
                        <button type="submit" class="btn btn-success">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>



    <div class="modal" id="OtherReportModal" tabindex="-1" aria-labelledby="ViewDetailsModal" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-light">
                    <h5 style="font-weight:550;" class="modal-title">Other Task</h5>
                    <button type="button" class="border-0" style="background: border-box;" data-bs-dismiss="modal" aria-label="Close"><i class="bi bi-x-circle-fill" style="font-size: x-large;"></i></button>
                </div>
                <form action="<?php echo e(route('report.other')); ?>" method="POST" id="otherTaskReport">
                    <?php echo csrf_field(); ?>
                    <div class="modal-body" style="max-height:50vh;overflow-y:auto;">
                        <div class="alert alert-warning d-flex align-items-center py-1" role="alert">
                            <i class="bi bi-exclamation-triangle-fill me-2"></i>
                            <div>
                                <strong>Note:</strong> Please enter <strong>other tasks</strong> only here. 
                                <span class="text-danger">Project-related tasks should be added in the <strong>Project section</strong>.</span>
                                Also, make sure to enter <strong>task time in minutes</strong>.
                            </div>
                        </div>
                        <div class="task-container">
                            <div class="row task-row mb-4">
                                <div class="col-8">
                                    <input type="text" name="task_name[]" class="form-control" placeholder="Enter task..">
                                    <div class="text-danger error-task-name small"></div>
                                </div>
                                <div class="col-2">
                                    <input type="number" name="task_timing[]" class="form-control" placeholder="Task timing (min)..">
                                    <div class="text-danger error-task-timing small"></div>
                                </div>
                                <div class="col-2">
                                    <button type="button" class="btn btn-success btn-add" style="margin:0!important;">+</button>
                                    <button type="button" class="btn btn-danger btn-remove" style="margin:0!important;">-</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary cutom-close" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success">Submit Report</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
<script src="https://code.jquery.com/jquery-3.7.1.js"></script>
<script src="https://code.jquery.com/ui/1.14.0/jquery-ui.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
<script src="https://cdn.rawgit.com/dubrox/Multiple-Dates-Picker-for-jQuery-UI/master/jquery-ui.multidatespicker.js"></script>

<script>

    // LOADER
    function busy(show) {
        let $body = $('body');
        let $html = '<div class="fixed busy"><div class="card"><div class="card-body d-flex align-items-center flex-column py-2 px-3"><img src="/assets/images/svgs/img_loader.svg" width="32px"><small style="font-size:12px;">Loading...</small></div></div></div>';

        $body.find('.fixed.busy').remove();

        if(show) {
            $body.append($html);
        }

        console.log('Inside Busy Loader Function');
    }

    $(document).ajaxStart(function() {
        busy(1);
    });

    $(document).ajaxStop(function() {
        busy();
    });

    // update Time 
    function updateTime() {
        const rows = document.querySelectorAll("#myTable tr");
        rows.forEach((row, index) => {
            if (row.hasAttribute('data-given-time')) {
                const givenTime = new Date(row.getAttribute('data-given-time'));
                const now = new Date();
                const diff = now - givenTime;
                const hours = Math.floor(diff / 1000 / 60 / 60);
                const minutes = Math.floor(diff / 1000 / 60) % 60;
                const seconds = Math.floor(diff / 1000) % 60;
                var timePassedElement = `${hours}h ${minutes}m ${seconds}s ago`;
                $("#timePassed"+index).text(timePassedElement);
            }
        });
    }

    document.addEventListener('DOMContentLoaded', function () {
        function handleTabClick(event) {
            if (!event.target.classList.contains('projectButton')) return;
            const tabButtons = document.querySelectorAll('.projectButton');
            // Remove 'active' class and 'aria-selected' from all buttons
            tabButtons.forEach(button => {
                button.classList.remove('active');
                button.setAttribute('aria-selected', 'false');
            });
            // Add 'active' class and 'aria-selected' to the clicked button
            event.target.classList.add('active');
            event.target.setAttribute('aria-selected', 'true');
            // Optional: Log the clicked tab's data for debugging
            console.log(`Tab clicked: ${event.target.getAttribute('data-tab')}, Project ID: ${event.target.getAttribute('data-project')}`);
        }
        // Automatically set the first tab as active by default
        const firstTabButton = document.querySelector('.projectButton');
        if (firstTabButton) {
            firstTabButton.classList.add('active');
            firstTabButton.setAttribute('aria-selected', 'true');
        }
        // Attach event listener using event delegation on the parent container
        document.getElementById('myTab').addEventListener('click', handleTabClick);
    });


    //callender
    $(function() {
        const dailyReport = <?php echo json_encode($dailyReport); ?>;
        function getCompletionRates() {
            const completionRates = {};
            dailyReport.forEach(item => {
                const dateString = item.date;
                const date = new Date(dateString);
                date.setDate(date.getDate() - 1); // Subtract 1 day
                const adjustedDateString = date.toISOString().split('T')[0]; // Format as YYYY-MM-DD
                completionRates[adjustedDateString] = parseFloat(item.complation_rate);
            });

            return completionRates;
        }

        function normalizeDate(date) {
            const newDate = new Date(date);
            newDate.setHours(0, 0, 0, 0);
            return newDate;
        }

        $('#datepicker1').datepicker({
            dateFormat: "yy-mm-dd",
            // beforeShowDay: function(date) {
            //     console.log(dailyReport);
            //     if()
            //     // const completionRates = getCompletionRates();
            //     // const normalizedDate = normalizeDate(date);
            //     // const dateString = normalizedDate.toISOString().split('T')[0]; 
            //     // const completionRate = completionRates[dateString];
            //     // if (completionRate !== undefined) {
            //     //     if (completionRate < 100) {
            //     //         return [true, 'highlighted-red', 'Completion rate is less than 100'];
            //     //     } else {
            //     //         return [true, 'highlighted-green', 'Completion rate is 100'];
            //     //     }
            //     // }
            //     // return [true, '', '']; 
            // },
            beforeShowDay: function(date) {
                let formattedDate = $.datepicker.formatDate("yy-mm-dd", date);
                let report = dailyReport.find(item => item.date === formattedDate);
                if (report) {
                    if (report.total_task == report.complate_task) {
                        return [true, 'highlighted-green', 'Completion rate is 100%'];
                    } else {
                        return [true, 'highlighted-red', 'Tasks are pending'];
                    }
                }
                return [true, '', ''];
            },
            onSelect: function(dateText, inst) {
                var start_date = "start_date=" + dateText;
                TaskData(start_date);
            },
       
        });
    });

    // Task Get 
    $(document).on('click', '.projectButton', function(event) {
        event.preventDefault();
        var filterType = $(this).data('filter'); 
        var filterValue = $(this).data('project'); 
        var activeClass =  $(this).data('tab'); 
        var start_date = $('#dateSet').val();
        var filterValue1 = filterValue;
        if(!filterValue1){
            filterValue1=0;
        }
        $('#projectID').val(filterValue);
        $('.projectButton').removeClass('active');
        $('#'+activeClass+'-tab').addClass('active');
        var id = "<?php echo e($id ?? ''); ?>";
        if (id) {
            var href = "<?php echo e(route('project.task', ':id')); ?>";
            href = href.replace(':id', id);
        }
        $.ajax({
            url: href, 
            type: 'GET',
            data: {
                'project' : filterValue1,
            },
            success: function (response) {
                $('#myTable tbody').empty(); 
                $('#projectData').empty(); 
                $('#myTable tbody').append(response.data); 
                $('#totalTaskShow').text(response.totalTask ?? '0')
                $('#totalPendingShow').text(response.pendingTask ?? '0')
                $('#totalComplatingShow').text(response.doneTask ?? '0')
                if(response.projectData){
                    $('#projectData').append(`
                        <div class="col-md-12">
                            <button class="btn btn-md btn-secondary">Project Name : ${response.projectData.name}</button> <!-- Access name here -->
                            <a data-toggle="tooltip" data-placement="top" title="Project : ${response.projectData.name}" 
                            data-date="${response.date}"
                            class="btn btn-md btn-success pull-right sendReportTab" href="#" 
                            data-href="<?php echo e(url('task/send/GenerateReport/${response.projectData.id}')); ?>">
                                Send Today Report : ${response.date} 
                            </a>
                            <hr>
                        </div>
                    `); 
            }
            },  
            error: function (err) {
                toastr.info("Error! Please Contact Admin.");
            },
        });
        var filterParams = { [filterType]: filterValue, ['start_date']: start_date, };
        TaskData(filterParams);
    });

    // start task 
    $(document).on("click",'.startTask',function (event) {
        event.preventDefault();          
        $(".error").text("");
        var message = $(this).data('da');
        var taskId = $(this).data("taskid");
        var dateId = $(this).data("dateid");  
        var token = $('meta[name="csrf-token"]').attr('content');
        var date = $('#dateSet').val();
        var type = "startTask";
        let dataa;let confirmAction;
        if(date){
            dataa = { type:type,task_id: taskId, dateId: dateId, date:date,message:message }; 
        }else{
            dataa = { type:type,task_id: taskId, dateId: dateId,message:message };
        }
        if (message === "startTask") {
            confirmAction = confirm("Are you sure you want to start this task?");
        } else if (message === "endTask") {
            confirmAction = confirm("Are you sure this task is done?");
        } else if (message === "pausedTask") {
            confirmAction = confirm("Are you sure you want to paused this task?");
        } else if (message === "resumeTask") {
            confirmAction = confirm("Are you sure you want to resume this task?");
        } else {
            confirmAction = confirm("Are you sure you want to proceed?");
        }

        if (!confirmAction) {
            return false;
        }

        $.ajax({
            headers: {
                'X-CSRF-TOKEN': token 
            },
            url: "<?php echo e(route('project.task')); ?>", 
            type: 'GET',
            data: dataa,
            success: function (response) {
                toastr.options = {
                    positionClass: 'toast-top-right',
                    closeButton: true, 
                    progressBar: true, 
                    timeOut: 3000, 
                    extendedTimeOut: 1000, 
                    iconClass: 'toast-success-icon'
                };
                
                $('#myTable tbody').empty();
                $('#myTable tbody').append(response.data); 
                // $('#myTable tbody tr').hide().each(function(index, row) {
                //     // Delay each row's slide-down for staggered animation effect
                //     $(row).delay(100 * index).slideDown(500); // 500ms for smoother animation
                // });
                if (response.errors) {
                    // Display validation errors
                    var msg = Object.keys(response.errors)[0];
                    msg = response.errors[msg];
                    $.each(response.errors, function (field, message) {
                        var ff = field.replace(/\./g, "-");
                        $("#error-" + ff).text(message[0]);
                    });
                    // toastr.error(msg);
                } else if (response.success) {
                    // Handle successful submission
                    // toastr.success("Success! Form Submitted successfully.");
                    var filterValue = $('#projectID').val();
                    var filterParams = { ['project']: filterValue , ['start_date'] : date };
                    TaskData(filterParams);
                }
            },
            error: function (err) {
                toastr.info("Error! Please Contact Admin.");
            },
        });
    });

    // filter Form
    $(document).on('submit','#ajaxFormData',function(e){
        e.preventDefault();
        var formData = $(this).serialize(); 
        TaskData(formData);
    });

    function TaskData(filterParams = {}){
            $('#tableData').html('');
            var dd = $('#start_date').val();
            $('#dateSet').val(dd);
            filterParams = convertQueryStringToObject(filterParams);
            <?php if(request()->userId): ?>
                filterParams.userId = "<?php echo e(request()->userId); ?>";
            <?php endif; ?>
            <?php if(request()->project): ?>
                filterParams.project = "<?php echo e(request()->project); ?>";
                filterParams.projectId = "yes";
            <?php endif; ?>
        
            $.ajax({
            url: "<?php echo e(route('project.task.ajax')); ?>", 
            type: "GET",
            data: filterParams,
            success: function (response) {
                // console.log(response);
                $('#tableData').html(response);
            },
            error: function (err) {
                toastr.info("Error! Please Contact Admin.");
            },
        });
    }

    $(document).on('click','.sendReportTab',function(){
        var href = $(this).data('href');
        var date = $(this).data('date');
        $('#sendGenrateReport').data('action', href);
        $('#sendGenrateReport').attr('data-action', href);
        $('#ReportDate').val(date);
        $('#emailRemark').val('');
        $('#genrateReportModal').modal('show');
    });

    $(document).on('submit','#sendGenrateReport',function(e){
        e.preventDefault();
        confirmAction = confirm("Are you sure you want to proceed?");
        if (!confirmAction) {
            return false; 
        }
        var url = $(this).data("action");
        console.log(url);
        // $(this).attr('disabled',true);
        var method = $(this).data("method");
        var formData = new FormData(this);
        $.ajax({
            url: url,
            type: method,
            data: formData,
            processData: false,
            contentType: false,
            success: function (response) {
                $('#genrateReportModal').modal('hide');
                if (response.errors) {
                    toastr.success(response.error);
                } else if (response.success) {
                    toastr.success(response.message);
                }
                console.log(response);
            },
            error: function (err) {
                toastr.info("Error! Please Contact Admin.");
            },
        });
    });

    $(document).on('click', '.submitReport', function() {
        $('.form-control').each(function() {
            $(this).val('');
        });

        var taskId = $(this).data('id'); 
        var date = $(this).data('date'); 
        var attachement =  $(this).data('attachement'); 
        var attach = $(this).data('attach');
        var report  = $(this).data('remarkable');
        var url  = $(this).data('url');
        if(attach == "1"){
            $('#attach').show();
        }
        if(url == "1"){
            $('#url').show();
        }

        if(report == "1"){
            $('#remarkable').show();
        }

        $('#tasdkIdReport').val(taskId);
        $('#submit_date').val(date);
        $('.submitReportModal').modal('show');
    });

    // Function to convert query string to an object
    function convertQueryStringToObject(queryString) {
        var obj = {};
        var params = new URLSearchParams(queryString);
        for (var pair of params.entries()) {
            if (pair[1].length > 0) { // only add to object if value is not empty
                obj[pair[0]] = pair[1];
            }
        }
        return obj;
    }

    $(document).on("submit",'#send-ajax-form-report',function (event) {
        event.preventDefault();
        var url = $(this).data("action");
        var method = $(this).data("method");
        var formData = new FormData(this);
        $.ajax({
            url: url,
            type: method,
            data: formData,
            processData: false,
            contentType: false,
            success: function (response) {
                if (response.errors) {
                    // If there are validation errors, display them
                    var msg = Object.keys(response.errors)[0];
                    msg = response.errors[msg];
                    $.each(response.errors, function (field, message) {
                        var ff = field.replace(/\./g, "-");
                        $("#error-" + ff).text(message[0]);
                    });
                    toastr.error(msg);
                } else if (response.status === 'created') {
                    toastr.success("Success! " + response.message);

                    // Hide the modal and clear the input fields
                    $('.submitReportModal').modal('hide');
                    $('.modalInput').val('');

                    // Get filter parameters for TaskData function
                    var filterValue = $('#projectID').val();
                    var date = $('#dateSet').val();
                    var filterParams = { 
                        'project': filterValue, 
                        'start_date': date 
                    };
                    // Call TaskData function with filter parameters
                    TaskData(filterParams);
                    $('#totalTaskShow').text(response.data.totalTask ?? '0')
                    $('#totalPendingShow').text(response.data.pendingTask ?? '0')
                    $('#totalComplatingShow').text(response.data.doneTask ?? '0')
                    location.reload();
                }
            },
            error: function (err) {
                toastr.info("Error! Please Contact Admin.");
            },
        });
    });

    
    function RejectReport(id) {
        Swal.fire({
            title: 'Are you sure?',
            text: 'Do you really want to Reject this Report?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, Reject it!',
            cancelButtonText: 'No, cancel!'
        }).then((result) => {
            if (result.isConfirmed) {
                Reject(id); // Make the AJAX request to fetch task users
            }
        });
    }

    function Reject(id) {
        $('#reject-form').attr('action', '/report/' + id + '/1');
        $('#rejectModel').modal('show');
    }

    function RejectView(remark){
        $('#report').modal('show');
        $('.modal-body').text(remark);
    }

    function MarkAsComplete(id){
        $('#task_id').val(id);
        $('#MarkAsComplete').modal('show');
    }

    $('#totalTask').val(<?php echo e(request()->totalTask ?? '0'); ?>)
    $('#PendingTask').val(<?php echo e(request()->pendingTask ?? '0'); ?>)
    $('#completeTask').val(<?php echo e(request()->doneTask ?? '0'); ?>)


    setInterval(updateTime, 1000); 
    TaskData();


    $(document).on('click','.viewDetails',function(e){
        e.preventDefault();
        var id = $(this).data('id');
        var token = $('meta[name="csrf-token"]').attr('content');
        var url = "<?php echo e(url('get/task/details')); ?>";
        // $(this).attr('disabled',true);
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': token
            },
            url: url, 
            type: 'POST',
            data: { id:id},
            success: function (response) {
                console.log(response.data.description);
                $('#taskDetailsDiv').html(response.data.description);
                $('#ViewDetailsModal').show();
                // $(this).attr('disabled',false);
            },
            error: function (err) {
                toastr.error(response.error);
            },
        });
    });

    $(document).on('click','.cutom-close',function(){
        $('#ViewDetailsModal').hide();
    });
</script>
<script>
    $(document).ready(function() {
        // Add new row with slideDown animation
        $(document).on('click', '.btn-add', function() {
            let clone = $(this).closest('.task-row').clone();
            clone.find('input').val(''); // clear values
            clone.hide(); // hide before adding
            $('.task-container').append(clone);
            clone.slideDown(300); // animate slideDown
        });

        // Remove row with slideUp animation
        $(document).on('click', '.btn-remove', function() {
            if ($('.task-row').length > 1) {
                $(this).closest('.task-row').slideUp(300, function() {
                    $(this).remove();
                });
            } else {
                alert('At least one task row is required.');
            }
        });

        // ========== Real-Time Validation Clear ==========
        $(document).on('input change', 'input[name="task_name[]"], input[name="task_timing[]"]', function() {
            $(this).siblings('.text-danger').text('');
        });

        // ===== Form validation before submit =====
        $('#otherTaskReport').on('submit', function(e) {
            e.preventDefault(); // stop normal submit

            let isValid = true;

            $('.task-row').each(function() {
                let nameInput = $(this).find('input[name="task_name[]"]');
                let timeInput = $(this).find('input[name="task_timing[]"]');
                let nameError = $(this).find('.error-task-name');
                let timeError = $(this).find('.error-task-timing');

                nameError.text('');
                timeError.text('');

                // Validate task name
                if ($.trim(nameInput.val()) === '') {
                    nameError.text('Task name is required.');
                    isValid = false;
                }

                // Validate task timing
                let timingValue = $.trim(timeInput.val());
                if (timingValue === '') {
                    timeError.text('Task timing is required.');
                    isValid = false;
                } else if (!$.isNumeric(timingValue)) {
                    timeError.text('Task timing must be a numeric value.');
                    isValid = false;
                } else if (Number(timingValue) <= 0) {
                    timeError.text('Task timing must be greater than 0.');
                    isValid = false;
                }
            });

            // If valid, submit or process data
            if (isValid) {
                // alert('Form submitted successfully!');
                // You can now send data via AJAX or proceed with actual form submission
                this.submit();
                busy(1);
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
<?php endif; ?><?php /**PATH /home/bookmziw/adx_tms/laravel/resources/views/admin/user/tasks.blade.php ENDPATH**/ ?>