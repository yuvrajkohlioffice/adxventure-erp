<?php if (isset($component)) { $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54 = $component; } ?>
<?php $component = App\View\Components\AppLayout::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('app-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(App\View\Components\AppLayout::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script src="path/to/jquery-ui.multidatespicker.js"></script>


  <link rel="stylesheet" type="text/css" href="https://unpkg.com/trix@2.0.8/dist/trix.css">
  <script type="text/javascript" src="https://unpkg.com/trix@2.0.8/dist/trix.umd.min.js"></script>
    
    <div class="pagetitle">
        <h1> Create Task of  Project : <?php echo e($data->name ?? ''); ?></h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?php echo e(url('/')); ?>">Home</a></li>
                <li class="breadcrumb-item active">Create Task</li>
            </ol>
        </nav>
    </div>
    <!-- End Page Title -->
    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body p-4">
                                <form autocomplete="off" data-method="POST" data-action="<?php echo e(route('tasks.store')); ?>" id="ajax-form">
                                    <?php echo csrf_field(); ?>
                                    <input type="hidden" name="project" value="<?php echo e($id); ?>" />
                                    <div class="row">
                                        <div class="col-md-12">
                                             <div class="form-group">
                                                <label for="exampleInputEmail1">Task Name</label>
                                                <input type="text" class="form-control" id="exampleInputEmail1" name="name" value="<?php echo e(old('name')); ?>" placeholder="Enter name..">
                                                <small id="error-name" class="form-text error text-muted"></small>
                                            </div>
                                        </div>
                                        <!-- <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="exampleInputEmail1">Task Priority</label>
                                                <select class="form-control" name="category">
                                                    <option value="">SELECT</option>
                                                    <option value="1">Normal</option>
                                                    <option value="2">Medium</option>
                                                    <option value="3">High</option>
                                                    <option value="4">Urgent</option>
                                                </select>
                                                <small id="error-category" class="form-text error text-muted"></small>
                                            </div>
                                        </div> -->
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="exampleInputEmail1">Task Type</label>
                                                <select class="form-control" name="type" id="type">
                                                <option value="1">Daily</option>
                                                <option value="4">Once</option>
                                                <option value="2">Weekly</option>
                                                <option value="3">Monthly</option>
                                                </select>
                                                <small id="error-type" class="form-text error text-muted"></small>
                                            </div>
                                            </div>

                                            <div class="row" style="display:none;" id="dates">
                                            <div class="col-md-6">
                                                <label for="exampleInputEmail2">Task Start Date</label>
                                                <input type="text" class="form-control" id="datePick" name="assign_dates" />
                                                <small id="error-assign_dates" class="form-text error text-muted"></small>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="exampleInputEmail2"> Task Deadline Date</label>
                                                <input type="date" class="form-control" id="exampleInputEmail2" name="deadline" min="<?php echo e(date('Y-m-d')); ?>">
                                                <small id="error-deadline" class="form-text error text-muted"></small>
                                            </div>
                                            </div>
                                            <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="exampleInputEmail2">Estimate time (Note:- In Minutes)</label>
                                                <input type="number" class="form-control" id="exampleInputEmail2" name="estimated_time" placeholder="Enter Estimate time..">
                                                <small id="error-estimated_time" class="form-text error text-muted"></small>
                                            </div>
                                        </div>
                                        <?php if(Auth::user()->hasRole(['Technology Tech Lead','	Technology Manager','Digital Marketing Manager','Super-Admin','Admin','Project-Manager'])): ?>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="exampleInputEmail1">Assign Employee</label><br>
                                                    <?php if(count($data->users) > 0): ?>
                                                        <?php $__currentLoopData = $data->users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <input type="checkbox" name="executive[]" value="<?php echo e($user->id); ?>" >  &nbsp; <?php echo e($user->name ?? 0); ?> (<?php echo e($user->role->name ?? 0); ?>) &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
                                                    
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    <?php endif; ?>
                                                <small id="error-executive" class="form-text error text-muted"></small>
                                            </div>
                                        </div>
                                        <?php endif; ?>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="exampleInputPassword1">Task Description</label>
                                                <input id="x" type="hidden" name="description">
                                                <trix-editor input="x"></trix-editor>
                                               <small id="error-description" class="form-text error text-muted"></small>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="exampleInputEmail1">Attachement:</label>  &nbsp; &nbsp;
                                                <input type="checkbox" id="exampleInputEmail2" name="attachment" />
                                                &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;                        
                                                <label for="exampleInputEmail1">Remark:</label> &nbsp; &nbsp;
                                                <input type="checkbox" id="exampleInputEmail2" name="remark" />
                                                &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;                        
                                                <label for="exampleInputEmail1">Url:</label> &nbsp; &nbsp;
                                                <input type="checkbox" id="exampleInputEmail2" name="url"/>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <br>
                                            <button id="submit-btn" type="submit" class="btn btn-primary btn-lg">
                                            <span class="loader" id="loader" style="display: none;"></span>
                                            Create Task</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
<script src="https://cdn.jsdelivr.net/gh/dubrox/Multiple-Dates-Picker-for-jQuery-UI@master/jquery-ui.multidatespicker.js"></script>

<script>
  $(document).ready(function () {
    $('#dates').hide(); // Initially hide the date section

    let isMultiPickerActive = false; // Track if multi-date picker is active

    // Function to initialize single date picker
    function initializeSingleDatePicker() {
      $('#datePick').multiDatesPicker('destroy'); // Destroy multi-date picker if initialized
      $('#datePick').val(''); // Clear the input field
      $('#datePick').datepicker({
        dateFormat: 'yy-mm-dd'
      });
    }

    // Function to initialize multi-date picker
    function initializeMultiDatePicker() {
      if (!isMultiPickerActive) {
        $('#datePick').datepicker('destroy'); // Destroy single date picker if initialized
        $('#datePick').val(''); // Clear the input field
        $('#datePick').multiDatesPicker({
          dateFormat: 'yy-mm-dd',
          maxPicks: 10 // Limit to a maximum of 7 dates
        });
        isMultiPickerActive = true;
      }
    }

    // Trigger when task type changes
    $('#type').change(function () {
      let selectedType = $(this).val();
      
      // Show or hide dates section based on task type
      if (selectedType == "4") { // If "Once" is selected
        initializeSingleDatePicker(); // Initialize single date picker
        isMultiPickerActive = false; // Reset multi picker flag
        $('#dates').show(); // Show the date section
      } 
      else if (selectedType == "2") { // If "Weekly" is selected
        initializeMultiDatePicker(); // Initialize multi-date picker
        $('#dates').show(); // Show the date section
      } 
      else {
        $('#dates').hide(); // Hide the dates section for other task types
      }
    });
  });
</script>

 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $component = $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>

<?php /**PATH /home/adxventure/lara_tms/resources/views/admin/tasks/create.blade.php ENDPATH**/ ?>