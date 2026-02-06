<div class="modal fade" id="sendProposal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true"
        data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Send Proposal</h5>
                    <button type="button" class="border-0" style="background: border-box;" data-bs-dismiss="modal"
                        aria-label="Close"><i class="bi bi-x-circle-fill" style="font-size: xx-large;"></i></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-8">
                            <!-- Preview Image -->
                            <div id="imagePreview" style="display: none;">
                                <img id="proposalImage" src="#" alt="Image Preview"
                                    style="max-width: 100%; margin-bottom: 10px;">
                                <div id="imageMessage"></div> <!-- Display message with image -->
                            </div>

                            <!-- Preview PDF -->
                            <div id="pdfPreview" style="display: none;">
                                <a id="proposalPdfLink" href="#" target="_blank" class="btn btn-secondary">View PDF</a>
                                <div id="pdfMessage"></div> <!-- Display message with PDF -->
                            </div>
                        </div>
                        <div class="col-4">
                            <form class="ajax-form" data-action="{{ route('crm.send.custome.proposal') }}"
                                data-method="POST" id="custome-proposal-form">
                                @csrf
                                <input type="hidden" name="proposal_user" id="proposal_id" value="">
                                <div class="form-group">
                                    <select class="form-control" name="proposal_type"
                                        onchange="proposalType(this.value)">
                                        <option selected value="">Choose Proposal Type..</option>
                                        <option value="1">Send With Image</option>
                                        <option value="2">Send With Pdf</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Send Via <span class="text-danger">*</span></label>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="sendbywhatshapp"
                                            id="sendByWhatsapp" value="1">
                                        <label class="form-check-label" for="sendByWhatsapp">Send by Whatsapp</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="sendbyemail"
                                            id="sendbyemail" value="1">
                                        <label class="form-check-label" for="sendbyemail">Send by Email</label>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary mt-3"><i class="bi bi-send"></i>
                                    Send</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>