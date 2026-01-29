<x-app-layout>
    @section('title','Add-Invoice')
    <style>
    .col-12 mt-3 {
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
        <h1>{{strtoupper($lead->name)}}</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                <li class="breadcrumb-item active">Create Invoice</li>
            </ol>
        </nav>
    </div>
    <section class="section">
        <div class="row">
            <div class="col-lg-6">
                <div class="card"> 
                    <div class="card-body p-4">
                    <h3> 
                        Invoice Form
                    </h3>
                        <form autocomplete="off" data-method="POST"
                            data-action="{{route('lead.prposel.invoice', ['leadId' => $lead->id] + ($id ? ['id' => $id] : []))}}"
                            id="ajax-form" enctype="multipart/form-data">
                            @csrf
                            <legand></legand>
                            <div class="row"> 
                                @if(isset($invoice))
                                    <input type="hidden" name="invoice_id" value="{{$invoice->id}}">
                                @endif
                                <div class="col-12 mt-3">
                                    <label for="exampleInputgstno2">Office<span class="text-danger">*</span></label>
                                    <select class="form-control" name="office" required>
                                        <option selected disabled>Select Office...</option>
                                        @foreach($offices as $office)
                                        <option value="{{$office->id}}">{{$office->name}}</option>
                                        @endforeach
                                    </select>
                                    <small id="error-office" class="form-text error text-danger"></small>
                                </div>
                                <div class="col-12 mt-3">
                                    <label for="exampleInputgstno2">Bill<span class="text-danger">*</span></label>
                                    <select class="form-control" name="gst" onchange="getBankDetail(this.value)"
                                        required>
                                        <option selected disabled>Select Bill</option>
                                        <option value="1">With Gst</option>
                                        <option value="0">Without Gst</option>
                                    </select>
                                    <small id="error-bill" class="form-text error text-danger"></small>
                                </div>
                                <div class="col-12 mt-3 mb-3">
                                    <label for="exampleInputgstno2">Bank Details<span
                                            class="text-danger">*</span></label>
                                    <select class="form-control" name="bank_details" id="bankDetails" required> 
                                        @if(isset($invoice))
                                            <option selected value="{{$invoice->bank}}">{{$invoice->Bank->bank_name}} - {{$invoice->Bank->account_no}}</option>
                                        @else
                                            <option selected disbled>Select First Bill</option>
                                        @endif
                                    </select>
                                    <small id="error-invoicedate" class="form-text error text-danger"></small>
                                </div>
                                <div class="col-12 ">
                                    <label for="exampleInputgstno2">Gst No.</label>
                                    @if(isset($invoice))
                                    <input type="text" class="form-control" id="exampleInputgstno2" name="gst_no"
                                        placeholder="Enter GST No." value="{{ old('gst_no', $invoice->gst_no) }}">
                                        @else
                                        <input type="text" class="form-control" id="exampleInputgstno2" name="gst_no"
                                            placeholder="Enter GST No." value="{{ old('gst_no') }}">
                                    @endif
                                    <small id="error-gstno" class="form-text error text-danger"></small>
                                </div>
                                <div class="col-12 mt-3">
                                    <label for="exampleInputgstno2">Gst(%)</label>
                                    
                                    @if(isset($invoice))
                                    <input type="text" class="form-control" id="exampleInputgstno2" name="gst"
                                        placeholder="Enter GST Percantage.." value="{{old('gst',$invoice->gst)}}" readonly>
                                        @else
                                        <input type="text" class="form-control" id="exampleInputgstno2" name="gst"
                                            placeholder="Enter GST Percantage.." value="{{old('gst')}}" readonly>
                                    @endif
                                    <small id="error-gst" class="form-text error text-danger"></small>
                                </div>
                                <div class="col-12 mt-3">
                                    <label for="exampleInputdiscount2">Discount(Total Amount :{{$amount}}{{$currency}})</label>
                                    <input type="hidden" value="{{$amount}}" name="total_amount">
                                    @if(isset($invoice))
                                    <input type="number" class="form-control" id="exampleInputdiscount2" name="discount" placeholder="Enter Discount Amount" value="{{old('discount',$invoice->discount)}}" max="{{$amount}}">
                                    @else
                                    <input type="number" class="form-control" id="exampleInputdiscount2" name="discount" placeholder="Enter Discount Amount" value="{{old('discount')}}" max="{{$amount}}">
                                    @endif
                                    <small id="error-discount" class="form-text error"></small>
                                </div>
                            </div>
                            <div class="col-12 mt-3">
                                <a href="{{route('lead.prposel.service',['leadId' => $lead->id])}}" class="btn btn-warning">
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
        if (gst == 1) {
                $('input[name="gst"]').val(18); // Correct selector for input with name="gst"
            } else {
                $('input[name="gst"]').val(0);
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
    $(document).ready(function () {
        $('#exampleInputdiscount2').on('input', function () {
            var value = parseFloat($(this).val());
            var maxAmount = parseFloat($(this).attr('max'));
            
            if (isNaN(value)) {
                $(this).val(''); // Clear the input field
                $('#error-discount').text('Please enter a valid Amount').addClass('text-danger');
            } else if (value > maxAmount) {
                $(this).val(maxAmount); // Set value to the max allowed value
                $('#error-discount').text('Discount Amount Not be greater then Total Amount').addClass('text-danger');
            } else {
                $('#error-discount').text('').removeClass('text-danger');
            }
        });
    });
</script>

</x-app-layout>