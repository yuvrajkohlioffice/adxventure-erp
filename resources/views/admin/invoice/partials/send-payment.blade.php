<div class="modal fade" id="sendPaymentLink" tabindex="-1" aria-labelledby="leadModalLabel" data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered ">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="leadModalLabel">Send  Payment Details & Invoice </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-12">
                                <form class="ajax-form" action="{{route('payment.link.send')}}" data-method="POST">
                                    @csrf
                                    <input type="hidden" name="sendPaymentId" id="sendPaymentId">
                                    <div class="mb-3">
                                        <label class="form-label">Select Send Details</label>
                                        <select class="form-select" name="send_details" onclick="Details(this.value)">
                                            <option selected disabled>Select Send details</option>
                                            <option value="send_payemnt_details">Send Payment Details</option>
                                            <option value="send_invoice_again">Send Invoice Again</option>
                                            <option value="send_receipt_again">Send Receipt Again</option>
                                        </select>
                                    </div>
                                    <div class="payment-details" style="display:none;">
                                        <div class="mb-3">
                                            <label class="form-label">Choose Bank<span class="text-danger">*</span></label>
                                         {{--   <select class="form-select" name="bank" >
                                                @if(isset($banks))
                                                @foreach($banks as $bank)
                                                <option value="{{$bank->id}}">
                                                    @if($bank->gst == 1)
                                                        GST -
                                                    @else
                                                    Non-GST -
                                                    @endif
                                                    {{ $bank->bank_name ?? '--' }}({{ $bank->account_no ?? '--' }})</option>
                                                @endforeach
                                                @endif
                                            </select>--}}
                                            <select class="form-select" id="bankSelect" name="bank">
                                            </select>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Payment Link / Invoice Send Via <span class="text-danger" >*</span> </label>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="sendbywhatshapp" id="sendByWhatsapp" value="1">
                                            <label class="form-check-label" for="sendByWhatsapp">Send by Whatsapp</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="sendbyemail" id="sendbyemail" value="1">
                                            <label class="form-check-label" for="sendbyemail">Send by Email</label>
                                        </div>     
                                    </div>
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                </form>
                            </div>
                        </div>  
                    </div>
                </div>
            </div>
        </div>
    </div>