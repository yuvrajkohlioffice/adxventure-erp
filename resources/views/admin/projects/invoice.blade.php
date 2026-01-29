<x-app-layout>
    @section('title','Create-Invoice')
    <style>
    .col-6 mt-3 {
        margin-top: 10px;
        margin-bottom: 10px;
    }

    label {
        font-weight: 600;
    }
    </style>
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link rel="stylesheet" type="text/css" href="https://unpkg.com/trix@2.0.8/dist/trix.css">
    <script type="text/javascript" src="https://unpkg.com/trix@2.0.8/dist/trix.umd.min.js"></script>

    <div class="pagetitle">
        <h1>{{strtoupper($project->name)}}</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                <li class="breadcrumb-item active">Create Invoice</li>
            </ol>
        </nav>
    </div>
    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    
                    <div class="card-body p-4">
                    <div class="card-title"> 
                    </div>
                        <form autocomplete="off" data-method="POST"
                            data-action="{{ route('project.invoice.create',['project_id'=> $project_id,'client_id' => $client_id]) }}"
                            id="ajax-form" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-3 mt-3">
                                    <label for="exampleInputgstno2">Gst No.</label>
                                    <input type="text" class="form-control" id="exampleInputgstno2" name="gst_no"
                                        placeholder="Enter GST No.">
                                    <small id="error-gstno" class="form-text error text-muted"></small>
                                </div>
                                <div class="col-3 mt-3">
                                    <label for="exampleInputdiscount2">Discount(%)</label>
                                    <input type="number" class="form-control" id="exampleInputdiscount2" name="discount" placeholder="Enter Discount Percentage" max="100">
                                    <small id="error-discount" class="form-text error"></small>
                                </div>
                                <div class="col-3 mt-3">
                                    <label for="exampleInputgstno2">Date of Invoice<span
                                            class="text-danger">*</span></label>
                                    <input type="date" class="form-control dateInvoice" id="exampleInputinvoicedate2"
                                        name="invoice_date" placeholder="Select Invoice Date.">
                                    <small id="error-invoicedate" class="form-text error error-invoicedate"></small>
                                </div>
                                <div class="col-3 mt-2">
                                    <label for="exampleInputPassword1" class="form-label">Invoive Type<span
                                            class="text-danger">*<span></label>
                                    <select class="form-control" name="invoice_type">
                                        <option selected disabled>Select type..</option>
                                        <option value="one time">One Time</option>
                                        <option value="weekly">Weekly</option>
                                        <option value="monthly">Monthly</option>
                                        <option value="15 days"> 15 days</option>
                                    </select>
                                </div>
                                <div class="col-6 mt-3">
                                    <label for="exampleInputgstno2">Bill<span class="text-danger">*</span></label>
                                    <select class="form-control" name="gst" onchange="getBankDetail(this.value)"
                                        required>
                                        <option selected disabled>Select Bill</option>
                                        <option value="1">With Gst</option>
                                        <option value="0">Without Gst</option>
                                    </select>
                                    <small id="error-bill" class="form-text error text-muted"></small>
                                </div>
                                <div class="col-6 mt-3">
                                    <label for="exampleInputgstno2">Bank Details<span
                                            class="text-danger">*</span></label>
                                    <select class="form-control" name="bank_details" id="bankDetails" required>
                                        <option selected disbled>Select First Bill</option>
                                    </select>
                                    <small id="error-invoicedate" class="form-text error text-muted"></small>
                                </div>
                                <div class="col-3 mt-3">
                                    <input type="checkbox" value="1" id="advanced" name="advanced">
                                    <label for="advanced">Advanced Payment</label>
                                </div>
                            </div>
                            <div id="advanced_row" style="display: none;">
                                <div class="row">
                                <div class="col-6 mt-3">
                                    <label>Payment Mode<span class="text-danger">*</span></label>
                                    <select class="form-control"  name="mode">
                                        <option value="">Select Payment Mode</option>
                                        <option>Cash</option>
                                        <option>Debit/Credit Card</option>
                                        <option>Net Banking</option>
                                        <option>Cheque</option>
                                        <option>Other</option>
                                    </select>
                                </div>
                                <div class="col-6 mt-3">
                                    <label>Receipt Number<span class="text-danger">*</span></label>
                                    <input type="text" name="receipt_number" class="form-control" 
                                        placeholder="Receipt Number" value="">
                                </div>
                                <div class="col-6 mt-3 mb-2">
                                    <label>Deposit Date<span class="text-danger">*</span></label>
                                    <input type="date" name="desopite_date" class="form-control dateInvoice" 
                                        value="{{ date('Y-m-d') }}">
                                    <small id="error-invoicedate" class="form-text error error-invoicedate"></small>
                                </div>
                                <div class="col-6 mt-3 mb-2">
                                    <label>Deposit Time<span class="text-danger">*</span></label>
                                    <input type="time" name="time" class="form-control">
                                </div>
                                <p class="mb-2"><b>Maximum Payment Amount is {{ $total_amount }}</b>
                                </p>
                                <div class="col-6 mt-2">
                                    <label>Amount<span class="text-danger">*</span></label>
                                    <input type="number" name="amount" id="amount_field" class="form-control" min="1" max="{{ $total_amount }}">
                                    <div class="text-danger" id="amount_error" style="display:none;">Amount cannot be
                                        greater than {{ $total_amount }}</div>
                                </div>
                                <div class="col-6 mt-2">
                                    <label>Attach File<span class="text-danger">*</span></label>
                                    <input type="file" name="image" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label>Make Remark<span class="text-danger">*</span></label>
                                    <textarea rows="3" name="remark" class="form-control" 
                                        placeholder="Type here..."></textarea>
                                </div>
                                </div>
                            </div>
                            <div class="col-12 mt-3">
                                <a href="{{route('project.work', ['client_id' => $client_id, 'project_id' => $project_id])}}" class="btn btn-warning">
                                    <span class="loader" id="loader" style="display: none;"></span>
                                    Previous
                                </a>
                                <button id="submit-btn" type="submit" class="btn btn-primary" style="float:right">
                                    <span class="loader" id="loader" style="display: none;"></span>
                                    Save & Next</button>
                            </div>
                    </form>
                </div>
            </div>
        </div>
        </div>
    </section>
    <script>
    function getBankDetail(gst) {
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
                $('#bankDetails').empty();

                // Append new options based on response data
                $.each(response.banks, function(index, bank) {
                    $('#bankDetails').append($('<option>').text(bank.bank_name + ' - ' + bank
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

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        var totalAmount = @json($total_amount);
        var amountField = document.getElementById('amount_field');
        var errorField = document.getElementById('amount_error');

        amountField.addEventListener('input', function() {
            var value = parseFloat(amountField.value);

            if (isNaN(value) || value < 1 || !Number.isInteger(value)) {
                errorField.textContent = 'Amount must be a positive integer.';
                errorField.style.display = 'block';
                amountField.value = 0;
            } else if (value > totalAmount) {
                errorField.textContent = 'Amount cannot be greater than ' + totalAmount + '.';
                errorField.style.display = 'block';
                amountField.value = 0;
            } else {
                errorField.style.display = 'none';
            }
        });
    });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var checkbox = document.getElementById('advanced');
            var advancedRow = document.getElementById('advanced_row');

            checkbox.addEventListener('change', function() {
                if (checkbox.checked) {
                    advancedRow.style.display = 'block';
                } else {
                    advancedRow.style.display = 'none';
                }
            });
        });
    </script>
<script>
    $(document).ready(function () {
        $('#exampleInputdiscount2').on('input', function () {
            var value = parseFloat($(this).val());
            if (isNaN(value) || value < 0 || value > 100) {
                $(this).val(''); // Clear the input field
                $('#error-discount').text('Please enter a valid percentage (0-100)').addClass('text-danger');
            } else {
                $('#error-discount').text('').removeClass('text-danger');
            }
        });
    });
</script>

</x-app-layout>