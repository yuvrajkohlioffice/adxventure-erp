<?php if (isset($component)) { $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54 = $component; } ?>
<?php $component = App\View\Components\AppLayout::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('app-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(App\View\Components\AppLayout::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
    <?php $__env->startSection('title','Quotation'); ?>
        <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <div class="pagetitle">
        <h1> Quotation</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?php echo e(url('/')); ?>">Home</a></li>
                <li class="breadcrumb-item">Quotation</li>
                <li class="breadcrumb-item active">Client Details</li>
            </ol>
        </nav>
    </div>
    <!-- End Page Title -->
    <section class="section">
        <div class="row">
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-body p-4">
                        <form autocomplete="off" data-method="POST" data-action="<?php echo e(route('crm.lead.update', ['id' => $lead->id])); ?>" id="ajax-form" enctype="multipart/form-data">
                            <?php echo csrf_field(); ?>
                            <input type="hidden" name="client" value="1">
                            <div class="row">
                                <div class="col-md-12">
                                    <label for="name">Name</label>
                                    <input type="text" class="form-control" id="name" name="name" value="<?php echo e($lead->name); ?>" placeholder="Enter name.." required>
                                    <small id="error-name" class="form-text error text-danger"></small>
                                </div>
                                <div class="col-md-12  mt-3">
                                    <label for="company_nmae">Company Name</label>
                                    <input type="text" class="form-control" id="company_nmae" name="company_name" value="<?php echo e($lead->company_name); ?>" placeholder="Enter Company name..">
                                    <small id="error-company_name" class="form-text error text-danger"></small>
                                </div>
                                <div class="col-md-6  mt-3">
                                    <label for="email">Email</label>
                                    <input type="email" class="form-control" id="email" name="email" value="<?php echo e($lead->email); ?>" placeholder="Enter Email..">
                                    <small id="error-email" class="form-text error text-danger"></small>
                                </div>
                                <div class="col-md-6  mt-3">
                                    <label for="country-select">Country<span class="text-danger">*</span></label>
                                    <select id="country-select" name="country" class="form-select" required onchange="syncPhoneCode()">
                                        <option selected disabled>Select Country..</option>
                                        <?php $__currentLoopData = $countries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $country): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($country->id); ?>" data-phonecode="<?php echo e($country->phonecode); ?>" <?php if($lead->country == $country->id): ?> selected <?php endif; ?>><?php echo e($country->nicename); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                    <small id="error-country" class="form-text error text-danger"></small>
                                </div>
                                <?php 
                                $phone = explode('-',$lead->phone);
                                $phone_no = $phone[1] ?? $lead->phone;
                                ?>
                                <div class="col-md-6 mt-3">
                                    <label for="phone-code-select">Phone Code<span class="text-danger">*</span></label>
                                    <select id="phone-code-select" name="phone_code" class="form-select" required onchange="syncCountry()">
                                        <option selected disabled>Select Country Code..</option>
                                        <?php $__currentLoopData = $countries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $country): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($country->phonecode); ?>" data-countryid="<?php echo e($country->id); ?>" <?php if($phone[0] == $country->phonecode): ?> selected <?php endif; ?>><?php echo e($country->phonecode); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                    <small id="error-phone_code" class="form-text error text-danger"></small>
                                </div>
                                <div class="col-md-6 mt-3">
                                    <label for="phone">Phone No.<span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" id="phone" name="phone" value="<?php echo e($phone_no); ?>" placeholder="Enter Mobile No..." required minlength="1" maxlength="15"> 
                                    <small id="error-phone" class="form-text error text-danger"></small>
                                </div>
                                <div class="col-md-6 mt-3">
                                    <label for="city">City</label>
                                    <input type="text" class="form-control" id="city" name="city" value="<?php echo e($lead->city); ?>" placeholder="Enter  City name..">
                                    <small id="error-city" class="form-text error text-danger"></small>
                                </div>
                                <div class="col-md-6 mt-3">
                                    <label for="website">Website</label>
                                    <input type="text" class="form-control" id="website" name="website" value="<?php echo e($lead->website); ?>" placeholder="Enter Website Url..">
                                    <small id="error-website" class="form-text error text-danger"></small>
                                </div>
                                <div class="col-md-6 mt-3">
                                    <label for="domain">Domain expiry date</label>
                                    <input type="date" class="form-control" id="domain" name="domian_expire" value="<?php echo e($lead->domian_expire); ?>">
                                    <small id="error-domain_expiry_date" class="form-text error text-danger"></small>
                                </div>
                                <div class="col-md-6 mt-3">
                                    <label for="category">Client Category<span class="text-danger">*</span></label>
                                    <select name="client_category"  id="category" class="form-select" placeholder="Select Client Category.." required>
                                        <option selected value="<?php echo e($lead->client_category); ?>"><?php echo e($lead->category->name ?? 'N/A'); ?></option>
                                        <?php if(isset($categories)): ?>
                                        <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($category->category_id); ?>"><?php echo e($category->name); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        <?php endif; ?>
                                    </select>                                    
                                    <small id="error-client_category" class="form-text error text-danger"></small>
                                </div>  
                                <div class="col-md-12  mt-3">
                                    <label for="project_category">Project Category</label>
                                    <select name="project_category[]"  id="project_category" class="form-select select-2-multiple" multiple placeholder="Select Project Category.." >
                                        <?php
                                            $projectCategoryIds = json_decode($lead->project_category, true) ?? [];
                                            $allSelected = (count($projectCategoryIds) === count($projectCategories));
                                        ?>
                                        <?php if(isset($projectCategories)): ?>
                                            <?php $__currentLoopData = $projectCategories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($category->id); ?>" <?php echo e(in_array($category->id, $projectCategoryIds) ? 'selected' : ''); ?>><?php echo e($category->name); ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        <?php endif; ?>
                                    </select>
                                    <small id="error-project_category" class="form-text error text-danger"></small>
                                </div>
                                <div class="col-md-3 mt-3">
                                    <button id="submit-btn" type="submit" class="btn btn-primary">
                                        Save & next
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        $(document).ready(function() {
            $('.select-2-multiple').select2({
            placeholder: "Select one or more options",
            allowClear: true,
            width: '100%'
            });
        });
        
        function syncPhoneCode() {
            var countrySelect = document.getElementById('country-select');
            var phoneCodeSelect = document.getElementById('phone-code-select');
            var selectedCountryId = countrySelect.value;
            var selectedOption = Array.from(countrySelect.options).find(option => option.value === selectedCountryId);
            var phoneCode = selectedOption ? selectedOption.getAttribute('data-phonecode') : '';
            Array.from(phoneCodeSelect.options).forEach(option => {
                option.selected = option.value === phoneCode;
            });
        }

        function syncCountry() {
            var countrySelect = document.getElementById('country-select');
            var phoneCodeSelect = document.getElementById('phone-code-select');
            var selectedPhoneCode = phoneCodeSelect.value;
            var selectedOption = Array.from(phoneCodeSelect.options).find(option => option.value === selectedPhoneCode);
            var countryId = selectedOption ? selectedOption.getAttribute('data-countryid') : '';
            Array.from(countrySelect.options).forEach(option => {
                option.selected = option.value === countryId;
            });
        }
        
    </script>


 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $component = $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?><?php /**PATH /home/adxventure/lara_tms/resources/views/admin/crm/prposal/client.blade.php ENDPATH**/ ?>