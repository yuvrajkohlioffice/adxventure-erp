<div class="modal fade" id="message" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true"
        data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog  modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Send Company Portfolio</h5>
                    <button type="button" class="border-0" style="background: border-box;" data-bs-dismiss="modal"
                        aria-label="Close"><i class="bi bi-x-circle-fill" style="font-size: xx-large;"></i></button>
                </div>
                <div class="modal-body">
                    <form id="ajax-form" data-action="{{ route('crm.send.offer.message') }}" data-method="POST">
                        @csrf
                        <input type="hidden" name="message_user" value="">
                        <label class="form-label">Send Via <span class="text-danger">*</span> </label>
                        <div class="form-group">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="sendbywhatshapp"
                                    id="sendByWhatsapp" value="1">
                                <label class="form-check-label" for="sendByWhatsapp">Send by Whatsapp</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="sendbyemail" id="sendbyemail"
                                    value="1">
                                <label class="form-check-label" for="sendbyemail">Send by Email</label>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary w-50 mt-3"><i class="bi bi-send"></i>
                            Send</button>
                    </form>
                </div>
            </div>
        </div>
    </div>