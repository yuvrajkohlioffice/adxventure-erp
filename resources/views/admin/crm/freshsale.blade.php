<x-app-layout>
    @section('title','Fresh Sale')
        <!-- Flatpickr Timepicker css -->
    <link href="{{asset('assets/vendor/datepicker/flatpickr.min.css')}}" rel="stylesheet" type="text/css" />
    <style>
        .col-3{
            float:right;
        }
    </style>
    
    <div class="pagetitle">
        <button style="float:right;" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#AddModel" >Create Lead</button>
         <!-- <a style="float:right; margin-left:10px" class="btn btn-primary"  href="{{route('crm.create')}}">Create Lead</a> -->
        <h1>Fresh Sale</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                <li class="breadcrumb-item active">All Fresh Sale</li>
            </ol>
        </nav>
    </div>

    {{-- @include('include.alert') --}}
    @if(Session::has('insert'))
    <div class="alert alert-success">
        <strong> {{session('insert')}}</strong>
    </div>
    @endif
    @if(Session::has('danger'))
    <div class="alert alert-danger">
        <strong> {{session('danger')}}</strong>
    </div>
    @endif

    <!-- Alternatively, display all errors at once -->
@if($errors->any())
<div class="alert alert-danger">
    <ul>
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif
    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <!-- filter Form Start -->
                        <form method="POST" action="{{route('crm.freshsale.invoice')}}">
                            @csrf
                            <input type="hidden" name="client_id" value="{{$client->id}}">
                            <div class="row m-4">
                                <div class="col-md-3 mt-3">
                                    <label>Billing Date<span class="text-danger">*</span></label>
                                    <input class="form-control" type="date" name="billing_date" id="billing_date" value="{{$invoice->billing_date ?? ''}}" required>
                                </div>
                                <div class="col-md-3 mt-3">
                                    <label>Client Gst No.</label>
                                    <input class="form-control" type="text" name="gst_no" placeholder="Enter Client Gst no." value="{{$invoice->client_gst_no ?? ''}}">  
                                </div>
                                <div class="col-md-3 mt-3">
                                    <label for="exampleInputgstno2">Office<span class="text-danger">*</span></label>
                                    <select class="form-control" name="office">
                                        <option selected disabled>Select Office</option>
                                        @if(isset($invoice->office))
                                            @foreach($offices as $office)
                                            <option value="{{$office->id}}" @if($office->id == $invoice->office ?? '') selected @endif> {{$office->name}}</option>
                                            @endforeach
                                        @else
                                            @foreach($offices as $office)
                                            <option value="{{$office->id}}"> {{$office->name}}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                    <small id="error-bill" class="form-text error text-muted"></small>
                                </div>
                                <div class="col-md-3 mt-3">
                                    <label for="exampleInputgstno2">Bill<span class="text-danger">*</span></label>
                                    <select class="form-control" name="gst" onchange="getBankDetail(this.value)">
                                        <option selected disabled>Select Bill</option>
                                        @if(isset($invoice->Bank))
                                        <option value="1" @if($invoice->Bank->gst = 1) selected @endif>With Gst</option>
                                        <option value="0" @if($invoice->Bank->gst != 1) selected @endif>Without Gst</option>
                                        @else
                                        <option value="1">With Gst</option>
                                        <option value="0">Without Gst</option>  
                                        @endif 

                                    </select>
                                    <small id="error-bill" class="form-text error text-muted"></small>
                                </div>
                                <div class="col-md-3 mt-3">
                                    <label class="form-label" for="exampleInputgstno2">Bank Details<span class="text-danger">*</span></label>
                                    <select class="form-control bankDetails" name="bank_details">
                                        @if(isset($invoice->Bank))  
                                            <option value="{{$invoice->Bank->id}}" selected>{{$invoice->Bank->bank_name}}</option>
                                        @else
                                        <option sletectd disabled>Select Bank Details..</option>
                                        @endif
                                    </select>
                                    <small id="error-invoicedate" class="form-text error text-muted"></small>
                                </div>
                                <div class="col-md-3 mt-3">
                                    <label for="gst" class="form-label">GST(%)<span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" name="gst" id="gst" value="{{$invoice->gst ?? ''}}">
                                </div>
                                <div class="col-md-3 mt-3">
                                    <label for="discount" class="form-label">Discount</label>
                                    <input type="number" class="form-control" name="discount" id="discount" value="{{$invoice->discount ?? ''}}">
                                </div>
                                <div class="col-md-3 mt-3">
                                    <label for="" class="form-label">Currency<span class="text-danger">*</span></label>
                                    <select class="form-select" name="currency" required>
                                        <option selected disabled>Choose Currency</option>
                                        @if(isset($invoice->currency))
                                        <option value="$" @if($invoice->currency == "$")  selected @endif>USD ($)</option> 
                                        <option value="₹" @if($invoice->currency == "₹")  selected @endif >INR (₹)</option> 
                                        <option value="£" @if($invoice->currency == "£")  selected @endif>Pound (£)</option> 
                                        @else
                                        <option value="$" >USD ($)</option> 
                                        <option value="₹">INR (₹)</option> 
                                        <option value="£">Pound (£)</option> 
                                        @endif
                                    </select>
                                </div>
                                <div id="workRows">
                                    @if(isset($invoice->services))
                                    @foreach($invoice->services as $service)
                                        <div class="row work-row mt-3">
                                            <div class="col">
                                                <label>Work Name<span class="text-danger">*</span></label>
                                                <input type="text" name="work_name[]" class="form-control" value="{{$service->work_name}}">
                                            </div>
                                            <input type="hidden" name="work_id[]" class="form-control" value="{{$service->id}}">
                                            <div class="col">
                                                <label>Work Quantity<span class="text-danger">*</span></label>
                                                <input type="number" name="quantity[]" class="form-control quantity" value="{{$service->work_quality}}">
                                            </div>
                                            <div class="col">
                                                <label>Work Price<span class="text-danger">*</span></label>
                                                <input type="number" name="price[]" class="form-control price" value="{{$service->work_price}}">
                                            </div>
                                            <div class="col">
                                                <label>Work Type<span class="text-danger">*</span></label>
                                                <select class="form-select" name="work_type[]">
                                                    <option value="One time" @if($service->work_type ="One time") selected @endif>One time</option>
                                                    <option value="Weekly" @if($service->work_type ="Weekly") selected @endif>Weekly</option>
                                                    <option value="Monthly" @if($service->work_type ="Monthly") selected @endif>Monthly</option>
                                                    <option value="3 Month" @if($service->work_type ="3 Month") selected @endif>3 Month</option>
                                                    <option value="6 Month" @if($service->work_type ="6 Month") selected @endif>6 Month</option>
                                                    <option value="Yearly" @if($service->work_type =" Yearly") selected @endif>Yearly</option>
                                                </select>
                                            </div>
                                            <div class="col-1 mt-4">
                                                <button class="btn btn-outline-success">+</button>
                                                <button class="btn btn-outline-danger">-</button>
                                            </div>
                                        </div>
                                        @endforeach
                                    @else
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
                                                <option value="3 Month">3 Month</option>
                                                <option value="6 Month">6 Month</option>
                                                <option value="Yearly">Yearly</option>
                                            </select>
                                        </div>
                                        <div class="col-1 mt-4">
                                            <button class="btn btn-outline-success">+</button>
                                            <button class="btn btn-outline-danger">-</button>
                                        </div>
                                    </div>
                        
                                    @endif
                                </div>
                                
                                <!-- Input fields for GST, discount, subtotal, and total -->
                                <!-- Display the calculated values -->
                                <div class="row mt-5">
                                    <div class="col-3">Subtotal: <b><span id="subtotal">0.00</span></b></div>
                                    <div class="col-3">GST:  <b><span id="gstAmount">0.00</span></b></div>
                                    <div class="col-3">Discount:  <b><span id="discountAmount">0.00</span></b></div>
                                    <div class="col-3">Total Amount: <b> <span id="totalAmount">0.00</span></b></div>
                                </div>
                                
                                <!-- Hidden input fields to hold calculated values -->
                                <input type="hidden" name="subtotal_value" id="subtotal_value">
                                <input type="hidden" name="gst_value" id="gst_value">
                                <input type="hidden" name="total_value" id="total_value">
                                @if(isset($invoice))
                                <input type="hidden" name="invoice_id" value="{{$invoice->id}}">
                                @endif
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

    <!-- Flatpickr Timepicker Plugin js -->
    <script src="{{asset('assets/vendor/datepicker/flatpickr.min.js')}}"></script>
    <script src="{{asset('assets/vendor/datepicker/form-picker.js')}}"></script>
    <script>
    $(document).ready(function() {

        const datePicker = flatpickr("#billing_date", {
            minDate: "today",
            dateFormat: "Y-m-d",
            defaultDate: "today",
        });

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
        });
    });
</script>
           <script>
            function getBankDetail(gst) {
                if(gst == 1){
                    $('#gst').val(18);
                }else{
                    $('#gst').val(0);
                }
                $.ajax({
                    url: '{{ route('get-bank-details') }}',
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
            </script>
</x-app-layout>