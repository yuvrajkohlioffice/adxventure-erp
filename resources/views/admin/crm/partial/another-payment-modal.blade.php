 <div class="modal" id="todayReportModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true"
        data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-xl modal-dialog-centered ">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel"><b>Today Report</b></h5>
                    <button type="button" class="border-0" style="background: border-box;" data-bs-dismiss="modal"
                        aria-label="Close"><i class="bi bi-x-circle-fill" style="font-size: xx-large;"></i></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        @foreach ($bdeReports['bdeReports'] as $report)
                        <div class="col-4">
                            <div class="card border shadow-sm p-3" style="border-radius: 12px;">
                                <div class="d-flex align-items-center">
                                    <img src="{{ $report['image'] ? asset($report['image']) : asset('/user1.png') }}"
                                        alt="user-image" class="rounded-circle border"
                                        style="width: 80px; height: 80px; object-fit: cover;">

                                    <div class="ms-3">
                                        <h5 class="mb-0 fw-bold">{{ $report['name'] }}</h5>
                                        <small>{{ $report['role'] }}</small><br>
                                        <small class="text-muted">
                                            <!-- <i class="bi bi-telephone-fill text-danger me-1"></i>{{ $report['email'] }}<br> -->
                                            <i class="bi bi-telephone-fill text-danger me-1"></i>{{ $report['phone'] }}
                                        </small>
                                    </div>
                                </div>
                                <hr>
                                <ul class="list-unstyled mb-0 ps-1">
                                    <li class="d-flex justify-content-between align-items-center">
                                        <span><i
                                                class="bi bi-person-lines-fill me-2 text-primary"></i><strong>Leads</strong></span>
                                        <span>{{ $report['assigned_leads'] }}</span>
                                    </li>
                                    <li class="d-flex justify-content-between align-items-center">
                                        <span><i
                                                class="bi bi-chat-dots-fill me-2 text-success"></i><strong>Followup</strong></span>
                                        <span>{{ $report['followups'] }}</span>
                                    </li>
                                    <li class="d-flex justify-content-between align-items-center">
                                        <span><i
                                                class="bi bi-file-earmark-text-fill me-2 text-warning"></i><strong>Proposal</strong></span>
                                        <span>{{ $report['proposals'] }}</span>
                                    </li>
                                    <li class="d-flex justify-content-between align-items-center">
                                        <span><i
                                                class="bi bi-file-earmark-check-fill me-2 text-info"></i><strong>Quotation</strong></span>
                                        <span>{{ $report['quotation'] }}</span>
                                    </li>
                                    <li class="d-flex justify-content-between align-items-center">
                                        <span><i
                                                class="bi bi-check2-circle me-2 text-danger"></i><strong>Converted</strong></span>
                                        <span>{{ $report['converted'] }}</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>