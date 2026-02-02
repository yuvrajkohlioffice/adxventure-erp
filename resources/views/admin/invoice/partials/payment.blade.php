<div class="modal" id="PaymentModel" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">       
        <div class="modal-dialog modal-lg modal-dialog-centered ">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Payment (<strong id="PaymentUser"></strong>)</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form class="ajax-form"  data-method="POST" data-action="{{route('payment.store')}}"> 
                        @csrf
                        <input type="hidden" name="invoice_id" id="paidId">
                        <div class="row">
                            <div class="col-6 mt-3">
                                <label>Payment Mode <span class="text-danger">*</span></label>
                                <select class="form-control" required name="mode">
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
                                <input type="date" name="deposit_date" id="deposit_date" class="form-control" required value="{{ date('Y-m-d') }}">
                            </div>
                            <div class="col-6 mt-3" id="">
                                <label>Amount<span class="text-danger">*</span></label>
                                <input type="number" name="amount" id="amount_field" 
                                    class="form-control" min="1" value="0" max="">
                            </div>
                            <div class="col-6 mt-3">
                                <label>Payment Status<span class="text-danger">*</span></label>
                                <select class="form-control" required name="payment_status" id="paymentStatus">
                                    <option value="">Select Payment Status</option>
                                    <option value="Partial-Paid">Partial-Paid</option>
                                    <option value="Paid">Paid</option>
                                </select>
                            </div>
                        </div>
                        <p class="mt-2">Maximum Payment Amount is: <strong class="totalAmount"></strong> </p>
                        <div class="row">
                            <div class="form-group">
                                <label>Payment Screen Shot<span class="text-danger">*</span></label>
                                <input type="file" name="image" class="form-control">
                            </div>  
                            <div id="additionalFields" style="display: none;">
                                <div class="col-12 mt-3">
                                    <label>Next Payment Date <span class="text-danger">*</span></label>
                                    <input type="date" name="next_billing_date" class="form-control" value="{{ date('Y-m-d') }}">
                                </div>
                                <div class="form-group">
                                    <label>Make Remark <span class="text-danger">*</span></label>
                                    <textarea rows="3" name="remark" class="form-control"  placeholder="Type here..."></textarea>
                                </div>
                            </div>
                            <div class="form-group" id="delay_reason_field" style="display: none;">
                                <label>Delay Reason <span class="text-danger">*</span></label>
                                <textarea rows="3" name="reason" class="form-control" placeholder="Type here..."></textarea>
                            </div>
                                <div class="form-group">
                                <button class="btn btn-success" type="submit" id="submit-payment-button">
                                    <i class="fa fa-check fa-fw"></i> submit
                                </button>
                                <button class="btn btn-warning generate_bill" type="submit" id="generate-bill-button" style="display:none;" data-id="1">
                                    <input type="hidden" name="generate_bill"  id="generate_bill">
                                    <i class="fa fa-check fa-fw"></i> Generate Bill
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>