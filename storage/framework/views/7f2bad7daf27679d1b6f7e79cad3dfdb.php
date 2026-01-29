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
    <?php $__env->startSection('title','Leads-Create'); ?>
    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <?php $__env->startSection('css'); ?>
    <style>
        .col-md-6,.col-md-2,.col-md-4 {
            margin-top: 10px;
            margin-bottom: 10px;
        }
        label {
            font-weight: 600;
        }
    </style>
    <?php $__env->stopSection(); ?>

    <div class="pagetitle">
        <a style="float:right;margin-left:10px" class="btn btn-sm btn-primary" href="<?php echo e(route('crm.index')); ?>"><i class="bi bi-people-fill"></i> Leads</a>

        <button style="float:right;margin-left:10px" class="btn  btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#AddModel"><i class="bi bi-file-earmark-plus-fill"></i> Import Lead</button>
     
        <h1>Create Lead</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?php echo e(url('/')); ?>">Home</a></li>
                <li class="breadcrumb-item active">Create Lead</li>
            </ol>
        </nav>
    </div>
    <?php if($errors->any()): ?>
        <div class="alert alert-danger">
            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php if(is_array($error)): ?>
                    <strong>Row <?php echo e($error['row']); ?>:</strong>
                    <ul>
                        <?php $__currentLoopData = $error['errors']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $msg): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li><?php echo e($msg); ?></li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                <?php else: ?>
                    <li><?php echo e($error); ?></li>
                <?php endif; ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    <?php endif; ?>

    <!-- End Page Title -->
    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <?php echo $__env->make('include.alert', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                    <div class="card-body p-4">
                        <form autocomplete="off" data-method="POST" data-action="<?php echo e(route('crm.store')); ?>" id="ajax-form" enctype="multipart/form-data">
                            <?php echo csrf_field(); ?>
                            <div class="row">
                                <!-- Form Fields -->
                                <div class="col-md-6">
                                    <label for="name">Name<span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="name" name="name" value="<?php echo e(old('name')); ?>" placeholder="Enter name.." required>
                                    <small id="error-name" class="form-text error text-danger"></small>
                                </div>
                                <div class="col-md-6">
                                    <label for="name">Company Name<span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="name" name="company_name" value="<?php echo e(old('company_name')); ?>" placeholder="Enter Company  name.." required>
                                    <small id="error-company_name" class="form-text error text-danger"></small>
                                </div>
                                <div class="col-md-6">
                                    <label for="email">Email</label>
                                    <input type="email" class="form-control" id="email" name="email" value="<?php echo e(old('email')); ?>" placeholder="Enter Email..">
                                    <small id="error-email" class="form-text error text-danger"></small>
                                </div>
                                <div class="col-md-6">
                                    <label for="country-select">Country<span class="text-danger">*</span></label>
                                    <select id="country-select" name="country" class="form-select" required onchange="syncPhoneCode('country-select', 'phone-code-select')">
                                        <option selected disabled>Select Country..</option>
                                        <?php $__currentLoopData = $countries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $country): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($country->id); ?>" data-phonecode="<?php echo e($country->phonecode); ?>"><?php echo e($country->nicename); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                    <small id="error-country1" class="form-text error text-danger"></small>
                                </div>

                                <div class="col-md-2">
                                    <label for="phone-code-select">Phone Code<span class="text-danger">*</span></label>
                                    <select id="phone-code-select" name="phone_code" class="form-select" required onchange="syncCountry('phone-code-select', 'country-select')">
                                        <option selected disabled>Select Country Code..</option>
                                        <?php $__currentLoopData = $countries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $country): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($country->phonecode); ?>" data-countryid="<?php echo e($country->id); ?>"><?php echo e($country->phonecode); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                    <small id="error-phone_code1" class="form-text error text-danger"></small>
                                </div>                  
                                <div class="col-md-4">
                                    <label for="phone">Phone No.<span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" id="phone" name="phone" value="<?php echo e(old('phone')); ?>" placeholder="Enter Mobile No..." required minlength="1" maxlength="15"> 
                                    <small id="error-phone" class="form-text error text-danger"></small>
                                </div>
                              
                                <div class="col-md-6">
                                    <label for="city">City</label>
                                    <input type="text" class="form-control" id="city" name="city" value="<?php echo e(old('city')); ?>" placeholder="Enter City Name..">
                                    <small id="error-city" class="form-text error text-danger"></small>
                                </div>
                                <div class="col-md-6">
                                    <label for="client_category">Client Category<span class="text-danger">*</span></label>
                                    <select name="client_category" class="form-control" placeholder="Select Client Category.." required>
                                        <option selected disabled>Select Client Category</option>
                                        <?php if(isset($categories)): ?>
                                        <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($category->category_id); ?>"><?php echo e($category->name); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        <?php endif; ?>
                                    </select>
                                    <small id="error-client_category" class="form-text error text-danger"></small>
                                </div>
                                <div class="col-md-6">
                                    <label for="lead_status">Lead Status<span class="text-danger">*</span></label>
                                    <select name="lead_status" class="form-control" required>
                                        <option selected disabled>Select Lead Status</option>
                                        <option value="1">Hot</option>
                                        <option value="2">Warm</option>
                                        <option value="3">Cold</option>
                                    </select>
                                    <small id="error-lead_status" class="form-text error text-danger"></small>
                                </div>
                                <div class="col-md-6">
                                    <label for="lead_source">Lead Source<span class="text-danger">*</span></label>
                                    <select name="lead_source" class="form-control" required onchange="toggleReferenceName(this.value)">
                                        <option selected disabled>Select Lead Source</option>
                                        <option value="1">Website</option>
                                        <option value="2">Social Media</option>
                                        <option value="3">Reference</option>
                                        <option value="4">Bulk lead</option>
                                    </select>
                                    <small id="error-lead_source" class="form-text error text-danger"></small>
                                </div>

                                <div class="col-md-6" id="reference-name-container" style="display: none;">
                                    <div class="col-md-12">
                                        <label for="website">Reference Name<span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="ref_name" value="<?php echo e(old('ref_name')); ?>" placeholder="Enter Reference Name..">
                                        <small id="error-ref_name" class="form-text error text-danger"></small>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="col-md-12">
                                        <label for="website">Website</label>
                                        <input type="text" class="form-control" id="website" name="website" value="<?php echo e(old('website')); ?>" placeholder="Enter Website Url..">
                                        <small id="error-website" class="form-text error text-danger"></small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label for="project_category">Service<span class="text-danger">*</span></label>
                                    <select name="project_category[]" class="form-control select2-form1" multiple required>
                                        <?php if(isset($services)): ?>
                                            <?php $__currentLoopData = $services; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $service): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($service->id); ?>"><?php echo e($service->name); ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        <?php endif; ?>
                                    </select>
                                    <small id="error-project_category" class="form-text error text-danger"></small>
                                </div>

                                <?php if(!Auth::user()->hasRole(['BDE'])): ?>
                                <div class="col-md-6">
                                    <label for="assign_to">Assign to<span class="text-danger">*</span></label>
                                    <select name="assign_user" class="form-control">
                                        <option value="">Select Assigned User..</option>
                                        <?php if(isset($users)): ?>
                                            <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($user->id); ?>"><?php echo e($user->name); ?> (<?php echo e($user->roles->pluck('name')->implode(', ')); ?>)</option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        <?php endif; ?>
                                    </select>
                                    <small id="error-assign_user" class="form-text error text-danger"></small>
                                </div>
                                <div class="col-md-6" id="blank" style="display:block;"></div>
                                <?php else: ?>
                                <div class="col-md-6" id="blank" style="display:block;"></div>
                                <?php endif; ?>
                                
                                <div class="col-md-3 mt-3">
                                    <button id="submit-btn" type="submit" class="btn btn-primary">
                                        <span class="loader" id="loader" style="display: none;"></span>
                                        Create Lead
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Upload CSV Modal -->
    <div class="modal" id="AddModel" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-lg">
            <div class="modal-content" style="top:120px">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Bulk lead</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="<?php echo e(route('crm.csv')); ?>" method="POST" enctype="multipart/form-data">
                        <?php echo csrf_field(); ?>
                        <div class="row">
                                <div class="col-md-6">
                                <label for="country-select1">Country<span class="text-danger">*</span></label>
                                <select id="country-select1" name="country" class="form-select" required onchange="syncPhoneCode('country-select1', 'phone-code-select1')">
                                    <option selected disabled>Select Country..</option>
                                    <option></option>   
                                    <?php $__currentLoopData = $countries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $country): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($country->id); ?>" data-phonecode="<?php echo e($country->phonecode); ?>"><?php echo e($country->nicename); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                                <small id="error-country2" class="form-text error text-danger"></small>
                            </div>
                            <div class="col-md-6">
                                    <label for="city">City</label>
                                    <input type="text" class="form-control" id="city" name="city" value="<?php echo e(old('city')); ?>" placeholder="Enter City Name..">
                                    <small id="error-city" class="form-text error text-danger"></small>
                                </div>

                            <div class="col-md-6">
                                <label for="phone-code-select1">Phone Code<span class="text-danger">*</span></label>
                                <select id="phone-code-select1" name="phone_code" class="form-select" required onchange="syncCountry('phone-code-select1', 'country-select1')">
                                    <option selected disabled>Select Country Code..</option>
                                    <?php $__currentLoopData = $countries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $country): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($country->phonecode); ?>" data-countryid="<?php echo e($country->id); ?>"><?php echo e($country->phonecode); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                                <small id="error-phone_code2" class="form-text error text-danger"></small>
                            </div>

                            <div class="col-md-6">
                                    <label for="client_category">Client Category<span class="text-danger">*</span></label>
                                    <select name="client_category" class="form-control" placeholder="Select Client Category.." required>
                                        <option selected disabled>Select Client Category</option>
                                        <?php if(isset($categories)): ?>
                                        <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($category->category_id); ?>"><?php echo e($category->name); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        <?php endif; ?>
                                    </select>
                                    <small id="error-client_category" class="form-text error text-danger"></small>
                                </div>
                                <div class="col-md-6">
                                    <label for="lead_status">Lead Status<span class="text-danger">*</span></label>
                                    <select name="lead_status" class="form-control" required>
                                        <option selected disabled>Select Lead Status</option>
                                        <option value="1">Hot</option>
                                        <option value="2">Warm</option>
                                        <option value="3">Cold</option>
                                    </select>
                                    <small id="error-lead_status" class="form-text error text-danger"></small>
                                </div>
                                <?php if(!Auth::user()->hasRole(['BDE'])): ?>
                                <div class="col-md-6">
                                    <label for="assign_to">Assign to</label>
                                    <select name="assign_user" class="form-control">
                                        <option value="">Select Assigned User..</option>
                                        <?php if(isset($users)): ?>
                                            <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($user->id); ?>"><?php echo e($user->name); ?> (<?php echo e($user->roles->pluck('name')->implode(', ')); ?>)</option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        <?php endif; ?>
                                    </select>
                                    <small id="error-assign_user" class="form-text error text-danger"></small>
                                </div>
                                <div class="col-md-6" id="blank" style="display:block;"></div>
                                <?php else: ?>
                                <div class="col-md-6" id="blank" style="display:none;"></div>
                                <?php endif; ?>   
                                <div class="col-md-12">
                                    <label for="project_category">Service<span class="text-danger">*</span></label>
                                    <select name="project_category[]" class="form-control select2-form2" multiple required>
                                        <?php if(isset($services)): ?>
                                            <?php $__currentLoopData = $services; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $service): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($service->id); ?>"><?php echo e($service->name); ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        <?php endif; ?>
                                    </select>
                                    <small id="error-project_category" class="form-text error text-danger"></small>
                                </div>
                                <div class="my-3">
                                    <label for="csv_file" class="form-label">Select CSV file:</label>
                                    <input type="file" class="form-control" id="csv_file" name="file" required>
                                    <small id="error-file" class="form-text error text-danger"></small>
                                </div>
                        </div>
                        <button type="submit" class="btn btn-primary mb-3">Upload</button>
                    </form>
                    <a class="btn btn-sm btn-warning" href="<?php echo e(route('crm.sample')); ?>"  style="float:right;">Download Sample CSV</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
    $(function() {
        $('#country').select2({
        placeholder: 'Select a country',
        allowClear: true,
        width: '100%',
        theme: 'bootstrap4',
          minimumResultsForSearch: 0  // use 'bootstrap4' as a stable theme
        });
    });
    </script>
<script>
$(document).ready(function() {
    // Initialize form 1 immediately
    $('.select2-form1').select2({
        placeholder: "Select one or more options",
        allowClear: true,
        width: '100%'
    });

    // Reinitialize form 2 when modal is opened
    $('#AddModel').on('shown.bs.modal', function () {
        // Remove existing select2 if already initialized
        if ($('.select2-form2').hasClass("select2-hidden-accessible")) {
            $('.select2-form2').select2('destroy');
        }

        // Initialize it again
        $('.select2-form2').select2({
            dropdownParent: $('#AddModel'), // Important: ensures dropdown stays inside modal
            placeholder: "Select one or more options",
            allowClear: true,
            width: '100%'
        });
    });
});
</script>


   <script>
        function toggleReferenceName(value) {
            const referenceNameContainer = document.getElementById('reference-name-container');
            const blank = document.getElementById('blank');

            if (value == '3') {
                referenceNameContainer.style.display = 'block';
                blank.style.display = '<?php echo e(Auth::user()->hasRole("BDE") ? "block" : "none"); ?>';
            } else {
                referenceNameContainer.style.display = 'none';
                blank.style.display = '<?php echo e(Auth::user()->hasRole("BDE") ? "none" : "block"); ?>';
            }
        }
    </script>

<script>
function syncPhoneCode(countrySelectId, phoneCodeSelectId) {
    var countrySelect = document.getElementById(countrySelectId);
    var phoneCodeSelect = document.getElementById(phoneCodeSelectId);

    // Get selected country ID
    var selectedCountryId = countrySelect.value;

    // Find the phone code associated with the selected country
    var selectedOption = Array.from(countrySelect.options).find(option => option.value === selectedCountryId);
    var phoneCode = selectedOption ? selectedOption.getAttribute('data-phonecode') : '';

    // Set the phone code in the phone code select
    Array.from(phoneCodeSelect.options).forEach(option => {
        option.selected = option.value === phoneCode;
    });
}

function syncCountry(phoneCodeSelectId, countrySelectId) {
    var countrySelect = document.getElementById(countrySelectId);
    var phoneCodeSelect = document.getElementById(phoneCodeSelectId);

    // Get selected phone code
    var selectedPhoneCode = phoneCodeSelect.value;

    // Find the country ID associated with the selected phone code
    var selectedOption = Array.from(phoneCodeSelect.options).find(option => option.value === selectedPhoneCode);
    var countryId = selectedOption ? selectedOption.getAttribute('data-countryid') : '';

    // Set the country ID in the country select
    Array.from(countrySelect.options).forEach(option => {
        option.selected = option.value === countryId;
    });
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
<?php endif; ?>




 <?php /**PATH /home/bookmziw/lara_tms/laravel/resources/views/admin/crm/create.blade.php ENDPATH**/ ?>