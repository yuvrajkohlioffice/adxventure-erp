<div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-fullscreen-sm-down">
            <div class="modal-content" style="width:1440px;top:150px;right:90%;">
                <form  class="ajax-form" data-action="{{ route('followup.store') }}" data-method="POST">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="staticBackdropLabel">Followup (<strong id="folowupClient"></strong>) </h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-4">
                                @csrf
                                <input type="hidden" name="invoice_id"  id="invoiceId" value="">
                                <div class="form-group">
                                    <input type="radio" name="reason" id="not_interested" value="Call Not Received">
                                    <label for="not_interested">Call Back Later</label>
                                </div>
                                <div class="form-group">
                                    <input type="radio" name="reason" id="call_not_received" value="Call Not Received">
                                    <label for="call_not_received">Call Not Received</label>
                                </div>
                                <div class="form-group">
                                    <input type="radio" name="reason" id="wrong_info" value="Incoming Not Availabale">
                                    <label for="wrong_info">Incoming Not Availabale</label>
                                </div>
                                <div class="form-group">
                                <input type="radio" name="reason" id="not_pickup" value="Not Reachable">
                                    <label for="not_pickup">Not Reachable</label>
                                </div>
                                <div class="form-group">
                                    <input type="radio" name="reason" id="not_pay" value="Not Pay">
                                    <label for="not_pay">Not Pay </label>
                                </div>
                                <div class="form-group">
                                    <input type="radio" name="reason" id="other_reason" value="Other" checked="">
                                    <label for="other_reason">Other</label>
                                </div>
                                <div class="form-group">
                                    <label>Remark (max 50 char)</label>
                                    <textarea class="form-control" name="remark"></textarea>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-6">
                                        <label>Next Follow Up Date</label>
                                        <input type="date" class="form-control" name="next_date" id="nextDate" min="{{ date('Y-m-d') }}">
                                    </div>
                                    <div class="col-6">
                                        <label>Next Follow Up Time</label>
                                        <input type="time" class="form-control" name="next_time" id="nextTime">
                                    </div>
                                </div>
                                <!-- Container for Follow-up Data -->
                                <div class="container" id="followupData">
                                    <h3 class="card-title text-center">Follow Up data</h3>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                </div>  
                            </div>
                            <div class="col-8">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Sr No.</th>
                                            <th>Reason</th>
                                            <th>Remark</th>
                                            <th>Next Followup Date</th>
                                            <th>Last Followup Date</th>
                                        </tr>
                                    </thead>
                                    <tbody id="followupTableBody">
                                        <!-- Follow-up data will be injected here by JavaScript -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                            
                    </div>
                
                </form>
            </div>
        </div>
    </div>