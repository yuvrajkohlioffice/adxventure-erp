<x-app-layout>
    @section('title','Receipt')
    @include('include.alert')

<section class="section">
<a href="javascript:void(0);" onclick="window.history.back();" class="btn btn-primary mb-2">Back</a>
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    {{-- <form method="GET" action="">
                        <div class="row m-4">
                            <div class="col">
                                    <input type="text" class="form-control" name="name"  placeholder ="Search By Client Name,Email,Phone..">
                            </div>
                            <div class="col">
                                <select class="form-select" name="invoice_day" id="invoice_day" fdprocessedid="3t8r0j">
                                    <option selected disabled>Search By Date..</option>
                                    <option value="All">All Lead</option>
                                    <option value="month">This Month</option>
                                    <option value="year">This year</option>
                                    <option value="custome">Custome Date</option>
                                </select>           
                            </div>
                            <!-- Date inputs (hidden by default) -->
                            <div class="col" id="to_date_container" style="display: none;">
                                <input type="date" name="to_date" id="to_date" class="form-control"> 
                            </div>
                            <div class="col" id="from_date_container" style="display: none;">
                                <input type="date" name="from_date" id="from_date" class="form-control">
                            </div>
                            <div class="col">
                                <select class="form-select" name="invoice_status" fdprocessedid="3t8r0j">
                                    <option selected disabled>Search By Type..</option>
                                    <option value="fresh">Fresh Sale</option>
                                    <option value="upsale">Up Sale</option>
                                    <option value="partial-paid">partial Paid</option>
                                    <option value="Paid">Paid</option>
                                    <option value="un-paid">Unpaid</option>
                                </select>
                            </div>
                            <div class="col">
                                <button type="submit" class="btn btn-success btn-md" fdprocessedid="j7i8d">Filter</button>
                                &nbsp; &nbsp;
                                <a href="{{ url('/invoice') }}" class="btn btn-danger">Refresh</a>
                            </div>
                        </div>
                    </form> --}}
                    <br>
                    <table class="table table-striped">
                        <thead>
                            <tr class="bg-success text-white table-bordered ">
                                 <th>Sr No.</th>
                                <th>Created Date</th>
                                <th>Deposit Date</th>
                                <th>Payment Mode</th>
                                <th>Receipt No.</th>
                                <th>Amount</th>
                                <th>Remark</th>
                                <th>Payent ScreenShot</th>
                                <th>Receipt</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(isset($payments))
                            @foreach($payments as $index => $payment)
                            <tr>
                                <td>{{$index +1}}</td>
                                <td>{{ \Carbon\Carbon::parse($payment->created_at)->format('d-m-Y H:i:s') }}</td>
                                <td>{{ \Carbon\Carbon::parse($payment->deposit_date)->format('d-m-Y') }}</td>
                                <td>{{$payment->mode}}</td>
                                <td>{{$payment->receipt_number}}</td>
                                <td>{{$payment->amount}}</td>
                                <td>{{$payment->remark}}</td>
                                <td>
                                    <a href="https://tms.adxventure.com/payment/{{$payment->image}}" target="_blank"><i class="bi bi-file-image"></i> View Scrrenshot</a>
                                </td>
                                <td>
                                    <a href="https://tms.adxventure.com/{{ $payment->pdf }}" target="_blank">
                                        <i class="bi bi-file-pdf"></i> View PDF
                                    </a>
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-warning" onclick="editReceipt({{$payment->id}},'{{$payment->image}}')">Edit</button>
                                </td>
                            </tr>
                            @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>


<div class="modal" id="editReceipt" tabindex="-1" aria-labelledby="exampleModalLabel" data-bs-backdrop="static" data-bs-keyboard="false" aria-modal="true" role="dialog" >       
    <div class="modal-dialog modal-lg modal-dialog-centered ">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Edit Receipt</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form class="ajax-form" data-method="POST" data-action="{{route('receipts.edit')}}"> 
                    @csrf
                    <input type="hidden" id="paymentId" name="id">
                    <!-- <div class="row">
                        <div class="col-6 mt-3">
                            <label>Payment Mode <span class="text-danger">*</span></label>
                            <select class="form-control" required="" name="mode">
                                <option value="">Select Payment Mode</option>
                                <option>Cash</option>
                                <option>Debit/Credit Card</option>
                                <option>Net Banking</option>
                                <option>Cheque</option>
                                <option>Other</option>
                            </select>
                        </div>
                        <div class="col-6 mt-3">
                            <label>Deposit Date<span class="text-danger">*</span></label>
                            <input type="date" name="deposit_date" id="deposit_date" class="form-control" required="" value="2025-04-14">
                        </div>
                        <div class="col-6 mt-3" id="">
                            <label>Amount<span class="text-danger">*</span></label>
                            <input type="number" name="amount" id="amount_field" class="form-control" min="1" value="0" max="21360">
                        </div>
                        <div class="col-6 mt-3">
                            <label>Payment Status<span class="text-danger">*</span></label>
                            <select class="form-control" required="" name="payment_status" id="paymentStatus">
                                <option value="">Select Payment Status</option>
                                <option value="Partial-Paid">Partial-Paid</option>
                                <option value="Paid">Paid</option>
                            </select>
                        </div>
                    </div> -->
                    <!-- <p class="mt-2">Maximum Payment Amount is: <strong class="totalAmount">21360</strong> </p> -->
                    <div class="row">
                        <div class="form-group">
                            <label>Payment Screen Shot<span class="text-danger">*</span></label>
                            <input type="file" name="image" class="form-control" id="image">
                            <span class="error" id="image_error"></span>
                        </div>  
                           
                        <!-- Preview Image -->
                        <div class="mt-2 text-center">
                            <img id="imagePreview" src="" alt="Image Preview" style="display: none; width: 300px; height: 300px; object-fit: contain; border-radius: 5px;">
                        </div>
                        <!-- <div id="additionalFields" style="display: none;">
                            <div class="col-12 mt-3">
                                <label>Next Payment Date <span class="text-danger">*</span></label>
                                <input type="date" name="next_billing_date" class="form-control" value="2025-04-14">
                            </div>
                            <div class="form-group">
                                <label>Make Remark <span class="text-danger">*</span></label>
                                <textarea rows="3" name="remark" class="form-control" placeholder="Type here..."></textarea>
                            </div>
                        </div>
                        <div class="form-group" id="delay_reason_field" style="display: none;">
                            <label>Delay Reason <span class="text-danger">*</span></label>
                            <textarea rows="3" name="reason" class="form-control" placeholder="Type here..."></textarea>
                        </div> -->
                            <div class="form-group">
                            <button class="btn btn-success" type="submit" id="submit-payment-button">
                                <i class="fa fa-check fa-fw"></i> Edit Receipt
                            </button>
                            <!-- <button class="btn btn-warning generate_bill" type="submit" id="generate-bill-button" style="display:none;" data-id="1">
                                <i class="fa fa-check fa-fw"></i> Edit Receipt
                            </button> -->
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
     // Show Image Preview When Selecting a File
    $("#image").on("change", function () {
        let file = this.files[0];
        if (file) {
            let reader = new FileReader();
            reader.onload = function (e) {
                $("#imagePreview").attr("src", e.target.result).show();
            };
            reader.readAsDataURL(file);
        }
    });
function editReceipt(id,image) {
    $('#paymentId').val(id);
    if (image) {
        $("#imagePreview").attr("src", "https://tms.adxventure.com/payment/" + image).show();
    } else {
        $("#imagePreview").hide();
    }
    $('#editReceipt').modal('show');
}

</script>
</x-app-layout>