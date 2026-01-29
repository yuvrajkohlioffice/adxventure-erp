<?php if (isset($component)) { $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54 = $component; } ?>
<?php $component = App\View\Components\AppLayout::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('app-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(App\View\Components\AppLayout::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
    <?php $__env->startSection('title','Candidates'); ?>
    <style>
        .col-3{
            float:right;
        }
    </style>
    <div class="pagetitle">
       
        <h1>Candidates</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?php echo e(url('/')); ?>">Home</a></li>
                <li class="breadcrumb-item active">Candidates</li>
            </ol>
        </nav>
    </div>
    <?php echo $__env->make('include.alert', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                    <button class="btn btn-primary mt-3" data-bs-toggle="modal" data-bs-target="#AddCandidate" >Add Candidates</button>
                        <div class="col-2 mt-3 mb-2 mx-2" style="float:right">
                            <input type="text" class="form-control" id="searchInput" placeholder="Search...">
                        </div> 
                        <table class="table table-striped table-bordered text-center mt-3">
                            <thead>
                                <tr class="bg-light">
                                    <th scope="col">#</th>
                                    <th scope="col">Add By</th>
                                    <th scope="col">Name</th>
                                    <th scope="col">Email</th>
                                    <th scope="col">Phone</th>
                                    <th scope="col">Date of Birth</th>
                                    <th scope="col">Interview</th>
                                    <th scope="col">Offer-letter</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i=1; ?>
                                <?php if(isset($users)): ?>
                                <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <th scope="row"><?php echo e($i++); ?></th>
                                        <td><?php echo e($user->user->name); ?></td>
                                        <td><?php echo e($user->name); ?></td>
                                        <td><?php echo e($user->email); ?></td>
                                        <td><?php echo e($user->phone); ?></td>
                                        <td><?php echo e($user->dob); ?></td>
                                        <td>
                                            <small>
                                                <div class="form-check form-check-inline text-start">
                                                    <input class="form-check-input checkbox-action" type="checkbox" id="inlineCheckbox3" data-id="<?php echo e($user->id); ?>" value="0" disabled <?php echo e($user->interview == 0 ? 'checked' : ''); ?>>
                                                    <label class="form-check-label" for="inlineCheckbox3">No Given Interview</label>
                                                </div><br>
                                                <div class="form-check form-check-inline text-start">
                                                    <input class="form-check-input checkbox-action" type="checkbox" id="inlineCheckbox1" data-id="<?php echo e($user->id); ?>" value="1" <?php echo e($user->interview == 1 ? 'checked' : ($user->interview == 2 || $user->interview == 3 ? 'checked' : '')); ?>>
                                                    <label class="form-check-label" for="inlineCheckbox1">Hr Round</label>
                                                </div><br>
                                                <div class="form-check form-check-inline text-start">
                                                    <input class="form-check-input checkbox-action" type="checkbox" id="inlineCheckbox2" data-id="<?php echo e($user->id); ?>" value="2" <?php echo e($user->interview == 2 || $user->interview == 3 ? 'checked' : ''); ?>>
                                                    <label class="form-check-label" for="inlineCheckbox2">Technical Round</label>
                                                </div><br>
                                                <div class="form-check form-check-inline text-start">
                                                    <input class="form-check-input checkbox-action" type="checkbox" id="inlineCheckbox4" data-id="<?php echo e($user->id); ?>" value="3" <?php echo e($user->interview == 3 ? 'checked' : ''); ?>>
                                                    <label class="form-check-label" for="inlineCheckbox4">Interview Crack</label>
                                                </div><br>
                                            </small>
                                        </td>
                                        <td>
                                            <?php if($user->interview == 3 && $user->intern != 1): ?>
                                                <a class="btn btn-primary btn-sm" onclick="GenrateOfferLetter(<?php echo e($user->id); ?>)">Genrate Offer letter</a>
                                                <?php if($user->offer_letter): ?>
                                                 <a href="<?php echo e(asset($user->offer_letter)); ?>" target="_blank" class="btn btn-warning btn-sm">View</a>
                                                 <?php endif; ?>
                                            <?php else: ?>
                                                <a href="<?php echo e(asset($user->offer_letter)); ?>" target="_blank" class="btn btn-danger btn-sm">Candidate Not Eligibile</a>
                                            <?php endif; ?>
                                        </td>
                                        <th>
                                            <?php if($user->offer_letter): ?>
                                                <?php if($user->status == 1): ?>
                                                <button  class="btn btn-sm btn-success">Employee Approved</button>
                                                <?php else: ?>
                                                <button  class="btn btn-sm btn-success" onclick="Approve(<?php echo e($user->id); ?>)">Approve</button>
                                                <?php endif; ?>
                                            <?php elseif($user->intern == 1): ?>
                                                <?php if($user->status == 1): ?>
                                                <button  class="btn btn-sm btn-success">Employee Approved</button>
                                                <?php else: ?>
                                                <button  class="btn btn-sm btn-success" onclick="Approve(<?php echo e($user->id); ?>,1)">Approve</button>
                                                <?php endif; ?>
                                            <?php else: ?>
                                                <?php if(Auth::user()->hasRole(['Super-Admin', 'Admin'])): ?>
                                                <a href="<?php echo e(route('candidates.destroy', ['candidate' => $user->id])); ?>" class="btn btn-sm btn-danger delete-btn"
                                                    onclick="event.preventDefault(); deleteConfirmation('<?php echo e(route('candidates.destroy', ['candidate' => $user->id])); ?>');">
                                                        Delete
                                                </a>
                                                <?php endif; ?>
                                            <?php endif; ?>
                                        </th>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php else: ?>
                                <tr>
                                    <td colspan="8">  <center>     NO DATA FOUND</center> </td>
                                </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Candidate Add Model  -->
    <div class="modal" id="AddCandidate" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content" style="top:120px">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add Candidate</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="ajax-form" data-action="<?php echo e(route('candidates.store')); ?>" data-method="POST">
                        <?php echo csrf_field(); ?>
                        <div class="row">
                            <div class="col-12 ">
                                <label for="name" class="form-label">Name</label>
                                <input type="text" class="form-control" name="name" placeholder="Enter Candidate Name.."  required>
                            </div>
                            <div class="col-12 mt-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" name="email" placeholder="Enter Candidate Email.." required>
                            </div>
                            <div class="col-12 mt-3">
                                <label for="phone_no" class="form-label">Phone No.</label>
                                <input type="number" class="form-control" name="phone" placeholder="Enter Candidate Phone No.." required>
                            </div>
                            <div class="col-12 mt-3">
                                <label for="dob" class="form-label">Date of Birth.</label>
                                <input type="date" class="form-control" name="date_of_birth" placeholder="Selct Candidate Date of Birth.." required>
                            </div>
                            <div class="col-12 mt-3">
                                <input type="checkbox" name="intern" value="1">
                                <label for="dob" class="form-label">Intern</label>
                            </div>
                        </div>
                        <div class="col-3 mt-3">
                            <button type="submit" class="btn btn-primary" style="float:right">Add</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
     <!-- Offer letter Model  -->
    <div class="modal" id="AddModel" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content" style="top:120px">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add Candidate</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form class="ajax-form" data-action="<?php echo e(route('offer.letter')); ?>" data-method="POST">
                        <?php echo csrf_field(); ?>
                        <input type="hidden" name="id">
                        <div class="row">
                            <div class="col-6">
                                <label for="name" class="form-label">Name</label>
                                <input type="text" class="form-control" name="name" placeholder="Enter Candidate Name.." required readonly>
                            </div>
                            <div class="col-6">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" name="email" placeholder="Enter Candidate Email.." required readonly>
                            </div>
                            <div class="col-6 mt-2">
                                <label for="phone_no" class="form-label">Phone No.</label>
                                <input type="number" class="form-control" name="phone" placeholder="Enter Candidate Phone No.." required readonly >
                            </div>
                            <div class="col-6 mt-2">
                                <label for="role" class="form-label">Role & Designation</label>
                                <select name="role" class="form-select" required>
                                    <option value="">Select Role & Designation</option>
                                    <?php $__currentLoopData = $roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($role->name); ?>"><?php echo e($role->name); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                        </div>  
                        <!-- Checkbox to show Before and After fields -->
                        <div class="form-check mt-3">
                            <input type="checkbox" class="form-check-input needBeforeAfterDetails"  value="1" name="checked">
                            <label class="form-check-label" for="needBeforeAfterDetails">Need CTC Before & After</label>
                        </div>
                        <div class="row ctcDetails">
                            <div class="col-6 mt-2">
                                <label for="ctc" class="form-label">CTC Amount</label>
                                <input type="number" class="form-control" name="ctc" placeholder="Enter Candidate CTC.." required>
                            </div>
                            <div class="col-6 mt-2">
                                <label for="period" class="form-label">CTC Period</label>
                                <input type="number" class="form-control" name="period" placeholder="Enter CTC Period.." min="0" max="12" required>
                            </div>
                        </div>
                        <!-- CTC Amount and Period Before and After (hidden by default) -->
                        <div  class="d-none row beforeAfterDetails">
                            <div class="col-6 mt-2">
                                <label for="before_ctc" class="form-label">CTC Amount Before</label>
                                <input type="number" class="form-control" name="before_ctc" placeholder="Enter CTC Amount Before.." min="0">
                            </div>
                            <div class="col-6 mt-2">
                                <label for="before_period" class="form-label">CTC Period Before</label>
                                <input type="number" class="form-control" name="before_period" placeholder="Enter CTC Period Before.." min="0" max="12">
                            </div>
                            <div class="col-6 mt-2">
                                <label for="after_ctc" class="form-label">CTC Amount After</label>
                                <input type="number" class="form-control" name="after_ctc" placeholder="Enter CTC Amount After.." min="0">
                            </div>
                            <div class="col-6 mt-2">
                                <label for="after_period" class="form-label">CTC Period After</label>
                                <input type="number" class="form-control" name="after_period" placeholder="Enter CTC Period After.." min="0" max="12">
                            </div>
                        </div>
                        <div class="col-3 mt-3">
                            <button type="submit" class="btn btn-primary" style="float:right">Send</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Approve  Model  -->
    <div class="modal" id="approve" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content" style="top:120px">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Approve </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form class="ajax-form" data-action="<?php echo e(route('add.employee')); ?>" data-method="POST" >
                        <?php echo csrf_field(); ?>
                        <div class="row">
                            <input type="hidden" name="candidate_id">
                            <div class="col-12">
                                <label for="name" class="form-label">Profile Image</label>
                                <input type="file" name="profile_image" class="form-control" required>
                            </div>
                            <div class="col-12 mt-3" >
                                <label for="name" class="form-label">Department</label>
                                <select name="department" class="form-select"  required>
                                    <option selected disabled>-- Select Department -- </option>
                                    <?php $__currentLoopData = $departments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $department): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($department->id); ?>"><?php echo e($department->name); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                            <div class="col-12 mt-2 intern">
                                
                            </div>
                        </div>
                        <div class="col-3 mt-3">
                            <button type="submit" class="btn btn-primary" style="float:right">Approve</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <?php echo e($users->links()); ?> 
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.needBeforeAfterDetails').change(function() {
                if ($(this).is(':checked')) {
                    $('.ctcDetails').addClass('d-none'); 
                    $('.beforeAfterDetails').removeClass('d-none');
                    $('.beforeAfterDetails input').attr('required', true); 
                    $('.ctcDetails input').removeAttr('required'); 
                } else {
                    $('.ctcDetails').removeClass('d-none'); 
                    $('.beforeAfterDetails').addClass('d-none'); 
                    $('.beforeAfterDetails input').removeAttr('required'); 
                    $('.ctcDetails input').attr('required', true); 
                }
            });
        });
    </script>    
    <script>
        function deleteConfirmation(deleteUrl) {
            swal({
                title: "Are you sure?",
                text: "Once deleted, you will not be able to recover this Offer Letter!",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            })
            .then((willDelete) => {
                if (willDelete) {
                    swal("Success!", "The Offer Letter has been deleted.", "success")
                        .then(() => {
                            // Redirect after the success message is acknowledged
                            window.location.href = deleteUrl;
                        });
                } else {
                    swal("Your Offer Letter is safe!", {
                        icon: "info",
                    });
                }
            });
        }
    </script>
    <script>
        // jQuery function to filter table rows based on search input
        $(document).ready(function() {
            $("#searchInput").on("keyup", function() {
                var value = $(this).val().toLowerCase();
                $("table tbody tr").filter(function() {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
                });
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            $('.checkbox-action').change(function(event) {
                event.preventDefault(); // Prevent the default checkbox behavior

                var checkbox = $(this);
                var roundType = checkbox.val();
                var id = checkbox.data('id');

                if (checkbox.is(':checked')) {
                    swal({
                        title: "Are you sure?",
                        text: "Do you want to mark this round as completed: " + roundType + " Round?",
                        icon: "warning",
                        buttons: true,
                        dangerMode: true,
                    })
                    .then((willConfirm) => {
                        if (willConfirm) {
                            $.ajax({
                                url: '<?php echo e(route("interview")); ?>',
                                type: 'POST',
                                data: {
                                    round: roundType,
                                    id: id,
                                    _token: '<?php echo e(csrf_token()); ?>'
                                },
                                success: function(response) {
                                    swal("Success!", "The round status has been updated.", "success")
                                    .then(() => {
                                        // Reload the page after the success message is acknowledged
                                        location.reload();
                                    });
                                },
                                error: function(error) {
                                    swal("Error!", "Failed to update the round status.", "error");
                                }
                            });
                        } else {
                            // Uncheck the box if canceled
                            checkbox.prop('checked', false);
                        }
                    });
                }
            });
        });
    </script>
    <Script>
        function GenrateOfferLetter(id){
            $.ajax({
                url: '<?php echo e(route("genrate.offer.letter")); ?>',
                type: 'POST',
                data: {
                    id: id,
                    _token: '<?php echo e(csrf_token()); ?>'
                },
                success: function(response) {
                    if(response.success) {
                    // Populate the modal input fields with the candidate data
                    $('input[name="id"]').val(response.candidate.id);
                    $('input[name="name"]').val(response.candidate.name);
                    $('input[name="email"]').val(response.candidate.email);
                    $('input[name="phone"]').val(response.candidate.phone);
                    $('select[name="role"]').val(response.candidate.role);
                    $('input[name="ctc"]').val(response.candidate.ctc);
                    $('input[name="period"]').val(response.candidate.ctc_period);
                    $('input[name="before_ctc"]').val(response.candidate.before_ctc);
                    $('input[name="before_period"]').val(response.candidate.before_ctc_period);
                    $('input[name="after_ctc"]').val(response.candidate.after_ctc);
                    $('input[name="after_period"]').val(response.candidate.after_ctc_period);
                    // Open the modal
                    $('#AddModel').modal('show');
                    }
                },
                error: function(error) {
                    swal("Error!", "Failed to Genrate the Offer letter.", "error");
                }
            });
        }
    </Script>
   <script>
    function Approve(id, intern = null) {
        if (intern) {
            var html = `
                <label for="role" class="form-label">Role & Designation</label>
                <input type="hidden" value="1" name="intern" >
                <select name="role" class="form-select" required>
                    <option value="">Select Role & Designation</option>
                    <?php $__currentLoopData = $roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($role->name); ?>"><?php echo e($role->name); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>`;
            $('.intern').html(html); // Inject the HTML into the div with class 'intern'
        } else {
            $('.intern').html(''); // Clear the intern role if not applicable
        }

        $('input[name="candidate_id"]').val(id); // Set the candidate ID in the hidden input field
        $('#approve').modal('show'); // Show the modal
    }
</script>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $component = $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>



<?php /**PATH /home/adxventure/lara_tms/resources/views/admin/candidates/index.blade.php ENDPATH**/ ?>