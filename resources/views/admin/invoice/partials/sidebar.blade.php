<div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasRight" aria-labelledby="offcanvasRightLabel" style="width:50vw">
        <div class="offcanvas-header">
            <h5 id="offcanvasRightLabel"><b>Create Invoice</b></h5>
            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            <form action="{{url('/invoice/createInvoice')}}" method="POST">   
                @csrf
                <div class="mb-3">
                    <label for="exampleInputEmail1" class="form-label">Select Client</label>
                    <select class="form-control" name="client_id" required onchange="getProject(this.value)">
                        <option value="">Select Client</option>
                        @if(isset($clients))
                            @foreach($clients as $client)
                            <option value="{{ $client->id }}">{{ $client->name }}</option>
                            @endforeach
                        @endif
                    </select>
                </div>
                <div class="mb-3">
                    <label for="exampleInputEmail1" class="form-label">Select Project<span class="text-danger">*</span></label>
                    <select class="form-control projectSelect" name="project_id">
                        <option value="">Select Project</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="exampleInputPassword1" class="form-label">Billing Date<span class="text-danger">*</span></label>
                    <input type="date" class="form-control" name="date" id="exampleInputPassword1">
                </div>
                <div class="mb-3">
                    <label for="exampleInputPassword1" class="form-label">Invoive Type</label>
                    <select class="form-control" name="type">
                        <option>Select type..</option>
                        <option value="1">One Time</option>
                        <option value="2">Weekly</option>
                        <option value="3">Monthly</option>
                        <option value="4"> 15 days</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="exampleInputgstno2">Office<span class="text-danger">*</span></label>
                    <select class="form-control" name="office">
                        <option selected disabled>Select Office</option>
                        @if(isset($offices))
                        @foreach($offices as $office)
                        <option value="{{$office->id}}"> {{$office->name}}</option>
                        @endforeach
                        @endif
                    </select>
                    <small id="error-bill" class="form-text error text-muted"></small>
                </div>
                <div class="mb-3">
                    <label for="" class="form-label">Currency<span class="text-danger">*</span></label>
                    <select class="form-select" name="currency" required="" fdprocessedid="fecp3v">
                        <option selected="" disabled="">Choose Currency</option>
                        <option value="$">USD ($)</option> 
                        <option value="₹">INR (₹)</option> 
                        <option value="£">Pound (£)</option> 
                    </select>
                </div>
                <div class="mb-3">
                    <label for="exampleInputgstno2">Bill<span class="text-danger">*</span></label>
                    <select class="form-control" name="gst" onchange="getBankDetail(this.value)">
                        <option selected disabled>Select Bill</option>
                        <option value="1">With Gst</option>
                        <option value="0">Without Gst</option>
                    </select>
                    <small id="error-bill" class="form-text error text-muted"></small>
                </div>
                <div class="mb-3">
                    <label for="exampleInputgstno2">Bank Details<span class="text-danger">*</span></label>
                    <select class="form-control bankDetails" name="bank_details">
                        <option sletectd disabled>Select Bank Details..</option>
                    </select>
                    <small id="error-invoicedate" class="form-text error text-muted"></small>
                </div>
                <div id="workRows">
                    <div class="row work-row">
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
                            <button type="button" class="btn btn-outline-success btn-sm btn-add">+</button>
                            <button type="button" class="btn btn-outline-danger btn-sm btn-remove">-</button>
                        </div>
                    </div>
                </div>
                
                <!-- Input fields for GST, discount, subtotal, and total -->
                <div class="my-3">
                    <label for="gst" class="form-label">GST(%)<span class="text-danger">*</span></label>
                    <input type="number" class="form-control" name="gst" id="gst" value="18">
                </div>
                <div class="my-3">
                    <label for="discount" class="form-label">Discount<span class="text-danger">*</span></label>
                    <input type="number" class="form-control" name="discount" id="discount" value="0">
                </div>
                
                <!-- Display the calculated values -->
                <div class="row">
                    <div class="col-3">Subtotal: <span id="subtotal">0.00</span></div>
                    <div class="col-3">GST: <span id="gstAmount">0.00</span></div>
                    <div class="col-3">Discount: <span id="discountAmount">0.00</span></div>
                    <div class="col-3">Total Amount: <span id="totalAmount">0.00</span></div>
                </div>
                
                <!-- Hidden input fields to hold calculated values -->
                <input type="hidden" name="subtotal_value" id="subtotal_value">
                <input type="hidden" name="gst_value" id="gst_value">
                <input type="hidden" name="total_value" id="total_value">
                
                <div class="mt-5">
                    <button type="submit" class="btn btn-primary">Create</button>
                </div>
            
            </form>
        </div>
    </div>
