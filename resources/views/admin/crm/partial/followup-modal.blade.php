 <div class="modal fade" id="followupModel" tabindex="-1" aria-labelledby="followupModalLabel" aria-hidden="true"
        data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content shadow-lg border-0">

                <div class="modal-header bg-light">
                    <div>
                        <h5 class="modal-title fw-bold text-primary" id="followupModalLabel">
                            <i class="bi bi-telephone-forward me-2"></i>Lead Follow Up
                        </h5>
                        <small class="text-muted">Lead: <span
                                class="FollowupUserName fw-semibold text-dark"></span></small>
                    </div>
                    <div class="close-btn">

                    </div>
                </div>

                <div class="modal-body bg-light">
                    <div class="row g-3">

                        <div class="col-lg-4">
                            <div class="card shadow-sm border-0 h-100">
                                <div class="card-header bg-white border-bottom-0 pt-3">
                                    <h6 class="fw-bold mb-0 text-uppercase text-secondary" style="font-size: 0.85rem;">
                                        Log Activity</h6>
                                </div>
                                <div class="card-body">
                                    <form class="ajax-form" id="followupFrom" action="{{ route('followup.store') }}"
                                        method="POST">
                                        @csrf
                                        <input type="hidden" name="lead_id" id="FollowupUser">
                                        <div class="form-group">
                                            <input type="radio" name="reason" id="call_back" value="call back later">
                                            <label for="call_back">Call Back Later</label>
                                        </div>
                                        <div class="form-group">
                                            <input type="radio" name="reason" id="call_me_tommrow"
                                                value="call Me Tomorrow">
                                            <label for="call_me_tommrow">Call Me Tomorrow</label>
                                        </div>
                                        <div class="form-group">
                                            <input type="radio" name="reason" id="payment_tomorrow"
                                                value="Payment Tomorrow">
                                            <label for="payment_tomorrow">Payment Tomorrow</label>
                                        </div>
                                        <div class="form-group">
                                            <input type="radio" name="reason" id="talk_with_my_partner"
                                                value="Talk With My Partner">
                                            <label for="talk_with_my_partner">Talk With My Partner</label>
                                        </div>
                                        <div class="form-group">
                                            <input type="radio" name="reason" id="other_company"
                                                value="Work with other company">
                                            <label for="other_company">Work with other company</label>
                                        </div>
                                        {{-- <div class="form-group">
                                            <input type="radio" name="reason" id="information_send"
                                                value="Information Send">
                                            <label for="information_send">Information Send</label>
                                        </div> --}}
                                        <div class="form-group">
                                            <input type="radio" name="reason" id="not_interested"
                                                value="Not interested">
                                            <label for="not_interested">Not Interested</label>
                                        </div>
                                        <div class="form-group">
                                            <input type="radio" name="reason" id="interested" value="Interested">
                                            <label for="interested">Interested</label>
                                        </div>
                                        <div class="form-group">
                                            <input type="radio" name="reason" id="wrong_info" value="Wrong Information">
                                            <label for="wrong_info">Wrong Information</label>
                                        </div>
                                        <div class="form-group">
                                            <input type="radio" name="reason" id="not_pickup" value="Not pickup">
                                            <label for="not_pickup">Not Pickup</label>
                                        </div>
                                        <div class="form-group">
                                            <input type="radio" name="reason" id="other_reason" value="Other">
                                            <label for="other_reason">Other</label>
                                        </div>
                                        <!-- Remark Field -->
                                        <div class="form-group" id="remarkField">
                                            <label>Remark <span class="text-danger">(max 50 words)</span></label>
                                            <textarea class="form-control" name="remark" maxlength="250"></textarea>
                                        </div>
                                        <div class="row" id="followupDate">
                                            <div class="col-6" id="next_followup_date">
                                                <label>Next Follow Up Date</label>
                                                <input type="date" class="form-control" name="next_date" id="next_date">
                                            </div>
                                            <div class="col-6" id="next_followup_time">
                                                <label>Next Follow Up Time</label>
                                                <input type="time" class="form-control timepicker" name="next_time">
                                            </div>
                                        </div>
                                        <button type="submit" id="followup-submit-btn"
                                            class="btn btn-primary w-100 mt-2">Submit</button>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-8">
                            <div class="card shadow-sm border-0 h-100">
                                <div
                                    class="card-header bg-white border-bottom-0 pt-3 d-flex justify-content-between align-items-center">
                                    <h6 class="fw-bold mb-0 text-uppercase text-secondary" style="font-size: 0.85rem;">
                                        Interaction History</h6>
                                </div>
                                <div class="card-body p-0">
                                    <div class="table-responsive" style="max-height: 500px; overflow-y: auto;">
                                        <table class="table table-hover table-striped align-middle mb-0">
                                            <thead class="bg-light text-secondary sticky-top">
                                                <tr style="font-size: 0.85rem;">
                                                    <th class="ps-3">#</th>
                                                    <th>Reason</th>
                                                    <th style="width: 40%;">Remark</th>
                                                    <th>Next Date</th>
                                                    <th>Created At</th>
                                                </tr>
                                            </thead>
                                            <tbody id="followupTableBody" style="font-size: 0.9rem;">
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="card-footer bg-white border-top-0">
                                    <nav>
                                        <ul id="paginationLinks"
                                            class="pagination justify-content-end pagination-sm mb-0"></ul>
                                    </nav>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>