<?php if (isset($component)) { $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54 = $component; } ?>
<?php $component = App\View\Components\AppLayout::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('app-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(App\View\Components\AppLayout::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
    <?php $__env->startSection('title','Upsale'); ?>
    <style>
        .col-3{
            float:right;
        }
    </style>
    
    <div class="pagetitle">
        <button style="float:right;" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#AddModel" >Create Lead</button>
         <!-- <a style="float:right; margin-left:10px" class="btn btn-primary"  href="<?php echo e(route('crm.create')); ?>">Create Lead</a> -->
        <h1>Upsale</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?php echo e(url('/')); ?>">Home</a></li>
                <li class="breadcrumb-item active">All Upsale</li>
            </ol>
        </nav>
    </div>

    <?php echo $__env->make('include.alert', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <!-- filter Form Start -->
                        <form method="POST" action="<?php echo e(route('crm.upsale.invoice')); ?>">
                            <?php echo csrf_field(); ?>
                            <input type="hidden" name="client_id" value="<?php echo e($client->id); ?>">
                            <div class="row m-4">
                                <div class="col-md-4">
                                    <label>Client Name</label>
                                    <input class="form-control" type="text" name="client_name" value="<?php echo e($client->name); ?>" readonly>
                                </div>
                                <div class="col-md-4">
                                    <label>Email</label>
                                    <input class="form-control" type="email" name="client_email" value="<?php echo e($client->email); ?>" readonly>  
                                </div>
                                <div class="col-md-4">
                                    <label>Phone</label>
                                    <input class="form-control" type="number" name="city_phone" value="<?php echo e($client->phone_no); ?>" readonly>  
                                </div>
                                <div class="col-md-4 mt-3">
                                    <label>Billing Date</label>
                                    <input class="form-control" type="date" name="billing_date" value="<?php echo e(old('billing_date')); ?>" required>  
                                </div>
                                <div class="col-md-4 mt-3">
                                    <label for="exampleInputgstno2">Office<span class="text-danger">*</span></label>
                                    <select class="form-control" name="office">
                                        <option selected disabled>Select Office</option>
                                        <?php $__currentLoopData = $offices; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $office): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($office->id); ?>"> <?php echo e($office->name); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                    <small id="error-bill" class="form-text error text-muted"></small>
                                </div>
                                <div class="col-md-4 mt-3">
                                    <label for="" class="form-label">Currency<span class="text-danger">*</span></label>
                                    <select class="form-select" name="currency" required onchange="Currency(this.value)">
                                        <option selected disabled>Choose Currency</option>
                                        <option value="$">USD ($)</option> 
                                        <option value="₹">INR (₹)</option> 
                                        <option value="£">Pound (£)</option> 
                                    </select>
                                </div>
                                <div class="col-md-3 mt-3">
                                    <label for="exampleInputgstno2">Bill<span class="text-danger">*</span></label>
                                    <select class="form-control" name="gst" onchange="getBankDetail(this.value)">
                                        <option selected disabled>Select Bill</option>
                                        <option value="1">With Gst</option>
                                        <option value="0">Without Gst</option>
                                    </select>
                                    <small id="error-bill" class="form-text error text-muted"></small>
                                </div>
                                <div class="col-md-3 mt-3">
                                    <label for="exampleInputgstno2">Bank Details<span class="text-danger">*</span></label>
                                    <select class="form-control bankDetails" name="bank_details">
                                        <option sletectd disabled>Select Bank Details..</option>
                                    </select>
                                    <small id="error-invoicedate" class="form-text error text-muted"></small>
                                </div>
                                <div class="col-md-3 mt-3">
                                    <label for="gst" class="form-label">GST(%)<span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" name="gst" id="gst" value="0" >
                                </div>
                                <div class="col-md-3 mt-3">
                                    <label for="discount" class="form-label">Discount<span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" name="discount" id="discount" value="<?php echo e(old('discount',0)); ?>">
                                </div>
                                <div id="workRows">
                                    <div class="row work-row mt-3">
                                        <div class="col">
                                            <label>Work Name<span class="text-danger">*</span></label>
                                            <input type="text" name="work_name[]" class="form-control">
                                        </div>
                                        <div class="col">
                                            <label>Work Quantity<span class="text-danger">*</span></label>
                                            <input type="number" name="quantity[]" class="form-control quantity" value="1">
                                        </div>
                                        <div class="col">              
                                            <label>Work Price<span class="text-danger">*</span></label>
                                            <input type="number" name="price[]" class="form-control price" value="0">
                                        </div>  
                                        <div class="col">
                                            <label>Work Type<span class="text-danger">*</span></label>
                                            <select class="form-select" name="work_type[]">
                                                <option value="One time">One time</option>
                                                <option value="Weekly">Weekly</option>
                                                <option value="Monthly">Monthly</option>
                                                <option value="Yearly">Yearly</option>
                                            </select> 
                                        </div> 
                                        <div class="col-1 mt-4">
                                            <button class="btn btn-outline-success">+</button>
                                            <button class="btn btn-outline-danger">-</button>
                                        </div> 
                                    </div>
                                </div>  
                                <!-- Inp ut fields for GST, discount, subtotal, and total -->
                                <!-- Disp lay the calculated values -->
                                <div class="row mt-5">
                                    <div class="col-3">Subtotal: <b><span id="subtotal">0.00</span></b></div>
                                    <div class="col-3">GST:  <b><span id="gstAmount">0.00</span></b></div>
                                    <div class="col-3">Discount:  <b><span id="discountAmount">0.00</span></b></div>
                                    <div class ="col-3">Total Amount: <b> <span id="totalAmount">0.00</span></b></div>
                                </div> 
                                 
                                <!-- Hidden input fields to hold calculated values -->
                                <input type="hidden" name="subtotal_value" id="subtotal_value">
                                <input type="hidden" name="gst_value" id="gst_value">
                                <input type="hidden" name="total_value" id="total_value">
                                 
                                <div class="mt-5">
                                    <button type="submit" class="btn btn-primary">Create</button>
                                </div>
                            </div> 
                        </form> 
                         
                    </div>
                </div> 
            </div> 
        </div>
    </section>    
 
     <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
     <script>
      $(document).ready(function() {
         // Calculate total when inputs change
         $(document).on('input', '.price, .quantity, #gst, #discount', function() {
             calculateTotal();
        });
  
        // Function to calculate subtotal, GST, discount, and total amount
          function calculateTotal() {
             let subtotal = 0;
             let gstPercent = parseFloat($('#gst').val()) || 0;
             let discount = parseFloat($('#discount').val()) || 0;
 
             // Calculate subtotal by looping through each row
             $('.work-row').each(function() {
                 let quantity = parseFloat($(this).find('.quantity').val()) || 0;
                 let price = parseFloat($(this).find('.price').val()) || 0;
                 subtotal += price; // Update subtotal calculation to multiply quantity and price
             });
 
             // Calculate GST amount
             let gstAmount = (subtotal * gstPercent) / 100;
 
             // Calculate total after discount
             let discountAmount = discount;
             let totalAmount = subtotal + gstAmount - discountAmount;
 
             // Display calculated values
             $('#subtotal').text(subtotal.toFixed(2));
             $('#gstAmount').text(gstAmount.toFixed(2));
             $('#discountAmount').text(discountAmount.toFixed(2));
             $('#totalAmount').text(totalAmount.toFixed(2));
 
             // Update hidden input fields
             $('#subtotal_value').val(subtotal.toFixed(2));
              $('#gst_value').val(gstAmount.toFixed(2));
             $('#total_value').val(totalAmount.toFixed(2));
        }
 
         // Trigger initial calculation on page load in case values are pre-filled
         calculateTotal();
 
         // Function to clone a work row
          $(document).on('click', '.btn-outline-success', function(e) {
             e.preventDefault(); // Prevent form submission
             let newRow = $(this).closest('.work-row').clone(); // Clone the row
 
             // Clear values in the new row
             newRow.find('input').val('');
             newRow.find('select').prop('selectedIndex', 0); // Reset the select to the first option
 
             // Append the new row to the workRows container
             $('#workRows').append(newRow);
             calculateTotal(); // Recalculate total after adding a new row
         });
 
         // Function to remove a work row
         $(document).on('click', '.btn-outline-danger', function(e) {
             e.preventDefault(); // Prevent form submission
             if ($('.work-row').length > 1) { // Ensure at least one row remains
                 $(this).closest('.work-row').remove();
                 calculateTotal(); // Recalculate total after removing a row
             } else {
                 alert("At least one work row must remain."); // Alert if trying to remove the last row
             }
        }) ;
    }); 
</script>
            <script>
             function getBankDetail(gst) {
                 $.ajax({
                     url: '<?php echo e(route('get-bank-details')); ?>',
                     method: 'POST',
                     headers: {
                         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                     },
                      data: {
                         'gst': gst
                    },
                     success: function(response) {
                         console.log(response);
          
                         // Clear existing options
                         $('.bankDetails').empty();
         
                        // Append new options based on response data
                          $.each(response.banks, function(index, bank) {
                             $('.bankDetails').append($('<option>').text(bank.bank_name + ' - ' + bank
                                 .account_no).attr('value', bank.id));
                         });
                     },
                     error: function(xhr, status, error) {
                         // Handle any errors here
                         console.error(error);
                     }
                }); 
            } 
 
             function Currency(currency) {
                 if ($.inArray(currency, ['£', '$']) !== -1) {
                     $('#gst').prop('readonly', true);
                 } else {
                     $('#gst').prop('readonly', false);
                 }
             }
             </script>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $component = $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>                                                    <?php /**PATH /home/adxventure/lara_tms/resources/views/admin/crm/upsale.blade.php ENDPATH**/ ?>